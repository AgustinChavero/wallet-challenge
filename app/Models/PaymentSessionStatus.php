<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSessionStatus extends Model
{
    use HasFactory;

    protected $table = 'payment_session_statuses';

    protected $fillable = [
        'code',
    ];

    public function sessions()
    {
        return $this->hasMany(PaymentSession::class, 'status_id');
    }
}
