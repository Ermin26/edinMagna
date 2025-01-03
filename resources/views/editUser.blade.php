@extends('boilerplate')
@push('styles')
<link rel="stylesheet" href="{{ url('css/login.css') }}">
@endpush
@section('title', 'Edit user')

@section('content')
    <section>
        <form id="form" action="{{route('updateUser')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">User</label>
                <select name="username" id="username" class="form-control" required>
                    <option selected>Choose User</option>
                    @foreach ($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="name" name="name" value="">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option selected>Choose role</option>
                    <option value="admin">Admin</option>
                    <option value="admin">Moderator</option>
                    <option value="visitor">Visitor</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Password again</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button class="btn btn-primary">Submit</button>
        </form>
    </section>

    <script>
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
    </script>
@endsection