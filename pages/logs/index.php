<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container ">
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Example-->
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">
                            Audit Trails
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dt_entries" class="table table-hover mb-6">
                                <thead class="">
                                    <tr>
                                        <th>Remarks</th>
                                        <th>User</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<script type="text/javascript">
    function getEntries() {
        $("#dt_entries").DataTable().destroy();
        $("#dt_entries").DataTable({
            "processing": true,
            "order": [[ 2, 'desc' ]],
            //"aaSorting": [],
            //"serverSide": true,
            "ajax": {
                "url": "controllers/sql.php?c=" + route_settings.class_name + "&q=show",
                "dataSrc": "data"
            },
            "columns": [
                {
                    "data": "remarks"
                },
                {
                    "data": "user"
                },
                {
                    "data": "date_added"
                }
            ]
        });
    }

    $(document).ready(function() {
        getEntries();
    });
</script>