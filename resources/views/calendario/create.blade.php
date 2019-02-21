@extends('layouts.admin')
@section('title','Calendarios')
@section('content')
    <!-- Breadcrumb-->
    <div class="breadcrumb-holder container-fluid">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/home')}}">Inicio</a></li>
            <li class="breadcrumb-item active"><a href="{{url('calendarios')}}">Calendarios</a></li>
            <li class="breadcrumb-item">Crear</li>
        </ul>
    </div>

    @if(Session::has('error'))
        <script>
            setTimeout(function(){
                swal("¡Algo ha salido mal!", "Ha habido un error al procesar la peticion, vuelte a intentarlo", "error");
            }, 500);
        </script>
    @endif

    <!-- Dashboard Counts Section-->
    <section class="dashboard-counts">
        <div class="container-fluid">
            <div class="bg-white has-shadow">
                <!-- Form Elements -->
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('calendarios.store')}}" method="POST" class="form-horizontal">
                            @csrf

                            <div class="form-group row">
                                <label for="create_jornada" class="form-control-label col-sm-3">Numero Jornada </label>
                                <div class="col-sm-9">
                                    <input id="create_jornada" type="text" name="jornada" class="form-control{{ $errors->has('jornada') ? ' is-invalid' : '' }}" value="{{old('jornada')}}" placeholder="Numero entero">
                                    @if ($errors->has('jornada'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('jornada') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="create_fecha" class="form-control-label col-sm-3">Fecha</label>
                                <div class="col-sm-9">
                                    <input id="create_fecha" type="date" name="fecha" class="form-control{{ $errors->has('fecha') ? ' is-invalid' : '' }}" value="{{old('fecha')}}" placeholder="">
                                    @if ($errors->has('fecha'))
                                        <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('fecha') }}</strong>
                                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="create_fase" class="col-sm-3 form-control-label">Fase</label>
                                <div class="col-sm-9">
                                    <select name="fase" id="create_fase" class="form-control{{ $errors->has('fase') ? ' is-invalid' : '' }}">
                                        <option value="0" >Seleccione una Clasificacion</option>
                                        @foreach($fases as $fase)
                                            <option value="{{$fase->id}}">{{$fase->nombre}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('fase'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('fase') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="create_torneo" class="col-sm-3 form-control-label">Departamento</label>
                                <div class="col-sm-9">
                                    <select name="torneo" id="create_torneo" class="form-control{{ $errors->has('torneo') ? ' is-invalid' : '' }}">
                                        <option value="0" >Seleccione un Torneo</option>
                                            @foreach($torneos as $torneo)
                                                <option value="{{$torneo->id}}">{{$torneo->nombre}}</option>
                                            @endforeach
                                    </select>
                                    @if ($errors->has('torneo'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('torneo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-sm-12 offset-sm-5">
                                    <button type="button"  onclick="window.location='{{route('calendarios.index')}}'" class="btn btn-secondary">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

