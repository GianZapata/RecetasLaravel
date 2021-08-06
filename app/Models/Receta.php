<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{
    //Campos que se agregaran
    protected $fillable = [
        'titulo',
        'preparacion',
        'ingredientes',
        'imagen',
        'categoria_id'
    ];
    
    // Obtiene la categoria via FK
    public function categoria()
    {
        return $this->belongsTo(CategoriaReceta::class);
    }

    // Obtiene la información via FK
    public function autor()
    {
        return $this->belongsTo(User::class,'user_id'); // EL fk de esta tabla
    }

    //Likes que ha recibido una receta
    public function likes()
    {
        return $this->belongsToMany(User::class,'likes_receta');
    }
}
