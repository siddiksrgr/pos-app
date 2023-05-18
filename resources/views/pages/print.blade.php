<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Success - {{ $transaction->invoice }}</title>
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>
<body onload="window.print()">

  <div class="row">
    <div class="col-md-12 text-center">
      <h5>{{ $store->name }}</h5>
      <span>{{ $store->address }} - {{ $store->phone_number }}</span>
    </div>
  </div>
  <hr>

  <div class="row">
    <div class="col-md-4">
      <p>Date: {{ $transaction->created_at }}</p>
    </div>
    <div class="col-md-4 text-center">
      <p>Customer: {{ $transaction->customer_id == null ? 'Umum' : $transaction->customer->name }}</p>
    </div>
    <div class="col-md-4 text-right">
      <p>Number: {{ $transaction->invoice }}</p>
    </div>
  </div>

  <table class="table table-sm table-striped table-bordered">
    <tr>
      <th>Product</th>
      <th>Qty</th>
      <th>Price</th>
      <th>Total</th>
    </tr>
    @foreach($transaction->transaction_details as $detail)
    <tr>
      <td>{{ $detail->product->name }}</td>
      <td>{{ $detail->qty }}</td>
      <td>{{ convert_rupiah($detail->product->sell_price) }}</td>
      <td>{{ convert_rupiah($detail->product->sell_price * $detail->qty)  }}</td>
    </tr>
    @endforeach
  </table>

  <div class="card p-0">
    <span>Grand Total: {{ convert_rupiah($transaction->total) }}</span>
    <span>Cash: {{ convert_rupiah($transaction->cash) }}</span>
    <span>Change: {{ convert_rupiah($transaction->change) }}</span>
  </div>

</body>
<script>
  setTimeout(function(){
    self.close();
  },10000);
</script>
</html>