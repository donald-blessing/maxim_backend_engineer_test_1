<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'release_date' => $this->release_date,
            'rating' => $this->rating,
            'country' => $this->country,
            'genre' => $this->genre,
            'photo' => $this->photo,
            'comments' => $this->comments,
            'comments_count' => $this->comments->count(),
            'characters' => $this->characters,
            'characters_count' => $this->characters->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
