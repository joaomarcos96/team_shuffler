<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class TeamController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $confirmedPlayersId = array_keys($request->get('confirm') ?? []);

        if (empty($confirmedPlayersId) || !is_array($confirmedPlayersId) || !count($confirmedPlayersId)) {
            Session::flash('message', 'Confirm players to be able to form teams');

            return Redirect::route('players.index');
        }

        /** @var Player[] $confirmedPlayers */
        $confirmedPlayers = Player::whereIn('id', $confirmedPlayersId)
            ->orderBy('level', 'ASC')
            ->get();

        // Group by level
        $playersGroupedByLevel = [];
        foreach ($confirmedPlayers as $player) {
            $playersGroupedByLevel[$player->level][] = $player;
        }

        // Shuffle
        foreach ($playersGroupedByLevel as $players) {
            shuffle($players);
        }

        return Redirect::route('players.index');
    }
}
