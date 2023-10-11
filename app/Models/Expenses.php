<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Expenses extends Model
{
    use HasFactory, Sortable;

    protected $guarded = ['id'];

    public $sortable = [
        'expenses_id',
        'expenses_date',
        'name',
        'amount',
    ];

    // protected $with = [
    //     'recipient_name',
    //     'name',
    //     'category',
    // ];

    // public function supplier(){
    //     return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    // }

    // public function user_created(){
    //     return $this->belongsTo(User::class, 'created_by', 'id');
    // }

    // public function user_updated(){
    //     return $this->belongsTo(User::class, 'updated_by', 'id');
    // }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('expenses_date', 'like', '%' . $search . '%');
            });
        });
    }
}
