<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<link rel="stylesheet" href="./css/styleA.css">

<div style="padding: 20px;height:100%">
    <div class="clearfix">
        <h2 class="float-left">


            <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-user-plus"
                    style="font-size: 24px;"></i></button>
        </h2>
    </div>
    <hr>
<div class="row">

<div class="col-xl-3 col-md-6">
    <div class="card bg-c-yellow update-card">
        <div class="card-block">
            <div class="row align-items-end">
                <div class="col-8">
                    <h4 class="text-white">$30200</h4>
                    <h5 class="text-white m-b-0">All Earnings</h5>
                </div>
                <div class="col-4 text-right">
                    <canvas id="update-chart-1" height="10"></canvas>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <p class="text-white m-b-0"><i
                    class="feather icon-clock text-white f-14 m-r-10"></i>update
                : 2:15 am</p>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6">
    <div class="card bg-c-green update-card">
        <div class="card-block">
            <div class="row align-items-end">
                <div class="col-8">
                    <h4 class="text-white">290+</h4>
                    <h5 class="text-white m-b-0">Page Views</h5>
                </div>
                <div class="col-4 text-right">
                    <canvas id="update-chart-2" height="50"></canvas>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <p class="text-white m-b-0"><i
                    class="feather icon-clock text-white f-14 m-r-10"></i>update
                : 2:15 am</p>
        </div>
    </div>
</div>

<div class="col-xl-3 col-md-6">
    <div class="card bg-c-pink update-card">
        <div class="card-block">
            <div class="row align-items-end">
                <div class="col-8">
                    <h4 class="text-white">145</h4>
                    <h5 class="text-white m-b-0">Task Completed</h5>
                </div>
                <div class="col-4 text-right">
                    <canvas id="update-chart-3" height="50"></canvas>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <p class="text-white m-b-0"><i
                    class="feather icon-clock text-white f-14 m-r-10"></i>update
                : 2:15 am</p>
        </div>
    </div>
</div>
<div class="col-xl-3 col-md-6">
    <div class="card bg-c-lite-green update-card">
        <div class="card-block">
            <div class="row align-items-end">
                <div class="col-8">
                    <h4 class="text-white">500</h4>
                    <h5 class="text-white m-b-0">Downloads</h5>
                </div>
                <div class="col-4 text-right">
                    <canvas id="update-chart-4" height="50"></canvas>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <p class="text-white m-b-0"><i
                    class="feather icon-clock text-white f-14 m-r-10"></i>update
                : 2:15 am</p>
        </div>
    </div>
</div>


</div>


            <div class="row">
                <div class="col-md-6" style="padding:5px;">
                    <div id="container">
                    </div>
                </div>
                <div class="col-md-6" style="padding:5px;">
                <div id="container2">
                    </div>
                </div>

            </div>


</div>


<script>
    Highcharts.chart('container', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Browser market shares in January, 2018'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Chrome',
                y: 61.41,
                sliced: true,
                selected: true
            }, {
                name: 'Internet Explorer',
                y: 11.84
            }, {
                name: 'Firefox',
                y: 10.85
            }, {
                name: 'Edge',
                y: 4.67
            }, {
                name: 'Safari',
                y: 4.18
            }, {
                name: 'Sogou Explorer',
                y: 1.64
            }, {
                name: 'Opera',
                y: 1.6
            }, {
                name: 'QQ',
                y: 1.2
            }, {
                name: 'Other',
                y: 2.61
            }]
        }]
    });


    Highcharts.chart('container2', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Browser market shares in January, 2018'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Chrome',
                y: 61.41,
                sliced: true,
                selected: true
            }, {
                name: 'Internet Explorer',
                y: 11.84
            }, {
                name: 'Firefox',
                y: 10.85
            }, {
                name: 'Edge',
                y: 4.67
            }, {
                name: 'Safari',
                y: 4.18
            }, {
                name: 'Sogou Explorer',
                y: 1.64
            }, {
                name: 'Opera',
                y: 1.6
            }, {
                name: 'QQ',
                y: 1.2
            }, {
                name: 'Other',
                y: 2.61
            }]
        }]
    });
</script>