<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->boolean('has_all_keywords');
            $table->string('city');
            $table->string('type');
            $table->text('job_types');
            $table->text('job_styles');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alerts');
    }
}
