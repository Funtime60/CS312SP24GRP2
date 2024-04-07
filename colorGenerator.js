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

$(document).ready(function() {
	selectLockHandler();
	$("table#table1 select").on('change', selectLockHandler);
});
