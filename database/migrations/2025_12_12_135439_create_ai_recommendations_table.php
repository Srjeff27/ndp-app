<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->onDelete('cascade');
            $table->decimal('average_score', 5, 2);
            $table->integer('total_reviews');
            $table->enum('recommendation', ['approve', 'revise', 'reject']);
            $table->text('ai_analysis');
            $table->json('sentiment_breakdown')->nullable();
            $table->json('common_criticisms')->nullable();
            $table->timestamp('analyzed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_recommendations');
    }
};
