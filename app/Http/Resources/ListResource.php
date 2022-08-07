<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $items = ListItemResource::collection($this->items);
        $quantity = $items->count();

        return [
            'id' => $this->id,
            'quantity' => $quantity,
            'date' => $this->created_at->format('Y-m-d'),
            'items' => $items,
        ];
    }
}
