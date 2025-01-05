<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Location extends Model{
    protected $table = 'locations';
    protected $fillable = ['location','added','updated_by'];
}