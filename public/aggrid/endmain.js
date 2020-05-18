function onQuickFilterChanged() {
	gridOptions.api.setQuickFilter(document.getElementById('quickFilter').value);
}

document.querySelector('#addRow').addEventListener("click", function() {
	gridOptions.api.addItems([{}]);
});

	new agGrid.Grid(document.querySelector('#myGrid'), gridOptions);
