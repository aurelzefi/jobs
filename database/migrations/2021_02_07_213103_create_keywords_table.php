<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeywordsTable extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alert_id')->constrained()->cascadeOnDelete();
            $table->string('word');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
}
