<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('city');
            $table->string('type');
            $table->string('style');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
}
