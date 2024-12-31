@extends('boilerplate')
@section('title', "Home page")

@section('content')
        <table id="table" class="table table-bordered border-collapse border-2 border-light table-dark justifiy-content-center text-center w-75 ms-auto me-auto">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Location</th>
                    <th>Material</th>
                    <th>Supplier</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.getElementById('showAll').removeAttribute("disabled");
            document.getElementById('search').removeAttribute("disabled");
        })
        let test = @json($materials);
        let table = document.querySelector('#table tbody');

        function searchMaterial(){
            let searched = document.getElementById("search").value.toUpperCase();
            let results = test.filter(obj => obj.material.toUpperCase().startsWith(searched));
            table.innerHTML = '';
            let count = 1;
            results.forEach( item => {
                let row = table.insertRow();
                row.insertCell(0).textContent = count;
                row.insertCell(1).textContent = item.location;
                row.insertCell(2).textContent = item.material;
                row.insertCell(3).textContent = "Supplier";

                count++;
            })
            if(!searched){
                table.innerHTML = '';
            }
        }

        function showAll(){
            table.innerHTML = '';
            let count = 1;
            test.forEach(item => {
                let row = table.insertRow();
                row.insertCell(0).textContent = count;
                row.insertCell(1).textContent = item.location;
                row.insertCell(2).textContent = item.material;
                row.insertCell(3).textContent = "Supplier";

                count++;
            });
        }
    </script>

@endsection