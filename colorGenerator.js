let printMode = false;
let localDB = [{ "name": "White", "color": "#ffffff" }];

function selectLockHandler() {
	const arr = $("table#table1 select").toArray();
	var vals = []
	for(select of arr) {
		vals.push(select.value);
	}
	for(select of arr) {
		for(option of select) {
			if(option.selected) {
				option.disabled = false;
			}else if(vals.includes(option.value)) {
				option.disabled = true;
			}else {
				option.disabled = false;
			}
		}
	}
};

function selectPopHandler() {
    const selects = $("select");
    selects.empty();
    const nameArr = localDB.map((x) => x.name);
    selects.each(function(k, v) {
        const select = $("#" + v.id);
        let options = select[0].options;
        for (let i = options.length - 1; i >= 0; i--) {
            if (!nameArr.includes(options[i].value)) {
                options.remove(i);
            }
        }
       	localDB.forEach((x, i, a) => options.add(new Option(printMode ? x.name + " - " + x.color : x.name, x.name), i));
    });
	selects.each(function(k, v) {
		const select = $("#" + v.id);
		select.val(select[0][parseInt(select[0].id.substring(9,10))]?.value)
		if(!select.val()) {
			let msg = "Insufficient color options";
			select[0].options.add(new Option(msg, msg), select[0].options.length);
			select.val(msg);
			select[0].nextElementSibling.disabled = true;
			select[0].disabled = true;
		}
	});
}

async function getTable() {
    let requestObj = { 'type': 'getTable' };
    await APICall(requestObj)
        .then(function (response) {
            if (response.status === "error") {
                setError(response.message);
            }
            else {
                localDB = response.data;
                selectPopHandler();
            }
        })
        .catch(function (error) {
            console.log("API call failed: ", error);
        });
}

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

function convertColorToVal(name) {
	return localDB.find((x) => x?.name == name)?.color ?? "white"
}

function classColorHandler() {
	const arr = $("table#table1 select").toArray();
	const colorArr = arr.map((x) => convertColorToVal(x.value));
	let text = $("style#colorVar").text();
	let lines = text.split("\n");
	let varLines = lines.splice(2, lines.length - 4);
	varLines = varLines.map((x, i, a) => x.split(/(: |;)/).map((y, j) => (j == 2)?colorArr[i] ?? y:y).join(''));
	lines.splice(2, 0, ...varLines);
	text = lines.join("\n");
	$("style#colorVar").text(text);
};

function cellColorHandler() {
	const indexArr = ["index0", "index1", "index2", "index3", "index4", "index5", "index6", "index7", "index8", "index9"];
	const currColor = $("input:radio[name='colorSelectRadio']:checked")[0].value;
	const othrColrs = indexArr.filter((x) => x != currColor);
	$(this).removeClass(othrColrs.join(" "));
	$(this).addClass(currColor);

	colorListHandler();
};

function colorListHandler() {
	const slctList = $("table#table1 select").toArray();
	const otptList = slctList.map((x) => x.parentElement.nextElementSibling.firstElementChild);
	const indxList = slctList.map((x) => x.nextElementSibling.value);
	const cellList = indxList.map((x) => $("#table2 td.table2Cell."+x).toArray());
	otptList.forEach((x, i, a) => $("#"+x.id).attr("value", cellList[i].map((y, j) => y.id.substring(9).split("Col").map((z, k) => (k == 0)? parseInt(z) : String.fromCharCode(65 + parseInt(z))).reverse().join("")).toSorted().join(", ")));
};

$(document).ready(async function() {
	printMode = $(".table2Cell").length <= 0;
	await getTable();
	selectPopHandler()
	selectLockHandler();
	classColorHandler();
	if(!printMode) colorListHandler();
	$("table#table1 select").on('change', selectLockHandler);
	$("table#table1 select").on('change', classColorHandler);
	$(".table2Cell").on('click', cellColorHandler);
	// No longer used since it HAS to be called AFTER the cellColorHandler
	//$(".table2Cell").on('click', colorListHandler);
});
