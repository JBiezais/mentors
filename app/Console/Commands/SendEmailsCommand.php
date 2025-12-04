<?php

namespace App\Console\Commands;

use App\Mail\CustomMail;
use App\Mail\MenteeDataMail;
use App\Mail\MenteeHasNoMentor;
use App\Mail\MenteesBeginToApplyMail;
use App\Mail\MentorDataMail;
use App\Mail\VerificationMail;
use App\Mail\VerificationPassedMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use src\Domain\Event\Models\Event;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;

class SendEmailsCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

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

    public function handle(): int
    {
        $mentors = Mentor::with('students')->get();
        $students = Student::with('mentor')->get();

        $contacts = User::query()
            ->select(['phone', 'email', 'name'])
            ->where('use', 1)
            ->first()
            ->toArray();

        $events = Event::query()
            ->where(
                function ($q) {
                    $q->where('mentors_training', 1)
                        ->orWhere('mentees_applying', 1);
                }
            )
            ->where('sent', 0)
            ->whereDate('date', now())
            ->orderBy('date')
            ->get();

        $events->each(function ($event) use ($mentors, $contacts) {
            $this->menteesBeginToApply($mentors->where('status', 1), $event, $contacts);
            $event->sent = 1;
            $event->save();
        });

        Mail::query()
            ->where('sent', 0)
            ->get()
            ->chunk(100)
            ->each(function ($mails) use ($mentors, $students, $events, $contacts) {
                $mails->each(function ($mail) use ($mentors, $students, $events, $contacts) {
                    $this->processMail($mail, $mentors, $students, $events, $contacts);
                    $mail->sent = 1;
                    $mail->save();
                });
            });

        return Command::SUCCESS;
    }

    /**
     * @param Mail $mail
     * @param Collection<int, Mentor> $mentors
     * @param Collection<int, Student> $students
     * @param Collection<int, Event> $events
     */
    protected function processMail(Mail $mail, Collection $mentors, Collection $students, Collection $events, array $contacts): void
    {
        switch ($mail->type){
            case 'verification':
                if($mail->mentor_ids){
                    $this->verification(
                        $mentors->whereIn('id', $mail->mentor_ids),
                        $contacts
                    );
                }
                break;
            case 'verificationPassed':
                if($mail->mentor_ids){
                    $this->verificationPassed(
                        $mentors->whereIn('id', $mail->mentor_ids),
                        $events,
                        $contacts
                    );
                }
                break;
            case 'menteeData':
                if($mail->mentor_ids){
                    $this->menteeData(
                        $mentors->whereIn('id', $mail->mentor_ids),
                        $contacts
                    );
                }
                break;
            case 'mentorData':
                if($mail->student_ids){
                    $this->mentorData(
                        $students->whereIn('id', $mail->student_ids),
                        $contacts
                    );
                }
                break;
            case 'custom':
                $receivers = null;

                if($mail->mentor_ids){
                    $receivers = $mentors->whereIn('id', $mail->mentor_ids);
                }
                if($mail->student_ids){
                    $receivers = $students->whereIn('id', $mail->student_ids);
                }

                if($receivers){
                    $this->custom($receivers, $mail->content, $contacts);
                }
                break;
        }
    }

    /**
     * @param Collection<int, Mentor> $mentors
     */
    private function verification(Collection $mentors, array $contacts): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($contacts){
            $notifiables->each(function (Mentor $mentor) use ($contacts){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'email' => $mentor->email,
                    'key' => $mentor->key
                ];

                if($mentor->key && $mentor->email) {
                    $notification = new VerificationMail($emailData, $contacts);
                    $notification->sendEmail();
                }
            });
        });
    }

    /**
     * @param Collection<int, Mentor> $mentors
     * @param Collection<int, Event> $events
     */
    private function verificationPassed(Collection $mentors, Collection $events, array $contacts): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($events, $contacts){
            $notifiables->each(function(Mentor $mentor) use ($events, $contacts){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'events' => $events,
                    'email' => $mentor->email
                ];

                (new VerificationPassedMail($emailData, $contacts))->sendEmail();
            });
        });
    }

    /**
     * @param Collection<int, Mentor> $mentors
     */
    private function menteeData(Collection $mentors, array $contacts): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($contacts){
            $notifiables->each(function(Mentor $mentor) use ($contacts){
                if($mentor->students){
                    $emailData = [
                        'name' => $mentor->name,
                        'lastName' => $mentor->lastName,
                        'students' => $mentor->students,
                        'email' => $mentor->email
                    ];

                    (new MenteeDataMail($emailData, $contacts))->sendEmail();;
                }
            });
        });
    }

    /**
     * @param Collection<int, Student> $students
     */
    private function mentorData(Collection $students, array $contacts): void
    {
        $chunk = $students->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($contacts){
            $notifiables->each(function(Student $student) use ($contacts){
                if($student->mentor){
                    $emailData = [
                        'name' => $student->name,
                        'lastName' => $student->lastName,
                        'mentor' => [
                            'name' => $student->mentor->name,
                            'lastName' => $student->mentor->lastName,
                            'email' => $student->mentor->email,
                            'phone' => $student->mentor->phone
                        ],
                        'email' => $student->email
                    ];

                    if($student['email']){
                        (new MentorDataMail($emailData, $contacts))->sendEmail();;

                        $this->menteeData(collect([$student->mentor]), $contacts);
                    }
                }else{
                    $emailData = [
                        'name' => $student->name,
                        'lastName' => $student->lastName,
                        'email' => $student->email
                    ];
                    if($student['email']){
                        (new MenteeHasNoMentor($emailData, $contacts))->sendEmail();;
                    }
                }
            });
        });
    }


    /**
     * @param Collection<int, Mentor> $mentors
     */
    private function menteesBeginToApply(Collection $mentors, Event $event, array $contacts): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($event, $contacts){
            $notifiables->each(function (Mentor $mentor) use ($event, $contacts){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'event' => $event,
                    'email' => $mentor->email
                ];

                (new MenteesBeginToApplyMail($emailData, $contacts))->sendEmail();;
            });
        });
    }

    /**
     * @param Collection<int, Mentor|Student> $receivers
     */
    private function custom(Collection $receivers, string $content, array $contacts): void
    {
        $chunk = $receivers->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($content, $contacts){
           $notifiables->each(function(Mentor|Student $receiver) use ($content, $contacts){
               $emailData = [
                   'name' => $receiver->name,
                   'lastName' => $receiver->lastName,
                   'content' => $content,
                   'email' => $receiver->email
               ];

               (new CustomMail($emailData, $contacts))->sendEmail();;
           });
        });
    }
}
