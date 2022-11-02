<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Users</h3>
                <p class="text-subtitle text-muted">Manage users here</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./homepage">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
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
                                    <th></th>
                                    <th>Fullname</th>
                                    <th>Category</th>
                                    <th>Username</th>
                                    <th>Date Added</th>
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
<?php require_once 'modal_user.php'; ?>
<?php require_once 'modal_privileges.php'; ?>
<script type="text/javascript">
     function addUser(){
        addModal();
        $("#div_password").show();
    }

    function getUserDetails(id){
        $("#div_password").hide();
        getEntryDetails(id);
    }
    
    function getEntries() {
        $("#dt_entries").DataTable().destroy();
        $("#dt_entries").DataTable({
            "processing": true,
            "order": [[ 2, 'asc' ]],
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
                "dataSrc": "data"
            },
            "columns": [{
                    "mRender": function(data, type, row) {
                        return "<input type='checkbox' value=" + row.user_id + " class='dt_id' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        return row.user_category != 'S' ? '' : "<center><button class='btn btn-success btn-circle btn-sm' onclick='getUserPrivileges(" + row.user_id + ")'><span class='bi bi-key'></span></button></center>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        return "<center><button class='btn btn-primary btn-circle btn-sm' onclick='getUserDetails(" + row.user_id + ")'><span class='bi bi-pencil-square'></span></button></center>";
                    }
                },
                {
                    "data": "user_fullname"
                },
                {
                    "data": "category"
                },
                {
                    "data": "username"
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

    function getUserPrivileges(id) {
        $("#priv_user_id").val(id);
        $("#modalPrivileges").modal('show');

        $.ajax({
            type: "POST",
            url: "controllers/sql.php?c=UserPrivileges&q=lists",
            data: {
                input: {
                    id: id
                }
            },
            success: function(data) {
                var json = JSON.parse(data),
                    text_masterdata = '',
                    text_transaction = '',
                    text_report = '';

                if (json.data.masterdata.length > 0) {
                    for (let mIndex = 0; mIndex < json.data.masterdata.length; mIndex++) {
                        const rowData = json.data.masterdata[mIndex];
                        text_masterdata += skin_privilege(rowData.name, rowData.status, rowData.url);
                    }
                }
                $("#master_data_column").html(text_masterdata);

                if (json.data.transaction.length > 0) {
                    for (let mIndex = 0; mIndex < json.data.transaction.length; mIndex++) {
                        const rowData = json.data.transaction[mIndex];
                        text_transaction += skin_privilege(rowData.name, rowData.status, rowData.url);
                    }
                }
                $("#transaction_column").html(text_transaction);

                if (json.data.report.length > 0) {
                    for (let mIndex = 0; mIndex < json.data.report.length; mIndex++) {
                        const rowData = json.data.report[mIndex];
                        text_report += skin_privilege(rowData.name, rowData.status, rowData.url);
                    }
                }
                $("#report_column").html(text_report);
            }
        });
    }

    $("#frm_privileges_submit").submit(function(e) {
        e.preventDefault();

        $("#btn_submit_priv").prop('disabled', true);
        $("#btn_submit_priv").html("<span class='fa fa-spinner fa-spin'></span> Submitting ...");

        $.ajax({
            type: "POST",
            url: "controllers/sql.php?c=UserPrivileges&q=add",
            data: $("#frm_privileges_submit").serialize(),
            success: function(data) {
                var json = JSON.parse(data);
                if (json.data) {
                    success_update();
                }
                $("#btn_submit_priv").prop('disabled', false);
                $("#btn_submit_priv").html("<span class='fa fa-check-circle'></span> Submit");
            }
        });
    });
    
    function skin_privilege(item_name, status, url) {
        var check_input = status == 1 ? "checked" : '';
        return '<li class="list-group-item">' +
                    '<div class="form-check">' +
                        '<div class="checkbox">' + 
                            '<label class="form-check-label">' +
                            '<input class="checkbox" id="checkbox5" name="input[' + url + ']" value="1" type="checkbox" ' + check_input + '> ' + item_name + '<i class="input-helper"></i></label>' +
                        '</div>' +
                    '</div>' +
                '</li>';
    }
    $(document).ready(function() {
        getEntries();
    });

</script>