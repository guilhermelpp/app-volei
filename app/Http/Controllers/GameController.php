<?php

namespace App\Http\Controllers;

use App\Http\Requests\RemovePlayerToGameRequest;
use App\Http\Requests\UpdateGameScoreRequest;
use App\Models\Game;
use App\Models\PlayerList;
use App\Models\PlayerListItem;
use App\Services\CancelGameService;
use App\Services\EndGameService;
use App\Services\GameService;
use App\Services\RegenerateListService;
use App\Services\TeamsService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function createGame(PlayerList $playerList)
    {
        [$timeA, $timeB] = $this->createTeamsToGame($playerList);
        $serviceTeams = new GameService($timeA, $timeB);
        $game = $serviceTeams->createGame();

        return response()->json(['game' => $game, 'timeA' => $timeA, 'timeB' => $timeB]);
    }

    private function createTeamsToGame(PlayerList $playerList)
    {
        $serviceTeams = new TeamsService($playerList);
        $times = $serviceTeams->createTeamsToGame();

        return $times;
    }

    public function update(Request $request, Game $game)
    {
        return $game;
    }

    public function updateScore(UpdateGameScoreRequest $request, Game $game)
    {
        $game->update($request->validated());

        return $game;
    }

    public function endGame(Game $game)
    {
        $endGameServeice = new EndGameService($game);
        $game = $endGameServeice->execute();

        $regenerateListService = new RegenerateListService($game);
        $regenerateListService->execute();

        return $game;
    }

    public function removePlayerToGame(RemovePlayerToGameRequest $request, Game $game)
    {
        $idPlayer = $request->get('player_id');
        $list = PlayerListItem::where('player_id', $idPlayer)->where('player_list_id', $game->player_list_id)->firstOrFail();
        $list->touch();
        $cancelGame = new CancelGameService($game);

        $cancelGame->execute();

        return response()->json([], 204);
    }
}
