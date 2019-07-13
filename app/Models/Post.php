<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class Post extends Moloquent
{
    protected $connection = 'mongodb';

    protected $guarded = [];
}
