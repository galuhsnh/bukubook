<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('category.index', [
            'categories' => Category::paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('Category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => ['required', 'min:3']
        ]);

        try {
            Category::create($validated);
            return redirect()
                ->route('category.index')
                ->with('success', 'category added successfully');
        } catch (\Exception $e) {
            return redirect()
                ->route('category.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        //$category = Category::find($id);
        return view('category.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
        $validated = $request->validate([
            'name' => ['required', 'min:3']
        ]);

        try {
            $category->update($validated);
            return redirect()
                    ->route('category.index')
                    ->with('success', 'category updated successfully');
        } catch (\Exception $e) {
            return redirect()
                    ->route('category.index')
                    ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        try {
            $category->delete();
            return redirect()
                    ->route('category.index')
                    ->with('success', 'category has been deleted');
        } catch (\Exception $e) {
            return redirect()
                    ->route('category.index')
                    ->with('error', $e->getMessage());
        }
    }
}
