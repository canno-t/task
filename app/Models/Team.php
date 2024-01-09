<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $name
 * @property $creator
 *
 *
 * @mixin /Illuminate/Database/Eloquent/Builder
 */

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'creator'
    ];

    public function getTeamUsers(){
        return $this->hasMany(team_user::class, 'user_id', 'id');
    }
}
