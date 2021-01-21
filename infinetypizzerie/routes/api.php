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

/* Orders */
Route::post('createOrder', 'OrderController@create');
Route::get('getOrders/{id?}', 'OrderController@index');

/* User */
Route::get('usernamesAll', 'UserController@getUsernames');
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
