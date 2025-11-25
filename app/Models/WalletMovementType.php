<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class WalletMovementType extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'wallet_movement_types';

    protected $fillable = [
        'code',
        'description',
    ];

    public function movements()
    {
        return $this->hasMany(WalletMovement::class, 'type_id');
    }
}
