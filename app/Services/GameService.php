<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;

class GameService
{
    private int $playerListId;

    public function __construct(private Team $homeTeam, private Team $awayTeam)
    {
        if ($awayTeam->player_list_id !== $homeTeam->player_list_id) {
            throw new \DomainException('O time deve ter o mesmo time lista');
        }
        if ($awayTeam->id === $homeTeam->id) {
            throw new \DomainException('Os times devem ser diferentes');
        }
        $this->playerListId = $homeTeam->player_list_id;
    }

    public function createGame()
    {
        if ($this->hasGame()) {
            return Game::where('player_list_id', $this->playerListId)
            ->where('status', 'pending')->first();
        }
        $homeTeam = $this->homeTeam;
        $awayTeam = $this->awayTeam;
        $game = new Game();
        $game->away_team_id = $awayTeam->id;
        $game->home_team_id = $homeTeam->id;
        $game->player_list_id = $homeTeam->player_list_id;
        $game->save();

        return $game;
    }

    private function hasGame(): bool
    {
        return Game::where('player_list_id', $this->playerListId)
            ->where('status', 'pending')->exists();
    }
}
