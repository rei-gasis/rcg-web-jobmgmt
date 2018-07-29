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



		$scope.arr_categories = new Array;
		$scope.list_categories = [];

		$scope.currentPage = 1;
		$scope.numPerPage = 10;
		$scope.maxSize = 5;

		var begin = (($scope.currentPage - 1) * $scope.numPerPage)
		, end = begin + $scope.numPerPage;


		$scope.get_categories = function(){
			$http({
				method: 'get',
				url: "<?php echo base_url();?>" + 'dashboard/get_categories',
				dataType: 'json',
				contentType: "application/json"
			}).then(function(response){

				$scope.arr_categories = response.data; //convert to array
				$scope.list_categories = $scope.arr_categories;


				/*paginate*/
				$scope.paginatedCategories = $scope.arr_categories.slice(begin, end);


			}).catch(function(error){
				$scope.result = error;
			})
			;

			
		}


				/* 1. get records
				   2. assign to array
				   3. slice array

				   */



				   $scope.get_categories();


				   // $scope.paginatedCategories = [];


				   $scope.$watch('currentPage + numPerPage', function() {
				   	var begin = (($scope.currentPage - 1) * $scope.numPerPage)
				   	, end = begin + $scope.numPerPage;


				   	$scope.paginatedCategories = $scope.arr_categories.slice(begin, end);

				   });



				   $scope.category = {


				   };





				   $scope.action = "Register";

				   $scope.register_update_category = function(){
				   	$result = null;



				   	if($scope.action === 'Register'){

						//instantiate date_created
						$scope.category.date_created = new Date();

						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/register_category',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: $scope.category,
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
							url: "<?php echo base_url();?>" + 'dashboard/update_category',
							data: $scope.category,
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


					$scope.get_categories();
					
					//reset form
					$scope.category = {};
					
				}

				

				$scope.reset_form = function(){
					$scope.category = {};
					$scope.action = 'Register';
				}

				$scope.selectedCategory = null;
				$scope.selectCategory = function(category) {
					$scope.result = "";
					$scope.category = category;
					$scope.action = "Update";



					$('.alert').hide();

				}



				$scope.remove_category = function(){
					var category_id = $scope.category.category_id;

					// console.log(category_id);
					// $('#deleteModal').modal('hide');

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/remove_category',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: category_id,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.result = success.data;

							$('.alert').show();

							$scope.get_categories();
						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});

					//hide modal
					$('#deleteModal').modal('hide');

					$scope.action = "Register";

					$scope.get_categories();
					
					//reset form
					$scope.category = {};
				}

				$scope.search_category = function(){
					var category = $scope.category.cat;

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/search_category',
						data: category,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){
						$scope.arr_categories = response.data; //convert to array
						$scope.list_categories = $scope.arr_categories;
						
						$scope.paginatedCategories = $scope.arr_categories.slice(begin, end);

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
							<h3> Register Category </h3>
							<form id="category_form" role="form" ng-submit="register_update_category()">
								<div class="form-group">
									<input type="text" required="" pattern="[A-Za-z\/ ]{4,100}" placeholder="Category name" ng-model="category.name"/>
								</div>
								<button type="submit" class="btn btn-default" >{{action}}</button>
								<button type="submit" class="btn btn-default" ng-click="reset_form()">Reset</button>
							</form>
						</div>

						<div>

							<h3>Categories</h3>
							<div class="form-group pull-right">
								<input type="text" placeholder="Search" ng-model="category.cat" ng-keyup="search_category(category.cat)"/>
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							</div>
							

							<!-- <pagination 
							ng-model="currentPage"
							total-items="paginatedCategories.length"
							max-size="maxSize"  
							boundary-links="true"/> -->

							<table class="table table-striped table-bordered" id="category_tbl">
								<thead>
									<tr>
										<th>No</th>
										<th>Category</th>
										<th>Date created</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr ng-repeat="c in paginatedCategories" ng-click="selectCategory(c)">
										<td hidden>{{c.category_id}}</td>
										<td>{{$index+1}}</td>
										<td>{{c.name}}</td>
										<td>{{c.date_created | date: 'MMM dd yyyy'}}</td>
										<td><i class="glyphicon glyphicon-remove" data-toggle="modal" data-target="#deleteModal"></i></td>
										<!-- ng-click="removeAccount(u.user_id) -->
									</tr>
								</tbody>
							</table>
							<!-- "paginatedCategories.length" -->
							<pagination 
							ng-model="currentPage"
							total-items=arr_categories.length
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
						<h4 class="modal-title" id="myModalLabel">Remove Category</h4>
					</div>
					<div class="modal-body">
						This will remove category permanently. Are you sure you want to continue?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" ng-click="remove_category()">Yes</button>
					</div>
				</div>
			</div>
		</div>

		

	</body>
	</html>


