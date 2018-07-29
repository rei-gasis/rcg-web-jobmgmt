<!DOCTYPE html>
<html>
<head>
	<title>RCG Job Management</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<!-- chart js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

	<!-- angular -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script> 

	<style>
	/*@CHARSET "UTF-8";*/
/*
over-ride "Weak" message, show font in dark grey
*/



	.progress-bar {
		color: #333;
	} 

	/*
	Reference:
	http://www.bootstrapzen.com/item/135/simple-login-form-logo/
	*/

	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		outline: none;
	}

	.form-control {
		position: relative;
		font-size: 16px;
		height: auto;
		padding: 10px;
		@include box-sizing(border-box);

		&:focus {
			z-index: 2;
		}
	}

	body {
		background: url("<?php echo base_url() . 'public/images/login_bg.jpg'?>") no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}

	.login-form {
		margin-top: 60px;
	}

	form[role=login] {
		color: #5d5d5d;
		background: #f2f2f2;
		padding: 26px;
		border-radius: 10px;
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
	}
	form[role=login] img {
		display: block;
		margin: 0 auto;
		margin-bottom: 35px;
	}
	form[role=login] input,
	form[role=login] button {
		font-size: 18px;
		margin: 16px 0;
	}
	form[role=login] > div {
		text-align: center;
	}

	.form-links {
		text-align: center;
		margin-top: 1em;
		margin-bottom: 50px;
	}
	.form-links a {
		color: #fff;
	}

</style>

<script>
	$(document).ready(function(){
		$('form > #user_name').focus();

	});
</script>


</head>
<body>

	<script>
		var app = angular.module('rcgjm', []);
		app.controller('loginCtrl', ['$scope', '$http', function($scope, $http){


			

			$scope.user = {
				// user_name: 'rcg_admin',
				// password: 'admin1'
			};


			$scope.login = function(){
				$http({
					method: 'post',
					url: "<?php echo base_url()?>" + 'login/login_user',
					data: $scope.user,
					headers: {'Content-type': 'application/x-www-form-urlencoded'}

				}).then(function(success){
					window.location.href = "<?php echo base_url()?>" + 'dashboard/view_dashboard'
					// alert(success.data);
				}).catch(function(error){
					alert(error.data);
				});
			};

		}]);
	</script>

	<div class="container" ng-app="rcgjm" ng-controller="loginCtrl">

		<div class="row" id="pwd-container">
			<div class="col-md-4"></div>

			<div class="col-md-4">
				<section class="login-form">	
					<form ng-submit="login()" role="login">
						<img src="<?php echo base_url() . 'public/images/company_logo.png'?>" class="img-responsive" alt="" />
						<input type="text" name="email" placeholder="Username" required ="" class="form-control input-lg" ng-model="user.user_name" id="user_name"/>

						<input type="password" class="form-control input-lg" id="password" placeholder="Password" required="" ng-model="user.password"/>


						<!-- <div class="pwstrength_viewport_progress"></div> -->


						<button type="submit" name="go" class="btn btn-lg btn-primary btn-block">Sign in</button>
						<!-- <div>
							<a href="#">Create account</a> or <a href="#">reset password</a>
						 --></div>

					</form>

					<!-- <div class="form-links">
						<a href="#">www.website.com</a>
					 --></div>
				</section>  
				<p>{{result}};
			</div>

			<div class="col-md-4"></div>


		</div>

		<!-- <p>
			<a href="http://validator.w3.org/check?uri=http%3A%2F%2Fbootsnipp.com%2Fiframe%2FW00op" target="_blank"><small>HTML</small><sup>5</sup></a>
			<br>
			<br>

		 --></p>     


	</div>
</body>
</html>