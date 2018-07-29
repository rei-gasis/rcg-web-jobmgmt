	<!DOCTYPE html>
	<html>
	<head>

		<!-- bootstrap v4 
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url('public/css/bootstrapv3.css')?>"/>

	<script src="<?php echo base_url('public/js/jquery.js')?>"></script>
	<script src="<?php echo base_url('public/js/bootstrap.js')?>"></script>


	<!-- chart js -->
	<script src="<?php echo base_url('public/js/chart.js')?>"></script>

	<!-- angular -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script> 
	

	<style>

		

		.timeline-container {
			box-sizing: border-box;
		}
		

		/* Set a background color */


		
		.timeline {
			position: relative;
			max-width: 1200px;
			margin: 0 auto;
			left: -100px;
		}

		/* The actual timeline (the vertical ruler) */
		.timeline::after {
			content: '';
			position: absolute;
			width: 12px;
			background-color: #EAEAEA;
			top: 0;
			bottom: 0;
			left: 25%;
			margin-left: -3px;
		}

		/* Container around content */
		.container {
			padding: 10px 40px;
			position: relative;
			background-color: inherit;
			width: 50%;
		}

		/* The circles on the timeline */
		.container::after {
			content: '';
			position: absolute;
			width: 40px;
			height: 40px;
			right: 0px;
			background-color: #337AB7;
			border: 4px solid #F9F9F9;
			top: 15px;
			border-radius: 50%;
			z-index: 1;

		}

		/* Place the container to the left */
		.left {
			left: 0;
		}

		/* Place the container to the right */
		.right {
			left: 0%;
		}

		/* Add arrows to the left container (pointing right) */
		/*.left::before {
			content: " ";
			height: 0;
			position: absolute;
			top: 22px;
			width: 0;
			z-index: 1;
			right: 30px;
			border: medium solid white;
			border-width: 0 0 10px 10px;
			border-color: transparent transparent transparent #F9F9F9;	
		}*/

		/* Add arrows to the right container (pointing left) */
		.right::before {
			content: " ";
			height: 0;
			position: absolute;
			top: 22px;
			width: 0;
			z-index: 1;
			left: 30px;
			border: medium solid #F9F9F9;
			border-width: 10px 10px 10px 0;
			border-color: transparent #EAEAEA transparent transparent;
		}

		/* Fix the circle for containers on the right side */
		.right::after {
			left: -16px;
		}

		/* The actual content */
		.content {
			padding: 10px 10px;
			background-color: #EAEAEA;
			position: relative;
			border-radius: 6px;
		}

		/* Media queries - Responsive timeline on screens less than 600px wide */
		@media all and (max-width: 600px) {
			/* Place the timelime to the left */
			.timeline::after {
				left: 31px;
			}

			/* Full-width containers */
			.container {
				width: 100%;
				padding-left: 70px;
				padding-right: 25px;
			}

			/* Make sure that all arrows are pointing leftwards */
			.container::before {
				left: 60px;
				border: medium solid white;
				border-width: 10px 10px 10px 0;
				border-color: transparent white transparent transparent;
			}

			/* Make sure all circles are at the same spot */
			.left::after, .right::after {
				left: 15px;
			}

			/* Make all right containers behave like the left ones */
			.right {
				left: 0%;
			}
		}

		tr {
			cursor: pointer;

		}


	</style>


	<script>
		var app = angular.module('jobs', []);
		app.controller('mainCtrl', ['$scope', function ($scope){
  		//
  		/*$scope.filterJob = function(){
  			$jobFilter.

  		}*/

  		$scope.jobFilter = '';
  		$scope.filterJob = function(jobFilter){
  			if(jobFilter=='All Jobs') $scope.jobFilter = '';
  			else $scope.jobFilter = jobFilter;
  		}

  		$scope.searchByCategory = '';
  		$scope.setCategoryFilter = function(searchByCategory){
  			$scope.searchByCategory = searchByCategory;

  		}

  		

  		
  		$scope.jobList = [
  		{
  			jobId: 1,
  			category: 'Plaque',
  			qty: 10,
  			sales: 'Janet',
  			downPayment: 3000,
  			totalAmount: 10000,
  			customer: 'none',
  			date: '20180123T00:00:00',
  			//date: new Date(),
  			status: 'Layout'
  		},


  		{
  			jobId: 2,
  			category: 'Plaque',
  			qty: 3,
  			sales: 'Anne',
  			downPayment: 2000,
  			totalAmount: 4000,
  			customer: 'Jose Labanan',
  			date: '20180124T00:00:00',
  			status: 'Production'
  		},

  		{
  			jobId: 3,
  			category: 'Tarpaulin',
  			qty: 1,
  			sales: 'Anne',
  			downPayment: 150,
  			totalAmount: 150,
  			customer: 'none',
  			date: '20180124T00:00:00',
  			status: 'Production'
  		},

  		{
  			jobId: 4,
  			category: 'Magazine',
  			qty: 100,
  			sales: 'Rey',
  			downPayment: 10000,
  			totalAmount: 15000,
  			customer: 'none',
  			date: '20180124T00:00:00',
  			status: 'Layout'
  		},

  		{
  			jobId: 5,
  			category: 'Plaque',
  			qty: 6,
  			sales: 'Hilda',
  			downPayment: 3000,
  			totalAmount: 8000,
  			customer: 'Jollibee',
  			date: '20180124T00:00:00',
  			status: 'Claimed'
  		},

  		{
  			jobId: 6,
  			category: 'Tarpaulin',
  			qty: 2,
  			sales: 'Winnie',
  			downPayment: 900,
  			totalAmount: 900,
  			customer: 'none',
  			date: '20180126T00:00:00',
  			status: 'Pending'
  		},

  		{
  			jobId: 7,
  			category: 'Calling card',
  			qty: 200,
  			sales: 'Winnie',
  			downPayment: 900,
  			totalAmount: 900,
  			customer: 'Mcdonalds',
  			date: '20180125T00:00:00',
  			status: 'Completed'
  		}

  		];
  		

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

  		

  		
  		

  	}]);


		// $(document).ready(function(){
		// 	$('.sales-body').hide();


		// 	$('.sales-nav').click(function(){
		// 	//$('.sales-body').hide();
		// 	// console.log('yeah');

		// 	$('.main-dashboard').hide();
		// 	$('.dashboard-nav').removeClass('active');
		// 	$('.sales-body').show();
		// 	$(this).addClass('active');



		// });

		// 	$('.dashboard-nav').click(function(){
		// 	//$('.sales-body').hide();
		// 	// console.log('yeah');

		// 	$('.main-dashboard').show();
		// 	$(this).addClass('active');
		// 	$('.sales-body').hide();
		// 	$('.sales-nav').removeClass('active');


		// });



		// });

	</script>


</head>
<body>
	<!-- Dashboard navbar-->
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<a class="navbar-brand" href="#">RCG Job Management</a>
		</div>
	</nav>




	<div class="container-fluid" ng-app="jobs" ng-controller="mainCtrl">

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
				<div class="container-body sales-body">
					<canvas id="myChart" width="200" height="100"></canvas>

					<script>
						var ctx = document.getElementById('myChart').getContext('2d');
						var chart = new Chart(ctx, {

							type: 'bar',


							data: {
								labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
								datasets: [{
									label: "2017 Plaque sales by Quantity",
									backgroundColor: 'rgb(255, 0, 0)',
									borderColor: 'rgb(255, 99, 132)',
									data: [15, 8, 10, 20, 13, 30, 21, 36, 105, 65, 23, 180],
								}]
							},


							options: {}
						});
					</script>
				</div>
				

				


			</div> 		

			


		</div>
	</div>

</body>
</html>


