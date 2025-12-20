<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - MongoDB doesn't need explicit migrations
     * The collection will be created automatically when inserting documents
     */
    public function up(): void
    {
        // MongoDB doesn't require schema migrations
        // The personal_access_tokens collection will be created automatically
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the collection if it exists
        Schema::connection('mongodb')->dropIfExists('personal_access_tokens');
    }
};
