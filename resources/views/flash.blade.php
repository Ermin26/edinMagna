@if(session('success'))
<section id="alertSection">
    <div class="alert alert-success alert-dismissible fade-show text-center" role="alert">
        
         <strong>{{ session('success') }}</strong>
  <button type="button" id="success" class="close bg-transparent border-0" data-bs-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>
</section>
@endif
@if(session('error'))
<section id="alertSection">
    <div class="alert alert-danger alert-dismissible fade-show text-center" role="alert">
         <strong>{{ session('error')}}</strong>
  <button type="button" id="error" class="close bg-transparent border-0" data-bs-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>
</section>
@endif