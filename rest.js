let localDB = null;

$(document).ready(function () {
	selectPopHandler();
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

function selectPopHandler() {
	const selects = $("select");
	const nameArr = localDB.map((x) => x.name);
	console.log(nameArr);
	selects.each(function(k, v) {
	const select = $("#"+v.id);
	let options = select[0].options;
		for(let i = options.length - 1; i >= 0; i--) {
			if(!nameArr.includes(options[i].value)) {
				options.remove(i);
			}
		}
		nameArr.forEach((x, i, a) => options.add(new Option(x, x), i));
	});
}

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
        type: "POST",
        url: 'database.php',
        data: jsonstring, 
        contentType: 'application/json',
        dataType: 'json',
        success: function (retData) {
            console.log("success");
            console.log(retData);
        },
        error: function (xhr, status, err) {
            console.log("fail");
            console.log(err);
        },
    });
}

function requestColor(colorObj){
    submitColor(colorObj, 'getColor');
}

function submitColor(colorObj, type) {
    var jsonstring = JSON.stringify(colorObj);
    console.log(typeof colorObj);
    console.log(colorObj);
    console.log(typeof jsonstring);
    console.log(jsonstring);
    $.ajax({
        type: "POST",
        url: 'database.php',
        data: jsonstring, 
        contentType: 'application/json',
        dataType: 'json',
        success: function (retData) {
            console.log("success");
            console.log(retData);
        },
        error: function (xhr, status, err) {
            console.log("fail");
            console.log(err);
        },
    });
}
