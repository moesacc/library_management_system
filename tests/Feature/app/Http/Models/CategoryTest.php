<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a category', function () {
    $category = Category::factory()->create([
        'name' => 'Myanmar Literature',
    ]);

    expect($category)
        ->name->toBe('Myanmar Literature')
        ->description->toBeNull();

    $this->assertDatabaseHas('categories', [
        'name' => 'Myanmar Literature',
    ]);
});

it('can create a category with a parent', function () {
    $parent = Category::factory()->create();
    $child = Category::factory()->create([
        'parent_id' => $parent->id,
    ]);

    expect($child->parent_id)->toBe($parent->id);
});
