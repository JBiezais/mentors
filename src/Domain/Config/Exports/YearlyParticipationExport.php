<?php

namespace src\Domain\Config\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class YearlyParticipationExport implements FromCollection, WithHeadings, WithMapping, WithCharts, ShouldAutoSize, WithTitle
{
    protected Collection $years;

    public function title(): string
    {
        return 'Statistics';
    }

    public function collection(): Collection
    {
        $this->years = DB::table(
            function ($query) {
                $query->selectRaw('YEAR(created_at) as year, COUNT(id) as student_count, 0 as mentor_count')
                    ->from('students')
                    ->whereNull('deleted_at')
                    ->groupByRaw('YEAR(created_at)')
                    ->unionAll(
                        DB::table('mentors')
                            ->selectRaw('YEAR(created_at) as year, 0 as student_count, COUNT(id) as mentor_count')
                            ->whereNull('deleted_at')
                            ->groupByRaw('YEAR(created_at)')
                    );
            },'yearly_data')
            ->selectRaw('year, SUM(student_count) as student_count, SUM(mentor_count) as mentor_count')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return $this->years->isNotEmpty() ? $this->years : collect();
    }

    public function headings(): array
    {
        return ['Gads', 'Mentoru skaits', 'Studentu skaits'];
    }

    public function map($row): array
    {
        return [
            $row->year,
            $row->mentor_count ?? 0,
            $row->student_count ?? 0,
        ];
    }

    public function charts(): ?Chart
    {
        if ($this->years->isEmpty()) {
            return null;
        }

        $length = $this->years->count();
        $endRow = $length + 1;

        $categories = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $this->title().'!$A$2:$A$'.$endRow, null, $length);

        $labelMentor = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $this->title().'!$B$1:$B$1', null, 1);
        $labelMentee = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $this->title().'!$C$1:$C$1', null, 1);

        $mentorsData = new DataSeriesValues(
            dataType: DataSeriesValues::DATASERIES_TYPE_NUMBER,
            dataSource: $this->title().'!$B$2:$B$'.$endRow,
            pointCount: $length,
        );

        $studentsData = new DataSeriesValues(
            dataType: DataSeriesValues::DATASERIES_TYPE_NUMBER,
            dataSource: $this->title().'!$C$2:$C$'.$endRow,
            pointCount: $length,
        );

        $series = new DataSeries(
            plotType: DataSeries::TYPE_LINECHART,
            plotGrouping: DataSeries::GROUPING_STACKED,
            plotOrder: [0, 1],
            plotLabel: [$labelMentor, $labelMentee],
            plotCategory: [$categories],
            plotValues: [$mentorsData, $studentsData]
        );

        $plot = new PlotArea(null, [$series]);
        $legend = new Legend();

        $chart = new Chart(
            name: 'Gada līdzdalības diagramma',
            title: new Title('Gada dalībnieki'),
            legend: $legend,
            plotArea: $plot,
            xAxisLabel: new Title('Gads'),
            yAxisLabel: new Title('Dalībnieki')
        );

        $chart->setTopLeftPosition('E2');
        $chart->setBottomRightPosition('L20');

        return $chart;
    }
}
