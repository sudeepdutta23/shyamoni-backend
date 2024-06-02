<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cancel Order</title>
</head>
<body>
    <h5>
        Your Order Cancelled containing Order number {{ $shyamoniOrderID }} <br>
        Reciept No : {{ $orderDetails->receiptId }} <br>
        Payment Will Be Refunded within Seven Days
    </h5>

    <h5>Product Details</h5>

    <table class="table">
        <thead>
            <tr>
                <th>ProductName</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($getPriceDetails as $item)
                <tr>
                    <td>
                        {{ $item->productName  }}
                    </td>
                    <td>
                        {{ $item->pieces  }}
                    </td>
                    <td>
                        {{ $totalPrice  }}
                    </td>
                </tr>
            @endforeach
            <tr>

                <span>Sub Total : {{ $totalPrice }} </span>
            </tr>

        </tbody>
    </table>
</body>
</html>
