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

var newCount = 1;

document.querySelector('#addRow').addEventListener("click", function() {
	gridOptions.api.addItems([ {} ]);
});

document.addEventListener('DOMContentLoaded', function() {
    var gridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(gridDiv, gridOptions);
});