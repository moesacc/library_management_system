<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    /** @use HasFactory<\Database\Factories\CopyFactory> */
    use HasFactory;

    protected $fillable = [
        'book_id',
        'copy_number',
        'condition',
        'availability',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

}
