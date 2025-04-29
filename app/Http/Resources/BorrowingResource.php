<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        // return [
        //     "id" => $this->id,
        //     "user" ,
        //     "copy_id",
        //     "borrowed_at",
        //     "due_date",
        //     "returned_at",
        //     "created_at",
        //     "updated_at",
        // ];
    }
}
