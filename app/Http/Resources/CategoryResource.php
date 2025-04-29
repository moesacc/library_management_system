<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => Str::limit($this->description,20,'...more'),
            'parent'      => $this->whenLoaded('parent', function () {
                return [
                    'id'   => $this->parent->id,
                    'name' => $this->parent->name,
                ];
            }),
            'children'    => CategoryResource::collection($this->whenLoaded('children')),
            'created_at'  => $this->created_at?->diffForHumans(),
            'updated_at'  => $this->updated_at?->diffForHumans(),
        ];
    }
}
