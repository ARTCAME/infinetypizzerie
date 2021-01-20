<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    Gracias por tu pedido <i>{{ $order->user }}</i>,
    <hr>
    <div>
        <h4>Resumen de tu pedido:</h4>
        @foreach ($order->order as $pizza)
            <li>{{ $pizza['name'] . ' - ' . $pizza['price'] . '€'}}</li>
        @endforeach
        <p>
            <h6>Total: <b>{{ $order->subtotal }}€</b></h6>
        </p>
    </div>
    <hr>
    <h5>¡Hasta pronto!</h5>
</body>
</html>
