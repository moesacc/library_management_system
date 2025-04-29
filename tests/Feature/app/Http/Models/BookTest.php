<?php

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a book', function () {
    $author = Author::factory()->create();
    $category = Category::factory()->create();

    $book = Book::factory()->create([
        'title' => 'Shwe Hinthar',
        'author_id' => $author->id,
        'category_id' => $category->id,
    ]);

    expect($book)
        ->title->toBe('Shwe Hinthar')
        ->author->id->toBe($author->id)
        ->category->id->toBe($category->id);

    $this->assertDatabaseHas('books', [
        'title' => 'Shwe Hinthar',
    ]);
});
