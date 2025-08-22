<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoToInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('empresa_id')->comment('Ruta de la foto del artículo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventarios', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
}
