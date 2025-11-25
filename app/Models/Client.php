<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Client extends Model
{
    use HasFactory, SoftDeletes, UsesUuid;

    protected $table = 'clients';

    protected $fillable = [
        'document',
        'names',
        'email',
        'phone',
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'client_id');
    }
}
