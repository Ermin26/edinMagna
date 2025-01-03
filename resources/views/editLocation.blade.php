@extends('boilerplate')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
@endpush
@section('title', "Edit material")

@section('content')
<section>
    <h2 class="text-center text-light m-3 p-2">Update material</h2>
    <form id="form" action="{{route('updateLocation',$location->id)}}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{$location->location}}">
        </div>
        <button class="btn btn-primary">Submit</button>
    </form>
</section>

<script>
    document.getElementById('showAll').setAttribute("disabled", true);
    document.getElementById('search').setAttribute("disabled", true);
</script>
@endsection