<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CreateImportTemplate extends Command
{
    protected $signature = 'template:create-import';
    protected $description = 'Crear plantilla para importación de docentes y horarios';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'ci', 'nombre_docente', 'correo', 'password', 'codigo_docente', 'profesion',
            'codigo_grupo', 'sigla_grupo', 'nombre_grupo', 'sigla_materia', 'nombre_materia',
            'nivel_materia', 'horas_semana', 'horas_asignadas', 'nro_aula', 'tipo_aula',
            'capacidad_aula', 'piso_aula', 'dia', 'hora_inicio', 'hora_fin'
        ];

        // Agregar encabezados
        foreach ($headers as $index => $header) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
        }

        // Estilo para encabezados
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6FA']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        $sheet->getStyle('A1:U1')->applyFromArray($headerStyle);

        // Datos de ejemplo
        $sampleData = [
            ['1234567', 'Juan Pérez', 'juan.perez@email.com', '123456', 'DOC001', 'Lic. Matemáticas', 
             'GRP-INF-1A', 'INF-1A', 'Ingeniería Informática 1A', 'MAT101', 'Cálculo I', 
             1, 6, 6, 'A101', 'Teórica', 40, 1, 'Lunes', '08:00', '10:00'],
            ['1234567', 'Juan Pérez', 'juan.perez@email.com', '123456', 'DOC001', 'Lic. Matemáticas',
             'GRP-INF-1A', 'INF-1A', 'Ingeniería Informática 1A', 'MAT101', 'Cálculo I',
             1, 6, 6, 'A101', 'Teórica', 40, 1, 'Miércoles', '08:00', '10:00']
        ];

        // Agregar datos de ejemplo
        foreach ($sampleData as $rowIndex => $rowData) {
            foreach ($rowData as $colIndex => $value) {
                $sheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 2, $value);
            }
        }

        // Autoajustar columnas
        foreach (range('A', 'U') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Crear directorio si no existe
        $directory = storage_path('app/plantillas');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Guardar archivo
        $writer = new Xlsx($spreadsheet);
        $filePath = $directory . '/plantilla_docentes_horarios.xlsx';
        $writer->save($filePath);

        $this->info("Plantilla creada en: " . $filePath);
    }
}