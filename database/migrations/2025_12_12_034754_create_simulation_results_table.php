<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('simulation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('indicator');
            $table->float('old_score');
            $table->float('new_score');
            $table->float('change_percent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('simulation_results');
    }
};
