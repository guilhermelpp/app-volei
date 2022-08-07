<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlayerListItemRequest extends FormRequest
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
        // dd($this->playerList);

        return [
            'player_id' => [
                'required',
                Rule::exists('players', 'id'),
                Rule::unique('player_list_items', 'player_id')->where('player_list_id', $this->playerList->id),
            ],
        ];
    }
}
