@extends('layouts.app')

@section('hero')
    <div class="hero-categorias">
        <form action="{{route('buscar.show')}}" class="container h-100" >
            <div class="row h-100 align-items-center">
                <div class="col-md-4 texto-buscar">
                    <p class="display-4">Encuentra una receta para tu proxima comida</p>
                    <input type="search" name="buscar"  class="form-control">
                </div>
            </div>
        </form>
    </div>
@endsection
@section('content')
    {{-- <img src="{{asset('/images/bgimagen.jpg') }}" alt="Imagen Fondo"> --}}
    <div class="container nuevas-recetas">
        <h2 class="titulo-categoria text-uppercase mb-4">Ultimas Recetas</h2>
        <div class="owl-carousel owl-theme">
            @foreach($nuevas as $key => $nueva)
                <div class="card">
                    <img src="/storage/{{$nueva->imagen}}" class="card-img-top"  alt="Imagen Receta">
                    <div class="card-body">
                        <h3>{{Str::ucfirst($nueva->titulo)}}</h3>
                        <p>{{ Str::words(strip_tags( $nueva->preparacion),20) }}</p>
                        <a href="{{route('recetas.show',['receta' => $nueva->id ])}}" class="btn btn-primary d-block font-weight-bold text-uppercase">Ver receta</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        <h2 class="titulo-categoria text-uppercase mt-5 mb-4">Recetas más Votadas</h2>

        <div class="row">
            @foreach($votadas as $receta)
                @include('ui.receta')
            @endforeach
        </div>
    </div>

    @foreach($recetas as $key => $grupo)
    <div class="container">
        <h2 class="titulo-categoria text-uppercase mt-5 mb-4"> {{ str_replace('-',' ',$key) }}</h2>
        <div class="row">
            @foreach($grupo as $recetas)
                @foreach($recetas as $receta)
                    @include('ui.receta')
                @endforeach
            @endforeach
        </div>
    </div>
    @endforeach
@endsection

