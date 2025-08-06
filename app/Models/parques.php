<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parque extends Model
{
    use HasFactory;

    // Ana R. Cabrera - Nombre de la tabla en la base de datos
    protected $table = 'parques';

    // Ana R. Cabrera - La clave primaria de la tabla
    protected $primaryKey = 'cod_parque';

    // Ana R. Cabrera - Indicar que la tabla no usa timestamps (created_at, updated_at)
    public $timestamps = false;

    // Ana R. Cabrera - Campos que pueden ser llenados masivamente
    protected $fillable = [
        'nombre_parque',
        'ubicacion_parque',
        'fecha_inauguracion',
        'estado',
    ];
}