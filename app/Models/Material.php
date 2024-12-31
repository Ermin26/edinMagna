<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



Class Material extends Model{
    protected $table ='materials';
    protected $fillable = ['material', 'location'];
}