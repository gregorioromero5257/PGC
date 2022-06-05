<?php

namespace App\Exports;

use App\Project;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class ProjectsExport implements FromView, WithEvents
{
  protected $projects;

  public function __construct(array $projects)
  {
    $this->projects = $projects;
  }

  /**
  * @return \Illuminate\Support\Collection
  */
  // public function collection()
  // {
  //     return Project::whereIn('id', $this->projects)->with('clients')->orderBy('contract_start', 'desc')->get();
  // }

  public function view(): View
  {
    $projects = Project::whereIn('id', $this->projects)->with('clients')->orderBy('contract_start', 'desc')->get();

    return view('exports.projects', compact('projects'));
  }




  public function registerEvents(): array
  {
    return [
      AfterSheet::class => function (AfterSheet $event) {
        $event->sheet->getStyle('A1:D1')->applyFromArray([
          'font' => [
            'bold' => true
          ]
        ]);
        $event->sheet->getStyle('A1:E1')->applyFromArray(
          [
            'borders' => [
              'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
              ],
            ]
          ]
        );
        $event->sheet->getStyle('A2:E2')->applyFromArray(
          [
            'borders' => [
              'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
              ],
            ]
          ]
        );
      },
    ];
  }



}
