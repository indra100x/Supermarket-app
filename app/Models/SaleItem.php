<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
   protected $table="sale_items";
   protected $fillable=[
    'sale_id',
    'product_id',
    'quantity',
    'price'
   ];
     public function sales():BelongsTo{
        return $this->belongsTo(Sale::class);
     }
     public function products():BelongsTo{
        return $this->belongsTo(Product::class);
     }
}
