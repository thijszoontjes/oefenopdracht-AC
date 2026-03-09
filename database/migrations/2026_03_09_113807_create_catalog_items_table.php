<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_items', function (Blueprint $table) {
            $table->id();

            // Kernvelden vanuit de bronfeed
            $table->string('external_id')->index();
            $table->string('sku')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->string('currency', 3)->nullable();
            $table->integer('stock')->default(0);
            $table->string('availability', 30)->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('source_url')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->unsignedInteger('review_count')->default(0);

            // Volledige brondata bewaren zodat we niets kwijtraken
            $table->json('raw_payload');

            // Analyseresultaten
            $table->json('issues')->nullable();
            $table->unsignedTinyInteger('issue_count')->default(0);
            $table->unsignedTinyInteger('readiness_score')->default(100); // 0–100

            $table->timestamp('updated_at_source')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_items');
    }
};
