<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @stack('styles')
    <title>@yield('title')</title>
</head>
<body>
    <header class="bg-dark">
        @include('nav')
    </header>
    @include('flash')
    <main>
        <div id="test"></div>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        let navBtn = document.getElementById("navbarBtn");
        navBtn.addEventListener("click", ()=>{
            let collapsedDiv = document.getElementById("navbarNav");
            if(collapsedDiv.classList.contains("show")){
                navBtn.setAttribute('aria-expanded', 'false');
                navBtn.classList.add('collapsed');
                collapsedDiv.classList.remove('show');
            }else{
                navBtn.setAttribute('aria-expanded', 'true');
                navBtn.classList.remove('collapsed');
                collapsedDiv.classList.add('show');
            }
    })
    </script>
</body>
</html>