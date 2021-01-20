<?php

namespace App\Http\Controllers;

use stdClass;
use Exception;
use App\Models\Order;
use App\Mail\OrderMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    //
    public function create(Request $request) {
        try {
            $order = new Order();
            $order['price'] = $request->subtotal;
            $order['user_id'] = $request->user;
            $order['order'] = serialize($request->order); /* Serialize the array of pizzas of an order */
            // $order->save();
            // Mail::to("corcko@gmail.com")->send(new OrderMail($request->order, $request->subtotal));
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
}
