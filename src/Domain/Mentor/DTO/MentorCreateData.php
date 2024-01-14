<?php
namespace src\Domain\Mentor\DTO;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class MentorCreateData extends Data
{
    public function __construct(
        public int $faculty_id,
        public int $program_id,
        public string $name,
        public string $lastName,
        public string $phone,
        public string $email,
        public int $mentees,
        public int $year,
        public string $about,
        public string $why,
        public bool $lv,
        public bool $ru,
        public bool $en,
        public bool $privacy,
        public string $key,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->faculty_id,
            $request->program_id,
            $request->name,
            $request->lastName,
            $request->phone,
            $request->email,
            $request->mentees,
            $request->year,
            $request->about,
            $request->why,
            $request->lv,
            $request->ru,
            $request->en,
            $request->privacy,
            Str::random(20)
        );
    }
}
