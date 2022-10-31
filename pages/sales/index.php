<?php
$Sales = new Sales;
?>
<style>
    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid #dce7f1 !important;
        border-radius: 4px !important;
        height: 39px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #909ba6 !important;
        line-height: 36px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #909ba6 transparent transparent transparent !important;
        margin-top: 3px !important;

    }
</style>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Sales</h3>
                <p class="text-subtitle text-muted">Manage sales here</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./homepage">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="btn-group divider divider-right">
                    <div style="float: right">
                        <a href="#" class="btn btn-primary btn-sm btn-icon-split" onclick="addModal()">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Add Entry</span>
                        </a>
                        <a href="#" class="btn btn-danger btn-sm btn-icon-split" onclick='deleteEntry()' id='btn_delete'>
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text">Delete Entry</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="display expandable-table" id="dt_entries" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type='checkbox' onchange="checkAll(this, 'dt_id')"></th>
                            <th></th>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            <th>Date Modified</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?php require_once 'modal_sales.php'; ?>
<?php require_once 'modal_print.php'; ?>
<script type="text/javascript">
    function printRecord(id) {
        $("#tb_id").html("");
        $("#modalPrint").modal('show');
        $.ajax({
            type: 'POST',
            url: "controllers/sql.php?c=" + route_settings.class_name + "&q=getSalesHeader",
            data: {
                id: id
            },
            success: function(data) {
                var json = JSON.parse(data);
                // console.log(json.data[0].customer_name);
                $("#customer_name_span").html(json.data[0].customer_name);
                $("#reference_number_span").html(json.data[0].reference_number);
                $("#sales_date_span").html(json.data[0].sales_date_mod);
                getSalesDetails(json.data[0].sales_id);
            }
        });
    }

    function getSalesDetails(id) {

        $.ajax({
            type: 'POST',
            url: "controllers/sql.php?c=" + route_settings.class_name + "&q=getSalesDetails",
            data: {
                id: id
            },
            success: function(data) {
                var json = JSON.parse(data);
                var arr_count = json.data.length;
                var i = 0;
                while (i < arr_count) {
                    console.log(json.data[i]);
                    $("#tb_id").append('<tr>' +
                        '<td>' + json.data[i].product_name + '</td>' +
                        '<td>' + json.data[i].qty + '</td>' +
                        '<td>' + json.data[i].price + '</td>' +
                        '<td>' + json.data[i].qty * json.data[i].price + '</td>' +
                        '</tr>');
                    i++;
                }

            }
        });
    }


    function getEntries() {
        $("#dt_entries").DataTable().destroy();
        $("#dt_entries").DataTable({
            "processing": true,
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
                "dataSrc": "data"
            },
            "columns": [{
                    "mRender": function(data, type, row) {
                        return row.status == 'F' ? '' : "<input type='checkbox' value=" + row.sales_id + " class='dt_id' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        if (row.status == 'F') {
                            var display = "";
                        } else {
                            var display = "display: none;";
                        }

                        return '<div class="dropdown">' +
                            '<button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i>' +
                            '</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                            '<a class="dropdown-item" href="#" onclick="getEntryDetails2(' + row.sales_id + ')"><span class="bi bi-pencil-square"></span> Edit Record</a>' +
                            '<a class="dropdown-item" href="#" style="' + display + '" onclick="printRecord(' + row.sales_id + ')"><span class="fa fa-print"></span> Print Record</a>' +
                            '</div>';
                    }
                },
                {
                    "data": "sales_date"
                },
                {
                    "data": "reference_number"
                },
                {
                    "data": "customer_name"
                },
                {
                    "mRender": function(data, type, row) {
                        return row.status == 'F' ? "<strong style='color:#009688;'>Finished</strong>" : "<strong style='color:#795548;'>Saved</strong>";
                    }
                },
                {
                    "data": "date_added"
                },
                {
                    "data": "date_last_modified"
                }
            ]
        });
    }

    function getEntries2() {
        var params = "sales_id = '" + $("#hidden_id_2").val() + "'";
        $("#dt_entries_2").DataTable().destroy();
        $("#dt_entries_2").DataTable({
            "processing": true,
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show_detail",
                "dataSrc": "data",
                "method": "POST",
                "data": {
                    input: {
                        param: params
                    }
                }
            },
            "columnDefs": [{
                "targets": [2, 3, 4],
                "render": $.fn.dataTable.render.number(',', '.', 2, ''),
                "className": 'dt-body-right'
            }],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api();

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // Total over all pages
                total = api
                    .column(4)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(4, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(4).footer()).html(
                    "&#x20B1;" + pageTotal + ' ( &#x20B1; ' + total + ' )'
                );
            },
            "columns": [{
                    "mRender": function(data, type, row) {
                        return "<input type='checkbox' value=" + row.sales_detail_id + " class='dt_id_2' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "data": "product"
                },
                {
                    "data": "qty"
                },
                {
                    "data": "price"
                },
                {
                    "data": "amount"
                },
            ]
        });
    }

    function fetchProductsByCategory() {
        var product_category_id = $("#product_category_id").val();

    }

    function changeProduct() {
        var optionSelected = $("#product_id").find('option:selected').attr('product_price');
        $("#price").val(optionSelected);
    }

    $(document).ready(function() {

        getEntries();
        getSelectOption('Customers', 'customer_id', 'customer_name');
        getSelectOption('Products', 'product_id', 'product_name');

    });
</script>