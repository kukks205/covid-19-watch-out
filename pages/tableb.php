<script src="node_modules/angular/angular.js"></script>
<script>

angular.module('ngApp', [])

.controller('homeCtrl', function($scope,$http) {

    $http.get('./get_risk_person.php')
        .success(function(res) {

            $scope.members =res;
            /*$scope.data = res[0];
            if ($scope.data.status == 'ok') {
                $scope.members = $scope.data.data;
            } else {
                alert("Something went wrong");
            }*/
        })
        .error(function(err) {
            alert("Something went wrong");
        });

   /* $scope.members = [{
        fname: 'Suputtana',
        lname: 'Pingmaung',
        email: 'kukks205@udonsoft.com'
    }, {
        fname: 'Jonh',
        lname: 'Dee',
        email: 'jonh@udonsoft.com'
    }, {
        fname: 'Mongkol',
        lname: 'Salee',
        email: 'mm@udonsoft.com'
    }, {
        fname: 'Sangwan',
        lname: 'Tong',
        email: 'sang@udonsoft.com'
    }, {
        fname: 'Tongdee',
        lname: 'Dum',
        email: 'tong@udonsoft.com'
    }];*/

});

</script>
<div ng-app="ngApp">


<div class="container" ng-controller="homeCtrl">
        <div class="page-header">
            <h1>ng-repeat<br>
            <small>การแสดงชุดข้อมูลด้วย ng-repeat</small></h1>
        </div>
        <input type="text" class="form-control" id="fname" ng-model="search" placeholder="Search..">
        <hr>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>E-Mail</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="m in members | filter : search">
                    <td>{{m.cid}}</td>
                    <td>{{m.lname}}</td>
                    <td>{{m.email}}</td>
                </tr>
        </table>
        </tbody>
        <hr>
    </div>

    </div>