<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Start - Form SubmissionLogger</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<style>
		.card {
			width: 35%;
		}
		
		@media (max-width: 991px) {
			.card {
				width: calc(100% - 30px);
			}
		}
	</style>
</head>
<body class="h-100 py-5 d-flex justify-content-center align-items-center bg-light">
	<div class="card shadow">
		<div class="card-body">
			<h4 class="card-title">Register a password to start</h4>

			<div class="alert alert-info">Password must be 12 characters</div>

			<form method="POST" autocomplete="off">
				<div class="form-group">
					<label for="password_register">Password</label>
					<input type="password" class="form-control" id="password_register" name="password_register" required minlength="12">
				</div>
				<button type="submit" class="btn btn-primary">Start</button>
			</form>
		</div>
	</div>
</body>
</html>