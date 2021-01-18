<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
    ];

    /**
     * Get the pizzas of an order
     */
    public function pizza()
    {
        return $this->hasMany(Pizza::class);
    }

    /**
     * Get the user of an order
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
