<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    //
    protected $table = 'programs';

    protected $fillable = ['id', 'title', 'air_time', 'story', 'staff', 'image_url', 'episodes'];
}
