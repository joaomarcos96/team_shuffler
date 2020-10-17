@extends('layouts.app')

@section('title', 'Add player')

@section('content')

<div style="margin-bottom: 20px;">
    <a class="btn btn-secondary" href="{{ route('players.index') }}"> Back</a>
</div>

<div class="row">
    <div class="col-12">
        <h2>Add player</h2>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('players.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <strong>Name:</strong>
                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="Name"
                    required>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <strong>Level:</strong>
                <input
                    type="number"
                    name="level"
                    class="form-control @error('level') is-invalid @enderror"
                    placeholder="Level"
                    min="1"
                    max="5"
                    required>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="custom-control custom-switch form-group">
                <input
                    type="checkbox"
                    class="custom-control-input"
                    name="goalkeeper"
                    id="goalkeeper">
                <label class="custom-control-label" for="goalkeeper">
                    Is goalkeeper?
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>

@endsection
