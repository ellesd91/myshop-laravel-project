@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li class="alert-text">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
