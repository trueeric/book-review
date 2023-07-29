<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title  = $request->input('title');
        $filter = $request->input('filter', '');

        //when($aa,function) pass something as a first argument, if $aa is NOT empty or NOT null ,run function
        // 先挑title 符合的
        $books = Book::when($title, fn($query, $title) =>
            $query->title($title)
        );
        // 過濾。 match 為php8的指令 類似 switch,要有 default選項
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_latest_month' => $books->highestRatedLastMonth(),
            'highest_rated_latest_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()
        };
        // 輸出結果
        $books = $books->get();

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show',
            [
                // 原始行 'book' => $book 這樣只有取出來，沒有任何排序，下面這樣寫會找出一本又重query,但從上面已找出來的該書資料再重排，loading或許還好。
                'book' => $book->load([
                    'reviews' => fn($query) => $query->latest(),
                ]),
            ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
