@extends('layouts.app')

@section('header', 'Products')

@section('content')
<div id="controller">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <a href="#" @click="addData()" data-toggle="modal" data-target="#modal-default" class="btn btn-sm btn-primary pull-right">Create New Product</a>
        </div>
        <div class="card-body table-responsive">
          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Category</th>
                <th>Product Code</th>
                <th>Name</th>
                <th>Buy Price</th>
                <th>Sell Price</th>
                <th>Stock</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <form :action="actionUrl" method="post" @submit.prevent="submitForm($event, data.id)"> 
        @csrf
        <input type="hidden" name="_method" value="PUT" v-if="editStatus">

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-if="editStatus == false">Create New Product</h4>
            <h4 class="modal-title" v-if="editStatus">Edit Product</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="errorStatus" class="alert bg-danger alert-dismissible fade show" role="alert">
              <h5><i class="icon fas fa-ban"></i> Alert!</h5>
              <div v-for="error in errors">
                <span>@{{ error.toString() }}</span> <br>
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="alertHide()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="form-group">
              <label for="category_id">Category</label>
              <select name="category_id" id="category_id" class="form-control">
                @foreach($categories as $category)
                <option :selected="data.category_id == {{ $category->id  }}" value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="product_code">Product Code</label>
              <input type="text" class="form-control" :value="data.product_code" name="product_code" id="product_code" placeholder="Enter product code" required>
            </div>
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" :value="data.name" name="name" id="name" placeholder="Enter name" autocomplete="off" required>
            </div>
            <div class="form-group">
              <label for="buy_price">Buy Price</label>
              <input type="number" class="form-control" :value="data.buy_price" name="buy_price" id="buy_price" placeholder="Enter buy price" required>
            </div>
            <div class="form-group">
              <label for="sell_price">Sell Price</label>
              <input type="number" class="form-control" :value="data.sell_price" name="sell_price" id="sell_price" placeholder="Enter sell price" required>
            </div>
            <div class="form-group">
              <label for="stock">Stock</label>
              <input type="number" min="1" class="form-control" :value="data.stock" name="stock" id="stock" placeholder="Enter stock" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script type="text/javascript">
  var actionUrl = '{{ url('products') }}';
  var apiUrl = '{{ url('api/products') }}';

  var columns = [
    {data: 'DT_RowIndex'},
    {data: 'category'},
    {data: 'product_code'},
    {data: 'name'},
    {data: 'formatted_buy_price'},
    {data: 'formatted_sell_price'},
    {data: 'stock'},
    {render: function(index, row, data, meta) { 
        return `<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">Edit</a> 
        <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">Delete</a> `;
      }, orderable: false, width: '200px' },
  ];
</script>
<script src="{{ asset('js/crud.js') }}"></script>
@endsection

