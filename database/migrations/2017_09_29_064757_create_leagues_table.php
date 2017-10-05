<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leagues', function (Blueprint $table) {

			$table->integer('id')->nullable();
			$table->string('league');
			$table->integer('year');
			$table->string('name')->nullable();
			$table->dateTime('sync')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('leagues');
	}
}
