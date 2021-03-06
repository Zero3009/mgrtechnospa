<?php

namespace App;

use App\PrimaryModel;

class Stock extends PrimaryModel
{
    protected $table = 'stock';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['productos_id', 'proveedores_id','serial','precio_entrada','precio_salida','fecha_entrada','fecha_salida','disponible','estado', 'clientes_id'];

    public function setSerialAttribute($value)
	{
	    $this->attributes['serial'] = parent::toUpperOrNull($value);
	}

	public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }
}
