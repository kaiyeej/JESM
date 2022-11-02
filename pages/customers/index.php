<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Customers</h3>
                <p class="text-subtitle text-muted">Manage customers here</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./homepage">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Customers</li>
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
                    <div class="col-sm-10">
                        <table class="table" id="dt_entries">
                            <thead>
                                <tr>
                                    <th><input type='checkbox' onchange="checkAll(this, 'dt_id')"></th>
                                    <th></th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>Contact #</th>
                                    <th>Remarks</th>
                                    <th>Date Added</th>
                                    <th>Date Last Updated</th>
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
<?php require_once 'modal_customer.php'; ?>
<script type="text/javascript">

    function getEntries() {
        $("#dt_entries").DataTable().destroy();
        $("#dt_entries").DataTable({
            "processing": true,
            "order": [[ 2, 'asc' ]],
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
                "dataSrc": "data",
                "type":"POST"
            },
            "columns": [{
                    "mRender": function(data, type, row) {
                        return "<input type='checkbox' value=" + row.customer_id + " class='dt_id' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        return "<center><button class='btn btn-primary btn-circle btn-sm' onclick='getEntryDetails(" + row.customer_id + ")'><span class='bi bi-pencil-square'></span></button></center>";
                    }
                },
                {
                    "data": "customer_name"
                },
                {
                    "data": "customer_address"
                },
                {
                    "data": "customer_contact_number"
                },
                {
                    "data": "remarks"
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

    $(document).ready(function() {
        getEntries();
    });

</script>