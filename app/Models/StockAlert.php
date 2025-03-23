<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
 protected $table="stock_alerts";
 protected $fillable=[
    'product_id',
    'thershold',
 ];
 public function products():BelongsTo{
    return $this->belongsTo(Product::class);
 }
}
