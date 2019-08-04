<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminAuthResource extends JsonResource
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
            'code' => $this->employee_code,
            'region' => ($this->region === 'NG') ? "Nigeria" : "United Kingdom",
            'created' => $this->getHumanCreatedAt(),
            'role' => ($this->getRoleNames()) ? $this->getRoleNames() : "None assigned"
        ];
    }
}
