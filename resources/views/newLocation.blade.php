@extends('boilerplate')
@section('title', 'New location')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
@endpush
@section('content')
    <section>
        <form action="{{route('addLocation')}}" id="locationForm" method="POST">
            @csrf
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" required>
            </div>
            <div id="addedRows">

            </div>
            <div id="btnRow" class="row d-flex w-100 ms-auto me-auto justify-content-around m-4">
                <button type="submit" class="btn btn-primary w-auto">Submit</button>
                <div id="clear" class="btn btn-sm btn-info w-auto" onclick="clearData()">Clear</div>
                <div class="btn btn-sm btn-info w-auto" onclick="addRow()">Add row</div>
            </div>
        </form>
    </section>
    <div class="row justify-content-center m-5">
        <button id="showLocations" class="btn btn-primary w-auto" onclick="showLocations()">Show locations</button>
        <button id="hideLocations" class="btn btn-primary w-auto" onclick="hideLocations()">Hide locations</button>
    </div>

    <section id="allLocations" class="text-center">
        <h2>All locations</h2>

        <table class="table table-dark border-2 border-light text-center">
            <thead>
                <th>Location</th>
                <th>User</th>
                <th>Role</th>
                <th>Created</th>
            </thead>
            <tbody>
                @forEach($locations as $location)
                <tr>
                    <td>{{$location->location}}</td>
                    <td>Edin</td>
                    <td>Admin</td>
                    <td>{{\Carbon\Carbon::parse($location->created_at)->format('d.m.Y')}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>


    <script>
        let fields = document.getElementById("addedRows");
        let formData = document.getElementById('locationForm');
        let before = document.getElementById('btnRow');
        let clearBtn = document.getElementById('clear');
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
        clearBtn.style.display = 'none';
        document.getElementById('allLocations').style.display = 'none';
        document.getElementById('hideLocations').style.display = 'none';


        function addRow(){
            clearBtn.style.display = 'flex';
            let locationDiv = document.createElement('div');
            locationDiv.classList.add('mb-3');
            locationDiv.innerHTML = `<label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" required>`;
            fields.appendChild(locationDiv);
        }

        function clearData(){
            fields.innerHTML = " ";
            clearBtn.style.display = 'none';
        }

        function showLocations() {
            document.getElementById('showLocations').style.display = 'none';
            document.getElementById('hideLocations').style.display = 'block';
            document.getElementById('allLocations').style.display = 'block';
        }
        function hideLocations(){
            document.getElementById('showLocations').style.display = 'block';
            document.getElementById('hideLocations').style.display = 'none';
            document.getElementById('allLocations').style.display = 'none';
        }

    </script>
@endsection