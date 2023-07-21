@extends('layouts.app')
@section('css')
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
@endsection
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Form</h3>
            </div>
            <div class="card-body">
                <x-alert/>
                <form action="{{ route('book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3 row">
                        <label for="inputTitle" class="col-sm-2 col-form-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="inputTitle"
                                name="title" value="{{ old('title', $book->title) }}">
                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="inputDescription"
                                name="description" value="{{ old('description', $book->description) }}"></textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputYear" class="col-sm-2 col-form-label">Year</label>
                        <div class="col-sm-10">
                            <input type="number" min="2010" max="{{ date('Y') }}" class="form-control @error('year') is-invalid @enderror" id="inputYear"
                                name="year" value="{{ old('year', $book->year) }}">
                            @error('year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputQuantity" class="col-sm-2 col-form-label">Quantity</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                id="inputQuantity" name="quantity" value="{{ old('quantity', $book->quantity) }}">
                            @error('quantity')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="selectCategory" class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10">
                            <select class="form-select @error('category') is-invalid @enderror" id="selectCategory"
                                name="category[]" multiple>
                                @foreach ($categories as $key => $category)
                                    <option value="{{ $category->id }}"
                                        {{ $book->categories->pluck('id')->contains($category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('Category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputCover" class="col-sm-2 col-form-label">Cover</label>
                        <div class="col-sm-10">
                            <input type="file" id="inputCover" class="form-control" name="cover" accept="image/*"
                                onchange="updatePreview(this, 'image-preview')" />

                            <img id="image-preview" src="{{ $book->cover_url }}" class="mt-3 img-fluid img-thumbnail"
                                style="width: 200px" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#selectCategory').select2({
            theme: 'bootstrap-5'
        });

        function updatePreview(input, target) {
            let file = input.files[0];
            let reader = new FileReader();

            reader.readAsDataURL(file);
            reader.onload = function() {
                let img = document.getElementById(target);
                img.src = reader.result;
            }
        }
    </script>
@endsection
