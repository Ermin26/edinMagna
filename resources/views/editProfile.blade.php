@extends('boilerplate')
@push('styles')
<link rel="stylesheet" href="{{ url('css/login.css') }}">
@endpush
@section('title', 'Edit user')

@section('content')
    <section>
        <form id="form" action="{{route('updateProfile', $user->id)}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Only for update">
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Password again</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat password">
            </div>
            <button class="btn btn-primary">Submit</button>
        </form>
    </section>

    <script>
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
    </script>
@endsection