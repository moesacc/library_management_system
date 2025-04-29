<?php

use App\Models\Book;
use App\Models\Copy;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});
// it('can list books', function () {
//     Book::factory()->count(3)->create();
//     $response = $this->get(route('api.books.index'))
//         ->assertOk();
//         ->assertJsonStructure(['data', 'links', 'meta']);
// });