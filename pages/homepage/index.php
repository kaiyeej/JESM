 
<div class="page-heading">
<h3>Dashboard</h3>
</div>
<?php
$Homepage = new Homepage();
$Main = new Connection();
?>
<div class="page-content">   
<section class="row">
    <div class="col-12 col-lg-9">
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon purple">
                                    <i style="margin: 3px 11px 15px 0px;" class="bi bi-cash-stack"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Job Order</h6>
                                <h6 class="font-extrabold mb-0"><?= number_format($Homepage->total_jo(),2); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon blue">
                                    <i style="margin: 3px 11px 15px 0px;" class="bi bi-person-lines-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Customer</h6>
                                <h6 class="font-extrabold mb-0"><?= $Homepage->total_customer(); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon green">
                                    <i style="margin: 3px 11px 15px 0px;" class="bi bi-boxes"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Product</h6>
                                <h6 class="font-extrabold mb-0"><?= $Homepage->total_product(); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon red">
                                    <i style="margin: 3px 11px 15px 0px;" class="bi bi-people-fill"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">User</h6>
                                <h6 class="font-extrabold mb-0"><?= $Homepage->total_user(); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Total Job Order</h4>
                    </div>
                    <div class="card-body">
                        <div id="chart-total-jo"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
      
        <div class="card">
            <div class="card-header">
                <h4><?= date("F",strtotime($Main->getCurrentDate())); ?> Top Service</h4>
            </div>
            <div class="card-body">
                <div id="chart-expenses"></div>
            </div>
        </div>
    </div>
</section>
</div>

<script>
salesGraph();
graphByExpense();


function graphByExpense(){
    $.getJSON("controllers/sql.php?c=Homepage&q=service_graph", function (data) {
        
        let inputArray = data.data;
        let total = inputArray.map((item) => item.total);
        let labels = inputArray.map(a => a.label);

       var options = {
            series: total,
            labels: labels,
            chart: {
                type:"donut",
                width:"100%",
                height:"350px",
                
            },
            dataLabels: {
                enabled: true
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                    width: 200
                    },
                    legend: {
                    show: false
                    }
                }
            }],
            legend: {
                position:"bottom"
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart-expenses"), options);
        chart.render();
    });
}

function salesGraph(){
    $.getJSON("controllers/sql.php?c=Homepage&q=monthly_graph", function (data) { 
        var options = {
            series: [{name:"sales",
                data: data.data
            }],
            chart: {
            type: 'bar',
            height: 350
            },
            dataLabels: {
            enabled: false
            },
            xaxis: {
            categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"
            ],
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-total-jo"), options);
        chart.render();
    });
}
</script>