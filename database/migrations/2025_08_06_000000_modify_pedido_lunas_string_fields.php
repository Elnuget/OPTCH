<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyPedidoLunasStringFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Primero guardamos los datos existentes
        $existingData = DB::table('pedido_lunas')->get();
        
        Schema::table('pedido_lunas', function (Blueprint $table) {
            // Eliminar las columnas existentes
            $table->dropColumn(['l_medida', 'l_detalle', 'tipo_lente', 'material', 'filtro']);
        });
        
        Schema::table('pedido_lunas', function (Blueprint $table) {
            // Recrear las columnas como TEXT
            $table->text('l_medida')->nullable()->after('pedido_id');
            $table->text('l_detalle')->nullable()->after('l_medida');
            $table->text('tipo_lente')->nullable()->after('l_precio');
            $table->text('material')->nullable()->after('tipo_lente');
            $table->text('filtro')->nullable()->after('material');
        });
        
        // Restaurar los datos existentes
        foreach ($existingData as $row) {
            DB::table('pedido_lunas')
                ->where('id', $row->id)
                ->update([
                    'l_medida' => $row->l_medida,
                    'l_detalle' => $row->l_detalle,
                    'tipo_lente' => $row->tipo_lente,
                    'material' => $row->material,
                    'filtro' => $row->filtro,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Guardar los datos existentes
        $existingData = DB::table('pedido_lunas')->get();
        
        Schema::table('pedido_lunas', function (Blueprint $table) {
            // Eliminar las columnas TEXT
            $table->dropColumn(['l_medida', 'l_detalle', 'tipo_lente', 'material', 'filtro']);
        });
        
        Schema::table('pedido_lunas', function (Blueprint $table) {
            // Recrear las columnas como STRING
            $table->string('l_medida', 191)->nullable()->after('pedido_id');
            $table->string('l_detalle', 191)->nullable()->after('l_medida');
            $table->string('tipo_lente', 191)->nullable()->after('l_precio');
            $table->string('material', 191)->nullable()->after('tipo_lente');
            $table->string('filtro', 191)->nullable()->after('material');
        });
        
        // Restaurar los datos (truncando si es necesario)
        foreach ($existingData as $row) {
            DB::table('pedido_lunas')
                ->where('id', $row->id)
                ->update([
                    'l_medida' => substr($row->l_medida, 0, 191),
                    'l_detalle' => substr($row->l_detalle, 0, 191),
                    'tipo_lente' => substr($row->tipo_lente, 0, 191),
                    'material' => substr($row->material, 0, 191),
                    'filtro' => substr($row->filtro, 0, 191),
                ]);
        }
    }
}
