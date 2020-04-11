<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="./node_modules/angular-datatables/dist/plugins/buttons/angular-datatables.buttons.min.js"></script>
<script src="./node_modules/angular-datatables/dist/plugins/tabletools/angular-datatables.tabletools.min.js"></script>
<script src="./node_modules/angular-datatables/dist/plugins/colvis/angular-datatables.colvis.min.js"></script>
<script src="./lib/dataTables.fixedHeader.min.js"></script>
<script src="./node_modules/angular-datatables/dist/plugins/fixedheader/angular-datatables.fixedheader.min.js"></script>
<script src="./node_modules/moment/moment.js"></script>
<script type="text/javascript" src="./lib/moment-with-locales.min.js"></script>
<script src="./node_modules/angular-moment/angular-moment.js"></script>

<script>
    'use strict';


    angular.module('ngApp', ['angularMoment', 'datatables', 'datatables.fixedheader', 'tmh.dynamicLocale',
            'datatables.buttons', 'datatables.tabletools', 'datatables.colvis'
        ])

        .run(function (DTDefaultOptions) {
            DTDefaultOptions.setDisplayLength(25);
            DTDefaultOptions.setDOM('lpfrtip');
        })
        .config(function (tmhDynamicLocaleProvider) {

            tmhDynamicLocaleProvider.defaultLocale('th');

        })
        .controller('homeCtrl', function ($scope, $http, moment, DTOptionsBuilder, DTColumnDefBuilder) {

            moment.locale('th');
            $scope.loading =true;


            $scope.thaiDate = function (date) {
                let bDate = moment(date).format('D MMM') + ' ' + (moment(date).get('year') + 543);
                return bDate;
            }


            $http.post("./get_risk_all_hosp.php")
                .success((res) => {
                    var data = res;
                    $scope.data = res;
                    $scope.loading =false;
                })
                .error(function (err) {
                    alert(JSON.stringify(err));
                });

            var vm = this;
            vm.data = [];
            vm.dtOptions = DTOptionsBuilder
                .newOptions()
                //.withOption('responsive', true)
                .withPaginationType('full_numbers')
                .withButtons([

                    'print',
                    'excel',
                    {
                        text: 'Some button',
                        key: '1',
                        action: function (e, dt, node, config) {
                            alert('Button activated');
                        }
                    }
                ]);
            //.withDisplayLength(false);
            vm.dtColumnDefs = [
                DTColumnDefBuilder.newColumnDef(0),
                DTColumnDefBuilder.newColumnDef(1).notVisible(),
                DTColumnDefBuilder.newColumnDef(2).notSortable()
            ];


        });
</script>
<div ng-app="ngApp">

    <div style="padding: 20px;height:100%">

        <div class="clearfix">

            <h4 class="float-left">
                <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-list-ul"
                        style="font-size: 24px;"></i></button>

                <b>ทะเบียนกลุ่มเสี่ยงที่ลงทะเบียนทั้งหมด</b>
                <small>หน่วยบริการ <?=$hospname?></small>
            </h4>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group" style="padding-top: 5px">
                 <a href="udonthani3_xls.php?hospcode=<?=$hospcode?>" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> ส่งออก Excel</a>
                </div>
                
            </div>
        </div>

        <div class="line"></div>
        <div class="table-responsive" ng-controller="homeCtrl">

            <div id="loading" align='center' ng-show="loadinge"><img src="./img/loading.gif"></div>

            <table datatable="ng" dt-options="showCase.dtOptions" dt-column-defs="showCase.dtColumnDefs"
                dt-columns="showCase.dtColumns" class="table table-sm table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col" align="center" width='5px'>#</th>
                        <th scope="col" align="center" width='10px' >CID</th>
                        <th scope="col" align="center">ชื่อ-สกุล</th>
                        <th scope="col" align="center" width='10px'>อายุ</th>
                        <th scope="col" align="center">ที่อยู่</th>
                        <th scope="col" align="center">หมู่</th>
                        <th scope="col" align="center">ตำบล</th>
                        <th scope="col" align="center">เบอร์ติดต่อ</th>
                        <th align="center">ประเภท</th>
                        <th scope="col" align="center">มาจากประเทศ</th>
                        <th scope="col" align="center">เมือง</th>
                        <th scope="col" align="center" width='150px'>ถึงบ้าน</th>
                        <th scope="col" align="center" width='150px'>ครบเฝ้าระวัง</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="m in data">
                        <td>{{$index +1}}</td>
                        <td>{{m.cid}}</td>
                        <td>{{m.fullname}}</td>
                        <td>{{m.age}}</td>
                        <td>{{m.address}}</td>
                        <td>{{m.mooban}}</td>
                        <td>{{m.tambonname}}</td>
                        <td>{{m.telphone}}</td>
                        <td>{{m.risk_type_name}}</td>
                        <td>{{m.countryname}}</td>
                        <td ng-if="m.from_country=='764'">{{m.from_prov_name}}</td>
                        <td ng-if="m.from_country!='764'">{{m.from_city}}</td>
                        <td>{{thaiDate(m.date_arrival_home)}}</td>
                        <td>{{thaiDate(m.date_quarantine)}}</td>
                        <td><a href="index.php?url=pages/register_detail.php&cid={{m.cid}}" class="btn btn-sm btn-success "><i class="fas fa-user-check"></i></a></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>