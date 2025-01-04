@extends('boilerplate')
@section('title', 'New location')
@push('styles')
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
@endpush
@section('content')
    <section>
        <form id="form" action="{{route('addLocation')}}" id="locationForm" method="POST">
            @csrf
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" onkeyup="checkLoc(this)" required>
                <span class="errorSpan"></span>
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
    @if(count($locations) > 0)
    <div class="row justify-content-center m-5">
        <button id="showLocations" class="btn btn-primary w-auto" onclick="showLocations()">Show locations</button>
        <button id="hideLocations" class="btn btn-primary w-auto" onclick="hideLocations()">Hide locations</button>
    </div>
    @else
    <h3 class="text-center text-light m-3 p-4">No locations have bin added yet.</h3>
    @endif
    <section id="allLocations" class="text-center">
        <h2>All locations</h2>

        <table class="table table-dark border-2 border-light text-center">
            <thead>
                <th>Location</th>
                <th>User</th>
                <th>Role</th>
                <th>Created</th>
                <th>Edit</th>
            </thead>
            <tbody>
                @forEach($locations as $location)
                <tr>
                    <td>{{$location->location}}</td>
                    <td>Edin</td>
                    <td>Admin</td>
                    <td>{{\Carbon\Carbon::parse($location->created_at)->format('d.m.Y')}}</td>
                    <td><a class="btn btn-sm btn-warning" href="editLocation/{{$location->id}}">Edit</a></td>
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
        
        let locations = @json($locations);
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
        document.getElementById('allLocations').style.display = 'none';
        document.getElementById('hideLocations').style.display = 'none';
        document.querySelector('.errorSpan').style.display = 'none';
        clearBtn.style.display = 'none';
        let locationsArray = [];
        locations.forEach(element => {
            locationsArray.push(element.location);
        });
        function checkLoc(inputElement){
            let parentDiv = inputElement.closest('.mb-3');
            let errorSpan = parentDiv.querySelector('.errorSpan');
            let location = inputElement.value;
            if(locationsArray.includes(location)){
                errorSpan.textContent = "Location already exists";
                errorSpan.style.display = 'block';
            }else{
                errorSpan.style.display = 'none';
            }
        }

        function addRow(){
            clearBtn.style.display = 'flex';
            let locationDiv = document.createElement('div');
            locationDiv.classList.add('mb-3');
            locationDiv.innerHTML = `<label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" onkeyup="checkLoc(this)" required>
                <span class="errorSpan"></span>`;
            fields.appendChild(locationDiv);
            let errorSpan = locationDiv.querySelector('.errorSpan');
            errorSpan.style.display = 'none';
        }

        function clearData(){
            fields.innerHTML = " ";
            document.getElementById('location').value= "";
            clearBtn.style.display = 'none';
            document.querySelector('.errorSpan').style.display = 'none';
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