<?php

namespace App\Http\Controllers;

use stdClass;
use Exception;
use App\Models\Order;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Create and store an order to the db and send a confirmation email to the user
     *
     * @param Request {user, userId, order, subtotal}
     */
    public function create(Request $request) {
        try {
            $order = new Order();
            $order['price'] = $request->subtotal;
            $order['user_id'] = $request->userId;
            $order['order'] = serialize($request->order); /* Serialize the array of pizzas of an order */
            $order->save();
            /* Create and send a confirmation email */
            $mailOrder = new stdClass();
            $mailOrder->subtotal = $request->subtotal;
            $mailOrder->user = $request->user;
            $mailOrder->order = $request->order;
            Mail::to("corcko@gmail.com")->send(new OrderMail($mailOrder));
            return $order;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al registrar el pedido: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get orders from the db. If the id is provided return only the orders of this user.
     *
     * @param Number $id (user id)
     */
    public function index($id = null)
    {
        try {
            if (isset($id)) {
                $rawOrders = DB::table('orders')
                                ->where('user_id', $id)
                                ->get();
            } else {
                $rawOrders = DB::table('orders')->get();
            }
            $orders = [];
            /* Convert to array the order column */
            foreach ($rawOrders as $ro) {
               $ro->order = unserialize($ro->order);
               array_push($orders, $ro);
            }
            return $orders;
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los pedidos de la base de datos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
