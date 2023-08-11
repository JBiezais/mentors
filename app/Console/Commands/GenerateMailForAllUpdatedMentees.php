<?php

namespace App\Console\Commands;

use App\Models\Mail;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMailForAllUpdatedMentees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:mentors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $students = Student::query()->whereNotNull('mentor_id')->get()->filter(function (Student $student){
            return $student->updated_at->gt($student->created_at);
        });

        $studentIds = $students->pluck('id')->toArray();
        $mentorIds = array_unique($students->pluck('mentor_id')->toArray());

        Mail::create([
            'mentor_ids' => null,
            'student_ids' => json_encode($studentIds),
            'content' => null,
            'type' => 'mentorData'
        ]);

        Mail::create([
            'mentor_ids' => json_encode($mentorIds),
            'student_ids' => null,
            'content' => null,
            'type' => 'menteeData'
        ]);
    }
}
