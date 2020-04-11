<script src="node_modules/angular/angular.js"></script>
<script src="./node_modules/angular-dynamic-locale/dist/tmhDynamicLocale.js"></script>
<link rel="stylesheet" href="./node_modules/angular-datatables/dist/css/angular-datatables.css">
<script src="./lib/jquery.dataTables.min.js"></script>
<script src="./node_modules/angular-datatables/dist/angular-datatables.min.js"></script>
<script src="./lib/dataTables.fixedHeader.min.js"></script>
<script src="./node_modules/angular-datatables/dist/plugins/fixedheader/angular-datatables.fixedheader.min.js"></script>
<script src="./node_modules/moment/moment.js"></script>
<script type="text/javascript" src="./lib/moment-with-locales.min.js"></script> 
<script src="./node_modules/angular-moment/angular-moment.js"></script>

<script>
    'use strict';


    angular.module('ngApp', ['angularMoment', 'datatables', 'datatables.fixedheader', 'tmh.dynamicLocale'])


        .config(function (tmhDynamicLocaleProvider) {

            tmhDynamicLocaleProvider.defaultLocale('th');

        })
        .controller('homeCtrl', function ($scope, $http, moment, DTOptionsBuilder, DTColumnDefBuilder) {

            moment.locale('th');


            $scope.thaiDate = function (date) {
                let bDate = moment(date).format('D MMM') + ' ' + (moment(date).get('year') + 543);
                return bDate;
            }


            $http.post("./get_risk_person.php")
                .success((res) => {
                    var data = res;
                    $scope.data = res;
                    console.log(JSON.stringify(res));
                })
                .error(function (err) {
                    alert(JSON.stringify(err));
                    //window.location.href = 'login.html';
                });

            var vm = this;
            vm.data = [];
            vm.dtOptions = DTOptionsBuilder.newOptions()
                .withPaginationType('full_numbers')
                .withDisplayLength(20);
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

            <h2 class="float-left">
                <button type="button" id="sidebarCollapse" class="btn btn-info"><i class="fas fa-user-clock"
                        style="font-size: 24px;"></i></button>

                <b>เดินทางจากพื้นที่เสี่ยงในประเทศ</b>
            </h2>
        </div>
        <div class="line"></div>
        <div class="table-responsive" ng-controller="homeCtrl">
            <table datatable="ng" dt-options="showCase.dtOptions" dt-column-defs="showCase.dtColumnDefs"
                dt-columns="showCase.dtColumns" class="table table-sm table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ลำดับ</th>
                        <th scope="col" width='18px'>CID</th>
                        <th scope="col">ชื่อ-สกุล</th>
                        <th scope="col">อายุ</th>
                        <th scope="col">ที่อยู่</th>
                        <th scope="col">หมู่</th>
                        <th scope="col">ตำบล</th>
                        <th scope="col">เบอร์ติดต่อ</th>
                        <th scope="col">มาจากจังหวัด</th>
                        <th scope="col">จุดเสี่ยง</th>
                        <th scope="col">ถึงบ้าน</th>

                        <th scope="col">ครบเฝ้าระวัง</th>
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
                        <td>{{m.villagecode}}</td>
                        <td>{{m.tambonname}}</td>
                        <td>{{m.telphone}}</td>
                        <td>{{m.from_prove_name}}</td>
                        <td>{{m.place_name}}</td>
                        <td>{{thaiDate(m.date_arrival_home)}}</td>
                        <td>{{thaiDate(m.date_quarantine)}}</td>
                        <td><a href="index.php?url=pages/register_detail.php&cid={{m.cid}}" class="btn btn-sm btn-success "><i class="fas fa-user-check"></i></a></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>