<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'firstname' => $this->profile->firstname,
            'lastname' => $this->profile->lastname,
            'middlename' => $this->profile->middlename,
            'phone' => $this->profile->phone,
            'sex' => $this->profile->sex,
            'nationality' => $this->profile->nationality,
            'city' => $this->profile->city,
            'address' => $this->profile->address,
            'description' => $this->profile->description,
            'status' => ($this->status === 0) ? "Active" : "Inactive",
            'birthday' => $this->profile->dob,
            'created' => $this->getHumanCreatedAt()
        ];
    }
}
