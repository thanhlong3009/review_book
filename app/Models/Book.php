<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $table = 'books';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'author',
        'description',
        'image',
        'view'
    ];

    public function points() {
        return $this->hasMany(Point::class, 'book_id');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'book_id');
    }

}
