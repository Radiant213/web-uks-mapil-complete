<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    public function students() {
        return $this->hasMany(Student::class);
    }
    protected $guarded = ['id'];
    protected $fillable = ['kelas'];
}
