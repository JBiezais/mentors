<?php
namespace src\Domain\Event\DTO;

use Spatie\LaravelData\Data;

class EventCreateData extends Data
{
    public function __construct(
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
