<nav class="navbar sticky-top navbar-expand-lg bg-dark text-light">
    <div class="container-fluid text-light p-0 justify-content-start">
      <a href="/" class="btn-sm btn-secondary"><img src="{{asset('imgs/home.png')}}" alt="Home icon"></a>
        <input type="search" name="search" id="search" onkeyup="searchMaterial()" placeholder=" Search location">
        <button id="showAll" class="btn btn-sm btn-secondary" onclick="showAll()">Show all</button>
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <div class="navbar-nav">
            <a href="/addMaterial" class="btn btn-sm btn-secondary">Add material</a>
            <a href="/newLocation" class="btn btn-sm btn-secondary">Add location</a>
            <a href="/newUser" class="btn btn-sm btn-secondary">Add user</a>
            <a href="/editUser" class="btn btn-sm btn-secondary">Edit user</a>
            <a href="/login" class="btn btn-sm btn-secondary ms-auto">Log in</a>
            <a href="#" class="btn btn-sm btn-secondary">Log out</a>
          </div>
        </ul>
      </div>
    </div>
</nav>