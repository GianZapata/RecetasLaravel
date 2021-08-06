<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Receta;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => 'show']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function show(Perfil $perfil)
    {
        //Obtener recetas con paginacion
        $recetas = Receta::where('user_id',$perfil->user_id)->paginate(10);
        return view('perfiles.show',compact('perfil','recetas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function edit(Perfil $perfil)
    {
        //Ejecutar el policy
        $this->authorize('view',$perfil);

        return view('perfiles.edit',compact('perfil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perfil $perfil)
    {
        //Ejecutar el policy
        $this->authorize('update',$perfil);

        // Validar
        $data = request()->validate([
            'nombre' => 'required',
            'url' => 'required',
            'biografia' => 'required'
        ]);

        // Si el usuario sube una imagen
        if($request['imagen']){
            
            //Obtener ruta de imagen 
            $ruta_imagen = $request['imagen']->store('upload-perfiles','public');
            //Resize de la imagen

            $img = Image::make(public_path("storage/{$ruta_imagen}"))->fit(600,600);
            $img->save();
            
            //Crear un arreglo de imagen
            $array_imagen = ['imagen' => $ruta_imagen];
        }
        
        // Asignar nombre y url 
        auth()->user()->name = $data['nombre'];
        auth()->user()->url = $data['url'];
        auth()->user()->save();

        // Eliminar url y name de $data
        unset($data['url']);
        unset($data['nombre']);
        // Asignar biografia e imagne
        // Guardar información
        auth()->user()->perfil()->update(array_merge($data,$array_imagen ?? []));

        // Redireccionar
        return redirect()->action([RecetaController::class,'index']);
    }

}
