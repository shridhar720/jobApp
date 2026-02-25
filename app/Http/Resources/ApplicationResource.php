<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'job' => $this->whenLoaded('job', function () {
                return [
                    'id' => $this->job->id ?? null,
                    'title' => $this->job->title ?? null,
                ];
            }),
            'applicant' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id ?? null,
                    'name' => $this->user->name ?? null,
                    'email' => $this->user->email ?? null,
                ];
            }),
            'cv_path' => $this->cv_path,
            'cover_letter' => $this->cover_letter,
            'status' => $this->status,
            'reviewed_at' => $this->reviewed_at,
            'created_at' => $this->created_at,
        ];
    }
}
