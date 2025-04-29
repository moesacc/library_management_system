<?php

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create an author', function () {
    $author = Author::factory()->create([
        'name' => 'Min Thu Wun',
    ]);

    expect($author)
        ->name->toBe('Min Thu Wun')
        ->bio->not()->toBeNull();

    $this->assertDatabaseHas('authors', [
        'name' => 'Min Thu Wun',
    ]);
});
