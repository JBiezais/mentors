<?php

namespace src\Domain\Mentor\Builders;

use src\Domain\Shared\Contracts\FilterFromRequestBuilder;

class MentorBuilder extends FilterFromRequestBuilder
{
    public function requested(): MentorBuilder
    {
        return $this->where('status', 0);
    }

    public function confirmed(): MentorBuilder
    {
        return $this->where('status', 1);
    }
}
