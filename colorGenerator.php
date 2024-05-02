<!DOCTYPE html>
<html>
	<?php
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	?>
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
			$iRowColumn	= $_POST["RowCol"] ?? 1;
			$iColor		= $_POST["Color"]  ?? 1;

			/* Colors are sorted in a rough gradient + gray/black based on the assumption that the color name to hex will follow the HTML standards */
			$asColorList = array("red", "brown", "orange", "yellow", "green", "teal", "blue", "purple", "gray", "black");
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="colorGenerator.js"></script>
		<script src="validation.js"></script>
		<style>
			<?php
				if(!isset($_POST["printMode"])) {
					echo "/*";
				}
			?>
			:root {
				filter: grayscale(1);
				backdrop-filter: grayscale(1);
				--ContentWidth: min(50vh, 50vw);
			}
			nav .navbar div.brand {
				margin: auto;
			}
			nav .navbar ul.menu {
				display: none;
			}/**/
		</style>
	</head>
	<body>
		<?php
			include 'navbar.php';
		?>
		<h1 id="pageTitle">Color Generator</h1>
		<form id="tableContainer" method="POST">
			<?php
				if(isset($_POST["printMode"])) {
					echo "<!--";
				}
			?>
			<label for="RowCol">Number of colors between 1 and 10: </label>
			<input type="number" class="" id="Color" name="Color" min="1" max="10" value="<?php echo $iColor ?>" required>
			<br>
			<label for="RowCol">Number of rows and columns between 1 and 26: </label>
			<input type="number" class="" id="RowCol" name="RowCol" min="1" max="26" value="<?php echo $iRowColumn ?>" required>
			<br>
			<input type="submit" value="Submit">
			<div class="tableSpacer"></div><!---->
			<div id="table1Container">
				<div id="table1ErrorMsg" class="errorMsg" style="display: none;">Error: Setting Error Message Failed!</div>
				<table id="table1">
					<tbody>
						<?php
							/* Add whitespace so that html is readable in DevTools */
							for($iI = 0; $iI < $iColor; $iI++) {
								echo ($iI != 0 ? "\n\t\t\t\t\t\t" : "") . "<tr id=\"table1Row" . $iI . "\" class=\"table1Row\">";
								echo "\n\t\t\t\t\t\t\t<td id=\"table1Row" . $iI . "ColL\"class=\"table1CellL\">";
								echo "\n\t\t\t\t\t\t\t\t<select" . (isset($_POST["printMode"]) ? " disabled " : " ") . "name=\"table1Row" . $iI . "Select\" id=\"table1Row" . $iI . "Select\">";
								for($iJ = 0; $iJ < count($asColorList); $iJ++) {
									echo "\n\t\t\t\t\t\t\t\t\t<option" . ((!isset($_POST["table1Row" . $iI . "Select"]) && $iJ == $iI) || (isset($_POST["table1Row" . $iI . "Select"]) && $_POST["table1Row" . $iI . "Select"] == $asColorList[$iJ]) ?	' selected="selected" ' : ' ') . "value=\"$asColorList[$iJ]\">$asColorList[$iJ]</option>";
								}
								echo "\n\t\t\t\t\t\t\t\t</select>";
								echo "\n\t\t\t\t\t\t\t\t<input" . (((isset($_POST["colorSelectRadio"])? $_POST["colorSelectRadio"] : "index0") == "index" . $iI)?' checked="checked" ' : ' ') . (isset($_POST["printMode"]) ? " disabled " : " ") . "type=\"radio\" name=\"colorSelectRadio\" id=\"table1Row" . $iI . "Radio\" value=\"index" . $iI . "\">";
								echo "\n\t\t\t\t\t\t\t</td>";
								echo "\n\t\t\t\t\t\t\t<td id=\"table1Row" . $iI . "ColR\"class=\"table1CellR\"></td>";
								echo "\n\t\t\t\t\t\t</tr>";
							}
							echo "\n";
						?>
					</tbody>
				</table>
			</div>
			<div class="tableSpacer"></div>
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
			<div class="tableSpacer"></div>
			<input id="printMode" type="<?php echo (isset($_POST['printMode']) ? 'hidden' : 'submit')?>" name="printMode" value="Printable View">
			<div class="tableSpacer"></div>
		</form>
	</body>
</html>
