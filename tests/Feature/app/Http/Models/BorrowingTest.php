<?php

use App\Models\Book;
use App\Models\Copy;
use App\Models\User;
use App\Models\Author;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a borrowing', function () {
    $user = User::factory()->create();
    $copy = Copy::factory()->create();

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'copy_id' => $copy->id,
        'borrowed_at' => now(),
        'due_date' => now()->addDays(14),
    ]);

    expect($borrowing)->toBeInstanceOf(Borrowing::class)
        ->and(Borrowing::find($borrowing->id))->not->toBeNull()
        ->and($borrowing->user_id)->toBe($user->id)
        ->and($borrowing->copy_id)->toBe($copy->id);
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $copy = Copy::factory()->create();

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'copy_id' => $copy->id,
        'borrowed_at' => now(),
        'due_date' => now()->addDays(14),
    ]);

    expect($borrowing->user)->toBeInstanceOf(User::class)
        ->and($borrowing->user->id)->toBe($user->id);
});

it('belongs to a copy', function () {
    $user = User::factory()->create();
    $copy = Copy::factory()->create();

    $borrowing = Borrowing::create([
        'user_id' => $user->id,
        'copy_id' => $copy->id,
        'borrowed_at' => now(),
        'due_date' => now()->addDays(14),
    ]);

    expect($borrowing->copy)->toBeInstanceOf(Copy::class)
        ->and($borrowing->copy->id)->toBe($copy->id);
});
