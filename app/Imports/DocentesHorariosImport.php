<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Aula;
use App\Models\GrupoMateria;
use App\Models\Horario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DocentesHorariosImport
{
    private $errors = [];
    private $importedCount = 0;
    private $skippedCount = 0;

    public function import($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("El archivo no existe: " . $filePath);
        }

        $file = fopen($filePath, 'r');
        if (!$file) {
            throw new \Exception("No se pudo abrir el archivo: " . $filePath);
        }

        // Leer encabezados
        $headers = fgetcsv($file);
        if (!$headers) {
            fclose($file);
            throw new \Exception("El archivo CSV está vacío o no tiene formato correcto");
        }

        // Normalizar headers (remover BOM y espacios)
        $headers = array_map(function($header) {
            return trim($header, "\xEF\xBB\xBF \t\n\r\0\x0B");
        }, $headers);

        // Verificar si tiene la columna modalidad
        $tieneModalidad = in_array('modalidad', $headers);

        $lineNumber = 1;

        while (($row = fgetcsv($file)) !== FALSE) {
            $lineNumber++;

            // Saltar filas vacías
            if (empty(array_filter($row))) {
                continue;
            }

            // Combinar headers con datos
            if (count($headers) !== count($row)) {
                $this->errors[] = "Línea {$lineNumber}: Número de columnas no coincide con los encabezados";
                $this->skippedCount++;
                continue;
            }

            $rowData = array_combine($headers, $row);

            // Si no tiene modalidad, agregar valor por defecto
            if (!$tieneModalidad) {
                $rowData['modalidad'] = 'presencial';
            }

            try {
                $this->processRow($rowData, $lineNumber);
            } catch (\Exception $e) {
                $this->errors[] = "Línea {$lineNumber}: " . $e->getMessage();
                $this->skippedCount++;
            }
        }

        fclose($file);
    }

    private function processRow($row, $lineNumber)
    {
        // Limpiar y validar datos
        $row = $this->cleanRowData($row);

        // Validar fila
        $validator = Validator::make($row, $this->rules(), $this->customValidationMessages());
        
        if ($validator->fails()) {
            throw new \Exception(implode(', ', $validator->errors()->all()));
        }

        // Procesar usuario/docente
        $user = $this->processUser($row);
        if (!$user) {
            throw new \Exception("No se pudo procesar el usuario");
        }

        // Procesar grupo materia y horario
        $this->processGrupoMateriaHorario($row, $user);

        $this->importedCount++;
    }

    private function cleanRowData($row)
    {
        $cleaned = [];
        foreach ($row as $key => $value) {
            $cleaned[$key] = trim($value);
            
            // Convertir valores numéricos
            if (in_array($key, ['nivel_materia', 'horas_semana', 'horas_asignadas', 'capacidad_aula', 'piso_aula', 'cupo_maximo', 'cupo_minimo'])) {
                $cleaned[$key] = is_numeric($value) ? (int)$value : 0;
            }
            
            // Valor por defecto para modalidad
            if ($key === 'modalidad' && empty($value)) {
                $cleaned[$key] = 'presencial';
            }

            // Para campos de aula en modalidad virtual, limpiar si están vacíos
            if ($key === 'modalidad' && $cleaned[$key] === 'virtual') {
                if (empty($cleaned['nro_aula'])) $cleaned['nro_aula'] = '';
                if (empty($cleaned['tipo_aula'])) $cleaned['tipo_aula'] = '';
                if (empty($cleaned['capacidad_aula'])) $cleaned['capacidad_aula'] = 0;
                if (empty($cleaned['piso_aula'])) $cleaned['piso_aula'] = 0;
            }
        }
        return $cleaned;
    }

    private function processUser($row)
    {
        // Verificar si el usuario ya existe
        $user = User::where('ci', $row['ci'])->first();

        if (!$user) {
            // Crear nuevo usuario
            $user = User::create([
                'ci' => $row['ci'],
                'nombre' => $row['nombre_docente'],
                'correo' => $row['correo'],
                'password' => Hash::make($row['password']),
                'rol' => 'docente',
            ]);

            // Crear registro en docentes
            Docente::create([
                'user_id' => $user->id,
                'codigo_docente' => $row['codigo_docente'],
                'profesion' => $row['profesion'],
            ]);
        }

        return $user;
    }

    private function processGrupoMateriaHorario($row, $user)
    {
        // Buscar o crear grupo
        $grupo = Grupo::firstOrCreate(
            ['codigo_grupo' => $row['codigo_grupo']],
            [
                'sigla_grupo' => $row['sigla_grupo'],
                'nombre_grupo' => $row['nombre_grupo'],
                'cupo_maximo' => $row['cupo_maximo'] ?? 40,
                'cupo_minimo' => $row['cupo_minimo'] ?? 10,
                'estado' => 'activo'
            ]
        );

        // Buscar o crear materia
        $materia = Materia::firstOrCreate(
            ['sigla_materia' => $row['sigla_materia']],
            [
                'nombre_materia' => $row['nombre_materia'],
                'nivel' => $row['nivel_materia'],
                'horas_semana' => $row['horas_semana'] ?? 4,
                'estado' => 'activa'
            ]
        );

        // Buscar o crear aula (solo para modalidad presencial)
        $aulaId = null;
        if ($row['modalidad'] === 'presencial') {
            if (empty($row['nro_aula'])) {
                throw new \Exception("El número de aula es requerido para modalidad presencial");
            }

            $aula = Aula::firstOrCreate(
                ['nro_aula' => $row['nro_aula']],
                [
                    'tipo' => $row['tipo_aula'] ?? 'Teórica',
                    'capacidad' => $row['capacidad_aula'] ?? 40,
                    'piso' => $row['piso_aula'] ?? 1,
                    'estado' => 'disponible'
                ]
            );
            $aulaId = $aula->id;

            // Verificar conflicto de horario solo para modalidad presencial
            if ($this->tieneConflictoHorario($aulaId, $row['dia'], $row['hora_inicio'], $row['hora_fin'])) {
                throw new \Exception("Conflicto de horario en el aula {$row['nro_aula']} para el día {$row['dia']} de {$row['hora_inicio']} a {$row['hora_fin']}");
            }
        }

        // Verificar conflicto de docente (ambas modalidades)
        if ($this->tieneConflictoDocente($user->id, $row['dia'], $row['hora_inicio'], $row['hora_fin'])) {
            throw new \Exception("El docente ya tiene una clase asignada en el día {$row['dia']} de {$row['hora_inicio']} a {$row['hora_fin']}");
        }

        // Crear o actualizar grupo_materia
        $grupoMateria = GrupoMateria::updateOrCreate(
            [
                'grupo_id' => $grupo->id,
                'materia_id' => $materia->id,
            ],
            [
                'docente_id' => $user->id,
                'horas_asignadas' => $row['horas_asignadas'] ?? 4,
            ]
        );

        // Crear horario
        Horario::create([
            'grupo_materia_id' => $grupoMateria->id,
            'aula_id' => $aulaId, // Puede ser null para virtual
            'dia' => $row['dia'],
            'hora_inicio' => $row['hora_inicio'],
            'hora_fin' => $row['hora_fin'],
            'modalidad' => $row['modalidad'],
        ]);
    }

    private function tieneConflictoHorario($aulaId, $dia, $horaInicio, $horaFin, $excluirId = null)
    {
        $query = Horario::where('aula_id', $aulaId)
            ->where('dia', $dia)
            ->where('modalidad', 'presencial')
            ->where(function($q) use ($horaInicio, $horaFin) {
                $q->where(function($q2) use ($horaInicio, $horaFin) {
                    $q2->where('hora_inicio', '<', $horaFin)
                       ->where('hora_fin', '>', $horaInicio);
                });
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    private function tieneConflictoDocente($docenteId, $dia, $horaInicio, $horaFin, $excluirId = null)
    {
        $query = Horario::whereHas('grupoMateria', function($query) use ($docenteId) {
                $query->where('docente_id', $docenteId);
            })
            ->where('dia', $dia)
            ->where(function($q) use ($horaInicio, $horaFin) {
                $q->where(function($q2) use ($horaInicio, $horaFin) {
                    $q2->where('hora_inicio', '<', $horaFin)
                       ->where('hora_fin', '>', $horaInicio);
                });
            });

        if ($excluirId) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->exists();
    }

    public function rules(): array
    {
        return [
            'ci' => 'required|string|max:20',
            'nombre_docente' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'codigo_docente' => 'required|string|max:50',
            'profesion' => 'required|string|max:255',
            'codigo_grupo' => 'required|string|max:50',
            'sigla_grupo' => 'required|string|max:20',
            'nombre_grupo' => 'required|string|max:255',
            'sigla_materia' => 'required|string|max:20',
            'nombre_materia' => 'required|string|max:255',
            'nivel_materia' => 'required|integer|min:1',
            'nro_aula' => 'required_if:modalidad,presencial|string|max:20',
            'dia' => ['required', Rule::in(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'])],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'modalidad' => ['required', Rule::in(['presencial', 'virtual'])],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'hora_fin.after' => 'La hora fin debe ser posterior a la hora inicio',
            'dia.in' => 'El día debe ser: Lunes, Martes, Miércoles, Jueves, Viernes o Sábado',
            'modalidad.in' => 'La modalidad debe ser: presencial o virtual',
            'nro_aula.required_if' => 'El número de aula es requerido para modalidad presencial',
            'correo.email' => 'El correo debe tener un formato válido',
            '*.required' => 'El campo :attribute es obligatorio',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }
}