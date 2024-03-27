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
use Symfony\Component\Console\Command\Command as CommandAlias;

class SendEmailsCommand extends Command
{
    private array $contacts;
    public function __construct()
    {
        parent::__construct();
        $this->contacts =  User::query()->select(['phone', 'email', 'name'])->where('use', 1)->first()->toArray();
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
        $mentors = Mentor::query()->with('students')->get();
        $students = Student::query()->with('mentor')->get();
        $events = Event::query()
            ->where(function($q){
                $q->orWhere('mentors_training', 1);
                $q->orWhere('mentees_applying', 1);
            })
            ->orderBy('date')
            ->get();

        $events->where('sent', 0)
            ->each(function(Event $event) use ($mentors){
                if(
                    $event->mentees_applying &&
                    Carbon::today()->format("d/m/y") === Carbon::parse($event->date)->format("d/m/y")
                ){
                    $this->menteesBeginToApply($mentors->where('status', 1), $event);
                    $event->update(['sent' => 1]);
                }
            });

        Mail::query()->where('sent', 0)->get()
            ->chunk(100)
            ->each(function(Collection $mails) use ($mentors, $students, $events){
                $mails->each(function(Mail $mail) use ($mentors, $students, $events){
                    switch ($mail->type){
                        case 'verification':
                            if($mail->mentor_ids){
                                $this->verification(
                                    $mentors->whereIn('id', $mail->mentor_ids)
                                );
                            }
                            break;
                        case 'verificationPassed':
                            if($mail->mentor_ids){
                                $this->verificationPassed(
                                    $mentors->whereIn('id', $mail->mentor_ids),
                                    $events
                                );
                            }
                            break;
                        case 'menteeData':
                            if($mail->mentor_ids){
                                $this->menteeData(
                                    $mentors->whereIn('id', $mail->mentor_ids)
                                );
                            }
                            break;
                        case 'mentorData':
                            if($mail->student_ids){
                                $this->mentorData(
                                    $students->whereIn('id', $mail->student_ids)
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
                                $this->custom($receivers, $mail->content);
                            }
                            break;
                    }

                    $mail->update([
                        'sent' => 1
                    ]);
                });
            });

        return CommandAlias::SUCCESS;
    }

    /**
     * @param Collection<int, Mentor> $mentors
     */
    private function verification(Collection $mentors): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables){
            $notifiables->each(function (Mentor $mentor){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'key' => $mentor->key
                ];

                if($mentor->key && $mentor->email) {
                    $mentor->notify(new VerificationMail($emailData, $this->contacts));
                }
            });
        });
    }

    /**
     * @param Collection<int, Mentor> $mentors
     * @param Collection<int, Event> $events
     */
    private function verificationPassed(Collection $mentors, Collection $events): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($events){
            $notifiables->each(function(Mentor $mentor) use ($events){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'events' => $events
                ];

                $mentor->notify(new VerificationPassedMail($emailData, $this->contacts));
            });
        });
    }

    /**
     * @param Collection<int, Mentor> $mentors
     */
    private function menteeData(Collection $mentors): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables){
            $notifiables->each(function(Mentor $mentor){
                if($mentor->students){
                    $emailData = [
                        'name' => $mentor->name,
                        'lastName' => $mentor->lastName,
                        'students' => $mentor->students
                    ];

                    $mentor->notify(new MenteeDataMail($emailData, $this->contacts));
                }
            });
        });
    }

    /**
     * @param Collection<int, Student> $students
     */
    private function mentorData(Collection $students): void
    {
        $chunk = $students->chunk(1000);
        $chunk->each(function(Collection $notifiables){
            $notifiables->each(function(Student $student){
                if($student->mentor){
                    $emailData = [
                        'name' => $student->name,
                        'lastName' => $student->lastName,
                        'mentor' => [
                            'name' => $student->mentor->name,
                            'lastName' => $student->mentor->lastName,
                            'email' => $student->mentor->email,
                            'phone' => $student->mentor->phone
                        ]
                    ];

                    if($student['email']){
                        $student->notify(new MentorDataMail($emailData, $this->contacts));

                        $this->menteeData(collect([$student->mentor]));
                    }
                }else{
                    $emailData = [
                        'name' => $student->name,
                        'lastName' => $student->lastName,
                    ];
                    if($student['email']){
                        $student->notify(new MenteeHasNoMentor($emailData, $this->contacts));
                    }
                }
            });
        });
    }


    /**
     * @param Collection<int, Mentor> $mentors
     */
    private function menteesBeginToApply(Collection $mentors, Event $event): void
    {
        $chunk = $mentors->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($event){
            $notifiables->each(function (Mentor $mentor) use ($event){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'event' => $event
                ];

                $mentor->notify(new MenteesBeginToApplyMail($emailData, $this->contacts));
            });
        });
    }

    /**
     * @param Collection<int, Mentor|Student> $receivers
     */
    private function custom(Collection $receivers, string $content): void
    {
        $chunk = $receivers->chunk(1000);
        $chunk->each(function(Collection $notifiables) use ($content){
           $notifiables->each(function(Mentor|Student $receiver) use ($content){
               $emailData = [
                   'name' => $receiver->name,
                   'lastName' => $receiver->lastName,
                   'content' => $content
               ];

               $receiver->notify(new CustomMail($emailData, $this->contacts));
           });
        });
    }
}
