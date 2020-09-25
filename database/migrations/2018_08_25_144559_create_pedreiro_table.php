<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedreiroTable extends Migration
{
    public function up()
    {
        Schema::create('pedreiro_table', function (Blueprint $table) {
            $table->bigIncrements('id');

            // add fields

            $table->timestamps();
        });
    }
}
