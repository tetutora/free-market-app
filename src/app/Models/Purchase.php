<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
