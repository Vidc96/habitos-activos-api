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
        $book = new Book;
        $book->book_title = $request->input('book_title');
        $book->authors = $request->input('authors');
        $book->book_description = $request->input('book_description');
        $book->link = $request->input('link');
        $book->image_url = $request->input('image_url');
        $book->save();
        return response()->json($book, 201);
    }
    /**
     * Remove the specified book from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return response()->json(null, 204);
    }
}
