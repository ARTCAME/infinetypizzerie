<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Pizza;
use Illuminate\Http\Request;
use App\Models\PizzaIngredient;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PizzaController extends Controller
{
    //
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
            $pizza->save();
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

    public function getIngredients($id)
    {
        $ig = PizzaIngredient::where('pizza_id', $id)->get();
        // return response()->json($ig);
        // return response()->json([$ig]);
        return $ig;
    }

    public function index()
    {
        try {
            $rawPizzas = DB::table('pizzas')
                ->select('id', 'name', 'price')
                ->get();
            return $rawPizzas;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las pizzas de la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $currId = $request->currData['id'];
            $pizza = Pizza::where('id', $currId)->first();
            $pizza->name = $request->newData['name'];
            $pizza->price = $request->newData['price'];
            $pizza->save();
            $currIngredients = $this->getIngredients($currId);
            /* Delete the current dependencies */
            /* Renew the records (its ids) on every edit of a pizza!! ಠ_ಠ but it works (for now), working for a best way to compare the news with the olds ingredients */
            foreach($currIngredients as $ingredient) {
                $this->deletePizzaIngredient($ingredient['id']);
            }
            /* Add new dependencies */
            foreach ($request->newData['ingredients'] as $ingredient) {
                $pizzaIg = new PizzaIngredient();
                $pizzaIg['pizza_id'] = $currId;
                $pizzaIg['ingredient_id'] = $ingredient['id'];
                $pizzaIg->save();
            }
            return $pizza;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el ingredients en la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
