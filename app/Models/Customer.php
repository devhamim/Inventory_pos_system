<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'photo',
        'city',
        'account',
        'state',
        'zip',
        'country',
        'comments',
        'company',
        'notes',
    ];

    protected $guarded = [
        'id',
    ];

    public $sortable = [
        'name',
        'email',
        'phone',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%')->orWhere('phone', 'like', '%' . $search . '%');
        });
    }
}
