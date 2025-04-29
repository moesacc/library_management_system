<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'copy_id',
        'borrowed_at',
        'due_date',
        'returned_at'
    ];
    
    public function copy()
    {
        return $this->belongsTo(Copy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
