<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChildwithParentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'birthdate' => $this->birthdate,
            'place_of_birth' => $this->place_of_birth,
            'gender' => $this->gender,
            'photo' => $this->photo,
            'current_residence' => $this->current_residence,
            'parent' => [
                'id' => $this->parent->id,
                'first_name' => $this->parent->first_name,
                'last_name' => $this->parent->last_name,
                'job_title' => $this->parent->job_title,
                'address' => $this->parent->address,
                'Personal Phone'=>$this->parent->p,
                'user' => [ 
                    'email' => $this->parent->user->email,
                    'phone' => $this->parent->user->phone
                ]
            ],
            'application' => [
                'status' => $this->applications->first() ? $this->applications->first()->status : 'N/A',
                'date_submitted' => $this->applications->first() ? $this->applications->first()->date_submitted : 'N/A',
            ]
        ];
    }
}
