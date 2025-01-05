@extends('boilerplate')
@section('title', 'Add material')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush
@section('content')
    <section>
        <form id="form" action="{{route('newMaterial')}}" id="materialForm" method="POST">
            @csrf
            <div class="mb-3 bg-dark p-4 fields">
                <label for="location" class="form-label mt-3">Location</label>
                <input type="text" class="form-control location" id="location" name="location[]" onkeyup="checkLocation(this)" onfocus="showOptions(this)" onblur="hideOptions(this)"  autocomplete="off" required>
                <span class="errorSpan"></span>
                <div class="locationOptions" onmousedown="event.preventDefault()">
                    @foreach($locations as $location)
                        <span class="option" value="{{$location->location}}" onclick="selectedLoc(this)">{{$location->location}}</span>
                    @endforeach
                </div>

                <label for="material" class="form-label mt-3">Material</label>
                <input type="text" class="form-control" id="material" name="material[]" required>

                <label for="supplier" class="form-label mt-3">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier[]" required>
            </div>
            <div id="addedRows">

            </div>
            <div id="btnRow" class="row d-flex w-100 ms-auto me-auto justify-content-around m-4">
                <div class="btn btn-sm btn-info w-auto" id="bothAdd" onclick="addAll()">Add all</div>
                <div class="btn btn-sm btn-info w-auto" id="materialAdd" onclick="addMat()">Add material</div>
                <div class="btn btn-sm btn-info w-auto" id="locationAdd" onclick="addLoc()">Add location</div>
            </div>
            <div id="btnRow" class="row d-flex w-100 ms-auto me-auto justify-content-around m-4">
                <button type="submit" class="btn btn-primary w-auto">Submit</button>
                <div class="btn btn-sm btn-info w-auto" id="clear" onclick="clearData()">Clear</div>
            </div>
        </form>
    </section>

    @if(count($materials) > 0)
    <div class="row justify-content-center m-5">
        <div class="btn btn-primary w-auto" id="showMaterials" onclick="showMaterial()">Show materials</div>
        <div class="btn btn-primary w-auto" id="hideMaterials" onclick="hideMaterial()">Hide materials</div>
    </div>
    @else
    <h3 class="text-light text-center m-3 p-4">No materials have been added yet.</h3>
    @endif
    <section id="allMaterials">
        <h2 class="text-center">All materials</h2>
        <table class="table table-dark table-bordered border-2 border-light text-center">
            <thead>
                <th>Material</th>
                <th>Supplier</th>
                <th>Edit</th>
            </thead>
            <tbody>
                @foreach ($materials as $material)
                        <tr>
                            <td>{{$material->material}}</td>
                            <td>{{$material->supplier}}</td>
                            <td><a class="btn btn-sm btn-warning" href="editMaterial/{{$material->id}}">Edit</a></td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </section>


    <script>
        document.getElementById('showAll').setAttribute("disabled", true);
        document.getElementById('search').setAttribute("disabled", true);
        let elements = @json($locations);
        let fields = document.getElementById("addedRows");
        let before = document.getElementById("btnRow");
        let bothAdd = document.getElementById("bothAdd");
        let materialAdd = document.getElementById("materialAdd");
        let locationAdd = document.getElementById("locationAdd");
        let locationLabel = document.getElementById("locationLabel");
        let clear = document.getElementById("clear");
        let allMaterials = document.getElementById("allMaterials");
        let showMaterials = document.getElementById("showMaterials");
        let hideMaterials = document.getElementById("hideMaterials");
        let errorSpan = document.querySelectorAll(".errorSpan");

        document.querySelector('.errorSpan').style.display = 'none';
        clear.style.display = "none";
        allMaterials.style.display = "none";
        hideMaterials.style.display = "none";

        let locations = [];
        elements.forEach(element =>{
            locations.push(element.location);
        })
        function selectedLoc(selected){
            let parentDiv = selected.closest('.fields');
            let inputField = parentDiv.querySelector('.location');
            let options = parentDiv.querySelector('.locationOptions');
            inputField.value = selected.innerHTML;
            options.style.display = "none";
        }

        function showOptions(field){
            let parentDiv = field.closest('.fields');
            let inputField = parentDiv.querySelector('.location');
            let options = parentDiv.querySelector('.locationOptions');
            options.style.width = inputField.offsetWidth + "px";
            options.style.display = "flex";
        }
        function hideOptions(field){
            let parentDiv = field.closest('.fields');
            let inputField = parentDiv.querySelector('.location');
            let options = parentDiv.querySelector('.locationOptions');
            options.style.display = "none";
        }
        function checkLocation(inputData){
            let parentDiv = inputData.closest('.fields');
            let data = parentDiv.querySelector('.location');
            let errorSpan = parentDiv.querySelector('.errorSpan');
            if( data.value.length > 0 && !locations.includes(data.value)){
                errorSpan.textContent = "Location not exist.";
                errorSpan.style.display = 'block';
            }else{
                errorSpan.style.display = "none";
            }
            let result = locations.filter(location => location.startsWith(data.value));
            if(result){
                let newOptions = parentDiv.querySelector('.locationOptions');
                newOptions.innerHTML = '';
                result.forEach(location => {
                    let newSpan = document.createElement('span');
                    newSpan.classList.add('option');
                    newSpan.value = location;
                    newSpan.innerHTML = `${location}`;
                    newOptions.appendChild(newSpan);
                })
            }
        }
        function addAll(){
            clear.style.display = "flex";
            materialAdd.classList.add('disabled');
            locationAdd.classList.add('disabled');
            let addMatToForms = document.createElement("div");
            addMatToForms.classList.add('mb-3', 'p-4', 'bg-dark', 'fields');
            let addFieldMaterial = `
                <label for="location" class="form-label mt-3">Location</label>
                <input type="text" class="form-control location" id="location" name="location[]" onkeyup="checkLocation(this)" onfocus="showOptions(this)" onblur="hideOptions(this)"  autocomplete="off" required>
                <span class="errorSpan"></span>
                <div class="locationOptions" onmousedown="event.preventDefault()">
                    @foreach($locations as $location)
                        <span class="option" value="{{$location->location}}" onclick="selectedLoc(this)">{{$location->location}}</span>
                    @endforeach
                </div>
                
                <label for="material" class="form-label mt-3">Material</label>
                <input type="text" class="form-control" id="material" name="material[]" required>
                
                <label for="supplier" class="form-label mt-3">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier[]" required>`;
            addMatToForms.innerHTML = addFieldMaterial;
            fields.appendChild(addMatToForms);
            document.querySelector('.errorSpan').style.display = 'none';
        }
        function addMat(){
            clear.style.display = "flex";
            bothAdd.classList.add('disabled');
            locationAdd.classList.add('disabled');
            let addMatToForms = document.createElement("div");
            addMatToForms.classList.add('mb-3', 'p-4', 'bg-dark');
            let addFieldMaterial = `<label for="material" class="form-label">Material</label>
                <input type="text" class="form-control" id="material" name="material[]" required>
                <label for="supplier" class="form-label mt-4">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier[]" required>`;
            addMatToForms.innerHTML = addFieldMaterial;
            fields.appendChild(addMatToForms);
        }
        function addLoc(){
            clear.style.display = "flex";
            bothAdd.classList.add('disabled');
            materialAdd.classList.add('disabled');
            let addMatToForms = document.createElement("div");
            addMatToForms.classList.add('mb-3', 'p-4', 'bg-dark', 'fields');
            let addFieldLocation = `<label for="location" class="form-label mt-3">Location</label>
                <input type="text" class="form-control location" id="location" name="location[]" onkeyup="checkLocation(this)" onfocus="showOptions(this)" onblur="hideOptions(this)"  autocomplete="off" required>
                <span class="errorSpan"></span>
                <div class="locationOptions" onmousedown="event.preventDefault()">
                    @foreach($locations as $location)
                        <span class="option" value="{{$location->location}}" onclick="selectedLoc(this)">{{$location->location}}</span>
                    @endforeach
                </div>`;
            addMatToForms.innerHTML = addFieldLocation;
            fields.appendChild(addMatToForms);
            errorSpan.forEach(span =>{span.style.display = 'none';})
        }

        function clearData(){
            fields.innerHTML =" ";
            materialAdd.classList.remove('disabled')
            locationAdd.classList.remove('disabled')
            bothAdd.classList.remove('disabled')
            clear.style.display = "none";
        }

        function showMaterial(){
            allMaterials.style.display = "block";
            showMaterials.style.display = "none";
            hideMaterials.style.display = "block";
        }
        function hideMaterial(){
            allMaterials.style.display = "none";
            showMaterials.style.display = "block";
            hideMaterials.style.display = "none";
        }
    </script>
@endsection