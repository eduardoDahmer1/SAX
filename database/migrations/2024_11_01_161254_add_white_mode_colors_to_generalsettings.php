<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->string('white_mode_primary_color', 191)->nullable();
            $table->string('white_mode_text_color', 191)->nullable();
            $table->string('white_mode_secondary_color', 191)->nullable();
            $table->string('white_mode_secondary_text_color', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn([
                'white_mode_primary_color',
                'white_mode_text_color',
                'white_mode_secondary_color',
                'white_mode_secondary_text_color'
            ]);
        });
    }
};
