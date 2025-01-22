<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    // Especifica los campos que pueden ser asignados masivamente
    protected $fillable = [
        'fecha',
        'numero_orden',
        'fact',
        'examen_visual',
        'cliente',
        'cedula',     // Agregar este campo
        'paciente', // New field
        'celular',
        'correo_electronico',
        // Remover estos campos ya que ahora van en la tabla pedido_lunas
        // 'l_detalle',
        // 'l_medida',
        // 'l_precio',
        'd_inventario_id',
        'd_precio',
        'total',
        'saldo',
        // Nuevos campos
        'tipo_lente',
        'material',
        'filtro',
        'valor_compra',
        'motivo_compra',
        'usuario' // ...added usuario...
    ];

    protected $dates = [
        'fecha',
        // ...other date fields...
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Define si tu modelo debe usar timestamps (created_at y updated_at)
    public $timestamps = true;

    // Relación con el modelo Inventario para 'a_inventario'
    public function aInventario()
    {
        return $this->belongsTo(Inventario::class, 'a_inventario_id');
    }

    // Relación con el modelo Inventario para 'd_inventario'
    public function dInventario()
    {
        return $this->belongsTo(Inventario::class, 'd_inventario_id');
    }

    public function inventarios()
    {
        return $this->belongsToMany(Inventario::class, 'pedido_inventario')
                    ->withPivot(['precio', 'descuento'])
                    ->withTimestamps();
    }

    // Add this relationship to the existing Pedido model
    public function lunas()
    {
        return $this->hasMany(PedidoLuna::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
