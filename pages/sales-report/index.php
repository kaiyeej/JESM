<style>
    .text-right {
        text-align: right;
    }
</style>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Sales Report</h3>
                <p class="text-subtitle text-muted">Generate reports here</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./homepage">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="btn-group divider divider-right">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Start Date</span>
                                <input type="date" class="form-control" id="start_date" value="<?php echo date('Y-m-01', strtotime(date("Y-m-d"))); ?>" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">End Date</span>
                                <input type="date" value="<?php echo date('Y-m-t', strtotime(date("Y-m-d"))) ?>" class="form-control" id="end_date" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="#" onclick="getEntries()" class="btn icon icon-left btn-success" style="float: left; margin-right: 10px;"><i data-feather="refresh-cw"></i> Generate</a> <a href="#" onclick="printCanvas()" class="btn icon icon-left btn-primary" style="float: left;"><i data-feather="printer"></i> Print</a>
                        </div>


                    </div>
                </div>
            </div>
            <div class="card-body" id="print_canvas">
                <br><center>
                    <h5 class="report-header">JID ELECTRICAL SERVICES MANAGEMENT SYSTEM</h5>
                    <h5>Sales Report</h5><br>
                </center><br>

                <table class="display expandable-table" id="dt_entries" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align:right;">Quantity</th>
                            <th style="text-align:right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        getEntries();
    });

    function getEntries() {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        $("#dt_entries").DataTable().destroy();
        $("#dt_entries").DataTable({
            "searching": false,
            "paging": false,
            "info": false,
            "processing": true,
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };
                pageTotal = api
                    .column(2, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                $(api.column(2).footer()).html(
                    "<strong>Grand Total: <span>&#8369;</span> " + this.fnSettings().fnFormatNumber(parseFloat(parseFloat(pageTotal).toFixed(2))) + "</strong>"
                );
            },
            "ajax": {
                "type":"POST",
                "url": "controllers/sql.php?c=JobOrder&q=generate_report",
                "dataSrc": "data",
                "data": {
                    start_date: start_date,
                    end_date: end_date
                }
            },
            "columns": [{
                    "data": "product"
                },
                {
                    "data": "qty", className: "text-right"
                },
                {
                    "data": "total", className: "text-right"
                }

            ]
        });
    }
</script>