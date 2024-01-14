<?php
namespace src\Domain\Event\DTO;

use Spatie\LaravelData\Data;

class EventUpdateData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $date,
        public ?string $location,
        public ?bool $mentors_training,
        public ?bool $mentees_applying,
        public ?string $description,
        public ?string $link
    ) {
    }
}
