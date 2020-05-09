console.log ('Hello');

function ObjectCellRenderer() {
}

ObjectCellRenderer.prototype.init = function (params) {
    this.span = document.createElement('span');
	this.refresh(params);
};

ObjectCellRenderer.prototype.refresh = function(params) {
	this.span.innerHTML = '';
	var nb = 1;
	this.refresh(params);
	if(params.value) {
		//params.api.onRowHeightChanged();
	}
}

ObjectCellRenderer.prototype.refresh = function(params) {
	if(params.value) {
		var val = '';
		val = params.value.val.split(';');
		nb = val.length;
		val = val.join('<br>');
		this.span.innerHTML = val;
		params.node.setRowHeight(25*nb);
	}
}

ObjectCellRenderer.prototype.getGui = function () {
    return this.span;
};

function ObjectEditor() {
	this.data = [];
}

ObjectEditor.prototype.init = function (params) {
	console.log(params);
    this.container = document.createElement('div');
	this.myDropdown=jSuites.dropdown(this.container, {
		data:this.data,
		type:'default',
		autocomplete:true,
		multiple:true
	});

	if (params.value) {

		var val = params.value.id.split(';');
		console.log(params.value.id);
		this.myDropdown.setValue(val);
	}

};

ObjectEditor.prototype.getGui = function () {
    return this.container;
};

ObjectEditor.prototype.afterGuiAttached = function () {
};

ObjectEditor.prototype.getValue = function () {

    return {id:this.myDropdown.getValue(),val:this.myDropdown.getText()};
};

ObjectEditor.prototype.destroy = function () {
};

ObjectEditor.prototype.isPopup = function () {
    return true;
};

var onKeyDown = function(event) {
	var key = event.which || event.keyCode;
	if (key == 37 ||  // left
		key == 39 || // right
		key == 9 ) {  // tab
		event.stopPropagation();
	}
};

