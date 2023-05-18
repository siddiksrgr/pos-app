var controller = new Vue({
    el: "#controller",
    data: {
        datas: [],
        data: {},
        apiUrl,
    },
    mounted: function () {
        this.datatable();
    },
    methods: {
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
        detailData(event, row) {
            this.data = this.datas[row];
            $("#modal-lg").modal();
        },
        formatRupiah(x) {
            return Number(x).toLocaleString("id-ID");
        },
    },
});
