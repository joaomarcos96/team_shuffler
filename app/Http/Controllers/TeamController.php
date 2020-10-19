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

        $numberOfPlayersPerTeam = (int) $request->get('players-per-team');
        $numberOfPlayers = count($confirmedPlayersId);

        if ($numberOfPlayers < $numberOfPlayersPerTeam * 2) {
            Session::flash('message', 'Cannot shuffle because the number of confirmed players is less than the number of players per team * 2');

            return Redirect::route('players.index');
        }

        $confirmedPlayers = Player::getConfirmedPlayers($confirmedPlayersId);
        $goalkeepers = $confirmedPlayers->filter(function ($player) {
            return $player->goalkeeper;
        });

        $numberOfGoalkeepers = $goalkeepers->count();
        $numberOfOutfieldPlayers = $numberOfPlayers - $numberOfGoalkeepers;

        $numberOfTeams = $this->getNumberOfTeams($numberOfPlayersPerTeam, $numberOfOutfieldPlayers, $numberOfGoalkeepers);
        if ($numberOfTeams < 2) {
            Session::flash('message', 'Cannot shuffle because there are less than 2 full teams');

            return Redirect::route('players.index');
        }

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

    /**
     * @param int $numberOfPlayersPerTeam
     * @param int $numberOfOutfieldPlayers
     * @param int $numberOfGoalkeepers
     * @return int
     */
    private function getNumberOfTeams($numberOfPlayersPerTeam, $numberOfOutfieldPlayers, $numberOfGoalkeepers)
    {
        $numberOfTeams = 0;

        while ($numberOfOutfieldPlayers > 0) {
            $numberOfOutfieldPlayers -= $numberOfPlayersPerTeam - ($numberOfGoalkeepers > 0 ? 1 : 0);
            $numberOfGoalkeepers--;
            $numberOfTeams++;
        }

        return $numberOfTeams;
    }
}
