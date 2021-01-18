<?php

namespace App\Http\Controllers;

use App\Models\Pizza;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PizzaController extends Controller
{
    //
    public function xx() {
        return response()->json('xx');
    }
    public function create(Request $request)
    {
        return response()->json('xxoxo');
        return response()->json($request->all());
        $v = Validator::make($request->all(), [
            'name' => 'requrired|min:3',
        ]);
        if ($v->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }
        $pizza = new Pizza($request->all());
        $pizza->save();
        return response()->json($pizza);
    }
}
