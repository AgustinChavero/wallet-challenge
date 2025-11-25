<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class WalletMovement extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'wallet_movements';

    protected $fillable = [
        'wallet_id',
        'type_id',
        'amount',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    public function movementType()
    {
        return $this->belongsTo(WalletMovementType::class, 'type_id');
    }
}
