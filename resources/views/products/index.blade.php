<!DOCTYPE html>
<html lang="en">
<head>
    <title>Interview</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>

    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css"
          rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>
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

    <ul class="alert alert-danger" style="display: none;"></ul>


    <table class="table">
        <thead>
        <th>Id</th>
        <th>Product Name</th>
        <th>Quantity in Stock</th>
        <th>Price per item</th>
        <th>Datetime submitted</th>
        <th>Total value number</th>
        </thead>
        <tbody>
        @if($products)
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><a id="name" href="#" class="editable" data-type="text" data-pk="{{ $product->id }}"
                           data-url="/products/edit"
                           data-title="Enter name">{{ $product->name }}</a></td>
                    <td><a id="quantity" href="#" class="editable" data-type="text" data-pk="{{ $product->id }}"
                           data-url="/products/edit"
                           data-title="Enter quantity">{{ $product->quantity }}</a></td>
                    <td><a id="price" href="#" class="editable" data-type="text" data-pk="{{ $product->id }}"
                           data-url="/products/edit"
                           data-title="Enter price">{{ $product->price }}</a></td>
                    <td>{{ $product->datetime }}</td>
                    <td class="{{ $product->id }}_total">{{ ($product->quantity * $product->price) }}</td>
                </tr>

            @endforeach
        @endif($products)

        </tbody>
        <tfoot>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td id="total">{{ $total }}</td>
        </tr>
        </tfoot>
    </table>

</div><!-- /.container -->


</body>

<script>
    $(function () {
        $.fn.editable.defaults.mode = 'inline';
        $('.editable').editable({
            params: function (params) {
                params._token = '<?php echo csrf_token(); ?>';

                return params;
            },
            validate: function(value) {
                if($.trim(value) == '') {
                    return 'This field is required';
                }
            },
            success: function (response, newValue) {

                console.log('.' + response.id + '_total', response.total);

                $('.' + response.id + '_total').text(response.total);
                $('#total').text(response.total_all);
            }
        });
    });


    function submit(e) {

        $('ul').hide();

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


                $('tbody').append('<tr><td>' + data.id + '</td><td>' + data.name + '</td><td>' + data.quantity + '</td><td>' + data.price + '</td><td>' + data.datetime + '</td><td>' + t + '</td></tr>');
            },
            error: function (xhr, status, response) {
                var error = jQuery.parseJSON(xhr.responseText);  // this section is key player in getting the value of the errors from controller.
                var info = $('.edit_alert');
                $('ul').empty();
                for (var k in error.message) {
                    if (error.message.hasOwnProperty(k)) {
                        error.message[k].forEach(function (val) {
                            $('ul').append('<li>' + val + '</li>');
                        });

                    }
                }

                $('ul').show();


            }
        });
    }
</script>

</html>

