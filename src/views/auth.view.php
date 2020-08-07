<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Auth - Form SubmissionLogger</title>
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
			<h4 class="card-title">Insert registered password to see logs</h4>

			<form method="POST" autocomplete="off">
				<div class="form-group">
					<label for="password_check">Password</label>
					<input type="password" class="form-control" id="password_check" name="password_check" required>
				</div>
				<button type="submit" class="btn btn-primary">Authenticate</button>
			</form>
		</div>
	</div>
</body>
</html>