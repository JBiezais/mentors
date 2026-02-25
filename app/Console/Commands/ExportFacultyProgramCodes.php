<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use src\Domain\Faculty\Models\Faculty;

class ExportFacultyProgramCodes extends Command
{
    protected $signature = 'i18n:export-faculties-programs
                            {--output= : Write JSON to storage/app path (e.g. i18n-faculties-programs.json)}
                            {--diff : Compare with locale files and print missing keys only}';

    protected $description = 'Export faculty and program codes from the database for i18n translation';

    public function handle(): int
    {
        $faculties = Faculty::query()->with('programs')->get();

        $facultyData = $faculties->map(fn ($f) => [
            'code' => $f->code,
            'title' => $f->title,
        ])->values()->toArray();

        $programData = collect();
        foreach ($faculties as $faculty) {
            foreach ($faculty->programs ?? [] as $program) {
                $programData->push([
                    'code' => $program->code,
                    'title' => $program->title,
                    'faculty_code' => $faculty->code,
                ]);
            }
        }
        $programData = $programData->unique('code')->values()->toArray();

        $output = [
            'faculties' => $facultyData,
            'programs' => $programData,
        ];

        if ($this->option('diff')) {
            $this->printDiff($output);
            return 0;
        }

        $json = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        if ($path = $this->option('output')) {
            $fullPath = storage_path('app/' . ltrim($path, '/'));
            File::ensureDirectoryExists(dirname($fullPath));
            File::put($fullPath, $json);
            $this->info("Exported to {$fullPath}");
        } else {
            $this->line($json);
        }

        return 0;
    }

    private function printDiff(array $output): void
    {
        $lvPath = resource_path('js/locales/lv.json');
        $lv = json_decode(File::get($lvPath), true);
        $existingFaculties = array_keys($lv['faculties'] ?? []);
        $existingPrograms = array_keys($lv['programs'] ?? []);

        $dbFacultyCodes = array_column($output['faculties'], 'code');
        $dbProgramCodes = array_column($output['programs'], 'code');

        $missingFaculties = array_diff($dbFacultyCodes, $existingFaculties);
        $missingPrograms = array_diff($dbProgramCodes, $existingPrograms);

        if (!empty($missingFaculties)) {
            $this->warn('Missing faculty translations:');
            foreach ($missingFaculties as $code) {
                $item = collect($output['faculties'])->firstWhere('code', $code);
                $this->line("  {$code}: " . ($item['title'] ?? ''));
            }
        }

        if (!empty($missingPrograms)) {
            $this->warn('Missing program translations:');
            foreach ($missingPrograms as $code) {
                $item = collect($output['programs'])->firstWhere('code', $code);
                $this->line("  {$code}: " . ($item['title'] ?? ''));
            }
        }

        if (empty($missingFaculties) && empty($missingPrograms)) {
            $this->info('All faculty and program codes have translations.');
        }
    }
}
