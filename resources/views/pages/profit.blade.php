@extends('layouts.app')

@section('header', 'Profits')

@section('content')
<div id="controller">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <p class="card-title text-bold">Total Profit: 
            <span class="text-success">Rp. {{ convert_rupiah($totalProfit) }}</span>
          </p>
        </div>
        <div class="card-body table-responsive">
          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Invoice</th>
                <th>Date</th>
                <th>Profit</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="modal-lg" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Detail Profit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <div class="modal-body">
        <p>Invoice: @{{ data.invoice }}</p>
        <p>Total Profit: @{{ data.profit }}</p>
        <table class="table table-sm table-striped table-bordered">
          <thead>
            <tr>
              <th style="width: 10px">No</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Buy Price</th>
              <th>Sell Price</th>
              <th>Qty</th>
              <th>Profit</th>
            </tr>
          </thead>
          <tr v-for="(item, index) in data.transaction_details">
            <td>@{{ index+1 }}</td>
            <td>@{{ item.product.product_code }}</td>
            <td>@{{ item.product.name}}</td>
            <td>Rp. @{{ formatRupiah(item.buy_price) }}</td>
            <td>Rp. @{{ formatRupiah(item.sell_price) }}</td>
            <td>@{{ item.qty }}</td>
            <td>Rp. @{{ formatRupiah(item.profit) }}</td>
          </tr>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
  var apiUrl = '{{ url('api/profits') }}';

  var columns = [
    {data: 'DT_RowIndex'},
    {data: 'invoice'},
    {data: 'date'},
    {data: 'profit'},
    {render: function(index, row, data, meta) { 
        return `<a href="#" class="btn btn-info btn-sm" onclick="controller.detailData(event, ${meta.row})">Detail</a> `;
      }, orderable: false},
  ];
</script>
<script src="{{ asset('js/report.js') }}"></script>
@endsection

