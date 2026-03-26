<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilContent extends Model
{
    protected $table = 'profil_contents';

    protected $fillable = ['type', 'title', 'content', 'image'];
}
