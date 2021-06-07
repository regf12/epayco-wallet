<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokensTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tokens', function (Blueprint $table) {
			$table->id();
			$table->timestamps();

			$table->integer('user_id')->unsigned();
			$table->string('key');
			$table->enum('status', ['ACTIVE', 'INACTIVE'])->nullable()->default('ACTIVE');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('tokens');
	}
}
