<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $team_id
 * @property $user_id
 *
 *
 * @mixin /Illuminate/Database/Eloquent/Builder
 */

class team_user extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'team_id'
    ];
    public function getTeam(){
        return $this->hasMany(Team::class, 'id', 'team_id');
    }
}
