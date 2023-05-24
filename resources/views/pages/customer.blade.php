@extends('layouts.app')

@section('title', 'Customers')

@section('header', 'Customers')

@section('content')
<div id="controller">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <a href="#" @click="addData()" data-toggle="modal" data-target="#modal-default" class="btn btn-sm btn-primary pull-right">Create New Customer</a>
        </div>
        <div class="card-body table-responsive">
          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Customer Code</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
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
            <h4 class="modal-title" v-if="editStatus == false">Create New Customer</h4>
            <h4 class="modal-title" v-if="editStatus">Edit Customer</h4>
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
              <label for="customer_code">Customer Code</label>
              <input type="text" class="form-control" :value="data.customer_code" name="customer_code" id="customer_code" placeholder="Enter customer code" required>
            </div>
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" :value="data.name" name="name" id="name" placeholder="Enter name" autocomplete="off" required>
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="number" class="form-control" :value="data.phone_number" name="phone_number" id="phone_number" placeholder="Enter phone_number" required>
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" class="form-control" :value="data.address" name="address" id="address" placeholder="Enter address" autocomplete="off" required>
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
  var actionUrl = '{{ url('customers') }}';
  var apiUrl = '{{ url('api/customers') }}';

  var columns = [
    {data: 'DT_RowIndex'},
    {data: 'customer_code'},
    {data: 'name'},
    {data: 'phone_number'},
    {data: 'address'},
    {render: function(index, row, data, meta) { 
        return `<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">Edit</a> 
        <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">Delete</a> `;
      }, orderable: false, width: '200px' },
  ];
</script>
<script src="{{ asset('js/crud.js') }}"></script>
@endsection

