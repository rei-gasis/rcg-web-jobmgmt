	<!DOCTYPE html>
	<html>
	<head>

		<title>RCG Job Management System</title>
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
			var app = angular.module('jobs', ['ui.bootstrap']);

			app.controller('mainCtrl', ['$scope', '$http', '$filter', '$window', function ($scope, $http, $filter, $window){
				
				$scope.jobFilter = '';
				$scope.job = {};
				$scope.filterJob = function(jobFilter){
					if(jobFilter=='All Jobs') $scope.jobFilter = '';
					else $scope.jobFilter = jobFilter;
				}

				$scope.searchByCategory = '';
				$scope.setCategoryFilter = function(searchByCategory){
					$scope.searchByCategory = searchByCategory;

				}


				$scope.arr_jobs = new Array;
				$scope.list_jobs = [];

				$scope.currentPage = 1;
				$scope.numPerPage = 10;
				$scope.maxSize = 5;

				var begin = (($scope.currentPage - 1) * $scope.numPerPage)
				, end = begin + $scope.numPerPage;



				$scope.getJobs = function(){
					$http({
						method: 'get',
						url: "<?php echo base_url();?>" + 'dashboard/get_jobs',
						dataType: 'json',
						contentType: "application/json"
					}).then(function(response){

						$scope.arr_jobs = response.data; //convert to array
						$scope.list_jobs = $scope.arr_jobs;


						/*paginate*/
						$scope.paginatedJobs = $scope.arr_jobs.slice(begin, end);


					}).catch(function(error){
						$scope.result = error;
					});


				}

				// $scope.getJobs();

				$scope.$watch('currentPage + numPerPage', function() {
					var begin = (($scope.currentPage - 1) * $scope.numPerPage)
					, end = begin + $scope.numPerPage;


					$scope.paginatedJobs = $scope.arr_jobs.slice(begin, end);

				});


				$scope.viewJobDetails = function(job){
					$scope.job = job;

					$window.location.href = "<?php echo base_url()?>" + 'dashboard/view_job_details/' + $scope.job.job_id;


				}



				$scope.jobStatus = [
				'All Jobs',
				'Pending',
				'Layout',
				'Production',
				'Completed',
				'Claimed'

				];



				$selectedJob = 0;


				$scope.selectJob = function(selectedJob){
					$scope.selectedJob = selectedJob.jobId;

				};


				$scope.timeline = [
				{
					jobId: 1,
					dateStarted: '20180124T00:00:00',
					dateEnded: '20180125T00:00:00',
					status: 'Layout',
					remarks: 'test'

				},

				{
					jobId: 1,
					dateStarted: '20180125T00:00:00',
					dateEnded: '20180126T00:00:00',
					status: 'Production'

				},

				{
					jobId: 1,
					dateStarted: '20180126T00:00:00',
					dateEnded: '20180127T00:00:00',
					status: 'Completed'

				},

				{
					jobId: 2,
					dateStarted: '20180126T00:00:00',
					dateEnded: '20180127T00:00:00',
					status: 'Pending'

				}



				];


				// $scope.current_date = myDate.getDate();

				// $scope.current_date = moment();
				// $scope.view_next_day = function(){
				// 	$scope.current_date = $scope.current_date.add(1, 'days');
				// 	// $scope.current_date = $scope.current_date.format('MMM DD YYYY');


				// }

				// $scope.view_next_day();

				


				$scope.current_date = new Date();

				$scope.view_next_day = function(date){
					var new_date = new Date(date);
					new_date.setDate(new_date.getDate() + 1);

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_jobs_by_date',
						data: new_date,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){
						$scope.arr_jobs = response.data; //convert to array
						$scope.list_jobs = $scope.arr_jobs;
						
						$scope.paginatedJobs = $scope.arr_jobs.slice(begin, end);

						$scope.get_total_sales($scope.current_date);

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
						url: "<?php echo base_url();?>" + 'dashboard/get_jobs_by_date',
						data: new_date,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){
						$scope.arr_jobs = response.data; //convert to array
						$scope.list_jobs = $scope.arr_jobs;
						
						$scope.paginatedJobs = $scope.arr_jobs.slice(begin, end);

						$scope.get_total_sales($scope.current_date);

					}).catch(function(error){
						$scope.result = error;
					})
					;

					$scope.current_date = new_date;



				}

				$scope.view_sales_today = function(date){
					// var new_date = new Date(date);
					// new_date.setDate(new_date.getDate());

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_jobs_by_date',
						data: $scope.current_date,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){
						$scope.arr_jobs = response.data; //convert to array
						$scope.list_jobs = $scope.arr_jobs;
						
						$scope.paginatedJobs = $scope.arr_jobs.slice(begin, end);

					}).catch(function(error){
						$scope.result = error;
					})
					;

					// $scope.current_date = new_date;



				}

				$scope.total_sales = "";

				$scope.get_total_sales = function(date){
					// var new_date = new Date(date);
					// new_date.setDate(new_date.getDate());

					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_total_sales_by_date',
						data: $scope.current_date,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){
						$scope.total_sales = response.data;

						// $scope.arr_jobs = response.data; //convert to array
						// $scope.list_jobs = $scope.arr_jobs;
						
						// $scope.paginatedJobs = $scope.arr_jobs.slice(begin, end);

					}).catch(function(error){
						// $scope.result = error;
					})
					;

					// $scope.current_date = new_date;



				}

				$scope.get_total_sales();




			}]);




		</script>


	</head>



<body ng-app="jobs" ng-controller="mainCtrl">

	<!-- context menu -->
	<!-- <ul id="contextMenu" class="dropdown-menu" role="menu" style="display:none" >
		<li><a tabindex="-1" href="#">View</a></li>
		<li><a tabindex="-1" href="#">Transfer</a></li>
	</ul> -->
	
	<!-- Dashboard navbar-->
	<nav class="navbar navbar-default">
		<div class="container-fluid">

			<div class="navbar-header">
				<a class="navbar-brand" href="#">RCG Job Management System</a>
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

			<main class="col-md-9 border">
				<section class="row">
					<h3> Jobs </h3>
				</section>

				<section class="row">
					<label>Filter by Status</label>
					<div class="btn-group" role="group">
						<button id="status-btn" ng-repeat="button in jobStatus" ng-click="filterJob(button)" class="btn btn-default">{{button}}</button>
					</div>
				</section>

				<section class="row" style="margin-top:5px;">
					<!--<label style="display: inline-block;">View by day</label>-->
					<div class="btn-group" role="group">
						<button ng-click="view_prev_day(current_date)" class="btn btn-default">Prev</button>
						<!-- <button ng-click="view_next_day()" class="btn btn-default">{{current_date | date: 'MMM dd yyyy'}}</button> -->
						<!-- <button ng-click="view_next_day()" class="btn btn-default">{{current_date}}</button> -->
					</div>

					<span>{{current_date | date: 'MMM dd yyyy'}}</span>
					<!-- <span>{{current_date}}</span> -->

					<div class="btn-group" role="group">
						<button ng-click="view_next_day(current_date)" class="btn btn-default">Next</button>
						<!-- <button ng-click="view_next_day()" class="btn btn-default">{{current_date | date: 'MMM dd yyyy'}}</button> -->
						<!-- <button ng-click="view_next_day()" class="btn btn-default">{{current_date}}</button> -->
					</div>

					<div class="input-group pull-right" style="margin-right:20px;">
						<!-- <i class="fa fa-search"></i><input type="text" ng-model="searchByCategory" placeholder="Search by category" />		 -->
						<h5>Total Sales: {{total_sales}}</h5>

					</div>

				</section>

				<section class="row">


					<table id="tblJob" class="table table-striped table-hover" ng-init="view_sales_today()">
						<thead>
							<tr>
								<th>No</th>
								<th>Category</th>
								<th>Qty</th>
								<th>Total Amount</th>
								<th>Date Received</th>
								<th>Due Date</th>
								<th>Sales</th>
								<th>Status</th>
							</tr>
						</thead>



						<tbody>
							
							<tr ng-repeat="j in paginatedJobs | filter: {status: jobFilter}" ng-click="selectJob(j)" ng-dblclick="viewJobDetails(j)">
								<td>{{$index+1}}</td>
								<td>{{j.category}}</td>
								<td>{{j.qty}}</td>
								<!-- <td>{{j.totalAmount - j.downPayment}}</td> <!-- derived-->
								<td>{{j.total_amount}}</td>
								<td>{{j.date_received | date: 'MMM dd yyyy'}}</td>
								<td>{{j.date_due | date: 'MMM dd yyyy'}}</td>
								<td>{{j.sales}}</td>
								<td>{{j.status}}</td>

							</tr>

						</tbody>


							<!-- <tbody ng-show="searchByCategory != ''" >
								<tr ng-repeat="j in jobList | filter: {category: searchByCategory}" ng-click="selectJob(j)">
									<td>{{$index+1}}</td>
									<td>{{j.category}}</td>
									<td>{{j.qty}}</td>
									<td>{{j.sales}}</td>
									<td>{{j.downPayment}}</td>
									<td>{{j.totalAmount - j.downPayment}}</td> <!-- derived-->
									<!-- <td>{{j.totalAmount}}</td>
									<td>{{j.customer}}</td>
									<td>{{j.date | date: 'dd/MM/yyyy'}}</td>
									<td>{{j.status}}</td>

								</tr>	

							</tbody> -->


						</table>


					</section>



					<div class="container-body main-dashboard">
















					<!-- <div class="form-group pull-left" style="display: block">
						<input type="text" placeholder="Search" ng-model="category.cat" ng-change="search_category(category.cat)"/>
						<span class="glyphicon glyphicon-open" aria-hidden="true"></span>
					</div> -->

					




				</div> <!-- END OF MAIN SECTION -->


			</main> 		




		</div>
	</div>

	<!-- <script>
	(function ($, window) {

		$.fn.contextMenu = function (settings) {

			return this.each(function () {


				$(this).on("contextmenu", function (e) {

					if (e.ctrlKey) return;


					var $menu = $(settings.menuSelector)
					.data("invokedOn", $(e.target))
					.show()
					.css({
						position: "absolute",
						left: getMenuPosition(e.clientX, 'width', 'scrollLeft'),
						top: getMenuPosition(e.clientY, 'height', 'scrollTop')
					})
					.off('click')
					.on('click', 'a', function (e) {
						$menu.hide();

						var $invokedOn = $menu.data("invokedOn");
						var $selectedMenu = $(e.target);

						settings.menuSelected.call(this, $invokedOn, $selectedMenu);
					});

					return false;
				});

            //make sure menu closes on any click
            $('body').click(function () {
            	$(settings.menuSelector).hide();
            });
        });

			function getMenuPosition(mouse, direction, scrollDir) {
				var win = $(window)[direction](),
				scroll = $(window)[scrollDir](),
				menu = $(settings.menuSelector)[direction](),
				position = mouse + scroll;


				if (mouse + menu > win && menu < mouse) 
					position -= menu;

				return position;
			}    

		};
	})(jQuery, window);

	$("#tblJob").contextMenu({
		menuSelector: "#contextMenu",
		menuSelected: function (invokedOn, selectedMenu) {
			var msg = "test";
			alert(msg);
		}
	});

</script> -->

</body>
</html>