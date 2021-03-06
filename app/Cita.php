<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class Cita extends Model
{
    protected $fillable = ['fecha_hora', 'localizacion', 'duracion','medico_id', 'paciente_id'];
    protected $dates = ['fecha_hora','fecha_fin'];

    public function medico()
    {
        return $this->belongsTo('App\Medico');
    }

    public function paciente()
    {
        return $this->belongsTo('App\Paciente');
    }
    public function setFechaHoraAttribute($date){

        $this->attributes['fecha_hora'] = Carbon::parse($date);
    }

    public function mismaEspecialidad(){
        $especialidadMed=$this->medico->especialidad;
        $especialidadPac=$this->paciente->enfermedad->especialidad;
        if($especialidadMed==$especialidadPac){
            return TRUE;
        }else{
            flash("Medico de distinta especialidad");
            return FALSE;
        }
    }
}
