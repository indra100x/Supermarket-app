<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shift extends Model
{
    protected $table='shifts';
    protected $fillable=[
       'user_id',
       'cash_register_id',
       'start_at',
       'end_at',
    ];
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function cashRegister():BelongsTo{
        return $this->belongsTo(CashRegister::class);
    }
}
