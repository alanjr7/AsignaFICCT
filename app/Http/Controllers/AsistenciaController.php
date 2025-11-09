<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Horario;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class AsistenciaController extends Controller
{
    public function index()
    {
        $docenteId = auth()->id();
        $horaActual = now();
        
        // Obtener horarios del docente para hoy
        $horariosHoy = Horario::with(['grupoMateria.materia', 'aula'])
            ->whereHas('grupoMateria', function($query) use ($docenteId) {
                $query->where('docente_id', $docenteId);
            })
            ->where('dia', $this->obtenerDiaActual())
            ->get()
            ->map(function($horario) use ($horaActual) {
                $horaInicio = Carbon::parse($horario->hora_inicio);
                $horaFin = Carbon::parse($horario->hora_fin);
                
                // Determinar estado del horario
                $estado = 'fuera_horario';
                $puedeMarcar = false;
                
                if ($horaActual->between($horaInicio->copy()->subMinutes(15), $horaFin->copy()->addMinutes(15))) {
                    $estado = 'en_horario';
                    $puedeMarcar = $horaActual->between($horaInicio->copy()->subMinutes(15), $horaFin);
                }
                
                if ($horaActual->gt($horaFin->copy()->addMinutes(15))) {
                    $estado = 'finalizado';
                }
                
                $horario->estado = $estado;
                $horario->puede_marcar = $puedeMarcar;
                $horario->ya_marco_entrada = Asistencia::yaMarcoHoy(auth()->id(), $horario->id, 'entrada');
                $horario->ya_marco_salida = Asistencia::yaMarcoHoy(auth()->id(), $horario->id, 'salida');
                
                return $horario;
            });

        // Obtener asistencias de hoy
        $asistenciasHoy = Asistencia::with('horario.grupoMateria.materia')
            ->where('docente_id', $docenteId)
            ->where('fecha', today())
            ->orderBy('hora_marcado', 'desc')
            ->get();

        return view('asistencia.index', compact('horariosHoy', 'asistenciasHoy'));
    }

    public function marcarAsistencia(Request $request, Horario $horario)
    {
        // Verificar que el horario pertenezca al docente
        if ($horario->grupoMateria->docente_id !== auth()->id()) {
            return response()->json(['error' => 'No tienes permisos para marcar asistencia en este horario.'], 403);
        }

        // Verificar que sea el día correcto
        if ($horario->dia !== $this->obtenerDiaActual()) {
            return response()->json(['error' => 'No puedes marcar asistencia fuera del día programado.'], 400);
        }

        $horaActual = now();
        $horaInicio = Carbon::parse($horario->hora_inicio);
        $horaFin = Carbon::parse($horario->hora_fin);

        // Verificar que esté en horario (15 minutos antes hasta el final)
        if (!$horaActual->between($horaInicio->copy()->subMinutes(15), $horaFin)) {
            return response()->json(['error' => 'Solo puedes marcar asistencia 15 minutos antes o durante la clase.'], 400);
        }

        // Determinar tipo de marcado (entrada o salida)
        $tipo = 'entrada';
        $yaMarcoEntrada = Asistencia::yaMarcoHoy(auth()->id(), $horario->id, 'entrada');
        
        if ($yaMarcoEntrada && !Asistencia::yaMarcoHoy(auth()->id(), $horario->id, 'salida')) {
            $tipo = 'salida';
        } elseif ($yaMarcoEntrada) {
            return response()->json(['error' => 'Ya marcaste entrada y salida para esta clase hoy.'], 400);
        }

        // Obtener geolocalización
        $geolocalizacion = $this->obtenerGeolocalizacion($request);

        // Crear registro de asistencia
        $asistencia = Asistencia::create([
            'docente_id' => auth()->id(),
            'horario_id' => $horario->id,
            'fecha' => today(),
            'hora_marcado' => $horaActual->format('H:i:s'),
            'tipo' => $tipo,
            'latitud' => $geolocalizacion['latitud'],
            'longitud' => $geolocalizacion['longitud'],
            'direccion' => $geolocalizacion['direccion'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Registrar en bitácora
        Bitacora::create([
            'user_id' => auth()->id(),
            'accion_realizada' => "Asistencia {$tipo} marcada - {$horario->grupoMateria->materia->nombre_materia} - {$horario->dia}",
            'fecha_y_hora' => now(),
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => "Asistencia {$tipo} marcada exitosamente",
            'asistencia' => $asistencia,
            'tipo' => $tipo
        ]);
    }

    public function dashboardAdmin(Request $request)
    {
        // Verificar que sea admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $fecha = $request->get('fecha', today()->format('Y-m-d'));
        
        $asistencias = Asistencia::with(['docente', 'horario.grupoMateria.materia', 'horario.aula'])
            ->when($fecha, function($query) use ($fecha) {
                $query->where('fecha', $fecha);
            })
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_marcado', 'desc')
            ->get();

        $estadisticas = [
            'total' => $asistencias->count(),
            'entradas' => $asistencias->where('tipo', 'entrada')->count(),
            'salidas' => $asistencias->where('tipo', 'salida')->count(),
            'docentes_activos' => $asistencias->unique('docente_id')->count(),
        ];

        return view('asistencia.dashboard-admin', compact('asistencias', 'estadisticas', 'fecha'));
    }

    public function mapaAsistencias(Request $request)
    {
        // Verificar que sea admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        $fecha = $request->get('fecha', today()->format('Y-m-d'));
        
        $asistencias = Asistencia::with(['docente', 'horario.grupoMateria.materia'])
            ->where('fecha', $fecha)
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->get();

        return response()->json($asistencias);
    }

    private function obtenerDiaActual()
    {
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        return $dias[now()->englishDayOfWeek] ?? 'Lunes';
    }

    private function obtenerGeolocalizacion(Request $request)
    {
        $latitud = $request->get('latitud');
        $longitud = $request->get('longitud');
        $direccion = $request->get('direccion', 'Ubicación no disponible');

        // Si no se proporcionan coordenadas, intentar obtener por IP
        if (!$latitud || !$longitud) {
            return $this->obtenerGeolocalizacionPorIP($request->ip());
        }

        // Si tenemos coordenadas pero no dirección, intentar obtener dirección
        if (!$direccion || $direccion === 'Ubicación no disponible') {
            $direccion = $this->obtenerDireccionDesdeCoordenadas($latitud, $longitud);
        }

        return [
            'latitud' => $latitud,
            'longitud' => $longitud,
            'direccion' => $direccion
        ];
    }

    private function obtenerGeolocalizacionPorIP($ip)
    {
        try {
            // Usar un servicio de geolocalización por IP
            $response = Http::get("http://ip-api.com/json/{$ip}");
            
            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'success') {
                    return [
                        'latitud' => $data['lat'],
                        'longitud' => $data['lon'],
                        'direccion' => "{$data['city']}, {$data['regionName']}, {$data['country']}"
                    ];
                }
            }
        } catch (\Exception $e) {
            // Log error
            \Log::error('Error obteniendo geolocalización por IP: ' . $e->getMessage());
        }

        return [
            'latitud' => null,
            'longitud' => null,
            'direccion' => 'Ubicación no disponible'
        ];
    }

    private function obtenerDireccionDesdeCoordenadas($latitud, $longitud)
    {
        try {
            // Usar OpenStreetMap Nominatim para reverse geocoding
            $response = Http::get("https://nominatim.openstreetmap.org/reverse", [
                'lat' => $latitud,
                'lon' => $longitud,
                'format' => 'json'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['display_name'] ?? 'Ubicación no disponible';
            }
        } catch (\Exception $e) {
            \Log::error('Error obteniendo dirección desde coordenadas: ' . $e->getMessage());
        }

        return 'Ubicación no disponible';
    }
}