<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\EmpleadoImagen;
use App\Models\Departamento;
use App\Models\Puesto;
use Illuminate\Http\Request;
use App\Http\Requests\EmpleadoCreateRequest;
use App\Http\Requests\EmpleadoEditRequest;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['empleados'] = Empleado::all();
        return view('empleado.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        $data['departamentos'] = Departamento::all();
        $data['puestos'] = Puesto::all();
        return view('empleado.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmpleadoCreateRequest $request)
    {
        $data = [];
        $data['message'] = 'Un nuevo empleado ha sido añadido correctamente';
        $data['type'] = 'success';
        $empleado = new Empleado($request->all());
        
        try {
            $result = $empleado->save();
        } catch(\Exception $e) {
            $data['message'] = 'El empleado no ha podido ser añadido porque ya existe uno con ese correo o teléfono';
            $data['type'] = 'danger';
            return back()->withInput()->with($data);
        }
        if(!$result) {
            $data['message'] = 'El empleado no puede ser añadido';
            $data['type'] = 'danger';
            return back()->withInput()->with($data);  
        }
        
        if($request->has('idempleadojefe')) {
            $departamentos = Departamento::all();
            $id = $empleado->iddepartamento;
            $idjefe = $empleado->id;
        
            foreach($departamentos as $departamento) {
                if($departamento->id == $empleado->iddepartamento) {
                    DB::table('departamento')->where("departamento.id", '=',  $id)
                        ->update(['departamento.idempleadojefe' => $idjefe]);
                }
            }
        }
        
        $inputFile = 'photo';
        if($request->hasFile($inputFile) && $request->file($inputFile)->isValid()) {
            $fileUpload = $request->file($inputFile);
            $nameFile = $fileUpload->getClientOriginalName();
            $nameFile = Str::random(5) . '-' . $empleado->name . '-' . $nameFile;
            
            $image = new EmpleadoImagen();
            $image->idempleado = $empleado->id;
            $image->name = $request->input('imgname');
            $image->myname = $nameFile;
            $image->mimetype = $fileUpload->getMimeType();
            
            if($image->mimetype != null) {
                if($image->mimetype != 'image/jpeg' && $image->mimetype != 'image/png'
                    && $image->mimetype != 'image/bmp' && $image->mimetype != 'image/tiff') {
                        $empleado->delete();
                        $data['message'] = 'Solo se permite la subida de archivos que sean imágenes';
                        $data['type'] = 'danger';
                        return back()->withInput()->with($data);
                }
            }
            
            try {
                $resultImg = $image->save();
            } catch(\Exception $e) {
                $resultImg = false;
            }
            if(!$resultImg) {
                $data['message'] = 'El empleado no puede ser añadido';
                $data['type'] = 'danger';
                return back()->withInput()->with($data);
            }
            
            $fileUpload->storeAs('public/images/' . $empleado->id, $nameFile);
        
        } else {
            
            $image = new EmpleadoImagen();
            $image->idempleado = $empleado->id;
            $image->name = $request->input('imgname');
            
            if($image->myname == null && $image->name != null) {
                $empleado->delete();
                $data['message'] = 'No puedes establecer un nombre a una imagen que no existe';
                $data['type'] = 'danger';
                return back()->withInput()->with($data);
            }
            
            try {
                $resultImg = $image->save();
            } catch(\Exception $e) {
                $resultImg = false;
            }
            if(!$resultImg) {
                $data['message'] = 'El empleado no puede ser añadido';
                $data['type'] = 'danger';
                return back()->withInput()->with($data);  
            }
        }
        
        return redirect('empleado')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        $data = [];
        $data['departamentos'] = Departamento::all();
        $data['puestos'] = Puesto::all();
        $data['imagenes'] = EmpleadoImagen::all();
        $data['empleado'] = $empleado;
        return view('empleado.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit(Empleado $empleado)
    {
        $data = [];
        $data['departamentos'] = Departamento::all();
        $data['puestos'] = Puesto::all();
        $data['imagenes'] = EmpleadoImagen::all();
        $data['empleado'] = $empleado;
        return view('empleado.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(EmpleadoEditRequest $request, Empleado $empleado)
    {
        
        $inputFile = 'photo';
        $images = EmpleadoImagen::all();
        foreach($images as $image) {
            if($image->idempleado == $empleado->id) {
                $realimg = $image;
            }
        }
            
        if($request->hasFile($inputFile) && $request->file($inputFile)->isValid()) {
            $fileUpload = $request->file($inputFile);
            $nameFile = $fileUpload->getClientOriginalName();
            $nameFile = Str::random(5) . '-' . $empleado->name . '-' . $nameFile;
            
            if($fileUpload->getMimeType() != null) {
                if($fileUpload->getMimeType() != 'image/jpeg' && $fileUpload->getMimeType() != 'image/png'
                    && $fileUpload->getMimeType() != 'image/bmp' && $fileUpload->getMimeType() != 'image/tiff') {
                        $data['message'] = 'Solo se permite la subida de archivos que sean imágenes';
                        $data['type'] = 'danger';
                        return back()->withInput()->with($data);
                }
            }
            
            $args = [
                'name' => $request->input('imgname'),
                'myname' => $nameFile,
                'mimetype' => $fileUpload->getMimeType(),
                    
            ];
            
            try {
                $resultImg = $realimg->update($args); ;
            } catch(\Exception $e) {
                $resultImg = false;
            }
            if(!$resultImg) {
                $data['message'] = 'El empleado no puede ser editado';
                $data['type'] = 'danger';
                return back()->withInput()->with($data);  
            }
    
            $directory = '/public/images/' . $empleado->id;
            
            $response = Storage::deleteDirectory($directory);
            
            $fileUpload->storeAs('public/images/' . $empleado->id, $nameFile);
        
        } else {
            $image->name = $request->input('imgname');
            if($image->myname == null && $image->name != null) {
                $data['message'] = 'No puedes establecer un nombre a una imagen que no existe';
                $data['type'] = 'danger';
                return back()->withInput()->with($data);
            }
            
            try {
                $resultImg = $realimg->update(['name' => $request->input('imgname')]);
            } catch(\Exception $e) {
                $resultImg = false;
            }
            if(!$resultImg) {
                $data['message'] = 'El empleado no puede ser editado';
                $data['type'] = 'danger';
                return back()->withInput()->with($data);  
            }
        }
        
        $data = [];
        $data['message'] = 'El empleado ' . $empleado->name . ' se ha actualizado correctamente';
        $data['type'] = 'success';
        try {
            $result = $empleado->update($request->all());  
        } catch(\Exception $e) {
            $result = false;
        }
        if(!$result) {
            $data['message'] = 'El empleado no puede ser actualizado';
            $data['type'] = 'danger';
            return back()->withInput()->with($data);  
        }
        
        $departamentos = Departamento::all();
        
        foreach($departamentos as $departamento) {
            $id = $empleado->id;
            if($departamento->id != $empleado->iddepartamento) {
                if($departamento->idempleadojefe == $empleado->id) {
                    DB::table('departamento')->where("departamento.idempleadojefe", '=',  $id)
                        ->update(['departamento.idempleadojefe' => null]);
                }   
            }
        }
        
        $id = $empleado->iddepartamento;
        $idjefe = $empleado->id;
        if($request->has('idempleadojefe')) {
        
            foreach($departamentos as $departamento) {
                if($departamento->id == $empleado->iddepartamento) {
                    DB::table('departamento')->where("departamento.id", '=',  $id)
                        ->update(['departamento.idempleadojefe' => $idjefe]);
                }
            }
        } else {
            DB::table('departamento')->where("departamento.id", '=',  $id)
                ->update(['departamento.idempleadojefe' => null]);
        }
        
        return redirect('empleado')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Empleado $empleado)
    {
        $data = [];
        $data['message'] = 'El empleado ' . $empleado->name . ' ha sido eliminado';
        $data['type'] = 'success';
        
        $departamentos = Departamento::all();
        $imagenes = EmpleadoImagen::all();
        $id = $empleado->id;
        
        foreach($departamentos as $departamento) {
            if($departamento->idempleadojefe == $empleado->id) {
                DB::table('departamento')->where("departamento.idempleadojefe", '=',  $id)
                    ->update(['departamento.idempleadojefe' => null]);
            }
        }
        
        foreach($imagenes as $imagen) {
            if($imagen->idempleado == $empleado->id) {
                $imagen->delete();
                
                $directory = '/public/images/' . $empleado->id;
                $response = Storage::deleteDirectory($directory);
            }
        }
        
        try {
            $empleado->delete();
        } catch(\Exception $e) {
            $data['message'] = 'El empleado ' . $empleado->name . ' no ha podido ser eliminado';
            $data['type'] = 'danger';
        }
        return redirect('empleado')->with($data);
    }
}
