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
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->text('banner_search7')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search8')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search9')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search10')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search11')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search12')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search13')->nullable(); // Adiciona a nova coluna
            $table->text('banner_search14')->nullable(); // Adiciona a nova coluna
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagesettings', function (Blueprint $table) {
            $table->dropColumn('banner_search7'); // Remove a coluna na reversão
            $table->dropColumn('banner_search8'); // Remove a coluna na reversão
            $table->dropColumn('banner_search9'); // Remove a coluna na reversão
            $table->dropColumn('banner_search10'); // Remove a coluna na reversão
            $table->dropColumn('banner_search11'); // Remove a coluna na reversão
            $table->dropColumn('banner_search12'); // Remove a coluna na reversão
            $table->dropColumn('banner_search13'); // Remove a coluna na reversão
            $table->dropColumn('banner_search14'); // Remove a coluna na reversão
        });
    }
};
