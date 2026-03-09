<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('naitnetwork_people_tbl', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('profile_picture')->nullable();

            $table->string('name');
            $table->string('slug')->unique();

            $table->string('email')->nullable();
            $table->string('phone', 50)->nullable();

            $table->text('summary')->nullable();
            $table->longText('notes')->nullable();

            $table->boolean('is_public')->default(false);
            $table->string('public_token', 100)->nullable()->unique();
            $table->timestamp('public_expires_at')->nullable();

            $table->boolean('show_email_publicly')->default(false);
            $table->boolean('show_phone_publicly')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('naitnetwork_people_tbl');
    }
};