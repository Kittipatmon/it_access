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
        // request_forms
        Schema::create('request_forms', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->integer('current_step')->default(1);
            $table->timestamp('approved_at')->nullable();
            $table->text('details')->nullable(); // Additional details for the request
            $table->timestamps();
        });

        // approval_steps
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_form_id')->constrained('request_forms')->onDelete('cascade');
            $table->integer('step_order');
            $table->string('step_name');
            $table->foreignId('approver_id')->constrained('users');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });

        // approval_histories
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_form_id')->constrained('request_forms')->onDelete('cascade');
            $table->foreignId('action_by')->constrained('users');
            $table->string('action_type'); // created, approved, rejected, comment
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_histories');
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('request_forms');
    }
};
