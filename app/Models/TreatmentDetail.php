<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentDetail extends Model
{
    public function treatment() {
        return $this->belongsTo(Treatment::class);  
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }
    protected $table = 'treatments_details';
    protected $guarded = ['id'];
}
