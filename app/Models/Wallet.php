<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Wallet extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'wallets';

    protected $fillable = [
        'client_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function movements()
    {
        return $this->hasMany(WalletMovement::class);
    }
}
