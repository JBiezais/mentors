<?php

namespace src\Domain\Shared\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class FilterFromRequestBuilder extends Builder
{
    public function filterFromRequest(Request $request): self
    {
        if($request->keyword !== null){
            $values = explode(' ', trim($request->keyword));
            $this->keyword($values);
        }

        if($request->type !== null){
            switch ($request->type){
                case 'requested':
                    $this->requested();
                    break;
                case 'confirmed':
                    $this->confirmed();
                    break;
                default:
                    break;
            }
        }

        if($request->faculty !== null){
            $this->memberOfFaculty($request->faculty);
        }

        if($request->program !== null){
            $this->memberOfProgram($request->program);
        }

        return $this;
    }

    private function keyword(array $keywords): self
    {
        return $this->where(function($query) use ($keywords) {
            $query->orWhereIn('name', $keywords);
            $query->orWhereIn('lastName', $keywords);
        });
    }

    private function memberOfFaculty($facultyId): self
    {
        return $this->where('faculty_id', $facultyId);
    }

    public function memberOfProgram($programId): self
    {
        return $this->where('program_id', $programId);
    }

    abstract public function requested(): self;
    abstract public function confirmed(): self;
}
