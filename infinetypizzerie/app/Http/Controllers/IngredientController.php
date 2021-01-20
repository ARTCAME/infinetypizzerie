<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    /**
     * Create a new ingredient on the db
     */
    public function create(Request $request)
    {
        try {
            /* Validate the data */
            $v = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required',
            ]);
            if ($v->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 422);
            }
            $ingredient = new Ingredient();
            $ingredient->ingredient_name = $request->name;
            $ingredient->price = $request->price;
            $ingredient->save();
            return response()->json($ingredient);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el ingrediente en la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an ingredient on the db
     */
    public function delete(Request $request)
    {
        try {
            Ingredient::destroy($request->id);
            return response()->json($request->id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se ha podido borrar el ingrediente: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all the existing ingredients on the ingredients table
     */
    public function index()
    {
        try {
            $rawIngredients = DB::table('ingredients')
                ->select('id', 'ingredient_name', 'price')
                ->get();
            return $rawIngredients;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los ingredients de la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an ingredient
     */
    public function update(Request $request)
    {
        try {
            $currId = $request->currData['id'];
            $ingredient = Ingredient::where('id', $currId)->first();
            $ingredient->ingredient_name = $request->newData['name'];
            $ingredient->price = $request->newData['price'];
            $ingredient->save();
            return response()->json($ingredient);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el ingredients en la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
