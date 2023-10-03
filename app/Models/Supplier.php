<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Supplier extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'photo',
        'company',
        'account',
        'city',
        'state',
        'zip',
        'country',
        'comments',
        'notes',
    ];

    protected $guarded = [
        'id',
    ];

    public $sortable = [
        'name',
        'email',
        'company',
        'phone',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')->orWhere('company', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%')->orWhere('phone', 'like', '%' . $search . '%');
        });
    }
}
