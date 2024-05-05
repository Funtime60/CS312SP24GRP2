let localDB = [{ "name": "Black", "color": "#000000" }];

$(document).ready(function () {
    // retrieveTable():w
    selectPopHandler();
    $("#addColorForm").on("submit", function (event) {
        event.preventDefault();
        submitColor($(this), 'submit');
    });
    $("#edtColorForm").on("submit", function (event) {
        event.preventDefault();
        editColor($(this), 'edit');

    });
    $("#delColorForm").on("submit", function (event) {
        event.preventDefault();
        deleteColor($(this), 'delete');
    });
});

function retrieveTable() {
    return APICall({}, 'getTable');
}
function selectPopHandler() {
    const selects = $("select");
    const nameArr = localDB.map((x) => x.name);
    console.log(nameArr);
    selects.each(function (k, v) {
        const select = $("#" + v.id);
        let options = select[0].options;
        for (let i = options.length - 1; i >= 0; i--) {
            if (!nameArr.includes(options[i].value)) {
                options.remove(i);
            }
        }
        nameArr.forEach((x, i, a) => options.add(new Option(x, x), i));
    });
}
function submitColor(ctx, type) {
    let serialObject = serialize(ctx);
    serialObject['type'] = type;
    let response = APICall(serialObject);
}
function editColor(ctx, type) {
    let serialObject = serialize(ctx);
    serialObject['type'] = type;
    let response = APICall(serialObject);
}
function deleteColor(ctx, type) {
    let serialObject = serialize(ctx);
    serialObject['type'] = type;
    let response = APICall(serialObject);
}

function getColor(colorObj, type) {
    let response = APICall(colorObj);
    return response;
}

// Takes a jquery seleciton and extracts keys and values.
function serialize(obj) {
    let data = {};
    $.each(obj.serializeArray(), function (_, kv) {
        data[kv.name] = kv.value;
    });
    // debug
    console.log(typeof data);
    console.log(data);
    return data;
}

// Issue a REST API call and give back response
function APICall(data) {
    let response;
    var jsonString= JSON.stringify(data);
    console.log()
    $.ajax({
        type: "POST",
        url: 'database.php',
        data: jsonString,
        contentType: 'application/json',
        dataType: 'json',
        success: function (retData) {
            console.log("success");
            console.log(retData);
            response = retData;
        },
        error: function (xhr, status, err) {
            console.log("fail");
            console.log(err);
        },
    });
    return response;
}

