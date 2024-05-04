
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
    data['type']= type; 
    console.log(data);
    $.ajax({
        type: "POST",
        url: 'database.php',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function (retData) {
            console.log("success");
            console.log(retData);
        },
        failure: function (xhr, status, err) {
            console.log("fail");
            console.log(err);
        },
        always: function () {
            console.log("sent...");
        }
    });
}