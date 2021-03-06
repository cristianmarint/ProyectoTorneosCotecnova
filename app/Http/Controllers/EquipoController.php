<?php
/*
 * @Author: CristianMarinT 
 * @Date: 2019-02-20 14:08:14 
 * @Last Modified by: CristianMarinT
 * @Last Modified time: 2019-05-12 16:30:29
 */
namespace App\Http\Controllers;
use App\Models\Equipo;
use App\Models\Direccion;
use App\Models\Instituto;
use App\Models\Color;
use App\Models\InscripcionEquipo;
use App\Models\InscripcionJugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $equipos = Equipo::all();
        return view('equipo.index', compact('equipos'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institutos = Instituto::orderBy('nombre', 'asc')->get();
        $colores = Color::orderBy('nombre', 'asc')->get();
        return view('equipo.create', compact('institutos','colores'));
    }
    /**
     * Display the specified resource .
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response json
     */
    public function getEquipo($id){
        $equipo = Equipo::where('equipo_id',$id)
            ->orderBy('nombre', 'asc')
            ->get();
        return response()->json($equipo);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|min:3|max:60',
            'logo'   => 'image',
            'instituto' => 'required|integer|not_in:0|exists:instituto,id',
            'color' => 'required|integer|not_in:0|exists:color,id'
        ]);
        DB::beginTransaction();
        try{
            if ($request->hasFile('logo')) {
                $archivo = $request->file('logo');
                $nombreImg = 'storage/storage/img/equipo/' . time() . '-' . $archivo->getClientOriginalName();
                if ($archivo->move('storage/storage/img/equipo', $nombreImg)) {
                    echo "guardado";
                    $success = true;
                }
            } else {
                $nombreImg = 'storage/storage/img/equipo/default.png';
                $success = true;
            }
            $equipo = NEW Equipo();
                $equipo->nombre = $request->input('nombre');
                $equipo->logo = $nombreImg;
                $equipo->instituto_id = $request->input('instituto');
                $equipo->color_id = $request->input('color');
                $equipo->user_id = Auth::user()->id;
            $equipo->save();
        } catch (\exception $e){
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success){
            DB::commit();
            session()->flash('create', $equipo->nombre);
            return redirect(route('equipos.index'))->with('success');
        }else{
            if (file_exists(public_path($nombreImg))) {
                if($nombreImg != 'storage/storage/img/equipo/default.png'){
                    unlink(public_path($nombreImg));
                }
            }
            session()->flash('error', 'error');
            return redirect()->back()->withInput();
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $equipo = Equipo::findOrFail($id);
        $jugadores = Equipo::select('datos_basicos.cedula','datos_basicos.primer_nombre','datos_basicos.primer_apellido')
                            ->join('inscripcion_equipo','equipo.id','=','inscripcion_equipo.equipo_id')
                            ->join('inscripcion_jugador','inscripcion_jugador.inscripcion_equipo_id','inscripcion_equipo.id')
                            
                            ->join('jugador','jugador.id','inscripcion_jugador.jugador_id')
                            ->join('datos_basicos','datos_basicos.id','jugador.datos_basicos_id')
                            ->where('inscripcion_equipo.equipo_id','=',$id)
                            ->get();
        return view('equipo.show', compact('equipo','jugadores'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $equipo = Equipo::findOrFail($id);
        $institutos = Instituto::orderBy('nombre', 'asc')->get();
        $colores = Color::orderBy('nombre', 'asc')->get();
        return view('equipo.edit',compact('equipo','institutos','colores'));
        
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
        $equipo = Equipo::findOrFail($id);
        $data = $request->validate([
            'nombre' => 'required|min:3|max:60',
            'logo'   => 'image',
            'instituto' => 'required|integer|not_in:0|exists:instituto,id',
            'color' => 'required|integer|not_in:0|exists:color,id'
        ]);
        DB::beginTransaction();
        try{
            if($request->hasFile('logo')){
                $archivo = $request->file('logo');
                $nombreImg = 'storage/storage/img/equipo/'.time().'-'.$archivo->getClientOriginalName();
                if (file_exists(public_path($equipo->logo))) {
                    if($equipo->logo != 'storage/storage/img/equipo/default.png'){
                        unlink(public_path($equipo->logo));
                    }
                }
                if($archivo->move('storage/storage/img/equipo',$nombreImg)){
                    echo "Guardado";
                }else{
                    echo "error al guardar";
                }
            }else{
                $nombreImg = $equipo->logo;
            }
            $equipo->nombre = $request->input('nombre');
            $equipo->logo = $nombreImg;
            $equipo->instituto_id = $request->input('instituto');
            $equipo->color_id = $request->input('color');
            $equipo->user_id = Auth::user()->id;
            $equipo->save();
            $success = true;
        } catch (\exception $e){
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success){
            DB::commit();
            session()->flash('update', $equipo->nombre);
            return redirect(route('equipos.index'))->with('success');
        }else{
            session()->flash('error', 'error');
            return back()->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipo = Equipo::find($id);   
        $equipo->user_id = Auth::user()->id;
        $equipo->delete();
        $equipo->save();
        return redirect(route('equipos.index'))->with('success');
    }
}