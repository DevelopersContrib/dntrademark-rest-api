<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainsItemsOwner extends Model
{
    use HasFactory;

    public function item()
    {
        return $this->hasOne(DomainItem::class, 'id');
    }
}
