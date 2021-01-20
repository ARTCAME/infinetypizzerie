<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IngredientController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Ingredients */
Route::post('createIngredient', 'IngredientController@create');
Route::post('deleteIngredient', 'IngredientController@delete');
Route::post('updateIngredient', 'IngredientController@update');
Route::get('getIngredients', 'IngredientController@index');

/* Pizzas */
Route::post('createPizza', 'PizzaController@create');
Route::post('deletePizza', 'PizzaController@delete');
Route::post('updatePizza', 'PizzaController@update');
Route::get('getPizzas', 'PizzaController@index');
Route::get('getPizzaIngredients/{id}', 'PizzaController@getIngredients');

/* Order */
Route::post('createOrder', 'OrderController@create');

/* User */
Route::get('usernamesAll', 'UserController@getUsernames');
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

// Route::post('/login', function (Request $request) {
//     /* Validate the data */
//     $v = Validator::make($request->all(), [
//         'email' => 'required|email',
//         'password' => 'required'
//     ]);
//     if ($v->fails()) {
//         return response()->json([
//             'status' => 'error',
//             'errors' => $v->errors()
//         ], 422);
//     }
//     /* Get the user */
//     $user = User::where('email', $request->email)->first();
//     /* Confirm if the user exists and its credentials are correct */
//     if (!$user || !Hash::check($request->password, $user->password)) {
//         return response([
//             'message' => ['No hay ningún usuario registrado con los datos introducidos']
//         ], 401);
//     }
//     /* If everything is correct store a token */
//     $token = $user->createToken('my-app-token')->plainTextToken;
//     /* Confirm the successful login */
//     return response([
//         'user' => $user,
//         'token' => $token
//     ], 201);
// });

// Route::middleware('auth:api')->get('/user', function (Request $request) {
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
