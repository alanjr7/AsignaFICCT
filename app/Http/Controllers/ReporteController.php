<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Horario;
use App\Models\User;
use App\Models\GrupoMateria;
use App\Models\Materia;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function generarReporte(Request $request)
    {
        $request->validate([
            'tipo_reporte' => 'required|in:asistencias,horarios,docentes,materias',
            'formato' => 'required|in:excel,pdf,html',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'docente_id' => 'nullable|exists:users,id',
            'materia_id' => 'nullable|exists:materias,id',
        ]);

        $filtros = $request->only(['fecha_inicio', 'fecha_fin', 'docente_id', 'materia_id']);
        $tipoReporte = $request->tipo_reporte;
        $formato = $request->formato;

        try {
            switch ($tipoReporte) {
                case 'asistencias':
                    $data = $this->generarDatosAsistencias($filtros);
                    $titulo = 'Reporte de Asistencias';
                    $vista = 'reportes.asistencias';
                    break;
                
                case 'horarios':
                    $data = $this->generarDatosHorarios($filtros);
                    $titulo = 'Reporte de Horarios';
                    $vista = 'reportes.horarios';
                    break;
                
                case 'docentes':
                    $data = $this->generarDatosDocentes($filtros);
                    $titulo = 'Reporte de Docentes';
                    $vista = 'reportes.docentes';
                    break;
                
                case 'materias':
                    $data = $this->generarDatosMaterias($filtros);
                    $titulo = 'Reporte de Materias';
                    $vista = 'reportes.materias';
                    break;
                
                default:
                    return back()->with('error', 'Tipo de reporte no válido');
            }

            // Registrar en bitácora
            Bitacora::create([
                'user_id' => auth()->id(),
                'accion_realizada' => "Reporte generado: {$titulo} - Formato: " . strtoupper($formato),
                'fecha_y_hora' => now(),
            ]);

            // Generar según formato
            switch ($formato) {
                case 'excel':
                    return $this->generarCSV($data, $titulo, $tipoReporte);
                
                case 'pdf':
                    return $this->generarPDF($data, $titulo, $vista);
                
                case 'html':
                    return view($vista, compact('data', 'titulo', 'filtros'));
            }

        } catch (\Exception $e) {
            \Log::error('Error generando reporte: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }

    private function generarDatosAsistencias($filtros)
    {
        $query = Asistencia::with([
            'docente',
            'horario.grupoMateria.materia',
            'horario.grupoMateria.grupo',
            'horario.aula'
        ]);

        // Aplicar filtros
        if (!empty($filtros['fecha_inicio'])) {
            $query->where('fecha', '>=', $filtros['fecha_inicio']);
        }
        
        if (!empty($filtros['fecha_fin'])) {
            $query->where('fecha', '<=', $filtros['fecha_fin']);
        }
        
        if (!empty($filtros['docente_id'])) {
            $query->where('docente_id', $filtros['docente_id']);
        }

        $asistencias = $query->orderBy('fecha', 'desc')
                            ->orderBy('hora_marcado', 'desc')
                            ->get();

        // Estadísticas
        $estadisticas = [
            'total' => $asistencias->count(),
            'entradas' => $asistencias->where('tipo', 'entrada')->count(),
            'salidas' => $asistencias->where('tipo', 'salida')->count(),
            'docentes_unicos' => $asistencias->unique('docente_id')->count(),
            'dias_cubiertos' => $asistencias->unique('fecha')->count(),
        ];

        return [
            'asistencias' => $asistencias,
            'estadisticas' => $estadisticas,
            'filtros' => $filtros
        ];
    }

    private function generarDatosHorarios($filtros)
    {
        $query = Horario::with([
            'grupoMateria.materia',
            'grupoMateria.grupo',
            'grupoMateria.docente',
            'aula'
        ]);

        if (!empty($filtros['docente_id'])) {
            $query->whereHas('grupoMateria', function($q) use ($filtros) {
                $q->where('docente_id', $filtros['docente_id']);
            });
        }

        if (!empty($filtros['materia_id'])) {
            $query->whereHas('grupoMateria.materia', function($q) use ($filtros) {
                $q->where('id', $filtros['materia_id']);
            });
        }

        $horarios = $query->orderBy('dia')
                         ->orderBy('hora_inicio')
                         ->get();

        // Estadísticas por día
        $estadisticasDias = [
            'Lunes' => $horarios->where('dia', 'Lunes')->count(),
            'Martes' => $horarios->where('dia', 'Martes')->count(),
            'Miércoles' => $horarios->where('dia', 'Miércoles')->count(),
            'Jueves' => $horarios->where('dia', 'Jueves')->count(),
            'Viernes' => $horarios->where('dia', 'Viernes')->count(),
            'Sábado' => $horarios->where('dia', 'Sábado')->count(),
        ];

        return [
            'horarios' => $horarios,
            'estadisticas_dias' => $estadisticasDias,
            'total_horarios' => $horarios->count(),
            'filtros' => $filtros
        ];
    }

    private function generarDatosDocentes($filtros)
    {
        $query = User::where('rol', 'docente')->with(['docente']);

        $docentes = $query->orderBy('nombre')->get()->map(function($docente) {
            $horariosCount = Horario::whereHas('grupoMateria', function($q) use ($docente) {
                $q->where('docente_id', $docente->id);
            })->count();

            $asistenciasCount = Asistencia::where('docente_id', $docente->id)->count();

            return [
                'docente' => $docente,
                'horarios_count' => $horariosCount,
                'asistencias_count' => $asistenciasCount,
                'materias_count' => GrupoMateria::where('docente_id', $docente->id)->count()
            ];
        });

        return [
            'docentes' => $docentes,
            'total_docentes' => $docentes->count(),
            'filtros' => $filtros
        ];
    }

    private function generarDatosMaterias($filtros)
    {
        $query = Materia::with(['grupoMaterias.grupo', 'grupoMaterias.docente']);

        $materias = $query->orderBy('nombre_materia')->get()->map(function($materia) {
            $gruposCount = $materia->grupoMaterias->count();
            $docentesCount = $materia->grupoMaterias->unique('docente_id')->count();
            $totalHoras = $materia->grupoMaterias->sum('horas_asignadas');

            return [
                'materia' => $materia,
                'grupos_count' => $gruposCount,
                'docentes_count' => $docentesCount,
                'total_horas' => $totalHoras
            ];
        });

        return [
            'materias' => $materias,
            'total_materias' => $materias->count(),
            'filtros' => $filtros
        ];
    }

    private function generarCSV($data, $titulo, $tipoReporte)
    {
        $filename = $tipoReporte . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        switch ($tipoReporte) {
            case 'asistencias':
                $rows = $this->formatoAsistenciasCSV($data);
                break;
            case 'horarios':
                $rows = $this->formatoHorariosCSV($data);
                break;
            case 'docentes':
                $rows = $this->formatoDocentesCSV($data);
                break;
            case 'materias':
                $rows = $this->formatoMateriasCSV($data);
                break;
            default:
                $rows = [];
        }

        $callback = function() use ($rows) {
            $file = fopen('php://output', 'w');
            
            // Agregar BOM para UTF-8 en Excel
            fwrite($file, "\xEF\xBB\xBF");
            
            // Escribir headers si hay datos
            if (!empty($rows)) {
                fputcsv($file, array_keys($rows[0]));
                
                // Escribir datos
                foreach ($rows as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function formatoAsistenciasCSV($data)
    {
        $rows = [];
        foreach ($data['asistencias'] as $asistencia) {
            $rows[] = [
                'Docente' => $asistencia->docente->nombre,
                'Materia' => $asistencia->horario->grupoMateria->materia->nombre_materia,
                'Grupo' => $asistencia->horario->grupoMateria->grupo->nombre_grupo,
                'Fecha' => $asistencia->fecha->format('d/m/Y'),
                'Hora' => $asistencia->hora_marcado,
                'Tipo' => $asistencia->tipo == 'entrada' ? 'Entrada' : 'Salida',
                'Ubicación' => $asistencia->direccion ?? 'No disponible',
                'Aula' => $asistencia->horario->aula->nro_aula ?? 'No asignada'
            ];
        }
        return $rows;
    }

    private function formatoHorariosCSV($data)
    {
        $rows = [];
        foreach ($data['horarios'] as $horario) {
            $rows[] = [
                'Materia' => $horario->grupoMateria->materia->nombre_materia,
                'Grupo' => $horario->grupoMateria->grupo->nombre_grupo,
                'Docente' => $horario->grupoMateria->docente->nombre,
                'Día' => $horario->dia,
                'Hora Inicio' => $horario->hora_inicio,
                'Hora Fin' => $horario->hora_fin,
                'Aula' => $horario->aula->nro_aula ?? 'No asignada'
            ];
        }
        return $rows;
    }

    private function formatoDocentesCSV($data)
    {
        $rows = [];
        foreach ($data['docentes'] as $item) {
            $rows[] = [
                'Nombre' => $item['docente']->nombre,
                'Email' => $item['docente']->correo,
                'CI' => $item['docente']->ci,
                'Horarios Asignados' => $item['horarios_count'],
                'Asistencias Registradas' => $item['asistencias_count'],
                'Materias' => $item['materias_count']
            ];
        }
        return $rows;
    }

    private function formatoMateriasCSV($data)
    {
        $rows = [];
        foreach ($data['materias'] as $item) {
            $rows[] = [
                'Materia' => $item['materia']->nombre_materia,
                'Sigla' => $item['materia']->sigla_materia,
                'Horas Semanales' => $item['materia']->horas_semanales,
                'Grupos' => $item['grupos_count'],
                'Docentes' => $item['docentes_count'],
                'Total Horas' => $item['total_horas']
            ];
        }
        return $rows;
    }

    private function generarPDF($data, $titulo, $vista)
    {
        $pdf = PDF::loadView($vista, compact('data', 'titulo'));
        $pdf->setPaper('A4', 'landscape');
        
        $filename = strtolower(str_replace(' ', '_', $titulo)) . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function reporteRapido($tipo)
    {
        $formatos = ['excel', 'pdf', 'html'];
        $reportesRapidos = [
            'asistencias_hoy' => [
                'tipo_reporte' => 'asistencias',
                'fecha_inicio' => today()->format('Y-m-d'),
                'fecha_fin' => today()->format('Y-m-d'),
                'titulo' => 'Asistencias de Hoy'
            ],
            'horarios_activos' => [
                'tipo_reporte' => 'horarios',
                'titulo' => 'Horarios Activos'
            ],
            'docentes_activos' => [
                'tipo_reporte' => 'docentes',
                'titulo' => 'Docentes Activos'
            ]
        ];

        if (!array_key_exists($tipo, $reportesRapidos)) {
            return back()->with('error', 'Reporte rápido no disponible');
        }

        return view('reportes.rapidos', compact('reportesRapidos', 'tipo', 'formatos'));
    }

    public function descargarReporteRapido(Request $request, $tipo)
    {
        $reportesRapidos = [
            'asistencias_hoy' => [
                'tipo_reporte' => 'asistencias',
                'fecha_inicio' => today()->format('Y-m-d'),
                'fecha_fin' => today()->format('Y-m-d')
            ],
            'horarios_activos' => ['tipo_reporte' => 'horarios'],
            'docentes_activos' => ['tipo_reporte' => 'docentes']
        ];

        if (!array_key_exists($tipo, $reportesRapidos)) {
            return back()->with('error', 'Reporte rápido no disponible');
        }

        $request->merge($reportesRapidos[$tipo]);
        $request->merge(['formato' => $request->formato]);

        return $this->generarReporte($request);
    }
}