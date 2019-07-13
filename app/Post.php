<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class Post extends Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = [];
}
