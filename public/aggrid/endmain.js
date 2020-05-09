function onQuickFilterChanged() {
	gridOptions.api.setQuickFilter(document.getElementById('quickFilter').value);
}

	new agGrid.Grid(document.querySelector('#myGrid'), gridOptions);

document.querySelector('#addRow').addEventListener("click", function() {
    gridOptions.api.addItems([{}]);
});
