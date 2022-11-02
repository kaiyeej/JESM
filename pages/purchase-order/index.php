<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Purchase Order</h3>
                <p class="text-subtitle text-muted">Manage purchase order here</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./homepage">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Purchase Order</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
            <div class="row">
                <div class="col-sm-2">
                            <div class="list-group">
                                <a href="#" style="background:#3f51b5;text-align:center;color:#9fa8da;" class="list-group-item list-group-item-action" onclick="addModal()">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span class="text">Add</span>
                                </a>
                                <a href="#" style="background:#dc3545;text-align:center;color:#9fa8da;" class="list-group-item list-group-item-action" onclick='deleteEntry()' id='btn_delete'>
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </a>
                            </div>
                        </ul>
                    </div>
                    <div class="col-sm-10 table-responsive">
                        <table class="table mb-0" id="dt_entries">
                            <thead>
                                <tr>
                                    <th><input type='checkbox' onchange="checkAll(this, 'dt_id')"></th>
                                    <th></th>
                                    <th>Reference #</th>
                                    <th>Supplier</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Date Modified</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php require_once 'modal_po.php'; ?>
<?php require_once 'modal_print.php'; ?>
<script type="text/javascript">
    function printRecord(id) {
        $("#tb_id").html("");
        $("#modalPrint").modal('show');
        $.ajax({
            type: 'POST',
            url: "controllers/sql.php?c=" + route_settings.class_name + "&q=getPuchaseOrderHeader",
            data: {
                id: id
            },
            success: function(data) {
                console.log(data);
                var json = JSON.parse(data);

                $("#supplier_name_span").html(json.data[0].supplier_name);
                $("#reference_number_span").html(json.data[0].reference_number);
                $("#po_date_span").html(json.data[0].po_date_mod);
                $("#remarks_span").html(json.data[0].remarks);

                getPuchaseOrderDetails(json.data[0].po_id);
            }
        });
    }

    function getPuchaseOrderDetails(id) {

        $.ajax({
            type: 'POST',
            url: "controllers/sql.php?c=" + route_settings.class_name + "&q=getPuchaseOrderDetails",
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
                        '<td>' + json.data[i].supplier_price + '</td>' +
                        '<td>' + json.data[i].qty * json.data[i].supplier_price + '</td>' +
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
            "order": [[ 2, 'desc' ]],
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
                "dataSrc": "data"
            },
            "columns": [{
                    "mRender": function(data, type, row) {
                        return "<input type='checkbox' value=" + row.po_id + " class='dt_id' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        if (row.status == 'F') {
                            var display = "";
                        } else {
                            var display = "display: none;";
                        }
                        // return "<center><button class='btn btn-primary btn-circle btn-sm' onclick='getEntryDetails2(" + row.po_id + ")'><span class='bi bi-pencil-square'></span></button></center>";
                        return '<div class="dropdown">' +
                            '<button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i>' +
                            '</button><div class="dropdown-menu" aria-labelledby="dropdownMenuButton">' +
                            '<a class="dropdown-item" href="#" onclick="getEntryDetails2(' + row.po_id + ')"><span class="bi bi-pencil-square"></span> Edit Record</a>' +
                            '<a class="dropdown-item" href="#" style="' + display + '" onclick="printRecord(' + row.po_id + ')"><span class="fa fa-print"></span> Print Record</a>' +
                            // '<a class="dropdown-item" href="#">Option 3</a></div>' +
                            '</div>';
                    }
                },
                {
                    "data": "reference_number"
                },
                {
                    "data": "supplier_id"
                },
                {
                    "data": "remarks"
                },
                {
                    "mRender": function(data, type, row) {
                        return row.status == 'F' ? "<strong style='color:#009688;'>Finished</strong>" : "<strong style='color:#795548;'>Saved</strong>";
                    }
                },
                {
                    "data": "po_date"
                },
                {
                    "data": "date_last_modified"
                }
            ]
        });
    }

    function getEntries2() {
        var hidden_id_2 = $("#hidden_id_2").val();
        getSelectOption('Products', 'product_id', 'product_name');
        var param = "po_id = '" + hidden_id_2 + "'";
        $("#dt_entries_2").DataTable().destroy();
        $("#dt_entries_2").DataTable({
            "processing": true,
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show_detail",
                "dataSrc": "data",
                "type": "POST",
                "data": {
                    input: {
                        param: param
                    }
                }
            },
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
                        return "<input type='checkbox' value=" + row.po_detail_id + " class='dt_id_2' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "data": "product"
                },
                {
                    "data": "qty"
                },
                {
                    "data": "supplier_price"
                },
                {
                    "data": "amount"
                },
            ]
        });
    }

    $(document).ready(function() {
        getEntries();
        getSelectOption('Suppliers', 'supplier_id', 'supplier_name');
        getSelectOption('Products', 'product_id', 'product_name');
    });
</script>