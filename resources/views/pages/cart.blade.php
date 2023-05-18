@extends('layouts.app')

@section('header', 'Transaction')

@section('content')
<div id="controller">
  <div v-if="errorAdd" class="alert bg-danger alert-dismissible fade show" role="alert">
    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
    <div v-for="error in addErrors">
      <span>@{{ error.toString() }}</span> <br>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="errorAddClose()">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <div class="form-group row">
            <label for="date" class="col-md-4 col-form-label">Date</label>
            <div class="col-md-8">
              <input type="text" value="{{ $today }}" class="form-control" id="date" disabled>
            </div>
          </div>
          <div class="form-group row">
            <label for="customer_id" class="col-md-4 col-form-label">Customer</label>
            <div class="col-md-8">
              <select name="customer_id" id="customer_id" v-model="customer_id" @change="getCustomerID($event)" class="form-control" style="width: 100%;" required>
                <option value="">Umum</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->customer_code }} - {{ $customer->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <form :action="actionUrl" method="post" @submit.prevent="submitAdd($event)">
        @csrf
        <div class="card">
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-12">
                <input type="text" name="product_code" id="product_code" class="form-control" v-model="code" placeholder="Enter valid product code" required>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-7">
                <input type="text" name="product" id="product" class="form-control" v-model="product.name" placeholder="Product name" disabled>
              </div>
              <div class="col-md-3">
                <input type="number" min="1" id="qty" name="qty" class="form-control" placeholder="Qty" required>
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-block btn-primary">Add</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body text-right mb-1">
          <h3>No. @{{ invoice }}</h3>
          <h1 class="text-success">Rp. @{{ formatRupiah(total) }}</h1>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body table-responsive">
      <table id="datatable" class="table table-sm table-striped table-bordered">
        <thead>
          <tr>
            <th style="width: 20px">No</th>
            <th>Code</th>
            <th>Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <form method="post" @submit.prevent="submitProcess($event)">
    @csrf
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <div class="form-group row">
              <label for="total" class="col-md-4 col-form-label">Grand Total</label>
              <div class="col-md-8">
                <input type="number" id="total" name="total" class="form-control" v-model="total" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="cash" class="col-md-4 col-form-label">Cash</label>
              <div class="col-md-8">
                <input type="number" :min="total" id="cash" name="cash" class="form-control" v-model="cash" placeholder="0" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="change" class="col-md-4 col-form-label">Change</label>
              <div class="col-md-8">
                <input type="number" id="change" name="change" class="form-control" v-model="change" placeholder="0" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <input type="hidden" :value="customer_id" name="customer_id">
        <input type="hidden" :value="invoice" name="invoice">
        <button type="submit" class="btn btn-block btn-primary" :disabled="datas.length === 0">Process</button>
        <button type="button" @click="cancel()" class="btn btn-block btn-danger" :disabled="datas.length === 0">Cancel</button>  
      </div>
    </div>
  </form>

  <!-- Modal -->
  <div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
      <form :action="actionUrl" method="post" @submit.prevent="submitEdit($event, data.id)"> 
        @csrf
        @method('PUT')
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Qty</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div v-if="errorEdit" class="alert bg-danger alert-dismissible fade show" role="alert">
              <h5><i class="icon fas fa-ban"></i> Alert!</h5>
              <div v-for="error in editErrors">
                <span>@{{ error.toString() }}</span> <br>
              </div>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close" @click="errorEditClose()">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="form-group">
              <label for="qty">Qty</label>
              <input type="number" class="form-control" min="1" :value="data.qty" name="qty" id="qty" placeholder="Enter qty" autocomplete="off" required>
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

  var actionUrl = '{{ url('carts') }}';
  var apiUrl = '{{ url('api/carts') }}';

  var columns = [
    {data: 'DT_RowIndex'},
    {data: 'code'},
    {data: 'name'},
    {data: 'price'},
    {data: 'qty'},
    {data: 'total'},
    {render: function(index, row, data, meta) { 
        return `<a href="#" class="btn btn-warning btn-sm" onclick="controller.editData(event, ${meta.row})">Edit Qty</a> 
        <a class="btn btn-danger btn-sm" onclick="controller.deleteData(event, ${data.id})">Delete</a> `;
      }, orderable: false, width: '200px' },
  ];

  var controller = new Vue({
    el: "#controller",
    data: {
      datas: [],
      data: {},
      actionUrl,
      apiUrl,
      errorAdd: false,
      errorEdit: false,
      addErrors: [],
      editErrors: [],
      invoice: '',
      total: '',
      product: [],
      code: '',
      cash: '',
      change: '',
      customer_id: '',
    },
    mounted: function () {
      this.datatable();
      this.invoiceGenerator();
    },
    watch: {
      datas: function(val, oldVal) {
        this.getTotal();
      },
      code: function(val, oldVal) {
        this.getProduct();
      },
      cash: function(val, oldVal) {
        this.getChange();
      }
    },
    methods: {
      getCustomerID(event) {
        this.customer_id = event.target.value;
      },
      getProduct() {
        const _this = this;
        axios.get('/getProduct/'+_this.code)
          .then(function (response) {
          _this.product = response.data;
        })
        .catch(function (error) {
          this.errorStatus = true;
          this.errors = error.response.data.errors;
        });
      },
      datatable() {
        const _this = this;
        _this.table = $("#datatable")
        .DataTable({
          ajax: {
                  url: _this.apiUrl,
                  type: "GET",
                },
          columns,
          })
          .on("xhr", function () {
            _this.datas = _this.table.ajax.json().data;
          });
      },
      invoiceGenerator() {
        const _this = this;
        axios.get('/invoice')
          .then(function (response) {
            _this.invoice = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      getTotal() {
        const _this = this;
        axios.get('/total')
          .then(function (response) {
             _this.total = response.data;
          })
          .catch(function (error) {
            console.log(error);
          });
      },
      submitAdd(event) {
        const _this = this;
        axios.post(this.actionUrl, new FormData($(event.target)[0])).then((response) => {
          _this.table.ajax.reload();
        })
        .catch((error) => {
          this.errorAdd = true;
          this.addErrors = error.response.data.errors;
        });
      },
      editData(event, row) {
        this.data = this.datas[row];
        this.errorAdd = false;
        this.errorEdit = false;
        $("#modal-default").modal();
      },
      submitEdit(event, id) {
        const _this = this;
        axios.post(this.actionUrl + "/" + id, new FormData($(event.target)[0])).then((response) => {
          $("#modal-default").modal("hide");
          _this.table.ajax.reload();
        })
        .catch((error) => {
          this.errorEdit = true;
          this.editErrors = error.response.data.errors;
        });
      },
      deleteData(event, id) {
        if (confirm("Are you sure to delete?")) {
          $(event.target).parents("tr").remove();
          axios.post(this.actionUrl + "/" + id, { _method: "DELETE" }).then((response) => {
            this.table.ajax.reload();
          });
        }
      },
      errorAddClose() {
        this.errorAdd = !this.errorAdd;
      },
      errorEditClose() {
        this.errorEdit = !this.errorEdit;
      },
      getChange() {
        this.change = this.cash - this.total;
      },
      submitProcess(event) {
        if (confirm("Are you sure to process?")) {
          axios.post('/transactions', new FormData($(event.target)[0])).then((response) => {
            window.open('/print' + "/" + response.data, '_blank');
            window.location.reload();
          });
        }
      },
      cancel() {
        if (confirm("Are you sure to cancel?")) {
          axios.post('/cancel').then((response) => {
            window.location.reload();
          });
        }
      },
      formatRupiah(x){
        return Number(x).toLocaleString("id-ID");
      },
    },
  });
</script>
@endsection
