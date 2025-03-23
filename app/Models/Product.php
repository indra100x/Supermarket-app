<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table="products";
    protected $fillable=[
        'name',
        'barcode',
        'category_id',
        'price',

    ];
    public function category():BelongsTo{
        return $this->belongsTo(ProductCategory::class);
    }
   public function stock():HasOne{
    return $this->hasOne(Stock::class);
   }
   public function saleItems():HasMany{
    return $this->hasMany(SaleItem::class);
   }
}
