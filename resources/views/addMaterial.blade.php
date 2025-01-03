@extends('boilerplate')
@section('title', 'Add material')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush
@section('content')
    <section>
        <form id="form" action="{{route('newMaterial')}}" id="materialForm" method="POST">
            @csrf
            <div class="mb-3 bg-dark p-4">
                <label for="location" class="form-label mt-3">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" required>

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
        clear.style.display = "none";
        allMaterials.style.display = "none";
        hideMaterials.style.display = "none";
        function addAll(){
            clear.style.display = "flex";
            materialAdd.classList.add('disabled');
            locationAdd.classList.add('disabled');
            let addMatToForms = document.createElement("div");
            addMatToForms.classList.add('mb-3', 'p-4', 'bg-dark');
            let addFieldMaterial = `
                <label for="location" class="form-label mt-3">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" required>
                
                <label for="material" class="form-label mt-3">Material</label>
                <input type="text" class="form-control" id="material" name="material[]" required>
                
                <label for="supplier" class="form-label mt-3">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier[]" required>`;
            addMatToForms.innerHTML = addFieldMaterial;
            fields.appendChild(addMatToForms);
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
            addMatToForms.classList.add('mb-3', 'p-4', 'bg-dark');
            let addFieldLocation = `<label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location[]" required>`;
            addMatToForms.innerHTML = addFieldLocation;
            fields.appendChild(addMatToForms);
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