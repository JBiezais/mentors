<?php

namespace src\Domain\Config\Sheets;

use Illuminate\Support\Collection;
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
use src\Domain\Faculty\Models\Faculty;

class FacultyMentorMenteeSheet implements FromCollection, WithHeadings, WithMapping, WithCharts, ShouldAutoSize, WithTitle
{
    protected Collection $faculties;

    public function __construct(public int $year)
    {
    }

    public function title(): string
    {
        return strval($this->year);
    }

    public function collection(): Collection
    {
        $faculties = Faculty::query()
            ->withTrashed()
            ->withCount([
                'mentors' => fn($q) => $q->whereYear('created_at', $this->year)->withTrashed(),
                'students' => fn($q) => $q->whereYear('created_at', $this->year)->withTrashed()
            ])
            ->get();

        $this->faculties = $faculties->isNotEmpty() ? $faculties : collect(['empty']);

        return $this->faculties;
    }

    public function headings(): array
    {
        return ['Fakultātes nosaukums', 'Mentoru skaits', 'Mentorējamo skaits'];
    }

    public function map($row): array
    {
        return [
            $row->title ?? 'Nezināms',
            $row->mentors_count ?? 0,
            $row->students_count ?? 0,
        ];
    }

    public function charts(): ?array
    {
        $length = $this->faculties->count();
        $endRow = $length + 1;

        $categories = new DataSeriesValues(
            dataType: DataSeriesValues::DATASERIES_TYPE_STRING,
            dataSource: $this->title().'!$A$2:$A$'.$endRow,
            pointCount: $length
        );

        $mentorsData = new DataSeriesValues(
            dataType: DataSeriesValues::DATASERIES_TYPE_NUMBER,
            dataSource: $this->title().'!$B$2:$B$'.$endRow,
            pointCount: $length,
        );

        $menteesData = new DataSeriesValues(
            dataType: DataSeriesValues::DATASERIES_TYPE_NUMBER,
            dataSource: $this->title().'!$C$2:$C$'.$endRow,
            pointCount: $length,
        );


        $mentorsPieChart = $this->getMentorsChart($categories, $mentorsData);
        $mentorsPieChart->setTopLeftPosition('E2');
        $mentorsPieChart->setBottomRightPosition('L20');

        $menteesPieChart = $this->getMenteesChart($categories, $menteesData);
        $menteesPieChart->setTopLeftPosition('E22');
        $menteesPieChart->setBottomRightPosition('L40');

        $comparisonChart = $this->getComparisonChart($categories, $mentorsData, $menteesData);
        $comparisonChart->setTopLeftPosition('E42');
        $comparisonChart->setBottomRightPosition('L60');

        return [$mentorsPieChart, $menteesPieChart, $comparisonChart];
    }

    private function getComparisonChart(DataSeriesValues $categories, DataSeriesValues $mentorsData, DataSeriesValues $menteesData): Chart
    {
        $labelMentor = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $this->title().'!$B$1:$B$1', null, 1);
        $labelMentee = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $this->title().'!$C$1:$C$1', null, 1);

        $comparisonSeries = new DataSeries(
            plotType: DataSeries::TYPE_BARCHART,
            plotGrouping: DataSeries::GROUPING_CLUSTERED,
            plotOrder: [0, 1],
            plotLabel: [$labelMentor, $labelMentee],
            plotCategory: [$categories],
            plotValues: [$mentorsData, $menteesData]
        );

        $comparisonPlot = new PlotArea(null, [$comparisonSeries]);

        return new Chart(
            name: 'Salīdzinājums',
            title: new Title('Mentori un mentorējamie pēc fakultātēm'),
            legend: new Legend(),
            plotArea: $comparisonPlot,
            xAxisLabel: new Title('Fakultāte'),
            yAxisLabel: new Title('Skaits')
        );
    }

    private function getMentorsChart(DataSeriesValues $categories, DataSeriesValues $mentorsData): Chart
    {
        $mentorsPieSeries = new DataSeries(
            plotType: DataSeries::TYPE_PIECHART,
            plotGrouping: null,
            plotOrder: [0],
            plotCategory: [$categories],
            plotValues: [$mentorsData]
        );

        $mentorsPiePlot = new PlotArea(null, [$mentorsPieSeries]);

        return new Chart(
            name: 'Mentoru sadalījums',
            title: new Title('Mentoru sadalījums pa fakultātēm'),
            legend: new Legend(),
            plotArea: $mentorsPiePlot
        );
    }

    private function getMenteesChart(DataSeriesValues $categories, DataSeriesValues $menteesData): Chart
    {
        $menteesPieSeries = new DataSeries(
            plotType: DataSeries::TYPE_PIECHART,
            plotGrouping: null,
            plotOrder: [0],
            plotCategory: [$categories],
            plotValues: [$menteesData]
        );

        $menteesPiePlot = new PlotArea(null, [$menteesPieSeries]);

        return  new Chart(
            name: 'Mentorējamo sadalījums',
            title: new Title('Mentorējamo sadalījums pa fakultātēm'),
            legend: new Legend(),
            plotArea: $menteesPiePlot
        );
    }
}
