<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Domain;

class DomainItem extends Model
{
    use HasFactory;

    public function domain() {
        return $this->belongsTo(Domain::class, 'domain_id');
    }

    public function owner() {
        return $this->belongsTo(DomainsItemsOwner::class);
    }
}
