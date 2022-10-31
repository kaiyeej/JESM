<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Products</h3>
                <p class="text-subtitle text-muted">Manage products here</p>
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
                            <th>Product</th>
                            <th>Price</th>
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
<?php require_once 'modal_products.php'; ?>
<script type="text/javascript">

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
                        return "<input type='checkbox' value=" + row.product_id + " class='dt_id' style='position: initial; opacity:1;'>";
                    }
                },
                {
                    "mRender": function(data, type, row) {
                        return "<center><button class='btn btn-primary btn-circle btn-sm' onclick='getEntryDetails(" + row.product_id + ")'><span class='bi bi-pencil-square'></span></button></center>";
                    }
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "product_price"
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
        //getSelectOption('ProductCategories', 'product_category_id', 'product_category');
    });

</script>