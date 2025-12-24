<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,

            'category' => [
                'id'   => $this->category_id,
                'name' => $this->category?->name,
            ],

            'status' => [
                'key'   => $this->status->value,
                'label' => $this->status->label(),
            ],

            'user' => [
                'id'   => $this->user_id,
                'name' => $this->user?->name,
            ],

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
