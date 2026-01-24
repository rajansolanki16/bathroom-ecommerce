<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'method',
        'status',
        'mail_status',
        'transaction_id', 
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'int';
}
