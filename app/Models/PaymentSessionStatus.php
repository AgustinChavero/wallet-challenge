<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class PaymentSessionStatus extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'payment_session_statuses';

    protected $fillable = [
        'code',
    ];

    public function sessions()
    {
        return $this->hasMany(PaymentSession::class, 'status_id');
    }
}
