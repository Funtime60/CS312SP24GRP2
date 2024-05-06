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

function convertColorToVal(color) {
	// TODO: hook into DB
	return color
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
	otptList.forEach((x, i, a) => $("#"+x.id).attr("value", cellList[i].map((y, j) => y.id.substring(9).split("Col").map((z, k) => (k == 0)? parseInt(z) + 1 : String.fromCharCode(65 + parseInt(z))).reverse().join("")).toSorted().join(", ")));
};

$(document).ready(function() {
	selectLockHandler();
	classColorHandler();
	colorListHandler();
	$("table#table1 select").on('change', selectLockHandler);
	$("table#table1 select").on('change', classColorHandler);
	$(".table2Cell").on('click', cellColorHandler);
	// No longer used since it HAS to be called AFTER the cellColorHandler
	//$(".table2Cell").on('click', colorListHandler);
});
