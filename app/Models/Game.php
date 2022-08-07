<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'winner_team_id',
        'home_team_score',
        'away_team_score',
        'status',
    ];

    public function playerList()
    {
        return $this->belongsTo(PlayerList::class);
    }
}
