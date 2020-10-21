<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PlayerController extends Controller
{
    /**
     * @return Response
     */
    public function index()
    {
        $players = Player::all();

        return view('players.index', compact('players'));
    }

    /**
     * @return Response
     */
    public function create()
    {
        return view('players.create');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->handleGoalkeeperField($request);

        $request->validate(Player::$createValidationRules);

        Player::create($request->all());

        Session::flash('message', 'Player added successfully');

        return Redirect::route('players.index');
    }

    /**
     * @param Player $player
     * @return Response
     */
    public function edit(Player $player)
    {
        return view('players.edit', compact('player'));
    }

    /**
     * @param Request $request
     * @param Player $player
     * @return Response
     */
    public function update(Request $request, Player $player)
    {
        $this->handleGoalkeeperField($request);

        $request->validate(Player::$updateValidationRules);

        $player->update($request->all());

        Session::flash('message', 'Player updated successfully');

        return Redirect::route('players.index');
    }

    /**
     * @param Player $player
     * @return Response
     */
    public function destroy(Player $player)
    {
        $player->delete();

        Session::flash('message', 'Player deleted successfully');

        return Redirect::route('players.index');
    }

    /**
     * Handle the goalkeeper field in request params
     *
     * @param Request $request
     * @return void
     */
    private function handleGoalkeeperField(Request $request)
    {
        $goalkeeperValues = ['on', true, 'true', 1, '1'];

        $request->merge([
            'goalkeeper' => in_array(request('goalkeeper'), $goalkeeperValues)
        ]);
    }
}
