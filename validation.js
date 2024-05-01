document.addEventListener("DOMContentLoaded", (event) => {
    document.getElementById("RowCol").addEventListener("input", rowcolsValidator);
    document.getElementById("Color").addEventListener("input", numcolorsValidator);
    //document.getElementById("table1").addEventListener("input", comboboxValidator);
    //modifyInput();
    //makeSureTableExists();
});
//TODO:
//  Disable submit button untill both values are filled in.
//  
//const queryString = window.location.search;
// Will pull the parameters from the URL and push them into html input boxes
//const urlParams = new URLSearchParams(queryString);
const colors = { "warning": "#FF4444", };
let params = { rowcols: 1, colors: 1 };
//let comboBoxes = [];
//let comboBoxes_frame = [];
function rowcolsValidator() {
    // console.log("rowcolsValidator() called");
    let y = document.getElementById("RowCol");
    let x = parseInt(y.value);
    x = y.value;

    if (x >= 1 && x <= 26) {
        y.style.backgroundColor = "";
        params["rowcols"] = y.value;
    }
    else if (x == "") {
        y.style.backgroundColor = "";
        params["rowcols"] = 1;
    }
    else {
        y.style.backgroundColor = colors["warning"];
        params["rowcols"] = 1;
    }
}

function numcolorsValidator() {
    // console.log("numcolorsValidator() called");
    let y = document.getElementById("Color");
    // console.log("value of Color: " + y.value);
    let x = parseInt(y.value);
    x = y.value;

    if (x >= 1 && x <= 10) {
        y.style.backgroundColor = "";
        params["colors"] = y.value;

    }
    else if (x == "") {
        y.style.backgroundColor = "";
        params["colors"] = 1;

    }
    else {
        y.style.backgroundColor = colors["warning"];
        params["colors"] = 1;
    }
}

function modifyInput() {
    if (urlParams.has("RowCol") && urlParams.has("Color")) {
        let rowcol = urlParams.get('RowCol');
        let colors = urlParams.get('Color');
        document.getElementById("RowCol").value = rowcol;
        document.getElementById("Color").value = colors;
        params['rowcols'] = parseInt(urlParams.get('RowCol'));
        params['colors'] = parseInt(urlParams.get('Color'));
    }
    else {
        let rowcol = document.querySelector(`meta[name="iRowColumn"]`).content;
        let colors = document.querySelector(`meta[name="iColor"]`).content;
        document.getElementById("RowCol").value = rowcol;
        document.getElementById("Color").value = colors;
        params['rowcols'] = rowcol;
        params['colors'] = colors;
    }
}

function makeSureTableExists() {
    //check for existence of  tables and enumerate comboboxes
    // console.log("makeSureTableExists() has been run");
    comboBoxes = enumerateComboboxes();
    comboBoxes_UpdateFrame();
    // console.log("Here is the comboboxes return: ");
    // comboBoxes.forEach(console.log);
}
function comboBoxes_UpdateFrame() {
    comboBoxes_frame = extractValues(comboBoxes);
}

function extractValues(a){
    let b = [];
    a.forEach(function (x){
        b.push(x.value);
    });
    return b;
}

function comboboxValidator() {
    // need to find attribute : selected
    // console.log(comboBoxes_frame);
    let old = comboBoxes_frame;
    let nu = extractValues(comboBoxes);
/*     console.log("----");
    console.log(old);
    console.log(nu) */;
    let match;
    for(let i = 0; i < old.length; i++){
        if(nu[i] !== old[i]){
            match = i;
        }
    }

    // console.log(match);
    if(match === undefined)
        return;
    for(let i = 0; i < nu.length; i++){
        if(match === i){
            continue;
        }
        if(nu[i] === nu[match]){
            comboBoxes[match].value = old[match];
            comboBoxes[match].setCustomValidity("nah... cant pick "+ nu[match]);
            nu[match] = old[match];
            // comboBoxes[match].focus();
            // comboBoxes[match].blur();
            comboBoxes[match].reportValidity();
            break;
        }
    }
    // console.log(nu);
    comboBoxes_frame = nu;
}

function enumerateComboboxes() {
    var boxes = [];
    const targetID_prefix = "table1Row";
    const targetID_postfix = "ColL";
    for (var i = 0; i < params["colors"]; i++) {
        var pre = `${targetID_prefix}${i}${targetID_postfix}`;
        var elem = document.getElementById(pre).children[0];
        // console.log(pre);
        boxes.push(elem);
    }
    return boxes;
}
