<!DOCTYPE html>
<html>
<head>


	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url('public/css/bootstrapv3.css')?>"/>

	<script src="<?php echo base_url('public/js/jquery.js')?>"></script>
	<script src="<?php echo base_url('public/js/bootstrap.js')?>"></script>


	<!-- chart js -->
	<script src="<?php echo base_url('public/js/chart.js')?>"></script>
	<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script> -->

	<!-- angular -->
	<script src="<?php echo base_url('public/js/angular.js')?>"></script>

	<!--angular bootstrap UI  -->
	<script src="<?php echo base_url('public/js/angular-ui.js')?>"></script>





	<style>


		tr {
			cursor: pointer;

		}







	</style>


	<script>
		app = angular.module('jobs', ['ui.bootstrap']);





		app.controller('mainCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter){



			$scope.arr_workers = new Array;
			$scope.list_workers = [];

			$scope.currentPage = 1;
			$scope.numPerPage = 10;
			$scope.maxSize = 5;

			var begin = (($scope.currentPage - 1) * $scope.numPerPage)
			, end = begin + $scope.numPerPage;


			$scope.get_workers = function(){
				$http({
					method: 'get',
					url: "<?php echo base_url();?>" + 'dashboard/get_workers',
					dataType: 'json',
					contentType: "application/json"
				}).then(function(response){

						$scope.arr_workers = response.data; //convert to array
						$scope.list_workers = $scope.arr_workers;


						/*paginate*/
						$scope.paginatedWorkers = $scope.arr_workers.slice(begin, end);


					}).catch(function(error){
						$scope.result = error;
					})
					;

					
				}


				/* 1. get records
				   2. assign to array
				   3. slice array

				   */



				   $scope.get_workers();


				   // $scope.paginatedWorkers = [];


				   $scope.$watch('currentPage + numPerPage', function() {
				   	var begin = (($scope.currentPage - 1) * $scope.numPerPage)
				   	, end = begin + $scope.numPerPage;


				   	$scope.paginatedWorkers = $scope.arr_workers.slice(begin, end);

				   });



				   $scope.worker = {
				   		// first_name: 'Reniel Cris',
				   		// last_name: 'Gasis'

				   	};





				   	$scope.action = "Register";

				   	$scope.register_update_worker = function(){
				   		$result = null;



				   		if($scope.action === 'Register'){

						//instantiate date_created
						$scope.worker.date_created = new Date();

						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/register_worker',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: $scope.worker,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.result = success.data;

							$('.alert').show();

						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});

					}else if($scope.action === 'Update'){

						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/update_worker',
							data: $scope.worker,
							headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.result = success.data;

							$('.alert').show();
						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});

						$scope.action = "Register";
					}


					$scope.get_workers();
					
					//reset form
					$scope.worker = {};
					
				}

				

				$scope.reset_form = function(){
					$scope.worker = {};
					$scope.action = 'Register';
				}

				$scope.selectedWorker = null;
				$scope.selectWorker = function(artist) {
					$scope.result = "";
					$scope.worker = artist;
					$scope.action = "Update";



					$('.alert').hide();

				}


				$scope.remove_worker = function(){
					var worker_id = $scope.worker.worker_id;

					// console.log(category_id);
					// $('#deleteModal').modal('hide');

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/remove_worker',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: worker_id,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.result = success.data;

							$('.alert').show();

							$scope.get_workers();
						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});

					//hide modal
					$('#deleteModal').modal('hide');

					$scope.action = "Register";

					$scope.get_workers();
					
					//reset form
					$scope.worker = {};
				}

				$scope.search_worker = function(){
					var worker = $scope.worker.search_name;

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/search_worker',
						data: worker,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){
						$scope.arr_workers = response.data; //convert to array
						$scope.list_workers = $scope.arr_workers;
						
						$scope.paginatedWorkers = $scope.arr_workers.slice(begin, end);

					}).catch(function(error){
						$scope.result = error;
					})
					;
				}

			}]);




		</script>


	</head>
	<body ng-app="jobs" ng-controller="mainCtrl">

		<!-- Dashboard navbar-->

		<nav class="navbar navbar-default">
			<div class="container-fluid">

				<div class="navbar-header">
					<a class="navbar-brand" href="#">RCG Job Management</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?php echo base_url() . 'login/logout_user'?>">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>



		
		<div class="container-fluid">

			<!-- Dashboard menus-->
			<div class="row">
				<div class="col-md-3 border">

					<div>
						<ul class="nav nav-pills nav-stacked">
							<li class="active dashboard-nav"><a href="<?php echo base_url() . 'dashboard/view_dashboard'?>">Dashboard</a></li>
							<li class="customer-nav"><a href="<?php echo base_url() . 'dashboard/view_expenses'?>">Expenses</a></li>
							<!-- <li class="sales-nav"><a href="/codeigniter/dashboard/sales">Sales</a></li> -->
							<li class="customer-nav"><a href="#">Customers</a></li>
							<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Resources<b class="caret"></b></a> 
								<ul class="dropdown-menu sub-menu">
									<li><a href="<?php echo base_url() . 'dashboard/view_users'?>">User Accounts</a></li>
									<li><a href="<?php echo base_url() . 'category'?>">Categories</a></li>
									<li><a href="<?php echo base_url() . 'dashboard/view_layout_artists'?>">Layout Artists</a></li>
									<li><a href="<?php echo base_url() . 'dashboard/view_workers'?>">Workers</a></li>
									<!-- <li><a href="category">Categories</a></li> -->
								</ul>

							</li>


						</ul>

					</div>

				</div>


				<div class="col-md-9 border"> 
					<div class="container-body">
						<div hidden class="alert alert-success fade in" role="alert">{{result}}</div>
						<div class="reg-category">
							<h3> Register Worker </h3>
							<form id="category_form" role="form" ng-submit="register_update_worker()">
								<div class="form-group">
									<input type="text" required="" pattern="[A-Za-z\/ ]{4,100}" placeholder="First name" ng-model="worker.first_name"/>
								</div>
								<div class="form-group">
									<input type="text" required="" pattern="[A-Za-z\/ ]{4,100}" placeholder="Last name" ng-model="worker.last_name"/>
								</div>
								<button type="submit" class="btn btn-default" >{{action}}</button>
								<button type="submit" class="btn btn-default" ng-click="reset_form()">Reset</button>
							</form>
						</div>

						<div>

							<h3>Workers</h3>
							<div class="form-group pull-right">
								<input type="text" placeholder="Search" ng-model="worker.search_name" ng-change="search_worker(worker.search_name)"/>
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							</div>
							

							<!-- <pagination 
							ng-model="currentPage"
							total-items="paginatedCategories.length"
							max-size="maxSize"  
							boundary-links="true"/> -->

							<table class="table table-striped table-bordered" id="worker_tbl">
								<thead>
									<tr>
										<th>No</th>
										<th>First name</th>
										<th>Last name</th>
										<th>Date created</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="a in paginatedWorkers" ng-click="selectWorker(a)">
										<td hidden>{{a.artist_id}}</td>
										<td>{{$index+1}}</td>
										<td>{{a.first_name}}</td>
										<td>{{a.last_name}}</td>
										<td>{{a.date_created | date: 'MMM dd yyyy'}}</td>
										<td><i class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#deleteModal"></i></td>
										<!-- ng-click="removeAccount(u.user_id) -->
									</tr>
								</tbody>
							</table>
							<!-- "paginatedCategories.length" -->
							<pagination 
							ng-model="currentPage"
							total-items=arr_workers.length
							max-size="maxSize"  
							boundary-links="true"/>


						</div>






					</div> <!-- end of body -->





				</div> 		




			</div>
		</div>


		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Remove Worker</h4>
					</div>
					<div class="modal-body">
						This will remove worker permanently. Are you sure you want to continue?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" ng-click="remove_worker()">Yes</button>
					</div>
				</div>
			</div>
		</div>

		

	</body>
	</html>


