<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $table="sales";
    protected $fillable=[
      'cash_register_id',
      'payment_method',
      'price',
    ];
    public function cashRegister():BelongsTo{
        return $this->belongsTo(CashRegister::class);
    }
    public function saleItems():HasMany{
    return $this->hasMany(SaleItem::class);
    }
}
