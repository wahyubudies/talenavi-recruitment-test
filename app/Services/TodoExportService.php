<?php

namespace App\Services;

use App\Models\Todo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Events\AfterSheet;

class TodoExportService implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting,ShouldAutoSize
{

    public function collection()
    {
        $todos =  Todo::all();
        $summary = collect([
            ['Total Todos', '', '', '', '', $todos->count()],
            ['Total Time Tracked', '', '', '', '',$todos->sum('time_tracked')]
        ]);
        return $todos->concat($summary);
    }

    public function headings(): array
    {
        return ['Title', 'Assignee', 'Due Date', 'Time Tracked', 'Status', 'Priority'];
    }

    public function map($todo): array
    {
        if (is_array($todo)) {
            return array_values($todo); // Untuk summary row
        }

        return [
            $todo->title,
            $todo->assignee,
            $todo->due_date,
            $todo->time_tracked,
            $todo->status,
            $todo->priority,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => 'yyyy-mm-dd',
            'D' => '#,##0',
        ];
    }

    public function export($fileName = 'todos.xlsx')
    {
        return Excel::download($this, $fileName);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->mergeCells("B" . ($lastRow - 1) . ":F" . ($lastRow - 1));
                $event->sheet->mergeCells("B" . $lastRow . ":F" . $lastRow);

                $event->sheet->setCellValue("B" . ($lastRow - 1), 'Total Todos');
                $event->sheet->setCellValue("B" . $lastRow, 'Total Time Tracked');
            },
        ];
    }

}
