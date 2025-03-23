<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Income extends Model
{
    protected $table="incomes";
    protected $fillable=[
        'date',
        'total'
    ];
    public function sales():HasMany{
        return $this->hasMany(Sale::class);
    }
}
