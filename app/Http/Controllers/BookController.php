<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function getDatatable(){
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //dd(book::latest()->first);
        return view('book.index', [
            'books' => Book::with('categories')->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('book.create', [
            'categories' => Category::select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        //cek request
        //dd($request->all());
        $formData = $request->validated();
        try {
            $formData['cover'] = $request->file('cover')
                ->store('book-cover', 'public');
            $formData['created_by'] = Auth::user()->id;
            $formData['updated_by'] = Auth::user()->id;

            $book = Book::create($formData);
            $book->categories()->attach($formData['category']);

            return redirect()
                ->route('book.index')
                ->with('success', 'Book added successfully');
        } catch (\Exception $e) {
            //dd($e);
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
        return view('book.edit', [
            'book' => $book,
            'categories' => Category::select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //dd($request->validated(), $book);
        //
        $formData = $request->validated();
        try {
            if ($request->hasFile('cover')) {
                //hapus file lama dari storage
                Storage::delete('public/' . $book->cover);
                //simpan file baru ke storage
                $formData['cover'] = $request->file('cover')->store('book-cover', 'public');
            }
            $formData['updated_by'] = Auth::user()->id;

            //update data buku
            $book->update($formData);
            //update data kategori menggunakan sync
            //$book->categories()->sync($formData['category']); //sync hanya id
            $book->categories()->syncWithPivotValues(
                $formData['category'],
                ['updated_at' => now()]
            ); //sync dengan tambahan ingin update isi kolom updated_at

            return redirect()
                ->route('book.index')
                ->with('success', 'Book updated successfully');
        } catch (\Exception $e) {
            //dd($e);
            return redirect()
                ->back()
                ->with('error', 'Error updating' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
        try {
            //hapus relasi kategori
            $book->categories()->detach();
            //$book->tag()->detach();
            //hapus file cover dari storage
            if ($book->cover) {
                Storage::delete('public/' . $book->cover);
            }
            //hapus data buku
            $book->delete();
            return redirect()
                    ->route('book.index')
                    ->with('success', 'Book deleted successfully');
        } catch (\Exception $e) {
            return redirect()
                ->route('book.index')
                ->with('error', 'Error deleting: ' . $e->getMessage());
        }
    }
}
