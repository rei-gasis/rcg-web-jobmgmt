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
		// var base_url = 'http://' + "<?php echo base_url();?>";

		app.controller('mainCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter){

			
			$scope.user = {
				// first_name:'Janet',
				// last_name: 'Orboza',
				// user_name: 'jorboza',
				// password: '123',
				// retry_password: '123',
				// date_created: $filter('date')(new Date(), 'yyyyMMdd hh:mm:ss a', null)

				// first_name:'Winson',
				// last_name: 'Gasis',
				// user_name: 'rcg_admin',
				// password: 'admin1',
				// retry_password: 'admin1',
				// date_created: new Date()
			};

			$scope.arr_users = new Array;


			$scope.get_accounts = function(){
				$http({
					method: 'get',
					url: "<?php echo base_url();?>" + 'dashboard/get_accounts',
					dataType: 'json',
					contentType: "application/json"
				}).then(function(response){
						$scope.arr_users = response.data; //convert to array
						$scope.list_users = $scope.arr_users;

					}).catch(function(error){
						$scope.result = error;
					})
					;
				}
				
				$scope.action = "Register";

				$scope.register_update_account = function(){

					$scope.result = null;

					if($scope.user.user_name.length < 5) {
						$scope.result = 'User name is too short! It must be at least 5 characters.';
					}
					
					if($scope.user.retry_password !== $scope.user.password){
						$scope.result = 'Passwords mismatch!';
					}

					if($scope.user.password.length < 6){
						$scope.result = 'Password is too short! It must be at least 6 characters.';	
					}

					if($scope.result){
						$('.alert').show();
					}
					else{
						
						
						

						
						if($scope.action === 'Register'){

						//instantiate date_created
						$scope.user.date_created = new Date();

						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/register_account',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: $scope.user,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.result = success.data;

							$('.alert').show();

						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});

					}else if($scope.action === 'Update'){

						$scope.user.last_update_date = new Date();

						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/update_account',
							data: $scope.user,
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


					$scope.get_accounts();
					
					//reset form
					$scope.user = {};



					
				}

				
				
			};

			$scope.reset_form = function(){
				$scope.user = {};
				$scope.action = 'Register';
			}

			$scope.selectedUser = null;
			$scope.selectUser = function(user) {
				$scope.result = "";
				$scope.user = user;
				$scope.action = "Update";

				$('.alert').hide();

			}

			$scope.remove_account = function(){
				var user_id = $scope.user.user_id;

				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/remove_account',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: user_id,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.result = success.data;
							
							$('.alert').show();

							$scope.get_accounts();
						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});

					//hide modal
					$('#deleteModal').modal('hide');

					$scope.get_accounts();
					
					//reset form
					$scope.user = {};
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
						<div hidden class="alert alert-success" role="alert">{{result}}</div>
						<div class="reg-account">
							<h3> Register Account </h3>
							<form id="user_form" role="form" ng-submit="register_update_account()">
								<div class="form-group">
									<input type="text" required="" placeholder="First name" ng-model="user.first_name"/>
								</div>
								<div class="form-group">
									<input type="text" required="" placeholder="Last name" ng-model="user.last_name"/>
								</div>
								<div class="form-group">
									<input type="username" required="" placeholder="User name" ng-model="user.user_name"/>
								</div>
								<div class="form-group">
									<input type="password" required="" placeholder="Password" ng-model="user.password"/>
								</div>
								<div class="form-group">
									<input type="password" required="" placeholder="Re-type Password" ng-model="user.retry_password"/>
								</div>
								<button type="submit" class="btn btn-default" >{{action}}</button>
								<button type="submit" class="btn btn-default" ng-click="reset_form()">Reset</button>
							</form>
						</div>

						<div>
							<h3>Accounts</h3>
							<table class="table table-striped table-bordered" id="users_tbl">
								<thead>
									<tr>
										<th>No</th>
										<th>Username</th>
										<th>First name</th>
										<th>Last name</th>
										<th>Date created</th>
										<th>Last Update</th>

										<th></th>
									</tr>
								</thead>
								<tbody ng-init="get_accounts()">
									<tr ng-repeat="u in list_users" ng-click="selectUser(u)">
										<td hidden>{{u.user_id}}</td>
										<td>{{$index+1}}</td>
										<td>{{u.user_name}}</td>
										<td>{{u.first_name}}</td>
										<td>{{u.last_name}}</td>
										<td>{{u.date_created | date: 'MMM dd yyyy'}}</td><td>{{u.last_update_date | date: 'MMM dd yyyy'}}</td>
										<td><i class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#deleteModal"></i></td>
										<!-- ng-click="removeAccount(u.user_id) -->
									</tr>
								</tbody>

								
								
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
							<h4 class="modal-title" id="myModalLabel">Remove Account</h4>
						</div>
						<div class="modal-body">
							This will remove account permanently. Are you sure you want to continue?
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
							<button type="button" class="btn btn-primary" ng-click="remove_account()">Yes</button>
						</div>
					</div>
				</div>
			</div>

		</body>
		</html>


