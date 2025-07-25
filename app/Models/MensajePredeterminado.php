<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MensajePredeterminado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mensajes_predeterminados';
    
    protected $fillable = [
        'tipo',
        'mensaje'
    ];

    /**
     * Obtiene un mensaje por su tipo
     *
     * @param string $tipo
     * @return string|null
     */
    public static function obtenerMensaje($tipo)
    {
        $mensaje = self::where('tipo', $tipo)->latest()->first();
        
        if ($tipo === 'cumpleanos' && !$mensaje) {
            return '¡Feliz Cumpleaños! 🎉
Queremos desearte un día muy especial.

Te recordamos que puedes aprovechar nuestro descuento especial de cumpleaños en tu próxima compra.

¡Que tengas un excelente día!';
        }
        
        if ($tipo === 'consulta' && !$mensaje) {
            return 'Estimado/a [NOMBRE],

Le recordamos su cita oftalmológica programada para el [FECHA].

Por favor confirme su asistencia o comuníquese con nosotros si necesita reagendarla.

¡Le esperamos!';
        }
        
        return $mensaje ? $mensaje->mensaje : null;
    }
} 