@if (session('error'))
    <div class="alert {{ session('tipo') }} alert-dismissible fade show" role="alert">
        <strong>{{ session('mensaje') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif 