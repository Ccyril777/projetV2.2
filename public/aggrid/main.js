console.log('Hello');

function toArea(tab) {
	s = "";
	for (e in tab) {
		if (s == "") {
			s = JSON.stringify(tab[e]);
		} else {
			s += ',' + JSON.stringify(tab[e]);
		}
	}

	return s;
}

var $newrows = [];
$newrows[1] = {
	'id' : 0,
	'nom' : 'olivier'
};
console.log('Newrow = ' + toArea($newrows));
$newrows[2] = {
	'id' : 1,
	'nom' : 'cyril'
};
console.log('Newrow = ' + toArea($newrows));
$newrows[2] = {
	'id' : 1,
	'nom' : 'canoine',
	'techno' : 'symphony'
};
console.log('Newrow = ' + toArea($newrows));

function ObjectCellRenderer() {
}

ObjectCellRenderer.prototype.init = function(params) {
	this.span = document.createElement('span');
	this.refresh(params);
};

ObjectCellRenderer.prototype.refresh = function(params) {
	this.span.innerHTML = '';
	var nb = 1;
	this.refresh(params);
	if (params.value) {
		// params.api.onRowHeightChanged();
	}
}

ObjectCellRenderer.prototype.refresh = function(params) {
	if (params.value) {
		var val = '';
		val = params.value.val.split(';');
		nb = val.length;
		val = val.join('<br>');
		this.span.innerHTML = val;
		params.node.setRowHeight(25 * nb);
	}
}

ObjectCellRenderer.prototype.getGui = function() {
	return this.span;
};

function ObjectEditor() {
	this.data = [];
	this.confEditor = {
		data : this.data,
		type : 'default',
		autocomplete : true,
		multiple : false,
	};
}

ObjectEditor.prototype.init = function(params) {
	console.log(params);
	this.container = document.createElement('div');
	this.myDropdown = jSuites.dropdown(this.container, this.confEditor);

	if (params.value) {

		var val = params.value.id.split(';');
		console.log(params.value.id);
		this.myDropdown.setValue(val);
	}

};

ObjectEditor.prototype.getGui = function() {
	return this.container;
};

ObjectEditor.prototype.afterGuiAttached = function() {
};

ObjectEditor.prototype.getValue = function() {

	return {
		id : this.myDropdown.getValue(),
		val : this.myDropdown.getText()
	};
};

ObjectEditor.prototype.destroy = function() {
};

ObjectEditor.prototype.isPopup = function() {
	return true;
	true
};

var onKeyDown = function(event) {
	var key = event.which || event.keyCode;
	if (key == 37 || // left
	key == 39 || // right
	key == 9) { // tab
		event.stopPropagation();
	}
};

function description() {

	document.getElementById("affichage").innerHTML += "toto ";
}
