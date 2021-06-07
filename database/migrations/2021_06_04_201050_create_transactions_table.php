<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();
			$table->timestamps();

			$table->integer('user_id')->unsigned();
			$table->integer('receiver_id')->unsigned()->nullable();

			$table->decimal('mount', 15, 2);
			$table->enum('type', ['PAYMENT', 'RECHARGE'])->nullable()->default("RECHARGE");
			$table->enum('status', ['SENDED', 'ACEPTED', "REJECTED"])->nullable()->default("SENDED");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('transactions');
	}
}
