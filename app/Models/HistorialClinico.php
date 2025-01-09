<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    use HasFactory;

    protected $table = 'historiales_clinicos';

    protected $fillable = [
        'paciente_id',
        'fecha',
        'motivo_consulta',
        'enfermedad_actual',
        'antecedentes_personales_oculares',
        'antecedentes_personales_generales',
        'antecedentes_familiares_oculares',
        'antecedentes_familiares_generales',
        'agudeza_visual_vl_sin_correccion_od',
        'agudeza_visual_vl_sin_correccion_oi',
        'agudeza_visual_vl_sin_correccion_ao',
        'agudeza_visual_vp_sin_correccion_od',
        'agudeza_visual_vp_sin_correccion_oi',
        'agudeza_visual_vp_sin_correccion_ao',
        'optotipo',
        'lensometria_od',
        'lensometria_oi',
        'tipo_lente',
        'material',
        'filtro',
        'tiempo_uso',
        'refraccion_od',
        'refraccion_oi',
        'rx_final_dp',
        'rx_final_av_vl',
        'rx_final_av_vp',
        'diagnostico',
        'tratamiento'
    ];
}
