<!DOCTYPE html>
<html>
<head>
	<title>RCG Job Management System</title>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="<?php echo base_url('public/css/bootstrapv3.css')?>"/>

	<script src="<?php echo base_url('public/js/jquery.js')?>"></script>
	<script src="<?php echo base_url('public/js/bootstrap.js')?>"></script>



	<script src="<?php echo base_url('public/js/chart.js')?>"></script>
	


	<script src="<?php echo base_url('public/js/angular.js')?>"></script>

	
	<script src="<?php echo base_url('public/js/angular-ui.js')?>"></script>
	
	<script>
		var app = angular.module('test', ['ui.bootstrap']);

		app.controller('my_controller', ['$scope', '$http', '$filter', '$window', function ($scope, $http, $filter, $window){

			$scope.person = [
			{id: 1
				,name: 'jose'
				,age: 15

			}

			,{id: 2
				,name: 'pedro'
				,age: 38
			}	
			,{id: 3
				,name: 'carlo'
				,age: 27
			}	

			]

			$scope.row_counter = $scope.person.length;

			$scope.add_row = function() {

				var person = {id:"",name:"",age:""};
    
			    $scope.person.push(person);
			    $scope.row_counter++;
			  }



		$scope.show_new = function(){
			console.log($scope.person);
		}



		}]);

	</script>

</head>
<body ng-app="test" ng-controller="my_controller">

<!-- 	<?php
	$test_array = array(
		array("id"=>1
			,"name"=>"jose"
			,"age"=>"15")
		,array("id"=>2
			,"name"=>"pedro"
			,"age"=>"38")
		,array("id"=>3
			,"name"=>"carlo"
			,"age"=>"27")
		);



		?>
 -->

		<form ng-submit="show_new()">
			<table class="table">
				<thead>
					<td>id</td>
					<td>name</td>
					<td>age</td>
				</thead>
				<tbody>
				<tr ng-repeat="p in person">
					<td>{{$index+1}}</td>
					<td><input type="text" placeholder="name" ng-model="p.name"/></td>
					<td><input type="text" placeholder="age" ng-model="p.age"/></td>
				</tr>
				</tbody>
			</table>
			
			<button type="submit" class="btn btn-default">Submit</button>
			<button type="submit" class="btn btn-default" ng-click="add_row()">Add row</button>
		</form>

	</body>
	</html>

