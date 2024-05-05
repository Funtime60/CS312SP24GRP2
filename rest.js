let localDB = [{ "name": "Black", "color": "#000000" }];
const colorObjectKeys = ['name', 'color'];
const CObjKeys = Object.freeze({
    name: 'name',
    color: 'color',
});
const emptyColorObj = [{}];

const APIRequest = Object.freeze({
    SUBMIT: 'add',
    EDIT: 'edit',
    DELETE: 'delete',
    GET_TABLE: 'getTable',
    GET_COLOR: 'getColor'
});

$(document).ready(function () {

    // console.log(Object.keys(localDB[0]));
    getTable();
    // selectPopHandler();
    // refreshClientColors(retTable['data']);
    $("#addColorForm").on("submit", function (event) {
        event.preventDefault();
        addColor($(this));
        // getTable();
    });
    $("#edtColorForm").on("submit", function (event) {
        event.preventDefault();
        editColor($(this));
        // getTable();
    });
    $("#edtList").on("input", function (event) {
        let val = $(this).val();
        let found = localDB.find(
            o => o[CObjKeys.name].toLowerCase() === val.toLowerCase()
        );
        if (found) {
            $("#edtSelector").val(found[CObjKeys.color]);
        }
    });
    $("#delColorForm").on("submit", function (event) {
        event.preventDefault();
        deleteColor($(this));
        getTable();
    });
    $("#delList").on("input", function (event) {
        let val = $(this).val();
        let found = localDB.find(
            o => o[CObjKeys.name].toLowerCase() === val.toLowerCase()
        );
        if (found) {
            $("#delSelector").val(found[CObjKeys.color]);
        }
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

function editSelector(updatedColor) {
    // if (Object.keys(updatedColor).length != 2) {
    //     return;
    // }
    // let colorObj = renameKeys(updatedColor, colorObjectKeys);
    let colorObj = updatedColor;
    console.log(colorObj);
    let found = localDB.find(
        o => o[CObjKeys.name].toLowerCase() === colorObj[CObjKeys.name].toLowerCase()
    );
    if (found) {
        found[CObjKeys.color] = colorObj[CObjKeys.color];
    }
    console.log(localDB);
    selectPopHandler();

}
// Array.slice(inclusive_start, exclusive_end);
function addColor(ctx) {
    let serialObject = serialize(ctx);
    let oldObj = []
    oldObj[CObjKeys.name] = serialObject['addName'];
    oldObj[CObjKeys.color] = serialObject['addColor'];
    // console.log(oldObj);
    serialObject['type'] = APIRequest.SUBMIT;
    // console.log(serialObject);
    APICall(serialObject)
        .then(function (response) {
            if (response.status === "error") {
                setError(response.message);
            } else {
                updateSelector(oldobj);
            }
        })
        .catch(function (error) {
            console.log("API call failed: ", error);
            setError(error);
        });
}
function updateSelector(colorObj) {
    localDB.push(renameKeys(colorObj, colorObjectKeys));
    selectPopHandler();
}
function editColor(ctx) {
    let serialObject = serialize(ctx);
    if (serialObject['edtName'].length === 0){
        setError("Please confirm by typing in color.");
        return
    }
    else if(serialObject['edtName'].toLowerCase() !== serialObject['edtList'].toLowerCase()) {
        setError("Selected color and typed text do not match!");
        return;
    }
    let oldObj = [];
    oldObj[CObjKeys.name] = serialObject['edtName'];
    oldObj[CObjKeys.color] = serialObject['edtColor'];
    // console.log(oldObj);
    serialObject['type'] = APIRequest.EDIT;
    APICall(serialObject)
        .then(function (response) {
            if (response.status === "error") {
                setError(response.message);
            } else {
                editSelector(oldObj);
            }
        })
        .catch(function (error) {
            console.log("API call failed: ", error);
            setError(error);
        });
}
function deleteColor(ctx) {
    let serialObject = serialize(ctx);
    if (serialObject['delName'].length === 0) {
        setError("Please confirm by typing in color.");
        return;
    }
    else if (serialObject['delName'].toLowerCase() !== serialObject['delList'].toLowerCase()) {
        setError("Selected color and typed text do not match!");
        return;
    }
    let oldObj = [];
    oldObj[CObjKeys.name] = serialObject['edtName'];
    oldObj[CObjKeys.color] = serialObject['edtColor'];
    serialObject['type'] = APIRequest.DELETE;
    APICall(serialObject)
        .then(function (response) {
            if (response.status === "error") {
                setError(response.message);
            } else {
                editSelector(oldObj);
            }
        })
        .catch(function (error) {
            console.log("API call failed: ", error);
            setError(error);
        });
}
function getColor(colorObj) {
    // [{"name":"colorname",
    // "color": "#000000"}]
    colorObj['type'] = APIRequest.GET_COLOR;
    let response = APICall(colorObj);
    return response;
}

function setError(strError) {
    $(".ErrorRow > td").text(strError);
}

function getTable() {
    let requestObj = { 'type': APIRequest.GET_TABLE };
    APICall(requestObj)
        .then(function (response) {
            localDB = response.data;
            selectPopHandler();
            if (response.status === "error") {
                setError(response.message);
            }
        })
        .catch(function (error) {
            // console.log("API call failed: ", error);
            setError(err);
        });
}

// Takes a jquery seleciton and extracts keys and values.
function serialize(obj) {
    let data = {};
    $.each(obj.serializeArray(), function (_, kv) {
        data[kv.name] = kv.value;
    });
    // debug
    // console.log(typeof data);
    // console.log(data);
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
                // console.log("APICALL SUCCESS");
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

// Utility function
// returns an object with renamed keys.
// function renameKeys(oldObj, newKeys) {
//     let oldKeys = Object.keys(oldObj);
//     let newObj = {};
//     oldKeys.forEach((oldKey, index) => {
//         let newKey = newKeys[index];
//         newObj[newKey] = oldObj[oldKey];
//     });
//     return newObj;
// }