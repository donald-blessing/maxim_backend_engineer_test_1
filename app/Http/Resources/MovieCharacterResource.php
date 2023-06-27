<?php

namespace App\Http\Resources;

use App\Traits\GeneralHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieCharacterResource extends JsonResource
{
    use GeneralHelperTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movie_id' => $this->movie,
            'name' => $this->name,
            'gender' => $this->gender,
            'height' => [
                'cm' => $this->height,
                'feet_inches' => self::cm2feet($this->height),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
