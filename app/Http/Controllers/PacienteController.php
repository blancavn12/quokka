<?php

namespace App\Http\Controllers;

use App\Enfermedad;
use Illuminate\Http\Request;
use App\Paciente;
use App\Especialidad;
use App\Medico;

class PacienteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $especialidades= Especialidad::all()->pluck('name','id');
        //Filtro
        $especialidad_id=$request->get('especialidad_id');
        $query_base = Paciente::orderBy('id', 'desc');
        if(isset($especialidad_id) && $especialidad_id!=""){
            $query_base->where('especialidad_id',$especialidad_id);
        }
        $pacientes = $query_base->paginate(6);
        return view('pacientes/index',compact('pacientes'),['especialidades'=>$especialidades])->withUsers($pacientes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $enfermedades = Enfermedad::all()->pluck('name','id');

        return view('pacientes/create',['enfermedades'=>$enfermedades]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $enfermedad_id=$request->get('enfermedad_id');
        $enfermedad=Enfermedad::find($enfermedad_id);
        $especialidad_id=$enfermedad->especialidad_id;
        $request->merge(["especialidad_id"=>$especialidad_id]);

        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            //array nuhsa revisas
            'nuhsa' => 'required|nuhsa|unique:pacientes',
            'enfermedad_id' => 'required|exists:enfermedads,id',
            'especialidad_id' => 'required|exists:especialidads,id'
        ]);

        //TODO: crear validación propia para nuhsa

        $paciente = new Paciente($request->all());
        $paciente->save();

        // return redirect('especialidades');

        flash('Paciente creado correctamente');

        return redirect()->route('pacientes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // TODO: Mostrar las citas de un paciente
        $paciente = Paciente::find($id);

        return view('citas/index')->with('paciente',$paciente);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $paciente = Paciente::find($id);
        $enfermedades = Enfermedad::all()->pluck('name','id');

        return view('pacientes/edit',['paciente'=> $paciente,'enfermedades'=>$enfermedades ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $enfermedad_id=$request->get('enfermedad_id');
        $enfermedad=Enfermedad::find($enfermedad_id);
        $especialidad_id=$enfermedad->especialidad_id;
        $request->merge(["especialidad_id"=>$especialidad_id]);

        $this->validate($request, [
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            //array nuhsa revisas
            'nuhsa' => 'required|nuhsa|unique:pacientes',
            'enfermedad_id' => 'required|exists:enfermedads,id',
            'enfermedad_id' => 'required|exists:enfermedads,id'
        ]);

        $paciente = Paciente::find($id);
        $paciente->fill($request->all());

        $paciente->save();

        flash('Paciente modificado correctamente');

        return redirect()->route('pacientes.index');


    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $paciente = Paciente::find($id);
        $paciente->delete();
        flash('Paciente borrado correctamente');

        return redirect()->route('pacientes.index');
    }
}
