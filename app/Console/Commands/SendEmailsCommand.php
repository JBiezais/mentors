<?php

namespace App\Console\Commands;

use App\Mail\CustomMail;
use App\Mail\MenteeDataMail;
use App\Mail\MenteeHasNoMentor;
use App\Mail\MenteesBeginToApplyMail;
use App\Mail\MentorDataMail;
use App\Mail\MentorTrainingEventMail;
use App\Mail\VerificationMail;
use App\Mail\VerificationPassedMail;
use App\Models\Event;
use App\Models\Mail;
use App\Models\Mentor;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail as SendMail;

class SendEmailsCommand extends Command
{
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
    public function handle()
    {
        $events = Event::query()
            ->where(function($q){
                $q->orWhere('date', '>', Carbon::now()->subDay());
                $q->orWhere('date', '<', Carbon::now()->subDay());
            })
            ->where('sent', 0)
            ->where(function($q){
                $q->orWhere('mentors_training', 1);
                $q->orWhere('mentees_applying', 1);
            })
            ->get();
        if(!empty($events)){
            $mentors = Mentor::query()->where('status', 1)->get();
            foreach ($events as $event){
                if($event['mentors_training']){
                    $this->mentorTraining($mentors, $event);
                    $event->update(['sent' => 1]);
                }
                if($event['mentees_applying']){
                    $this->menteesBeginToApply($mentors, $event);
                    $event->update(['sent' => 1]);
                }
            }
        }

        $mails = Mail::query()->where('sent', 0)->get();
        if(!empty($mails)){
            foreach ($mails as $mail){
                switch ($mail->type){
                    case 'verification':
                        if($mail->mentor_ids){
                            $mentors = Mentor::query()
                                        ->select('id', 'name', 'lastName', 'email', 'key')
                                        ->whereIn('id', json_decode($mail->mentor_ids))
                                        ->get();
                            $this->verification($mentors);
                        }
                        break;
                    case 'mentorData':
                        if($mail->student_ids){
                            $students = Student::query()
                                        ->with('mentor')
                                        ->select('id', 'mentor_id', 'name', 'lastName', 'email')
                                        ->whereIn('id', json_decode($mail->student_ids))
                                        ->get();
                            $this->mentorData($students);
                        }
                        break;
                    case 'verificationPassed':
                        if($mail->mentor_ids){
                            $mentors = Mentor::query()
                                ->select('id', 'name', 'lastName', 'email')
                                ->whereIn('id', json_decode($mail->mentor_ids))
                                ->get();
                            $this->verificationPassed($mentors);
                        }
                        break;
                    case 'menteeData':
                        if($mail->mentor_ids){
                            $mentors = Mentor::query()
                                ->with('students')
                                ->select('id', 'name', 'lastName', 'email', 'key')
                                ->whereIn('id', json_decode($mail->mentor_ids))
                                ->get();
                            $this->menteeData($mentors);
                        }
                        break;
                    case 'custom':
                        $receivers = null;

                        if($mail->mentor_ids){
                            $receivers = Mentor::query()
                                ->select('id', 'name', 'lastName', 'email', 'key')
                                ->whereIn('id', json_decode($mail->mentor_ids))
                                ->get();
                        }
                        if($mail->student_ids){
                            $receivers = Student::query()
                                ->select('id', 'mentor_id', 'name', 'lastName', 'email')
                                ->whereIn('id', json_decode($mail->student_ids))
                                ->get();
                        }
                        if($receivers){
                            $this->custom($receivers, $mail->content);
                        }
                        break;
                }

                $mail->update([
                    'sent' => 1
                ]);
            }
        }else{
            $this->info('There is no emails queued');
        }

        return Command::SUCCESS;
    }

    private function verification($mentors){

        foreach ($mentors as $mentor){
            $emailData = [
                'name' => $mentor['name'],
                'lastName' => $mentor['lastName'],
                'key' => $mentor['key']
            ];
            if($mentor['key'] && $mentor['email']){
                SendMail::to($mentor['email'])->send(new VerificationMail($emailData));
                $this->info('Verification mail has been sent to '.$mentor['email']);
            }else{
                $this->info('Mentor has no verification key');
            }

        }
    }

    private function mentorData($students){
        foreach ($students as $student){
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
                    SendMail::to($student['email'])->send(new MentorDataMail($emailData));
                    $this->info('Mentor Data mail has been sent to '. $student['email']);
                }else{
                    $this->info('Student has not provided email');
                }
            }else{
                $this->info('Student has no mentor');

                $emailData = [
                    'name' => $student->name,
                    'lastName' => $student->lastName,
                ];
                if($student['email']){
                    SendMail::to($student['email'])->send(new MenteeHasNoMentor($emailData));
                    $this->info('Student sent confirmation mail');
                }
            }
        }
    }

    private function menteeData($mentors){
        foreach ($mentors as $mentor){
            if($mentor->students){
                $emailData = [
                    'name' => $mentor->name,
                    'lastName' => $mentor->lastName,
                    'students' => $mentor->students
                ];
                SendMail::to($mentor['email'])->send(new MenteeDataMail($emailData));
                $this->info('Mentees Data mail has been sent to '. $mentor['email']);
            }else{
                $this->info('Mentor has no mentees');
            }
        }
    }

    private function verificationPassed($mentors){
        $events = Event::query()->where(function($q){
            $q->orWhere('mentors_training', 1);
            $q->orWhere('mentees_applying', 1);
        })->orderBy('date')->get();
        foreach ($mentors as $mentor){
            $emailData = [
                'name' => $mentor->name,
                'lastName' => $mentor->lastName,
                'events' => $events
            ];
            SendMail::to($mentor['email'])->send(new VerificationPassedMail($emailData));
            $this->info('Verification Passed mail has been sent to '. $mentor['email']);
        }
    }

    private function mentorTraining($mentors, $event){
        foreach ($mentors as $mentor){
            $emailData = [
                'name' => $mentor->name,
                'lastName' => $mentor->lastName,
                'event' => $event
            ];
            SendMail::to($mentor['email'])->send(new MentorTrainingEventMail($emailData));
            $this->info('Mentor Training mail has been sent to '. $mentor['email']);
        }
    }

    private function menteesBeginToApply($mentors, $event){
        foreach ($mentors as $mentor){
            $emailData = [
                'name' => $mentor->name,
                'lastName' => $mentor->lastName,
                'event' => $event
            ];
            SendMail::to($mentor['email'])->send(new MenteesBeginToApplyMail($emailData));
            $this->info('Mentees Begin To Apply mail has been sent to '. $mentor['email']);
        }
    }

    private function custom($receivers, $data){
        foreach ($receivers as $receiver){
            $emailData = [
                'name' => $receiver->name,
                'lastName' => $receiver->lastName,
                'content' => $data
            ];
            SendMail::to($receiver['email'])->send(new CustomMail($emailData));
            $this->info('Mentees Begin To Apply mail has been sent to '. $receiver['email']);
        }
    }
}
