@extends('layouts.app')

@section('title', 'Store')

@section('header', 'Store')

@section('content')
<div id="controller">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Store Detail</h3>
        </div>
        <div class="card-body">
          <p class="text-bold">Name: <span class="font-weight-normal">@{{ data.name }}</span></p>
          <p class="text-bold">Phone Number: <span class="font-weight-normal">@{{ data.phone_number }}</span></p>
          <p class="text-bold">Address: <span class="font-weight-normal">@{{ data.address }}</span></p>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">Edit</button>
        </div>
    </div>
  </div>
  <div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <form :action="actionUrl" method="post" @submit.prevent="submitForm($event, data.id)"> 
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Store Detail</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="errorStatus" class="alert bg-danger alert-dismissible fade show" role="alert">
              <span>Errors :</span>
              <div v-for="error in errors">
                <span>- @{{ error.toString() }}</span> <br>
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="alertHide()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" :value="data.name" name="name" id="name" placeholder="Enter name" required>
            </div>
            <div class="form-group">
              <label for="phone_number">Phone Number</label>
              <input type="number" class="form-control" :value="data.phone_number" name="phone_number" id="phone_number" placeholder="Enter phone_number" required>
            </div>
            <div class="form-group">
              <label for="address">Address</label>
              <textarea type="text" class="form-control" :value="data.address" name="address" id="address" placeholder="Enter address" autocomplete="off" required></textarea>
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
  var actionUrl = '{{ url('store') }}';
  var apiUrl = '{{ url('api/store') }}';

  var controller = new Vue({
    el: "#controller",
    data: {
      data: {},
      actionUrl,
      apiUrl,
      errors: [],
      errorStatus: false,
    },
    mounted: function () {
      this.get_data();
    },
    methods: {
      get_data(){
        const _this = this;
        $.ajax({
          url: apiUrl,
          method: 'GET',
          success: function (data) {
            _this.data = JSON.parse(data);
          },
          error: function (error) {
            console.log(error);
          }
        });
      },
      submitForm(event, id) {
        event.preventDefault();
        const _this = this;
        axios.post(this.actionUrl + "/" + id, new FormData($(event.target)[0]))
        .then((response) => {
            $("#modal-default").modal("hide");
            _this.get_data();
        })
        .catch((error) => {
          this.errorStatus = true;
          this.errors = error.response.data.errors;
        });
      },
    }
});
</script>
@endsection

