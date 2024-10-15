<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama table yang digunakan oleh model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari table yang digunakan

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    
    // JS 4 PRAKTIKUM 1
    //protected $fillable = ['level_id', 'username', 'nama', 'password'];
    //protected $fillable = ['level_id', 'username', 'nama', 'password'];

    //JS 4 PRAKTIKUM 2.7
    public function level(): BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

}