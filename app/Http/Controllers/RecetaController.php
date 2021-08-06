<?php

namespace App\Http\Controllers;

use App\Models\CategoriaReceta;
use App\Models\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class RecetaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth',['except' => ['show','create']]);
        $this->middleware('auth',['except' => ['show','search']]); //Como segundo parametro hacer una excepcion para que no entre a la validacion de autenticacion
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtener Id del Usuario Logueado
        $usuario = Auth::user();
        
        // Var dump a recetas
        // Auth::user()->recetas->dd();
        
        // $recetas = auth()->user()->recetas->paginate(2);

        // Metodo para obtener las recetas sin paginacion
        // $recetas = Receta::where('user_id',$usuario)->get();

        // Metodo par obtener las recetas con paginacion
        $recetas = Receta::where('user_id',$usuario->id )->paginate(10);

        return view('recetas.index')->with('recetas',$recetas)->with('usuario',$usuario);
        // return view('recetas.index')->with('recetas',$recetas)->with('usuario',$usuario);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // DB::table('categoria_receta')->get()->pluck('nombre','id')->dd();

        // Obtener categorias sin modelo
        // $categorias = DB::table('categoria_recetas')->get()->pluck('nombre','id');

        // Obtener categorias con modelo
        $categorias = CategoriaReceta::all(['id','nombre']);
        return view('recetas.create')->with('categorias',$categorias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validación
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',
            'imagen' => 'required|image'
        ]);

        //Obtener ruta de imagen sin modelo
        $ruta_imagen = $request['imagen']->store('upload-recetas','public');

        //Resize de la imagen

        $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1200,550);
        $img->save();

        // //Guardar en la db (sin modelo)
        // DB::table('recetas')->insert([
        //     'titulo' => $data['titulo'],
        //     'ingredientes' => $data['ingredientes'],
        //     'preparacion' => $data['preparacion'],
        //     'imagen' =>  $ruta_imagen,
        //     'user_id' => Auth::user()->id,
        //     'categoria_id' => $data['categoria']
        // ]);
        
        // almacenar en la bd con modelo
        auth()->user()->recetas()->create([
            'titulo' => $data['titulo'],
            'ingredientes' => $data['ingredientes'],
            'preparacion' => $data['preparacion'],
            'imagen' =>  $ruta_imagen,
            'categoria_id' => $data['categoria']
        ]);

        // Redireccionar
        return redirect()->action([RecetaController::class,'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        // Obtener si el usuario actual le gusta la receta y esta autenticado
        $like = (auth()->user()) ? auth()->user()->meGusta->contains($receta->id) : false;

        // Pasa la cantidad de likes a la vista
        $likes = $receta->likes->count();

        return view('recetas.show',compact('receta','like','likes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function edit(Receta $receta)
    {
        // Revisar el policy
        $this->authorize('update',$receta);
        $categorias = CategoriaReceta::all(['id','nombre']);
        return view('recetas.edit',compact('categorias','receta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receta $receta)
    {
        // Revisar el policy
        $this->authorize('update',$receta);
        $data = request()->validate([
            'titulo' => 'required|min:6',
            'categoria' => 'required',
            'preparacion' => 'required',
            'ingredientes' => 'required',            
        ]);

        $receta->titulo = $data['titulo'];
        $receta->preparacion = $data['preparacion'];
        $receta->ingredientes = $data['ingredientes'];
        $receta->categoria_id = $data['categoria'];

        if(request('imagen')){
            //Obtener ruta de imagen sin modelo
            $ruta_imagen = $request['imagen']->store('upload-recetas','public');
            //Resize de la imagen
            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(1200,550);
            $img->save();

            //Asignar al objeto
            $receta->imagen = $ruta_imagen;
        }
        $receta->save();



        //redireccionar
        return redirect()->action([RecetaController::class,'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        //Ejecutar el policy
        $this->authorize('delete',$receta);

        //Eliminar la receta
        $receta->delete();

        return redirect()->action([RecetaController::class,'index']);
    }

    public function search(Request $request)
    {
        // $busqueda = $request['buscar'];
        $busqueda = $request->get('buscar');

        $recetas = Receta::where('titulo','like','%' . $busqueda . '%')->paginate(5);
        $recetas->appends(['buscar' => $busqueda]);
        return view('busquedas.show',compact('recetas','busqueda'));
    }
}
