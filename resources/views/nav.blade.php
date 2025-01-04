<nav class="navbar sticky-top navbar-expand-lg bg-dark text-light">
    <div class="container-fluid text-light p-0 justify-content-start">
      @if(Auth::user())
      <a href="/" class="btn-sm btn-secondary"><img src="{{asset('imgs/home.png')}}" alt="Home icon"></a>
        <input type="search" name="search" id="search" onkeyup="searchMaterial()" placeholder=" Search location">
        <button id="showAll" class="btn btn-sm btn-secondary" onclick="showAll()">Show all</button>
        <button id="navbarBtn" class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a href="/addMaterial" class="btn btn-sm btn-secondary">Add material</a></li>
            <li class="nav-item"><a href="/newLocation" class="btn btn-sm btn-secondary">Add location</a></li>
            @if(Auth::user()->role == 'admin')
            <li class="nav-item"><a href="/newUser" class="btn btn-sm btn-secondary">Add user</a></li>
            <li class="nav-item"><a href="/editUser" class="btn btn-sm btn-secondary">Edit user</a></li>
           @endif
            <li class="nav-item"><a href="/editProfile/{{Auth::user()->id}}" class="btn btn-sm btn-secondary">Profile</a></li>
      @endif
            @if(!Auth::user())
            <li class="nav-item ms-auto"><a href="#" class="btn btn-sm btn-secondary ms-auto">Log in</a></li>
            @else
            <form class="w-auto ms-auto" action="{{route('logout')}}" method="POST">
              @csrf
              <li class="nav-item ms-auto"><button class="btn btn-sm btn-secondary">Log out</button></li>
            </form>
            @endif
          </ul>
      </div>
    </div>
</nav>