function onQuickFilterChanged() {
	gridOptions.api
			.setQuickFilter(document.getElementById('quickFilter').value);
}

function onRemoveSelected() {
	addItems();
	
	var selectedData = gridOptions.api.getSelectedRows();
	console.log("selecteddata = " + selectedData);
	var res = gridOptions.api.applyTransaction({remove : selectedData });
	//printResult(res);
}

var newCount = 1;

function createNewRowData() {
  var newData = {
    usual_name: 'Toyota ' + newCount,
    
  };
  newCount++;
  return newData;
}
function addItems() {
	  var newItems = [createNewRowData(), createNewRowData(), createNewRowData()];
	  var res = gridOptions.api.applyTransaction({ add: newItems });
}

document.querySelector('#addRow').addEventListener("click", function() {
	gridOptions.api.addItems([ {} ]);
});

document.addEventListener('DOMContentLoaded', function() {
    var gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
});