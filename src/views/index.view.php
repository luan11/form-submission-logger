<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Logs - Form SubmissionLogger</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>
	<header>
		<nav class="navbar navbar-dark bg-dark shadow">
			<div class="container">
				<div class="row w-100">
					<form method="post" class="ml-auto">
						<input type="hidden" name="_logout" value="true">
						<button type="submit" class="btn btn-danger">Logout</button>
					</form>
				</div>
			</div>
		</nav>
	</header>

	<main class="py-5">
		<div class="container">
			<div class="row d-block">
				<?php if(empty($logs)): ?>
					<div id="alerts" class="mb-2">
						<div class="alert alert-info" role="alert">
							No logs found...
						</div>
					</div>
				<?php endif; ?>

				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">Data</th>
							<th scope="col">Date</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($logs as $log): ?>
							<tr>
								<td>
									<?php foreach($log['data'] as $key => $value): ?>
										<p><b><?php echo $key; ?>:</b> <?php echo $value; ?></p>
									<?php endforeach; ?>
								</td>
								<td><?php echo $log['date']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</main>
</body>
</html>