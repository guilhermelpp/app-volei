<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerListItemRequest;
use App\Http\Requests\StorePlayerListRequest;
use App\Http\Requests\UpdatePlayerListRequest;
use App\Http\Resources\ListResource;
use App\Models\PlayerList;
use DateTime;

class PlayerListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ListResource::collection(PlayerList::orderBy('updated_at', 'asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePlayerListRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlayerListRequest $request)
    {
        $date = (new DateTime())->format('Y-m-d');
        $playerList = PlayerList::findByDate($date);

        if ($playerList->count() !== 0) {
            return response()->json(['message' => 'Já existe uma lista para hoje'], 400);
        }

        return PlayerList::create($request->validated());
    }

    public function storePlayer(StorePlayerListItemRequest $request, PlayerList $playerList)
    {
        $data = [...$request->validated(), 'player_list_id' => $playerList->id];

        return $playerList->items()->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PlayerList  $playerList
     * @return \Illuminate\Http\Response
     */
    public function show(PlayerList $playerList)
    {
        return new ListResource($playerList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePlayerListRequest  $request
     * @param  \App\Models\PlayerList  $playerList
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlayerListRequest $request, PlayerList $playerList)
    {
        return $playerList->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PlayerList  $playerList
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlayerList $playerList)
    {
        return $playerList->delete();
    }
}
