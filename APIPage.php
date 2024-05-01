<!DOCTYPE html>
<html>
	<?php
		$sTitle = "API Interface Page";
	?>
	<head>
		<?php
			include 'headContents.php';
		?>
		<link rel="stylesheet" href="APIPage.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script>
			$(document).ready(function() {
				$("#getTotalProcessRate").on('click', getTotalProcessRate);
			});
		</script>
	</head>
	<body>
		<?php
			include 'navbar.php';
		?>
		<h1 id="pageTitle">API Interface</h1>
		<table>
			<tbody>
				<tr>
					<th colspan=4><h3>Add Color</h3></th>
				</tr>
				<tr>
					<form method="POST" id="addColorForm">
						<td colspan=2>
							<input type="text" id="addName" value="new color name" maxlength="42" name="addName">
							<div class="ErrorContainer">
								<svg id="ANErr" class="ErrorIcon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path>
									<path d="M11 7h2v7h-2zm0 8h2v2h-2z"></path>
								</svg>
							</div>
						</td>
						<td>
							<input type="color" id="addSelector" name="addColor"></input>
							<div class="ErrorContainer">
								<svg id="ASErr" class="ErrorIcon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path>
									<path d="M11 7h2v7h-2zm0 8h2v2h-2z"></path>
								</svg>
							</div>
						</td>
						<td><input type="submit" id="addSubmit" value="Add"></input></td>
					</form>
				</tr>
				<tr>
					<th colspan=4><h3>Edit Color</h3></th>
				</tr>
				<tr>
					<form method="POST" id="edtColorForm">
						<td>
							<select id="edtList" name="edtList">
							</select>
						</td>
						<td>
							<input type="text" id="edtName" value="" maxlength="42" name="edtName">
							<div class="ErrorContainer">
								<svg id="ENErr" class="ErrorIcon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path>
									<path d="M11 7h2v7h-2zm0 8h2v2h-2z"></path>
								</svg>
							</div>
						</td>
						<td>
							<input type="color" id="edtSelector" name="edtColor"></input>
							<div class="ErrorContainer">
								<svg id="ESErr" class="ErrorIcon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path>
									<path d="M11 7h2v7h-2zm0 8h2v2h-2z"></path>
								</svg>
							</div>
						</td>
						<td><input type="submit" id="edtSubmit" value="Edit"></input></td>
					</form>
				</tr>
				<tr>
					<th colspan=4><h3>Delete Color</h3></th>
				</tr>
				<tr>
					<form method="POST" id="delColorForm">
						<td>
							<select id="delList" name="delList">
							</select>
						</td>
                        <td>
                            <input type="text" id="delName" value="Type Name to Confirm" maxlength="42" name="delName">
                            <div class="ErrorContainer">
                                <svg id="ENErr" class="ErrorIcon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path>
                                    <path d="M11 7h2v7h-2zm0 8h2v2h-2z"></path>
                                </svg>
                            </div>
                        </td>
						<td>
							<input type="color" id="delSelector" name="delColor" disabled="disabled"></input>
							<div class="ErrorContainer">
								<svg id="ASErr" class="ErrorIcon" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<path d="M11.953 2C6.465 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.493 2 11.953 2zM12 20c-4.411 0-8-3.589-8-8s3.567-8 7.953-8C16.391 4 20 7.589 20 12s-3.589 8-8 8z"></path>
									<path d="M11 7h2v7h-2zm0 8h2v2h-2z"></path>
								</svg>
							</div>
						</td>
						<td><input type="submit" id="delSubmit" value="Delete"></input></td>
					</form>
				</tr>
			</tbody>
		</table>
	</body>
</html>
