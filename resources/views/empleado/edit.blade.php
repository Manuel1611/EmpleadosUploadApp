@extends('admin.base')

@section('cuerpo')

<div style="margin-left: 10%; width: 80%; height: 80%; display: flex; flex-direction: column;">
    
    <h1 style="color:black; margin-top: 20px; margin-bottom: 30px; text-transform: uppercase">Edita al empleado {{$empleado->name}}</h1>
    
    @if (Session::has('message'))
        <div class="alert alert-{{ session()->get('type') }}" role="alert">
          {{ session()->get('message') }}
        </div>
    @endif
    
    <form id="mainForm" action="{{ url('empleado/' . $empleado->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Nombre del empleado</span><br><br>
        <input class="form-control" style="margin-bottom: 25px" value="{{ old('name', $empleado->name) }}" type="text" name="name" autocomplete="off" placeholder="Nombre del empleado" min-length="2" max-length="50" required />
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Apellidos del empleado</span><br><br>
        <input class="form-control" style="margin-bottom: 25px" value="{{ old('surname', $empleado->surname) }}" type="text" autocomplete="off" name="surname" placeholder="Apellidos" min-length="2" max-length="100" required />
        @error('surname')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Correo electrónico del empleado</span><br><br>
        <input class="form-control" style="margin-bottom: 25px" value="{{ old('email', $empleado->email) }}" type="email" autocomplete="off" name="email" placeholder="Correo electrónico" min-length="2" max-length="120" required />
        @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Teléfono del empleado</span><br><br>
        <input class="form-control" style="margin-bottom: 25px" value="{{ old('phone', $empleado->phone) }}" type="number" name="phone" placeholder="Teléfono" min"600000000" max="999999999" step="1" required />
        @error('phone')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Fecha de contratación</span><br><br>
        <input class="form-control" style="margin-bottom: 25px; width: 300px;" value="{{ old('datecontract', $empleado->datecontract) }}" type="date" name="datecontract" required />
        @error('datecontract')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Departamento asociado</span><br><br>
        <select class="form-control" style="width: 300px; margin-bottom: 25px" name="iddepartamento">
            <option selected value="">&nbsp;</option>
            @foreach($departamentos as $departamento)
                <option value="{{ $departamento->id }}" {{ $departamento->id == $empleado->iddepartamento ? 'selected' : '' }}>{{ $departamento->name }}</option>
            @endforeach
        </select>
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Puesto asociado</span><br><br>
        <select class="form-control" style="width: 300px;" name="idpuesto">
            <option selected value="">&nbsp;</option>
            @foreach($puestos as $puesto)
                <option value="{{ $puesto->id }}" {{ $puesto->id == $empleado->idpuesto ? 'selected' : '' }}>{{ $puesto->name }}</option>
            @endforeach
        </select><br>
        <label style="margin-bottom: 25px">
            &nbsp;<input type="checkbox"
                @foreach($departamentos as $departamento)
                    @if($departamento->idempleadojefe == $empleado->id)
                        checked
                    @endif
                @endforeach
                name="idempleadojefe" />&nbsp;&nbsp;Ser jefe del departamento
        </label><br>
        &nbsp;<span style="font-size: 1.6em; color: blue">&raquo;</span>&nbsp;<span class="spanmio">Imagen</span><br><br>
        @foreach($imagenes as $imagen)
            @if($imagen->idempleado == $empleado->id)
                @if($imagen->myname != null)
                    <div class="parent-div" style="margin-bottom: 25px">
                        <div class="btn-upload" id="btn-upload" data-url= "{{ asset('storage/images/' . $empleado->id . '/' . $imagen->myname) }}"></div>
                        <input class="myinputfile" id="myinputfile" style="margin-bottom: 25px" type="file" name="photo" accept="image/*" />
                    </div>
                @else
                    <div class="parent-div" style="margin-bottom: 25px">
                        <div class="btn-upload" id="btn-upload" style="background: url({{ url('assets/admin/img/noimg.jpg') }}) center center no-repeat;
                            background-size: contain;"></div>
                        <input class="myinputfile" id="myinputfile" style="margin-bottom: 25px" type="file" name="photo" accept="image/*" />
                    </div>
                @endif
                <input class="form-control" value="{{ $imagen->name }}" style="width: 250px" type="text" autocomplete="off" name="imgname" placeholder="Nombre de la imagen" min-length="1" max-length="50" /><br>
            @endif
        @endforeach
        
    </form>
    
    @foreach($imagenes as $imagen)
        @if($imagen->idempleado == $empleado->id)
            @if($imagen->myname != null)
                <form id="formImg" action="{{ url('imagen/' . $imagen->id) }}" method="post">
                    @method('delete')
                    @csrf
                    <input form="formImg" type="submit" value="Borrar imagen" class="form-control"
                        style="margin-bottom: 25px; width: 250px; background-color: rgba(214, 39, 74, 1); color: white;
                        border: 0; text-decoration: none; text-align: center;">
                    </input>
                </form>
            @endif
        @endif
    @endforeach
    
    <div style="margin-bottom: 50px">
        <input form="mainForm" class="btn btn-primary" type="submit" value="Editar empleado"/>
        <a class="btn btn-info" href="{{ url('empleado') }}">Volver</a>
    </div>
    
</div>

@endsection

@section('js')
    <script src="{{ url('assets/admin/js/showImgEdit.js') }}" defer></script>
@endsection