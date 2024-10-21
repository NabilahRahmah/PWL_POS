<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; //implementasi class Authenticatable

class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user'; //mendefinisikan nama table yang digunakan oleh model
    protected $primaryKey = 'user_id'; //mendefinisikan primary key dari table yang digunakan

    protected $fillable = ['username', 'password', 'nama', 'level_id', 'created_at', 'updated_at'];

    protected $hidden = ['password']; //jangan di tampilka saat select

    protected $casts = ['password' => 'hashed']; //casting password agar otomatis di hash

    //JS 4 PRAKTIKUM 2.7
    public function level(): BelongsTo {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    /* Mendapatkan nama role */
public function getRoleName(): string
{
    return $this->level->level_nama;
}
/*
 * Cek apakah user memiliki role tertentu
 */
public function hasRole($role): bool
{
    return $this->level->level_kode == $role;
}

/**
 * Mendapatkan kode Role
 */
public function getRoleId(): string
{
    return $this->level->level_kode;
}
}