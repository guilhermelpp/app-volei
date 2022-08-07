<?php

namespace App\Services;

use App\Models\PlayerList;
use App\Models\Team;
use DomainException;
use Illuminate\Database\Eloquent\Collection;

class TeamsService
{
    private const NAME_TEAM_1 = 'Time A';

    private const NAME_TEAM_2 = 'Time B';

    public function __construct(private PlayerList $playerList)
    {
    }

    public function createTeamsToGame()
    {
        $teams = $this->getTeamsActive();
        if (count($teams) === 2) {
            return [$teams[0], $teams[1]];
        }
        if (count($teams) === 0) {
            return $this->createTwoTeams();
        }
        $name = $teams->first()->name;
        $nameNovo = $name === $this::NAME_TEAM_1 ? $this::NAME_TEAM_2 : $this::NAME_TEAM_1;

        $timeNovo = $this->createOneTeam($nameNovo);

        $retorno = $nameNovo === $this::NAME_TEAM_1 ? [$timeNovo, $teams[0]] : [$teams[0], $timeNovo];

        return $retorno;
    }

    private function createTwoTeams()
    {
        $players = $this->playerList->items()->limit(8)->get();
        if (count($players) < 8) {
            throw new DomainException('É preciso ter pelo menos 8 jogadores para criar o jogo');
        }
        $timeA = $this->createTeam($players->take(4), $this::NAME_TEAM_1);
        $timeB = $this->createTeam($players->skip(4)->take(4), $this::NAME_TEAM_2);

        return [$timeA, $timeB];
    }

    private function createOneTeam(string $name)
    {
        $players = $this->playerList->items()->limit(4)->get();
        if (count($players) < 4) {
            throw new DomainException('É preciso ter pelo menos 4 jogadores para criar o jogo');
        }
        $time = $this->createTeam($players, $name);

        return $time;
    }

    private function createTeam(Collection $players, string $name)
    {
        $team = new Team(['name' => $name, 'player_list_id' => $this->playerList->id]);
        $team->save();
        foreach ($players as $player) {
            $team->players()->create(['player_id' => $player->id]);
        }

        return $team;
    }

    private function getTeamsActive()
    {
        return Team::where('player_list_id', $this->playerList->id)->orderBy('name')->get();
    }
}
