<!DOCTYPE html>
<html>
<head>

	<title>RCG Job Management</title>
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


	<!-- pagination imports-->





	<style>


		tr {
			cursor: pointer;

		}

	</style>


	<script>
		app = angular.module('jobs', ['ui.bootstrap']);


		app.controller('mainCtrl', ['$scope', '$http', '$filter', function ($scope, $http, $filter){



			$scope.arr_expenses = new Array;
			$scope.list_expenses = [];

			$scope.currentPage = 1;
			$scope.numPerPage = 10;
			$scope.maxSize = 5;

			var begin = (($scope.currentPage - 1) * $scope.numPerPage)
			, end = begin + $scope.numPerPage;



			$scope.$watch('currentPage + numPerPage', function() {
				var begin = (($scope.currentPage - 1) * $scope.numPerPage)
				, end = begin + $scope.numPerPage;


				$scope.paginatedExpenses = $scope.arr_expenses.slice(begin, end);

			});


			$scope.current_date = new Date();

			$scope.view_next_day = function(date){
				var new_date = new Date(date);
				new_date.setDate(new_date.getDate() + 1);

				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/get_expenses_by_date',
					data: new_date,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					$scope.arr_expenses = response.data; 
					$scope.list_expenses = $scope.arr_expenses;

					$scope.paginatedExpenses = $scope.arr_expenses.slice(begin, end);

					$scope.get_total_expenses($scope.current_date);

				}).catch(function(error){
					$scope.result = error;
				})
				;

				$scope.current_date = new_date;

			}

			$scope.view_prev_day = function(date){
				var new_date = new Date(date);
				new_date.setDate(new_date.getDate() - 1);

				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/get_expenses_by_date',
					data: new_date,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					$scope.arr_expenses = response.data; 
					$scope.list_expenses = $scope.arr_expenses;

					$scope.paginatedExpenses = $scope.arr_expenses.slice(begin, end);

					$scope.get_total_expenses($scope.current_date);

				}).catch(function(error){
					$scope.result = error;
				})
				;

				$scope.current_date = new_date;

			}

			$scope.view_expenses_today = function(){

				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/get_expenses_by_date',
					data: $scope.current_date,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					$scope.arr_expenses = response.data; 
					$scope.list_expenses = $scope.arr_expenses;

					$scope.paginatedExpenses = $scope.arr_expenses.slice(begin, end);

				}).catch(function(error){
					$scope.result = error;
				})
				;




			}

			$scope.view_expenses_today();

			$scope.get_total_expenses = function(date){


				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/get_total_expenses_by_date',
					data: $scope.current_date,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}
				}).then(function(response){
					$scope.total_expense = response.data;

				}).catch(function(error){

				})
				;

			}

			$scope.get_total_expenses();



			$scope.expense = {


			};



			$scope.action = "Create";

			$scope.register_update_expense = function(){
				$result = null;


				if($scope.action === 'Create'){

					$scope.expense.date_start = new Date();

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/create_expense',

						data: $scope.expense,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(success){

						if(success.data=="success"){
							$scope.result = "Expense Created!";
						}else{
							$scope.result = "Creation Failed!";
						}


						$('.alert').show();

					}).catch(function(error){
						$scope.result = error.data;

						$('.alert').show();
					});

				}else if($scope.action === 'Update'){

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/update_expense',
						data: $scope.expense,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(success){
						if(success.data=="success"){
							$scope.result = "Expense Updated!";
						}else{
							$scope.result = "Update Failed!";
						}

						$('.alert').show();
					}).catch(function(error){
						$scope.result = error.data;

						$('.alert').show();
					});

					$scope.action = "Create";
				}


				$scope.view_expenses_today();
				$scope.get_total_expenses($scope.current_date);

				$scope.expense = {};

			}



			$scope.reset_form = function(){
				$scope.expense = {};
				$scope.action = 'Create';
			}

			$scope.selectedExpense = null;
			$scope.selectExpense = function(expense) {
				$scope.result = "";
				$scope.expense = expense;
				$scope.action = "Update";


				$('.alert').hide();

			}

			$scope.test = function(){
				alert('test');
			}


			$scope.remove_expense = function(){
				var expense_id = $scope.expense.expense_id;

				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/remove_expense',

					data: expense_id,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}
				}).then(function(success){
					if(success.data=="success"){
						$scope.result = "Expense Removed!";
					}else{
						$scope.result = "Remove Failed!";
					}

					$('.alert').show();

					$scope.get_categories();
				}).catch(function(error){

					$scope.result = error;


					$('.alert').show();
				});

				$('#deleteModal').modal('hide');

				$scope.action = "Create";

				$scope.get_expenses_by_date($scope.current_date);
				$scope.get_total_expenses($scope.current_date);

				$scope.expense = {};
			}

			$scope.search_expenses = function(){
				var name = $scope.expense.name;

				$http({
					method: 'post',
					url: "<?php echo base_url();?>" + 'dashboard/search_expenses',
					data: name,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}

				}).then(function(response){
						$scope.arr_expenses = response.data; //convert to array
						$scope.list_expenses = $scope.arr_expenses;
						
						$scope.paginatedExpenses = $scope.arr_expenses.slice(begin, end);

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

					<div class="container-body row">
						<div hidden class="alert alert-success fade in" role="alert">{{result}}</div>
						<div class="reg-category">
							<h3> {{action}} Expense </h3>
							<form id="category_form" role="form" ng-submit="register_update_expense()">
								<div class="form-group">
									<input type="text" required="" pattern="[A-Za-z\/ ]{4,100}" placeholder="Enter Name" ng-model="expense.name"/>
								</div>
								<div class="form-group">
									<input type="text" required="" pattern="[0-9\.]{1,10}" placeholder="Amount" ng-model="expense.amount"/>
								</div>
								<button type="submit" class="btn btn-default" >{{action}}</button>
								<button type="submit" class="btn btn-default" ng-click="reset_form()">Reset</button>
							</form>
						</div>
					</div>



					<section class="row">
						<h3> Expenses </h3>
					</section>

					<section class="row">
						<div class="input-group" style="margin-right:20px;">
							<h5>Total Expenses: {{total_expense}}</h5>

						</div>

					</section>							

					<section class="row">

						<div class="form-group pull-right" style="margin-right:20px;">
							<input type="text" placeholder="Search" ng-model="expense.name" ng-keyup="search_expenses(expense.name)"/>
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
						</div>

						<div class="btn-group" role="group">
							<button ng-click="view_prev_day(current_date)" class="btn btn-default">Prev</button>

						</div>

						<span>{{current_date | date: 'MMM dd yyyy'}}</span>


						<div class="btn-group" role="group">
							<button ng-click="view_next_day(current_date)" class="btn btn-default">Next</button>
						</div>


						<table class="table table-striped table-bordered" id="expense_tbl">
							<thead>
								<tr>
									<th>No</th>
									<th>Expense Name</th>
									<th>Amount</th>
									<th>Date created</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="e in paginatedExpenses" ng-click="selectExpense(e)">
									<td hidden>{{e.expense_id}}</td>
									<td>{{$index+1}}</td>
									<td>{{e.name}}</td>
									<td>{{e.amount}}</td>
									<td>{{e.date_start | date: 'MMM dd yyyy'}}</td>
									<td><i class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#deleteModal"></i></td>
									<!-- ng-click="removeAccount(u.user_id) -->
								</tr>
							</tbody>
						</table>
						<!-- "paginatedExpenses.length" -->
						<pagination 
						ng-model="currentPage"
						total-items=arr_expenses.length
						max-size="maxSize"  
						boundary-links="true"/>



					</section>












				</div> 		




			</div>
		</div>


		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Remove Expense</h4>
					</div>
					<div class="modal-body">
						This will remove expense permanently. Are you sure you want to continue?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" ng-click="remove_expense()">Yes</button>
					</div>
				</div>
			</div>
		</div>



	</body>
	</html>


