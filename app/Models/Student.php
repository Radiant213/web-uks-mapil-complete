<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function treatments() {
        return $this->hasMany(Treatment::class);
    }
    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }
    protected $guarded = ['id'];
}
