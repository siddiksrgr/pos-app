@extends('layouts.app')

@section('header', 'Categories')

@section('content')
<div id="controller">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <a href="#" @click="addData()" data-toggle="modal" data-target="#modal-default" class="btn btn-sm btn-primary pull-right">Create New Category</a>
        </div>
        <div class="card-body table-responsive">
          <table id="datatable" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Name</th>
                <th>Created At</th>
                <th>Total Products</th>
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
            <h4 class="modal-title" v-if="editStatus == false">Create New Category</h4>
            <h4 class="modal-title" v-if="editStatus">Edit Category</h4>
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
              <label for="name">Name</label>
              <input type="text" class="form-control" :value="data.name" name="name" id="name" placeholder="Enter name" autocomplete="off" required>
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
  var actionUrl = '{{ url('categories') }}';
  var apiUrl = '{{ url('api/categories') }}';

  var columns = [
    {data: 'DT_RowIndex'},
    {data: 'name'},
    {data: 'date'},
    {data: 'total_products'},
    {render: function(index, row, data, meta) { 
        return `<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">Edit</a> 
        <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">Delete</a> `;
      }, orderable: false, width: '200px' },
  ];
</script>
<script src="{{ asset('js/crud.js') }}"></script>
@endsection

