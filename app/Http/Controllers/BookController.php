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
        // 未使用cache  // $books = $books->get();
        // 使用cache, 有效期3600秒，原來$book做為callback 放在第3項參數,global 下use Illuminate\Support\Facades\Cache 使用Cache::remember 有風險，例如所有user 在3600秒內都可能拿到不屬於這個user的資料，用cache()也可達到相同的功能，但要設相關更精準的cache的範圍
        // $books = Cache::remember('books', 3600, fn() => $books->get());
        $cacheKey = 'books' . $filter . ':' . $title;
        $books    = cache()->remember($cacheKey, 3600, fn() => $books->get());

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
        // cache
        $cacheKey = 'book:' . $book->id;

        $book = cache()->remember($cacheKey, 3600, fn() => $book->load([
            'reviews' => fn($query) => $query->latest(),
        ]));
        return view('books.show', ['book' => $book]);

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
