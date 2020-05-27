function onQuickFilterChanged() {
	gridOptions.api
			.setQuickFilter(document.getElementById('quickFilter').value);
}

function onRemoveSelected() {
	
	var selectedData = gridOptions.api.getSelectedNodes();
	console.log(selectedData);
	for (i = 0; i < selectedData.length; i++){
			
		 matransaction = "{\"idrow\":\""+selectedData[i].id+"\", \"operation\": \"delete\",\"data\":"+ JSON.stringify(selectedData[i].data) + "}";
		 if(document.getElementById("affichage").innerHTML=="") {
		 document.getElementById("affichage").innerHTML = matransaction ;
		 }
		 else {
		 document.getElementById("affichage").innerHTML += ',' + matransaction;
		 }
		
	}
	selectedData = gridOptions.api.getSelectedRows();
	var res = gridOptions.api.applyTransaction({remove : selectedData});
	return i;
}
	
// matransaction = "{\"idrow\":"+event.node.id+", \"operation\": "addupdate"",
// \"data\":"+ JSON.stringify(event.data) + "}";
// if(document.getElementById("affichage").innerHTML=="") {
// document.getElementById("affichage").innerHTML = matransaction ;
// }
// else {
// document.getElementById("affichage").innerHTML += ',' + matransaction;
// }
//
// console.log('Data after change is', event.data);
	

	// printResult(res);

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