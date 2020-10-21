<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Utils\ArrayUtils;
use ArrayIterator;
use Illuminate\Database\Eloquent\Collection;
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
    public function store()
    {
        $confirmedPlayersId = array_keys(request('confirm') ?? []);

        if (empty($confirmedPlayersId) || !is_array($confirmedPlayersId) || !count($confirmedPlayersId)) {
            Session::flash('message', 'Confirm players to be able to form teams');

            return Redirect::route('players.index');
        }

        $numberOfPlayersPerTeam = (int) request('players-per-team');
        $numberOfPlayers = count($confirmedPlayersId);

        if ($numberOfPlayers < $numberOfPlayersPerTeam * 2) {
            Session::flash('message', 'Cannot shuffle because the number of confirmed players is less than the number of players per team * 2');

            return Redirect::route('players.index');
        }

        $confirmedPlayers = Player::getConfirmedPlayers($confirmedPlayersId);

        $outfieldPlayers = $confirmedPlayers->reject(function ($player) {
            return $player->goalkeeper;
        });
        $goalkeepers = $confirmedPlayers->filter(function ($player) {
            return $player->goalkeeper;
        });

        $numberOfGoalkeepers = $goalkeepers->count();
        $numberOfOutfieldPlayers = $outfieldPlayers->count();

        $numberOfTeams = $this->getNumberOfTeams($numberOfPlayersPerTeam, $numberOfOutfieldPlayers, $numberOfGoalkeepers);
        if ($numberOfTeams < 2) {
            Session::flash('message', 'Cannot shuffle because there are less than 2 full teams');

            return Redirect::route('players.index');
        }

        $teams = $this->assignGoalkeepersToTeams($goalkeepers, $numberOfTeams);

        $this->assignOutfieldPlayersToTeams($outfieldPlayers, $teams, $numberOfPlayersPerTeam);

        dd($teams);

        return Redirect::route('players.index');
    }

    private function assignGoalkeepersToTeams(Collection $goalkeepers, int $numberOfTeams): array
    {
        $teams = array_fill(0, $numberOfTeams, null);

        $goalkeepersGroupedByLevel = $goalkeepers->groupBy('level');

        $shuffledGoalkeepers = ArrayUtils::shuffleEveryElement($goalkeepersGroupedByLevel->toArray());

        $goalkeepersArray = array_values(collect($shuffledGoalkeepers)->collapse()->toArray());

        $assignableSize = min($numberOfTeams, count($goalkeepersArray));

        for ($i = 0; $i < $assignableSize; $i++) {
            $teams[$i][] = $goalkeepersArray[$i];
        }

        return $teams;
    }

    private function assignOutfieldPlayersToTeams(
        Collection $outfieldPlayers,
        array &$teams,
        int $numberOfPlayersPerTeam
    ) {
        $outfieldPlayersGroupedByLevel = $outfieldPlayers->groupBy('level');

        $shuffledOutfieldPlayers = ArrayUtils::shuffleEveryElement($outfieldPlayersGroupedByLevel->toArray());

        $outfieldPlayersArray = array_values(collect($shuffledOutfieldPlayers)->collapse()->toArray());

        $numberOfTeams = count($teams);
        $numberOfOutfieldPlayers = count($outfieldPlayersArray);

        for ($i = 0; $i < $numberOfOutfieldPlayers; $i++) {
            $team = $teams[$i % $numberOfTeams];

            if (empty($team) || count($team) < $numberOfPlayersPerTeam) {
                $teams[$i % $numberOfTeams][] = $outfieldPlayersArray[$i];
            } else {
                $teamIndex = 0;
                while ($teamIndex < count($teams) && count($teams[$teamIndex]) >= $numberOfPlayersPerTeam) {
                    $teamIndex++;
                }
                $teams[$teamIndex][] = $outfieldPlayersArray[$i];;
            }

            unset($outfieldPlayersArray[$i]);
        }

        // for ($i = $numberOfTeams - 2; $i >= 0; $i--) {
        //     $lastTeam = $teams[$numberOfTeams - 1];

        //     $removedFirstPlayerOfTheLastTeam = $lastTeam[0];

        //     unset($lastTeam[0]);

        //     if (count($teams[$i]) < $numberOfPlayersPerTeam) {
        //         $teams[$i][] = $removedFirstPlayerOfTheLastTeam;
        //     }
        // }
    }

    private function getNumberOfTeams(
        int $numberOfPlayersPerTeam,
        int $numberOfOutfieldPlayers,
        int $numberOfGoalkeepers
    ): int {
        $numberOfTeams = 0;

        while ($numberOfOutfieldPlayers > 0) {
            $numberOfOutfieldPlayers -= $numberOfPlayersPerTeam - ($numberOfGoalkeepers > 0 ? 1 : 0);
            $numberOfGoalkeepers--;
            $numberOfTeams++;
        }

        return $numberOfTeams;
    }
}
