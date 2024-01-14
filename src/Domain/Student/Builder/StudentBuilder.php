<?php

namespace src\Domain\Student\Builder;

use src\Domain\Shared\Contracts\FilterFromRequestBuilder;

class StudentBuilder extends FilterFromRequestBuilder
{
    public function requested(): StudentBuilder
    {
        return $this->whereNull('mentor_id');
    }

    public function confirmed(): StudentBuilder
    {
        return $this->whereNotNull('mentor_id');
    }
}
