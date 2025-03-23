<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table="stocks";
    protected $fillable=[
        'product_id',
        'quantity'
    ];
    public function products():BelongsTo{
        return $this->belongsTo(Product::class);
    }
}
