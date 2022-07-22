<?php

namespace App\Models;

use App\Commons\Traits\Image;
use Illuminate\Database\Eloquent\Model;

class Hello extends Model
{
    use Image;
    protected $guarded = [];
    protected $casts = [
        'list' => 'json',
    ];
}
