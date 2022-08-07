<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;

class CancelGameService
{
    public function __construct(private Game $game)
    {
        if ($game->status === 'completed') {
            throw new \DomainException('O jogo já foi finalizado');
        }
    }

    public function execute()
    {
        $teamHome = Team::find($this->game->home_team_id);
        $teamAway = Team::find($this->game->away_team_id);
        $teamHome->delete();
        $teamAway->delete();
        $this->game->delete();
    }
}
