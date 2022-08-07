<?php

namespace App\Http\Requests;

use App\Models\TeamPlayer;
use Illuminate\Foundation\Http\FormRequest;

class RemovePlayerToGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $game = $this->game;

        return [
            'player_id' => [
                'required',
                function ($attribute, $value, $fail) use ($game) {
                    $homeTeam = $game->home_team_id;
                    $awayTeam = $game->away_team_id;
                    $playersHome = TeamPlayer::where('team_id', $homeTeam)->where('player_id', $value)->get();
                    $playersAway = TeamPlayer::where('team_id', $awayTeam)->where('player_id', $value)->get();
                    if (count($playersHome) === 0 && count($playersAway) === 0) {
                        $fail('O jogador não está na lista de jogadores do time');
                    }
                },
            ],
        ];
    }
}
