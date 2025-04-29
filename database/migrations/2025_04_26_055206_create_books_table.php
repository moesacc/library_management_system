<?php

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Enums\BookStatusEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('summary');
            $table->decimal('price', 10, 2)->nullable();
            $table->string('status')->default(BookStatusEnum::DRAFT->value);

            $table->foreignIdFor(Author::class)->nullable();
            $table->foreignIdFor(Category::class)->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('copies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Book::class)->constrained()->cascadeOnDelete();
            $table->string('copy_number')->nullable();
            $table->enum('condition', ['new', 'good', 'fair', 'poor'])->default('good');
            $table->enum('availability', ['available', 'borrowed', 'lost', 'damaged'])->default('available');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copies');
        Schema::dropIfExists('books');
    }
};
