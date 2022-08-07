<?php

namespace App\Http\Controllers;

use App\Models\PlayerList;
use App\Services\TeamsService;

class TeamsController extends Controller
{
    public function createTeamsToGame(PlayerList $playerList)
    {
        $serviceTeams = new TeamsService($playerList);
        $times = $serviceTeams->createTeamsToGame();

        return response()->json($times);
    }
}
