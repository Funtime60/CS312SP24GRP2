<!DOCTYPE html>
<html>
	<?php
		$sTitle = "Color Generator";
	?>
	<head>
		<?php
			include 'headContents.php';
		?>
		<link rel="stylesheet" href="colorGenerator.css">

		<?php
			/* Variables are camel case, and prefixed by lowercase types. $i is integer, $as is string array, $b is boolean, etc */
			/* Read GET parameters and define globals here so we have them everywhere */
			/* Could switch to POST later if that's what is needed, just used GET since it's easy to set without a form */
			$iRowColumn = $_POST["RowCol"] ?? 1;
			$iColor		= $_POST["Color"]  ?? 1;

			/* Colors are sorted in a rough gradient + gray/black based on the assumption that the color name to hex will follow the HTML standards */
			$asColorList = array("red", "brown", "orange", "yellow", "green", "teal", "blue", "purple", "gray", "black");
		?>
	</head>
	<body>
		<?php
			include 'navbar.php';
		?>
		<div>
			<h1>Color Generator</h1>
		</div>
		<div id="tableContainer">
			<div id="table1Container">
				<div id="table1ErrorMsg" class="errorMsg" style="display: none;">Error: Setting Error Message Failed!</div>
				<table id="table1">
					<tbody>
						<?php
							/* Add whitespace so that html is readable in DevTools */
							for($iI = 0; $iI < $iColor; $iI++) {
								echo ($iI != 0 ? "\n\t\t\t\t\t\t" : "") . "<tr id=\"table1Row" . $iI . "\" class=\"table1Row\">";
								echo "\n\t\t\t\t\t\t\t<td id=\"table1Row" . $iI . "ColL\"class=\"table1CellL\">";
								echo "\n\t\t\t\t\t\t\t\t<select name=\"table1Row" . $iI . "Select\" id=\"table1Row" . $iI . "Select\">";
								for($iJ = 0; $iJ < count($asColorList); $iJ++) {
									echo "\n\t\t\t\t\t\t\t\t\t<option" . ($iJ == $iI ?  ' selected="selected" ' : ' ') . "value=\"$asColorList[$iJ]\">$asColorList[$iJ]</option>";
								}
								echo "\n\t\t\t\t\t\t\t\t</select>";
								echo "\n\t\t\t\t\t\t\t</td>";
								echo "\n\t\t\t\t\t\t\t<td id=\"table1Row" . $iI . "ColR\"class=\"table1CellR\"></td>";
								echo "\n\t\t\t\t\t\t</tr>";
							}
							echo "\n";
						?>
					</tbody>
				</table>
			</div>
			<div id="table2Container">
				<div id="table2ErrorMsg" class="errorMsg" style="display: none;">Error: Setting Error Message Failed!</div>
				<table id="table2">
					<tbody>
						<?php
							echo "<tr id=\"table2RowH\" class=\"table2Row\">";
							echo "\n\t\t\t\t\t\t\t<th id=\"table2RowHColH\" class=\"table2Header\"></th>";
							for($iI = 0; $iI < $iRowColumn; $iI++) {
								echo "\n\t\t\t\t\t\t\t<th id=\"table2RowHCol" . $iI . "\" class=\"table2Header\">" . chr(ord("A") + $iI) . "</th>";
							}
							echo "\n\t\t\t\t\t\t</tr>";
							for($iI = 0; $iI < $iRowColumn; $iI++) {
								echo "\n\t\t\t\t\t\t<tr id=\"table2Row" . $iI . "\" class=\"table2Row\">";
								echo "\n\t\t\t\t\t\t\t<th id=\"table2Row" . $iI . "ColH\" class=\"table2Header\">" . ($iI + 1) . "</th>";
								for($iJ = 0; $iJ < $iRowColumn; $iJ++) {
									echo "\n\t\t\t\t\t\t\t<td id=\"table2Row" . $iI . "Col" . $iJ . "\" class=\"table2Cell\"></td>";
								}
								echo "\n\t\t\t\t\t\t</tr>";
							}
							echo "\n";
						?>
					</tbody>
				</table>
			</div>
		</div>
	</body>
</html>
