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
					margin-top: 10px;
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

			#top_row {
				margin-top: 30px;
			}

			#payment_details, .container-fluid{
				margin-left: 30px;
			}

			#action_btn_grp {
				margin-right: 30px;
			}

		</style>

		<script>
			app = angular.module('rcg_jm', ['ui.bootstrap']);





			app.controller('mainCtrl', ['$scope', '$http', '$filter',



				function ($scope, $http, $filter){
					$scope.job = {
					};

					$scope.arr_categories = new Array;
					$scope.list_categories = [];

					$scope.action = "<?php echo $action?>";
					$scope.job.job_id = "<?php echo $job_id?>";

				// $scope.job = {};

				// $scope.update_job_details = function(){
				// 	if($scope.action == "correct"){
				// 		$http({
				// 			method: 'post',
				// 			data: $scope.job,
				// 			url: "<?php echo base_url()?>" + 'dashboard/update_job_details/',
				// 			headers: {'Content-type': 'application/x-www-form-urlencoded'}
				// 		}).success(function(){

				// 		});


				// 	}
				// }

				$scope.get_job_details = function(){
					$http({
						method: 'post',
						data: $scope.job.job_id,
						url: "<?php echo base_url()?>" + 'dashboard/get_job_details_by_id/',
						dataType: 'json',
						contentType: "application/json"
						

					}).then(function(response){

						$scope.job = response.data;


					}).catch(function(response){
						console.log(response);

					});

				}

				$scope.get_job_details();

				

				

				


				$scope.get_categories = function(){
					$http({
						method: 'get',
						url: "<?php echo base_url();?>" + 'dashboard/get_categories',
						dataType: 'json',
						contentType: "application/json"

					}).then(function(response){

						$scope.arr_categories = response.data; 
						$scope.list_categories = $scope.arr_categories;

					}).catch(function(error){
						$scope.result = error.data;
					})
					;


				}

				$scope.get_categories();

				$scope.get_sales = function(){
					$http({
						method: 'get',
						url: "<?php echo base_url();?>" + 'dashboard/get_accounts',
						dataType: 'json',
						contentType: "application/json"

					}).then(function(response){

						$scope.arr_sales = response.data; 
						$scope.list_sales = $scope.arr_sales;

					}).catch(function(error){
						$scope.result = error.data;
					})
					;


				}

				$scope.get_sales();


				// $scope.get_job_layout_artist = function(){
				// 	$http({
				// 		method: 'post',
				// 		url: "<?php echo base_url();?>" + 'dashboard/get_layout_artist',
				// 		data: $scope.job.job_id,
				// 		headers: {'Content-type': 'application/x-www-form-urlencoded'}
				// 	}).then(function(response){

						
				// 		$scope.job.artist_id = response.data.artist_id;
						
				// 	}).catch(function(error){
				// 		$scope.result = error;
				// 	});
				// }



				$scope.arr_artists = new Array;
				$scope.list_artists = [];

				$scope.get_artists = function(){
					$http({
						method: 'get',
						url: "<?php echo base_url();?>" + 'dashboard/get_artists',
						dataType: 'json',
						contentType: "application/json"

					}).then(function(response){

						

						$scope.arr_artists = response.data; 
						$scope.list_artists = $scope.arr_artists;


						// var artist = {artist_id:"",first_name:"",worker_id:"",details:"",date_start:""};

						// $scope.list_artists.push(artist);

					}).catch(function(error){
						
					})
					;


				}

				$scope.get_artists();


				$scope.update_job_details = function(){
					if($scope.action === 'correct'){
						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/correct_job_details',
							data: $scope.job,
							headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){

							if(success.data=="success"){
								$scope.result = "Job updated!";	
							}else if(success.data!=null){
								$scope.result = success.data;	
							}
							

							$('.alert').show();
						}).catch(function(error){
							$scope.result = error.data;

							$('.alert').show();
						});
					}
				}

				

				// $scope.get_job_layout_artist();



			}]);
		</script>
	</head>
	<body ng-app="rcg_jm" ng-controller="mainCtrl">
		
		<div class="container-fluid">
			<div class="row" id="top_row">
				<div class="col-md-7 border">
					<a href='<?php echo base_url() . "dashboard/view_dashboard"?>'>Dashboard</a>
					<a href='<?php echo base_url() . "dashboard/view_job_details/$job_id"?>'> > Details</a>
					<div id="action_btn_grp" class="pull-right">
						<!-- <button type="button" class="btn btn-danger btn-lg">Cancel</button> -->
						<input type="submit" class="btn btn-primary btn-lg" form="job_form" value="Save" ng-click="update_job_details()"/>
						<!-- <input type="submit" class="btn btn-primary btn-lg" form="job_form" value="Save" ng-click="update_job_details()"/> -->
						<!-- <button type="button" class="btn btn-primary btn-lg" form="job_form">Save</button> -->
					</div>
				</div>
				<div class="col-md-5 border">
				</div>
			</div>

			

			<div class="row">
				<div class="col-md-4 col-xs-8 border">
					<div class="reg-account">
						<div hidden class="alert alert-success fade in" role="alert">{{result}}</div>
						<h3> {{action | uppercase}} DETAILS </h3>
						<!-- {{job.artist_id}} -->

						<form id="job_form" role="form">
							<!-- <div class="form-group">
								<input type="text" required="" placeholder="" ng-model="job.first_name" size="40"/>
							</div> -->
							
							<div class="form-group">
								<label for="category_lov">Category</label>
								<select class="form-control" id="category_lov" ng-model="job.category_id" ng-options="category.category_id as category.name for category in list_categories"></select>
								
							</div>

							<div class="form-group">
								<label for="sales_lov">Sales</label>
								<select class="form-control" id="sales_lov" ng-model="job.sales_id" ng-options="sales.user_id as sales.full_name for sales in list_sales"></select>
								
							</div>

							<div class="form-group">
								<label for="qty">Quantity</label>
								<input class="form-control" type="text" required="" id="qty" pattern="[0-9]{1,7}" placeholder="Enter Quantity" ng-model="job.qty"/>
							</div>

							<div class="form-group">
								<label for="desc">Description</label>
								<input class="form-control" type="text" id="desc" pattern="[A-Za-z\/ ]{4,100}" placeholder="Enter Description" ng-model="job.description"/>
							</div>

							<div class="form-group">
								<label for="customer_name">Customer Name</label>
								<input class="form-control" type="text" id="customer_name" pattern="[A-Za-z\/ ]{4,100}" placeholder="Enter Customer Name" ng-model="job.customer_name"/>
							</div>

							<div class="form-group">
								<label for="contact">Contact</label>
								<input class="form-control" type="text" id="contact" placeholder="Enter Contact details" ng-model="job.customer_contact"/>
							</div>

							<div class="form-group">
								<label for="customer_name">Layout Artist</label>
								<select id="artist_lov" class="form-control" ng-model="job.artist_id" ng-options="artist.artist_id as artist.full_name for artist in list_artists"></select>
							</div>

							<h3>Payment details</h3>

							<div class="form-group">
								<label for="total_amount">Total Amount</label>
								<input class="form-control" type="text" required="" id="total_amount" pattern="[0-9]{1,7}" placeholder="Enter Total Amount" ng-model="job.total_amount"/>
							</div>


						</form>
					</div>

				</div>
				<div class="col-md-8 border">
				</div>



			</div>
		</div>
	</body>


</body>
</html>