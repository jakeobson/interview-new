<!DOCTYPE html>
<html lang="en">
<head>
    <title>Interview</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>

<div class="blog-header">
    <div class="container">
        <h1 class="blog-title">The Bootstrap Blog</h1>
        <p class="lead blog-description">An example blog template built with Bootstrap.</p>
    </div>
</div>

<div class="container">

    {{--<form>--}}


    <div class="form-group">
        <label for="exampleInputEmail1">Product name</label>
        <input type="text" name="name" class="form-control" placeholder="Product name" required>
    </div>

    <div class="form-group">
        <label for="exampleInputEmail1">Quantity in stock</label>
        <input type="text" name="quantity" class="form-control" placeholder="Quantity in stock" required>
    </div>


    <div class="form-group">
        <label for="exampleInputEmail1">Price per item</label>
        <input type="text" name="price" class="form-control" placeholder="Price per item" required>
    </div>
    <button onclick="submit(this); return false;" class="btn btn-default">Submit</button>
    {{--</form>--}}

    @if($products)

        <table class="table">
            <thead>
            <th>Product Name</th>
            <th>Quantity in Stock</th>
            <th>Price per item</th>
            <th>Datetime submitted</th>
            <th>Total value number</th>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->datetime }}</td>
                        <td>{{ ($product->quantity * $product->price) }}</td>
                    </tr>

                @endforeach

            </tbody>
            <tfoot>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td id="total">{{ $total }}</td>
            </tr>
            </tfoot>
        </table>
    @endif

</div><!-- /.container -->


</body>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script>
    function submit(e) {

        $.ajax({
            type: "POST",
            dataType: 'json',
            data: {
                name: $('input[name=name]').val(),
                quantity: $('input[name=quantity]').val(),
                price: $('input[name=price]').val(),
                _token: '<?php echo csrf_token(); ?>'
            },
            url: '/products',
            success: function (data) {
                console.log(data);

                var t = data.quantity * data.price;

                var total = parseFloat($('#total').text());
                total += t;

                $('#total').text(total);


                $('tbody').append('<tr><td>' + data.name + '</td><td>' + data.quantity + '</td><td>' + data.price + '</td><td>' + data.datetime + '</td><td>' + t + '</td></tr>');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
</script>

</html>

