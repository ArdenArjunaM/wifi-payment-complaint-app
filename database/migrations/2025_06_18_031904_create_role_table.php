<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id('id_role');
            $table->string('role')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }

};
