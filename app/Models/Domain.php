<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\DomainItem;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'domain_name',
        'no_of_items',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(DomainItem::class);
    }
}
