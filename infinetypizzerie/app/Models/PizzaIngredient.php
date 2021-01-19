<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaIngredient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pizza_id',
        'ingredient_id',
    ];

    /**
     * Get the ingredients of a pizza
     */
    public function ingredient()
    {
        return $this->hasMany(Ingredient::class);
    }
    /**
     * Get the pizzas
     */
    public function pizza()
    {
        return $this->hasMany(Pizza::class);
    }
}
