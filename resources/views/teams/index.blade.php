@extends('layouts.app')

@section('title', 'Created Team')

@section('content')

<div style="margin-bottom: 20px;">
    <a class="btn btn-secondary" href="{{ route('players.index') }}"> Back</a>
</div>

<div class="row">
    <div class="col-12">
        <h2>Shuffled Teams</h2>
    </div>
</div>

<br>

@if (count($teams))
<table class="table table-bordered">
    <tr>
        <th>#</th>
        <th>Players</th>
        <th>Points</th>
    </tr>
    @php $i = 1 @endphp
    @foreach ($teams as $team)
    @php $points = 0 @endphp
    <tr>
        <td>{{ $i++ }}</td>
        <td>
            <ul>
                @foreach($team as $player)
                    <li>
                        {{ $player['name'] }}, {{ $player['level'] }}{{ $player['goalkeeper'] ? ', Goalkeeper' : '' }}
                    </li>
                    @php $points += $player['level'] @endphp
                @endforeach
            </ul>
        </td>
        <td>{{ $points }}</td>
    </tr>
    @endforeach
</table>
@else
<p>No teams were created/shuffled</p>
@endif

@endsection
