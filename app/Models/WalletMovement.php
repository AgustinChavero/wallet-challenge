<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletMovement extends Model
{
    use HasFactory;

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
        return $this->belongsTo(MovementType::class, 'type_id');
    }
}
