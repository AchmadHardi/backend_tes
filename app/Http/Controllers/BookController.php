<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::orderBy('name', 'asc')->get();

        return view('buku.index', [
            'buku' => $books
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buku.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
        ]);

        Book::create($validated);

        Alert::success('Success', 'Book data saved successfully!');

        return redirect()->route('book.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);

        return view('buku.edit', [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'author' => 'required|max:255',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);

        Alert::success('Success', 'Book data updated successfully!');

        return redirect()->route('book.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        Alert::success('Success', 'Book data deleted successfully!');

        return redirect()->route('book.index');
    }
}
