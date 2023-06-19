<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display all books.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        return response()->json($books);
    }
    /**
     * Create a new book.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book = Book::create([
            'bookTitle' => $request->bookTitle,
            'authors' => $request->authors,
            'bookDescription' => $request->bookDescription,
            'link' => $request->link,
            'imageUrl' => $request->imageUrl,
        ]);

        return response()->json(['message' => 'Book created successfully'], 201);
    }
    /**
     * Remove the specified book from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($bookTitle)
    {
        $book = Book::where('bookTitle', $bookTitle)->firstOrFail();
        $book->delete();
        return response()->json(null, 204);
    }

}
