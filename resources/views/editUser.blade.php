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
                <input type="password" class="form-control" id="password" name="password" onkeyup="checkLength()">
                <span id="lengthPass">Minimum 5 characters</span>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Password again</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" onkeyup="checkPass()">
                <span id="checkMatch">Passwords don't match.</span>
            </div>
            <button class="btn btn-primary">Submit</button>
        </form>
    </section>

    <script>
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
        let checkPassLength = document.getElementById('lengthPass');
        let errorSpan = document.getElementById('checkMatch');
        let pass_one = document.getElementById('password');
        checkPassLength.style.display = 'none';
        errorSpan.style.display = 'none';
        function checkPass() {
            let pass_two = document.getElementById('password_confirmation');
            if (pass_two.value.length > 0 && pass_one.value !== pass_two.value) {
                errorSpan.style.display = 'block';
                errorSpan.textContent = "Passwords don't match.";
            }else if(pass_two.value.length < 1){
                errorSpan.style.display = 'block';
                errorSpan.textContent = "This field can't be empty";
            } else {
                errorSpan.style.display = 'none';
            }
        }

        function checkLength() {
            let pass = document.getElementById('password');
            if(pass.value.length > 0 && pass.value.length < 5){
                checkPassLength.style.display = 'block';
                checkPassLength.textContent = "Minimum 5 characters.";
            }else if(pass.value.length < 1){
                checkPassLength.textContent = "Please enter password";
                checkPassLength.style.display = 'block';
            }else{
                checkPassLength.style.display = 'none';
            }
        }
    </script>
@endsection