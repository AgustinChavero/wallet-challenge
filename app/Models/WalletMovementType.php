<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletMovementType extends Model
{
    use HasFactory;

    protected $table = 'wallet_movement_types';

    protected $fillable = [
        'code',
        'description',
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class, 'type_id');
    }
}
