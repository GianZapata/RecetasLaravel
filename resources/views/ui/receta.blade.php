<div class="col-md-4 mt-4">
    <div class="card shadow">
        <img class="card-img-top" src="/storage/{{$receta->imagen}}" alt="Imagen receta">
        <div class="card-body">
            <h3 class="card-title">{{$receta->titulo}}</h3>
            <div class="meta-receta d-flex justify-content-between">
                @php
                    $fecha = $receta->created_at
                @endphp
                <p class="text-primary fecha">
                    <fecha-receta fecha="{{$fecha}}"></fecha-receta>
                </p>
                <p>{{count($receta->likes)}} Les gustó</p>
            </div>
            <p>{{ Str::words(strip_tags( $receta->preparacion),20,'...') }}</p>
            <a class="btn btn-primary d-block btn-receta text-uppercase" href="{{route('recetas.show',['receta' => $receta->id ])}}">Ver receta</a>
        </div>
    </div>
</div>