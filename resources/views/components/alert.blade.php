<div>
    @if (session('success'))
    <div class="alert alert-success mb-3" role='alert'>
        {{ session('success') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger mb-3" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <!-- You must be the change you wish to see in the world. - Mahatma Gandhi -->
</div>
