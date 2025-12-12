<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of campus/institution
            $table->string('institution')->nullable();
            $table->string('country');
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Node Admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
