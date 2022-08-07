<?php

namespace App\Services;

use App\Models\Game;

class EndGameService
{
    const MAX_SCORE_WINNER = 14;

    const MIN_SCORE_WINNER = 12;

    const MAX_SCORE_LOSER = 10;

    public function __construct(private Game $game)
    {
        if ($game->status === 'completed') {
            throw new \DomainException('O jogo já foi finalizado');
        }
    }

    public function execute()
    {
        if (! $this->hasWinner()) {
            throw new \DomainException('Não há vencedor');
        }
        $winner = $this->getWinner();
        $this->game->winner_team_id = $winner;
        $this->game->status = 'completed';
        $this->game->save();

        return $this->game;
    }

    private function hasWinner()
    {
        $homeTeamScore = $this->game->home_team_score;
        $awayTeamScore = $this->game->away_team_score;
        $maxScore = max($homeTeamScore, $awayTeamScore);
        $minScore = min($homeTeamScore, $awayTeamScore);

        if ($maxScore < $this::MIN_SCORE_WINNER) {
            return false;
        }
        if ($maxScore === $this::MAX_SCORE_WINNER) {
            return true;
        }
        if ($minScore > $this::MAX_SCORE_LOSER) {
            return false;
        }

        return true;
    }

    private function getWinner()
    {
        return $this->game->home_team_score > $this->game->away_team_score ? $this->game->home_team_id : $this->game->away_team_id;
    }
}
