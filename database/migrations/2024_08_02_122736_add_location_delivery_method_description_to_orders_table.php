<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationDeliveryMethodDescriptionToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->string('delivery_method')->nullable();
            $table->text('description')->nullable();
            $table->string('payment_method')->nullable(); // Novo campo
            $table->string('payment')->nullable(); // Novo campo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->dropColumn('delivery_method');
            $table->dropColumn('description');
            $table->dropColumn('payment_method'); // Novo campo
            $table->dropColumn('payment'); // Novo campo
        });
    }
}