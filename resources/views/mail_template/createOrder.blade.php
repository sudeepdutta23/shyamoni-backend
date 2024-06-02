<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Order</title>
    <style>

        td, th {
            border: 1px solid rgb(24, 14, 14);
            padding: 5px 11px 5px 11px;
        }

        table {
            border-collapse:collapse;
            text-align: center;
        }

    </style>
</head>
<body>

    <h4>Your Order Created Successfully containing order number {{ $shyamoniOrderID }}</h4>
    <h4>Reciept No : {{ $orderDetails->receiptId }}</h4>
    <h4>Order Details</h4>

    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
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
                <td colspan="3">Sub Total : {{ $totalPrice }} </td>
            </tr>

        </tbody>
    </table>

    <br>


    <label for="">Cheers</label><br>
    <label for="">Shyamoni’s Customer Service Team</label><br>
    <label for="">Email: careshyamoni@gmail.com</label><br>
    <label for="">Mobile: +919387687985</label><br>
    <label for="">WhatsApp: +919387687985</label>



</body>
</html>

