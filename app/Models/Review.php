<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating'];
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        // 資料表updated及deleted後，清除相關的cache
        static::updated(fn(Review $review) => cache()->cache()->forget('book:' . $review->book_id));
        static::deleted(fn(Review $review) => cache()->cache()->forget('book:' . $review->book_id));
    }

}
