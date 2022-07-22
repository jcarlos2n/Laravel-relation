<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function user(){
        //inversa, una tarea podra ser creada por un usuario
        return $this->belongsTo(User::class);
    }
}
