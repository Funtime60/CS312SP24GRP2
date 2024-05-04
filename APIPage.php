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
	<!-- <script src= "rest.js"></script> -->
	<script>
		
$(document).ready(function () {
    $("#addColorForm").on("submit", function (event) {
        event.preventDefault();
        submitData($(this), 'submit');
    });
    $("#edtColorForm").on("submit", function (event) {
        event.preventDefault();
        submitData($(this), 'edit');
    });
    $("#delColorForm").on("submit", function (event) {
        event.preventDefault();
        submitData($(this), 'delete');
    });
});

function submitData(ctx, type) {
    // console.log($("#addColorForm"));
    // var dat = JSON.stringify(ctx.serializeArray());
    var data = {};

    $.each(ctx.serializeArray(), function (_, kv) {
        data[kv.name] = kv.value;
    });
    data['type'] = type;
    
    var jsonstring = JSON.stringify(data);
    console.log(typeof data);
    console.log(data);
    console.log(typeof jsonstring);
    console.log(jsonstring);
    $.ajax({
        type: 'POST',
        url: 'database.php',
        data: jsonstring, 
        contentType: 'application/json',
        dataType: 'json',
        success: function (retData) {
            console.log(retData);
        },
        error: function (xhr, status, err) {
			console.log(status);
            console.log(err);
        },
    });
}
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
				<th colspan=4>
					<h3>Add Color</h3>
				</th>
			</tr>
			<tr>
				<form method="POST" id="addColorForm">
					<td colspan=2><input type="text" id="addName" value="New color name" maxlength="42" name="addName"></td>
					<td><input type="color" id="addSelector" name="addColor"></input></td>
					<td><input type="submit" id="addSubmit" value="Add"></input></td>
				</form>
			</tr>
			<tr>
				<th colspan=4>
					<h3>Edit Color</h3>
				</th>
			</tr>
			<tr>
				<form method="POST" id="edtColorForm">
					<td>
						<select id="edtList" name="edtList">
						</select>
					</td>
					<td><input type="text" id="edtName" value="" maxlength="42" name="edtName"></td>
					<td><input type="color" id="edtSelector" name="edtColor"></input></td>
					<td><input type="submit" id="edtSubmit" value="Edit"></input></td>
				</form>
			</tr>
			<tr>
				<th colspan=4>
					<h3>Delete Color</h3>
				</th>
			</tr>
			<tr>
				<form method="POST" id="delColorForm">
					<td>
						<select id="delList" name="delList">
						</select>
					</td>
					<td><input type="text" id="delName" value="Type Name to Confirm" maxlength="42" name="delName"></td>
					<td><input type="color" id="delSelector" name="delColor" disabled="disabled"></input></td>
					<td><input type="submit" id="delSubmit" value="Delete"></input></td>
				</form>
			</tr>
			<tr class="ErrorRow">
				<td colspan=4>Error setting error message.</td>
			</tr>
		</tbody>
	</table>
</body>

</html>