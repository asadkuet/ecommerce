<?php

namespace App\Models;

use App\Models\Address;
use App\Models\User;
use App\Models\OrderStatus;
use App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'address_id',
        'order_status',
        'payment',
        'discounts',
        'total_products',
        'total',
        'tax',
        'total_paid',
        'invoice',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot([
                        'quantity',
                        'product_name',
                        'product_sku',
                        'product_description',
                        'product_price',
                        'product_attribute_id'
                    ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }
}
