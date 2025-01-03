@extends('boilerplate')
@push('styles')
<link rel="stylesheet" href="{{ url('css/login.css') }}">
@endpush
@section('title', 'Add user')

@section('content')
    <section>
        <form id="form" action="{{route('createUser')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" onkeyup="checkUser()" required>
                <span id="errorSpan" style="display: none"></span>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-control" required>
                    <option selected>Choose role</option>
                    <option value="admin">Admin</option>
                    <option value="moderator">Moderator</option>
                    <option value="visitor">Visitor</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" min="5" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Password again</label>
                <input type="password" class="form-control" id="password_confirmation" min="5" name="password_confirmation" required>
            </div>
            <button id="btnSubmit" class="btn btn-primary">Submit</button>
        </form>
    </section>

    <script>
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
        let users = @json($users);
        console.log(users);
        function checkUser(){
            let user = document.getElementById('username').value;
            console.log(users.includes(user));
            if(users.includes(user)){
                document.getElementById('errorSpan').style.display = 'block';
                document.getElementById('errorSpan').innerHTML = 'Username already exists';
                document.getElementById('btnSubmit').setAttribute('disabled',true);
            }else{
                document.getElementById('errorSpan').style.display = 'none';
                document.getElementById('errorSpan').innerHTML = '';
                document.getElementById('btnSubmit').removeAttribute('disabled');

            }
        }
    </script>
@endsection