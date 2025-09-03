<?php

declare(strict_types=1);

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
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignUuId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_purchases')->default(0);
            $table->decimal('total_spent', 12, 2)->default(0.00);
            $table->string('current_badge_key')->default('bronze');
            $table->timestamp('last_purchase_at')->nullable();
            $table->timestamp('first_purchase_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'total_purchases']);
            $table->index(['user_id', 'total_spent']);
            $table->index(['user_id', 'current_badge_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stats');
    }
};
