<?php

namespace App\Services;

use App\Models\Game;
use App\Models\PlayerList;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class RegenerateListService
{
    private PlayerList $list;

    public function __construct(private Game $game)
    {
        if ($game->status === 'pending') {
            throw new \DomainException('O jogo ainda não foi finalizado');
        }
        $this->list = $game->playerList;
    }

    public function execute()
    {
        if ($this->listHas12MorePlayers()) {
            return $this->regenerateListForMore12Players();
        }

        return $this->regenarateListForLess12Players();
    }

    private function regenerateListForMore12Players()
    {
        $numberWinners = $this->getNumberOfWinners();
        if ($numberWinners < 3) {
            $this->setPlayersEndList($this->getLoserTeam());

            return;
        }
        $this->setPlayersEndList($this->getWinnerTeam());
        sleep(1);
        $this->setPlayersEndList($this->getLoserTeam());
    }

    private function regenarateListForLess12Players()
    {
        $numberWinners = $this->getNumberOfWinners();
        if ($numberWinners < 3) {
            $this->setPlayersEndList($this->getLoserTeam());

            return;
        }
        $this->setPlayersEndList($this->getLoserTeam());
        sleep(1);
        $this->setPlayersEndList($this->getWinnerTeam());
    }

    private function listHas12MorePlayers(): bool
    {
        return $this->list->items->count() > 12;
    }

    private function getNumberOfWinners()
    {
        $winners = Game::where('winner_team_id', $this->game->winner_team_id)
            ->where('player_list_id', $this->list->id)
            ->where('status', 'completed')
            ->count();

        return $winners;
    }

    private function getWinnerTeam()
    {
        return Team::find($this->game->winner_team_id);
    }

    private function getLoserTeam()
    {
        $idLoser = $this->game->away_team_id === $this->game->winner_team_id ? $this->game->home_team_id : $this->game->away_team_id;

        return Team::find($idLoser);
    }

    private function setPlayersEndList(Team $team)
    {
        /**
         * @var Collection $playersList
         */
        $playersList = $this->list->items;
        $players = $team->players;
        foreach ($players as $player) {
            /**
             * @var PlayerList $itemList
             */
            $itemList = $playersList->firstOrFail('player_id', $player->player_id);
            $itemList->touch();
        }
        $team->delete();
    }
}
