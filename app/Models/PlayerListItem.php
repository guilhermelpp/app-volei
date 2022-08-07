<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'player_list_id',
    ];

    public function players()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
