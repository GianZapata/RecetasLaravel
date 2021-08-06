<?php

namespace App\Models;

use App\Models\Receta;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Evento que se ejecuta cuando un usuario es creado
    protected static function boot()
    {
        parent::boot();
        // Asignar pefil una vez se haya creado un usuario nuevo
        static::created(function($user){
            $user->perfil()->create();
        });
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** Relacion 1:n de Usuario a recetas **/
    public function recetas()
    {
        return $this->hasMany(Receta::class);
    }

    /** Relación 1:1 de usuario a perfil **/
    public function perfil()
    {
        return $this->hasOne(Perfil::class);
    }

    //Recetas que el usuario le ha dado me gusta

    public function meGusta()
    {
        return $this->belongsToMany(Receta::class,'likes_receta');
    }
}
