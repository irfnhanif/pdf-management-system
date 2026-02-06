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
        Schema::create('pdf_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 255);
            $table->string('original_name', 255)->nullable();
            $table->string('filepath', 500);
            $table->bigInteger('size')->nullable();
            $table->enum('status', ['CREATED', 'UPLOADED', 'DELETED']);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_files');
    }
};
