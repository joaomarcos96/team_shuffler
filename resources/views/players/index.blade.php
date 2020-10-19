@extends('layouts.app')

@push('scripts')
    <script src="{{ asset('js/players.js') }}"></script>
@endpush

@section('title', 'Players')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 style="float: left; margin-right: 40px;">Team Shuffler</h2>
        @if (count($players))
        <button class="btn btn-info" data-toggle="modal" data-target="#shuffle-modal">
            Shuffle
        </button>
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
        <th>#</th>
        <th>Name</th>
        <th>Level</th>
        <th>Goalkeeper</th>
        <th>Action</th>
    </tr>
    @php $i = 1 @endphp
    @foreach ($players as $player)
    <tr>
        <td>{{ $i++ }}</td>
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
<div class="modal" id="shuffle-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('teams.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Confirm players to shuffle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                        <label class="form-check-label" for="select-all">
                            Select all
                        </label>
                    </div>
                    @foreach ($players as $player)
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="confirm[{{ $player->id }}]"
                            id="confirm[{{ $player->id }}]">
                        <label class="form-check-label" for="confirm[{{ $player->id }}]">
                            {{ $player->name }}, Level {{ $player->level }}{{ $player->goalkeeper ? ', Goalkeeper' : '' }}
                        </label>
                    </div>
                    @endforeach
                    <br>
                    <div class="row">
                        <div class="col-8">
                            <input
                                type="number"
                                name="players-per-team"
                                class="form-control"
                                placeholder="Number of players per team"
                                required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Shuffle</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
<p>Add players to shuffle</p>
@endif

@endsection
