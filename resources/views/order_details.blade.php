<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PetShop</title>
</head>
<body>
<div class="col-md-12">
    <div class="col-md-12" style="float: left">
        Pet Shop
    </div>

    <div class="col-md-12" style="float: right">
        Date: {{$order->created_at}}
        <br>
        Invoice #: {{$order->uuid}}
    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="col-md-12">
        <div class="col-md-6" style="font-size: 12px;">
            Customer Details
            <div class="col-md-12" style="border: medium black solid;">
                Name: {{$order->user->first_name}}<br>
                Lastname: {{$order->user->last_name}}<br>
                ID: {{$order->user->uuid}}<br>
                Phone number: {{$order->user->phone_number}}<br>
                Email: {{$order->user->email}}<br>
                Address: {{$order->user->address}}<br>
            </div>
        </div>
        <br>
        <div class="col-md-6" style="font-size: 12px;">
            Billing/Shipping Details

            <div class="col-md-12" style="border: medium black solid;">
                Billing:  {{$order->address['billing']}}<br>
                Shipping:  {{$order->address['shipping']}}<br>
                <br>
                @isset($order->payment_id)
                    Payment Method: {{strtoupper($order->payment['type'])}}<br>
                    @foreach($order->payment['details'] as $detail)
                        {{$detail}}<br>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="col-md-12">
        Items:
        <table class="table table-bordered" style="font-size: 12px; border: black solid">
            <thead style="border: 1px solid;">
            <tr style="border: 1px solid;">
                <td><b>#</b></td>
                <td><b>ID</b></td>
                <td><b>Item Name</b></td>
                <td><b>Quantity</b></td>
                <td><b>Unit Price</b></td>
                <td><b>Price</b></td>
            </tr>
            </thead>
            <tbody style="border: 1px solid;">
            @foreach($order->order_products as $product)
            <tr style="border: 1px solid;">
                <td>
                    {{$product['id']}}
                </td>
                <td>
                    {{$product['uuid']}}
                </td>
                <td>
                    {{$product['product']}}
                </td>
                <td>
                    {{$product['quantity']}}
                </td>
                <td>
                    {{$product['price']}}
                </td>
                <td>
                    {{$product['quantity']*$product['price']}}
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-6" style="float: right">
        Total:
        <table>
            <tbody style="border: black solid 1px">
            <tr style="border: black solid 1px">
                <td>Subtotal</td>
                <td>$ {{$order['amount']}}</td>
            </tr>
            <tr style="border: black solid 1px">
                <td>Delivery Fee</td>
                <td>$ {{$order['delivery_fee']}}</td>
            </tr>
            <tr style="border: black solid 1px">
                <td>Total</td>
                <td>$ {{$order['amount'] + $order['delivery_fee']}}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
