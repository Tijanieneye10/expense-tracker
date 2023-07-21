<?php

use App\Models\MonthlyTarget;
use App\Models\User;
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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueid')->unique();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(MonthlyTarget::class, 'monthly_target');
            $table->string('expenses_title');
            $table->decimal('expenses_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
