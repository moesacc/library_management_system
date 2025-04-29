<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'summary' => $this->summary,
            'status' => $this->status,
            'published_at' => $this->published_at?->diffForHumans(),
            'created_at'  => $this->created_at?->diffForHumans(),
            'updated_at'  => $this->updated_at?->diffForHumans(),
            'author'    => new AuthorResource($this->whenLoaded('author')),
            'category'    => new CategoryResource($this->whenLoaded('category')),
            'copies'    => CopyResource::collection($this->whenLoaded('copies')),
        ];
    }
}
