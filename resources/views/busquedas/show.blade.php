@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="titulo-categoria text-uppercase mt-5 mb-4">Resultados Búsqueda: {{$busqueda}}</h2>
        @if(count($recetas) > 0)
        <div class="row">
            @foreach($recetas as $receta)
                @include('ui.receta')
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{$recetas->links("pagination::bootstrap-4")}}
        </div>
        @else
            <h3>No hay resultados en su busqueda... <a class="btn btn-link" href="{{route('inicio.index')}}">Regresar</a></h3>
        @endif

    </div>
@endsection