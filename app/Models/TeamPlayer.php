<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamPlayer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'player_id',
    ];

    public function players()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
