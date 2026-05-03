<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function medicines() {
        return $this->belongsToMany(Medicine::class, 'treatments_details')->withPivot('jumlah_obat');
    }

    public function treatment_details() {
        return $this->hasMany(TreatmentDetail::class);
    }
    
    protected $guarded = ['id'];
}
