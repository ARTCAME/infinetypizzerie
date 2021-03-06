<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Pizza;
use Illuminate\Http\Request;
use App\Models\PizzaIngredient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PizzaController extends Controller
{
    /**
     * Create a new pizza on the db
     */
    public function create(Request $request)
    {
        try {
            /* Validate the data */
            $v = Validator::make($request->all(), [
                'name' => 'required',
                'price' => 'required',
                'ingredients' => 'required'
            ]);
            if ($v->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $v->errors()
                ], 422);
            }
            $pizza = new Pizza();
            $pizza->name = $request->name;
            $pizza->price = $request->price;
            /* Store the image locally and save its path on the db */
            $pizza->image = isset($pizza->image) ? Storage::disk('public')->put('pizza_images', $request->file('image')) : null;
            $pizza->save();
            /* The ingredients comes into json object, decode they */
            $request->ingredients = json_decode($request->ingredients);
            /* Generate the ingredient dependencies */
            foreach ($request->ingredients as $key => $val) {
                $pizzaIg = new PizzaIngredient();
                $pizzaIg['pizza_id'] = $pizza->id;
                $pizzaIg['ingredient_id'] = $val;
                $pizzaIg->save();
            }
            return response()->json([$pizza]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al registrar la pizza en la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete one pizza on the db
     */
    public function delete(Request $request)
    {
        try {
            Pizza::destroy($request->id);
            return response()->json($request->id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se ha podido borrar el ingrediente: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete ingredient dependencies of a pizza on the table pizza_ingredients
     */
    public function deletePizzaIngredient($id) {
        try {
            PizzaIngredient::destroy($id);
            return response()->json($id);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se ha podido borrar el ingrediente: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get ingrediente dependencies of a pizza
     */
    public function getIngredients($id)
    {
        $ig = PizzaIngredient::where('pizza_id', $id)->get();
        return $ig;
    }

    /**
     * Get all the pizzas from the db
     */
    public function index()
    {
        try {
            $rawPizzas = DB::table('pizzas')
                ->select('id', 'name', 'price', 'image')
                ->get();
            return $rawPizzas;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las pizzas de la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update one pizza on the db
     *
     * TODO - On every update the pizza_ingredient data is being deleted/added and must be deleted when a dependency is deleted or added if a new dependency is received
     */
    public function update(Request $request)
    {
        try {
            /* The ingredients comes into json object, decode they */
            $request->ingredients = json_decode($request->ingredients);
            $currId = $request->id;
            $pizza = Pizza::where('id', $currId)->first();
            $pizza->name = $request->name;
            $pizza->price = $request->price;
            /* Store the image locally and save its path on the db */
            $pizza->image = isset($pizza->image) ? Storage::disk('public')->put('pizza_images', $request->file('image')) : null;
            $pizza->save();
            $currIngredients = $this->getIngredients($currId);
            /* Delete the current dependencies */
            /* Renew the records (its ids) on every edit of a pizza!! ಠ_ಠ but it works (for now), working for a best way to compare the news with the olds ingredients */
            foreach ($currIngredients as $ingredient) {
                $this->deletePizzaIngredient($ingredient['id']);
            }
            /* Add new dependencies */
            foreach ($request->ingredients as $ingredient) {
                $pizzaIg = new PizzaIngredient();
                $pizzaIg['pizza_id'] = $currId;
                $pizzaIg['ingredient_id'] = $ingredient->id;
                $pizzaIg->save();
            }
            return $pizza;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la pizza en la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
