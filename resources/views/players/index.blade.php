@extends('layouts.app')

@section('title', 'Players')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 style="float: left; margin-right: 40px;">Team Shuffler</h2>
        @if (count($players))
        <a class="btn btn-info" href="#">
            Shuffle
        </a>
        @endif
        <a class="btn btn-success" href="{{ route('players.create') }}">
            Add player
        </a>
    </div>
</div>

<br>

@if (count($players))
<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Level</th>
        <th>Goalkeeper</th>
        <th>Action</th>
    </tr>
    @foreach ($players as $player)
    <tr>
        <td>{{ $player->name }}</td>
        <td>{{ $player->level }}</td>
        <td>{{ $player->goalkeeper ? 'Yes' : 'No' }}</td>
        <td>
            <form action="{{ route('players.destroy',$player->id) }}" method="POST">
                <a class="btn btn-primary" href="{{ route('players.edit', $player->id) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@else
<p>Add players to shuffle</p>
@endif

@endsection
