<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryDiagram extends Model
{
    protected $table = 'history_diagrams';

    protected $fillable = ['title', 'description', 'year'];
}
