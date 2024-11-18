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
        Schema::create('chat_app_tokens', function (Blueprint $table) {
			$table->unsignedInteger('cabinetUserId')->primary();
			$table->char('accessToken', 64);
	        $table->unsignedInteger('accessTokenEndTime');
	        $table->char('refreshToken', 64);
	        $table->unsignedInteger('refreshTokenEndTime');

	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_app_tokens');
    }
};
