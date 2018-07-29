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


		.show_hide_section {
			display: none;
		}

			/*#action_btn_grp {
				margin-right: 30px;
			}*/

		</style>

		<script>


			var app = angular.module('rcg_jm', ['ui.bootstrap']);

			app.controller('mainCtrl', ['$scope', '$http', '$filter', '$window', function ($scope, $http, $filter, $window){
				$scope.job_id = "<?php echo $job_id?>";

				$scope.arr_progress = new Array;
				$scope.list_progress = [];

				$scope.get_job_progress = function(){
					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_job_progress',
						data: $scope.job_id,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){

						$scope.arr_progress = response.data; 
						$scope.list_progress = $scope.arr_progress;




					}).catch(function(error){
						$scope.result = error;
					});

				}

				$scope.rollback_progress = function(){
					var latest_status = $scope.list_progress[$scope.list_progress.length - 1].status;

					if(latest_status==="Pending"){

					}else{
						$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/rollback_progress',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: $scope.job_id,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$scope.get_job_progress();
							

							
						}).catch(function(error){
							alert(error.data);
						});
					}

					

					//hide modal
					$('#rollbackModal').modal('hide');

					
					
				}


				// $scope.get_job_progress();


				$scope.arr_payments = new Array;
				$scope.list_payments = [];

				$scope.get_job_payments = function(){
					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_job_payments',
						data: $scope.job_id,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){

						$scope.arr_payments = response.data; 
						$scope.list_payments = $scope.arr_payments;
						

					}).catch(function(error){
						$scope.result = error;
					});
				}

				$scope.layout_artist = "";

				$scope.get_job_layout_artist = function(){
					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_layout_artist',
						data: $scope.job_id,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){

						if(response.data!="null"){
							$scope.layout_artist = response.data.first_name + " " + response.data.last_name;	
						}
						
						
						
					}).catch(function(error){
						$scope.result = error;
					});
				}

				$scope.get_job_layout_artist();


				$scope.arr_job_workers = new Array;
				$scope.list_job_workers = [];

				$scope.get_job_workers = function(){
					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/get_job_workers',
						data: $scope.job_id,
						headers: {'Content-type': 'application/x-www-form-urlencoded'}
					}).then(function(response){

						$scope.arr_job_workers = response.data; 
						$scope.list_job_workers = $scope.arr_job_workers;
						

					}).catch(function(error){
						$scope.result = error;
					});
				}



				// $scope.get_job_payments();



				$scope.correct_job = function(){
					$window.location.href = "<?php echo base_url()?>" + 'dashboard/view_job_form/' + $scope.job_id + '/correct';
				}

				$scope.transfer_job = function(){
					$window.location.href = "<?php echo base_url()?>" + 'dashboard/view_job_transfer/' + $scope.job_id;
				}

				$scope.delete_job = function(){


					$http({
						method: 'post',
						url: "<?php echo base_url();?>" + 'dashboard/delete_job',
						 // url: 'http://localhost/codeigniter/dashboard/reg_account',
						 data: $scope.job_id,
						 headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(success){
							$window.location.href = "<?php echo base_url()?>" + 'dashboard/view_dashboard/';

							
						}).catch(function(error){
							alert(error.data);
						});

					//hide modal
					$('#deleteModal').modal('hide');

					
					
				}








				$scope.payment_tbl_action = "Correct";
				$scope.workers_tbl_action = "Correct";


				$scope.correct_payments = function(){

					if($scope.payment_tbl_action === "Correct"){
						$scope.payment_tbl_action = "Save";
					}else if ($scope.payment_tbl_action === "Save"){

						var data = {job_id: $scope.job_id
							,payment_details: $scope.list_payments
						}


						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/correct_payments',
							data: data,
							headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(response){

							// $scope.arr_workers = response.data; 
							// $scope.list_workers = $scope.arr_workers;

							$scope.payment_tbl_action = "Correct";




						}).catch(function(error){
							$scope.result = error;
						});

					}
				}


				// $scope.list_payment_type = [""]
				$scope.list_transaction_type = ['Down payment', 'Balance']
				$scope.list_payment_type = ['Cash', 'Bank deposit', 'Cheque', 'Money transfer']


				
				$scope.add_row_payments = function() {

					var payment = {payment_id:"",job_id:"",transaction_type:"Balance",payment_type:"",amount:"",details:"",date_start:""};

					$scope.list_payments.push(payment);
					
				}



				$scope.add_row_workers = function() {

					var worker = {production_id:"",job_id:"",worker_id:"",details:"",date_start:""};

					$scope.list_job_workers.push(worker);
					
				}

				$scope.arr_workers = new Array;
				$scope.list_workers = [];

				$scope.get_workers = function(){
					$http({
						method: 'get',
						url: "<?php echo base_url();?>" + 'dashboard/get_workers',
						dataType: 'json',
						contentType: "application/json"

					}).then(function(response){

						$scope.arr_workers = response.data; 
						$scope.list_workers = $scope.arr_workers;

					}).catch(function(error){
						$scope.result = error.data;
					})
					;

				}

				$scope.correct_workers = function(){

					if($scope.workers_tbl_action === "Correct"){
						$scope.workers_tbl_action = "Save";
					}else if ($scope.workers_tbl_action === "Save"){

						var data = {job_id: $scope.job_id
							,workers: $scope.list_job_workers
						}

						$http({
							method: 'post',
							url: "<?php echo base_url();?>" + 'dashboard/correct_workers',
							data: data,
							headers: {'Content-type': 'application/x-www-form-urlencoded'}
						}).then(function(response){

							$scope.get_job_workers();

							$scope.workers_tbl_action = "Correct";


						}).catch(function(error){
							$scope.result = error;
						});

						$scope.workers_tbl_action = "Correct"

					}
				}



				


			}]);

		</script>


	</head>

	<body ng-app="rcg_jm" ng-controller="mainCtrl">
		<div class="container-fluid">
			<div class="row" id="top_row">
				<div class="col-md-7 border">
					<a href="<?php echo base_url() . 'dashboard/view_dashboard'?>"><< Go back to dashboard</a>
					<div id="action_btn_grp" class="pull-right">
						<button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#deleteModal">Delete</button>
						<button type="button" class="btn btn-info btn-lg" ng-click="transfer_job()">Transfer</button>
						<!-- <button type="button" class="btn btn-success btn-lg">Update</button> -->
						<button type="button" class="btn btn-primary btn-lg" ng-click="correct_job()">Correct</button>
						<!-- <button type="button" class="btn btn-primary btn-lg">Archive</button> -->


					</div>


				</div>
				<div class="col-md-5 border">
				</div>
			</div>

			<div class="row">
				<div class="col-md-7">
					<div class="pull-right">
						<h5><?php echo 'Received: ' . date('M j Y', strtotime(substr($date_received, 0, 10)))?></h5>
						<h5><?php echo 'Due: ' . date('M j Y', strtotime(substr($date_due, 0, 10)))?></h5>
					</div>
				</div>
				<div class="col-md-5 border">


				</div>

			</div>		


			<div class="row">
				<div class="col-md-6 border">
					<?php 
					echo "<h2 id='category'>$category ($status)</h2>";
					?>

				</div>
				<div class="col-md-6 border">
					<!-- strtotime(substr($date_received, 0, 10)) -->

				</div>
			</div>

			<div class="row">
				<div class="col-md-6 border">
					<?php 
					echo "<h4>Sales: $sales</h4>";
					echo "<h4>Quantity: $qty</h4>";
					echo "<h4>Description:</h4>";
					echo "<p>$description</p>";
					echo '<h4>Customer: ' . $customer_name . '</h4>';
					echo '<h4>Contact no: ' . $customer_contact . '</h4>';

					?>
					<h4 id="layout_artist">Layout Artist: {{layout_artist}}</h4>

				</div>
				<div class="col-md-4 border">
					<!-- strtotime(substr($date_received, 0, 10)) -->

				</div>

				<div class="col-md-2">
				</div>
			</div>



			<div class="row">
				<div class="col-md-6 border">
					<h3>Payment details</h3>
					<div id="payment_details">
						<?php 
						echo "<p>Down Payment: $down_payment</p>";
						echo "<p>Balance: $balance</p>";
						echo "<p>Total Amount: $total_amount</p>";
						?>
					</div>

				</div>
				<div class="col-md-6 border">
					<!-- strtotime(substr($date_received, 0, 10)) -->

				</div>

			</div>


			<script>

				$(document).ready(function(){
					$("#payment_section").on("click", function(){
						$("#payment_history_table").toggleClass("show_hide_section");
						$("#payment_action").toggleClass("show_hide_section");


					});


					$("#progress_section").on("click", function(){
						$("#progress_timeline").toggleClass("show_hide_section");
						$("#elapsed_time").toggleClass("show_hide_section");
						$("#progress_action").toggleClass("show_hide_section");


					});

					$("#workers_section").on("click", function(){
						$("#workers_table").toggleClass("show_hide_section");
						$("#layout_artist").toggleClass("show_hide_section");

						$("#workers_action").toggleClass("show_hide_section");

					});

				});

			</script>

			<div class="row">
				<div class="col-md-7 border">
					<div id="payment_header">

						<div class="pull-left">
							<h3 id="payment_section"><a href="#payment_history_table">Payment history >></a></h3>
						</div>
						<div id="payment_action" class="pull-right show_hide_section">
							<button type="button" class="btn btn-primary btn-lg" ng-click="correct_payments()">{{payment_tbl_action}}</button>
							<button ng-show="payment_tbl_action=='Save'" type="button" class="btn btn-primary btn-lg" ng-click="add_row_payments()">Add Row</button>
						</div>
					</div>

					
					<div id="payment_history_table" class="show_hide_section" ng-init="get_job_payments()">
						<table ng-show="payment_tbl_action=='Correct'" class="table">
							<thead>
								<tr>
									<td>Transaction</td>
									<td>Payment Type</td>
									<td>Amount</td>
									<td>Date</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="p in list_payments">
									<td>{{p.transaction_type}}</td>
									<td>{{p.payment_type}}</td>
									<td>{{p.amount}}</td>
									<td>{{p.date_start| date: 'MMM dd yyyy'}}</td>
								</tr>
							</tbody>
						</table>

						<form ng-show="payment_tbl_action=='Save'">
							<table class="table">
								<thead>
									<tr>
										<td>Transaction</td>
										<td>Payment Type</td>
										<td>Amount</td>
										<!-- <td>Date</td> -->
									</tr>
								</thead>
								<tbody>

									<tr ng-repeat="p in list_payments">
										<td>{{p.transaction_type}}</td>
										<td><select class="form-control" ng-model="p.payment_type" ng-options="t as t for t in list_payment_type"></select></td>
										<td><input ng-model="p.amount" class="form-control" type="text" required="" pattern="[0-9]{1,7}" placeholder="Amount"/></td>
										<!-- <td>{{p.date_start| date: 'MMM dd yyyy'}}</td> -->
									</tr>
								</tbody>
							</table>
						</form>
					</div>

				</div>
				<div class="col-md-5 border">
					
				</div>

				

			</div>


			<div class="row">
				<div class="col-md-7 border">
					<div class="timeline-container">
						<!-- <h3 id="progress_section"><a href="#progress_timeline" style="text-decoration: none;">Progress >></a></h3> -->

						<div id="progress_header">
							<h3 id="progress_section"><a href="#progress_timeline">Progress >></a></h3>
							
							<div id="progress_action" class="pull-right show_hide_section">
								<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#rollbackModal">Rollback</button>
							</div>
						</div>
						
						<div id="progress_timeline" class="timeline show_hide_section" ng-init="get_job_progress()">
							<div class="container right" ng-repeat="t in list_progress" >
								<div class="content list-group">

									<span class="label label-primary">{{t.status}}</span>
									
									<p class="list-group-item-text">Started: {{t.date_start | date: 'MMM dd, yyyy | hh:mm:ss a'}}</p>




								</div>


							</div>
						</div>

					</div>

				</div>
				<div class="col-md-5 border">
					

				</div>

			</div>

			<div class="row">
				<div class="col-md-7 border">
					<div id="payment_header">

						<div class="pull-left">
							<h3 id="workers_section"><a href="#workers">Workers >></a></h3>
							
						</div>
						<div id="workers_action" class="pull-right show_hide_section">
							<button type="button" class="btn btn-primary btn-lg" ng-click="correct_workers()">{{workers_tbl_action}}</button>
							<button ng-show="workers_tbl_action=='Save'" type="button" class="btn btn-primary btn-lg" ng-click="add_row_workers()">Add Row</button>
						</div>
					</div>

					
					<div id="workers_table" class="show_hide_section" ng-init="get_job_workers()">
						<table class="table" ng-show="workers_tbl_action=='Correct'">
							<thead>
								<tr>
									<td>First Name</td>
									<td>Last Name</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="w in list_job_workers">
									<td>{{w.first_name}}</td>
									<td>{{w.last_name}}</td>
								</tr>
							</tbody>
						</table>

						<form ng-show="workers_tbl_action=='Save'">
							<table class="table">
								<thead>
									<tr>
										<td>Name</td>
									</tr>
								</thead>
								<tbody ng-init="get_workers()">
									<tr ng-repeat="p in list_job_workers">
										<td><select class="form-control" ng-model="p.worker_id" ng-options="worker.worker_id as worker.full_name for worker in list_workers"></select></td>
										<!-- <td>{{p.date_start| date: 'MMM dd yyyy'}}</td> -->
									</tr>
								</tbody>
							</table>
						</form>

					</div>

				</div>
				<div class="col-md-5 border">
					

				</div>

			</div>




		</div>

		<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Delete Job</h4>
					</div>
					<div class="modal-body">
						This will remove job permanently. Are you sure you want to continue?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" ng-click="delete_job()">Yes</button>
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade" id="rollbackModal" tabindex="-1" role="dialog" aria-labelledby="rollbackModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Rollback Progress</h4>
					</div>
					<div class="modal-body">
						This will rollback current job progress. Are you sure you want to continue?
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-primary" ng-click="rollback_progress()">Yes</button>
					</div>
				</div>
			</div>
		</div>


	</body>
	</html>