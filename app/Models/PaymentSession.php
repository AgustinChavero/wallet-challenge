<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class PaymentSession extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'payment_sessions';

    protected $fillable = [
        'wallet_id',
        'token',
        'amount',
        'status_id',
        'confirmed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function status()
    {
        return $this->belongsTo(PaymentSessionStatus::class, 'status_id');
    }
}
