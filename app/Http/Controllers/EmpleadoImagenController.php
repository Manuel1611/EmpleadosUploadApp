<?php

namespace App\Http\Controllers;

use App\Models\EmpleadoImagen;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class EmpleadoImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmpleadoImagen  $empleadoImagen
     * @return \Illuminate\Http\Response
     */
    public function show(EmpleadoImagen $empleadoImagen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmpleadoImagen  $empleadoImagen
     * @return \Illuminate\Http\Response
     */
    public function edit(EmpleadoImagen $empleadoImagen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmpleadoImagen  $empleadoImagen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmpleadoImagen $empleadoImagen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmpleadoImagen  $empleadoImagen
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $imagen = EmpleadoImagen::find($id);
        $id = $imagen->id;
        $idempleado = $imagen->idempleado;
        $data = [];
        $data['message'] = 'La imagen ' . $imagen->name . ' ha sido eliminada';
        $data['type'] = 'success';
        $flag = true;
        
        try {
            DB::table('empleado_imagen')->where("empleado_imagen.id", '=',  $id)
                ->update(['empleado_imagen.name' => null, 'empleado_imagen.myname' => null, 'empleado_imagen.mimetype' => null]);
        } catch(\Exception $e) {
            $flag = false;
            $data['message'] = 'La imagen ' . $imagen->name . ' no ha podido ser eliminada';
            $data['type'] = 'danger';
        }
        
        if($flag) {
            $directory = '/public/images/' . $idempleado;
            $response = Storage::deleteDirectory($directory);
        }
        
        return redirect('empleado/' . $idempleado . '/edit')->with($data);
    }
}
