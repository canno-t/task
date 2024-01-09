<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property $name
 *
 * @mixin /Illuminate/Database/Eloquent/Builder
 */
class TaskPriopity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
}
