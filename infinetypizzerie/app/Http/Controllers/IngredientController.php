<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    //
    public function create(Request $request)
    {
        $ingredient = new Ingredient();
        $ingredient->ingredient_name = $request->name;
        $ingredient->save();
        return response()->json([$ingredient]);
    }


    public function delete($id)
    {
        Ingredient::destroy($id);
        return response();
    }
}
