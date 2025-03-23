<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    protected $table="cash_registers";
     protected $fillable=[
      "status",
     ];
     public function sale():HasMany{
        return $this->hasMany(Sale::class);
     }
}
