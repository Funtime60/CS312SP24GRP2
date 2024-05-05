let localDB = [{ "name": "Black", "color": "#000000" }];

const APIRequest = Object.freeze({
    SUBMIT: 'add',
    EDIT: 'edit',
    DELETE: 'delete',
    GET_TABLE: 'getTable',
    GET_COLOR: 'getColor'
});

$(document).ready(function () {

    getTable();
    // selectPopHandler();
    // refreshClientColors(retTable['data']);
    $("#addColorForm").on("submit", function (event) {
        event.preventDefault();
        addColor($(this));
        getTable();
    });
    $("#edtColorForm").on("submit", function (event) {
        event.preventDefault();
        editColor($(this));
        getTable();
    });
    $("#delColorForm").on("submit", function (event) {
        event.preventDefault();
        deleteColor($(this));
        getTable();
    });
});

function selectPopHandler() {
    const selects = $("select");
    selects.empty();
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
function addColor(ctx) {
    let serialObject = serialize(ctx);
    serialObject['type'] = APIRequest.SUBMIT;
    let response = APICall(serialObject);

}
function editColor(ctx) {
    let serialObject = serialize(ctx);
    serialObject['type'] = APIRequest.EDIT;
    let response = APICall(serialObject);
}
function deleteColor(ctx) {
    let serialObject = serialize(ctx);
    serialObject['type'] = APIRequest.DELETE;
    let response = APICall(serialObject);
}
// [{"name":"colorname",
// "color": "#000000"}]
function getColor(colorObj) {
    colorObj['type'] = APIRequest.GET_COLOR;
    let response = APICall(colorObj);
    return response;
}

function getTable() {
    let requestObj = { 'type': APIRequest.GET_TABLE };
    APICall(requestObj)
        .then(function (response) {
            localDB = response.data;
            console.log(localDB);
            selectPopHandler();
        })
        .catch(function (error) {
            console.log("API call failed: ", error);
        });
}
// function refreshClientColors(obj) {
//     localDB.push(obj);
// }
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

// Issue a REST API call and give back promise 
function APICall(data) {
    return new Promise(function (resolve, reject) {
        var jsonString = JSON.stringify(data);
        var jsonString = JSON.stringify(data);
        $.ajax({
            type: "POST",
            url: 'database.php',
            data: jsonString,
            contentType: 'application/json',
            dataType: 'json',
            success: function (retData) {
                // console.log("success");
                // console.log(retData);
                resolve(retData);
            },
            error: function (xhr, status, err) {
                // console.log("fail");
                // console.log(err);
                reject(err);
            },
        });
    });
}