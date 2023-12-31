<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'product_name',
        'category_id',
        'unit_id',
        'product_code',
        'stock',
        'buying_price',
        'selling_price',
        'product_image',
        'color',
        'size',
    ];

    public $sortable = [
        'product_name',
        'category_id',
        'unit_id',
        'product_code',
        'stock',
        'buying_price',
        'selling_price',
        'color',
        'size',
    ];

    protected $guarded = [
        'id',
    ];

    protected $with = [
        'category',
        'unit'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function unit(){
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('product_name', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('unit', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        });
    }

    // order details 
    public function orders()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
