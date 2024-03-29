<!DOCTYPE html>
<html>
	<head>
		<meta name="author" content="Gabriel Bertasius, Issac Curtis, Jake Parra, Daniel Peaslee">
		<meta name="keywords" content="HTML, CSS, JavaScript,">
		<meta name="description" content="Color Generator Page for T3chSharkz29 LLC">
		<title>Color Generator</title>

		<link rel="stylesheet" href="colorGenerator.css">
		<style>
			.table1L {
				width-max: 20%;
			}
			.table2L {
				width: 80%;
			}
			#table1 {
				background-color: blue;
				color: red;
			}
		</style>

		<?php
			$iRowColumn = $_GET["RowCol"] ?? 1;
			$iColor     = $_GET["Color"]  ?? 1;
		?>
	</head>
	<body>
		<?php
			include 'navbar.html';
		?>
		<h1>Color Generator</h1>
		<table id="table1">
			<tbody>
				<?php
					for($iI = 0; $iI < $iColor; $iI++) {
						echo '<tr id=' . $iI . '><td class="table1L"></td><td class="table1R"></td></tr>';
					}
				?>		
			</tbody>
		</table>
		<table>
			
		</table>
	</body>
</html>
