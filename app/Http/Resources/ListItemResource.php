<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $player = (new PlayerResource($this->players))->toArray($request);
        $player['player_id'] = $player['id'];
        $player['id'] = $this->id;

        return [
            ...$player,
        ];
    }
}
