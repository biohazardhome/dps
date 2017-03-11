var Form = {

	select: function(name, items, selectedId) {
		var options = items.reduce(function(initial, item) {
			var selected = (item.id === selectedId) ? true : false;
			return initial + this.option(item.name, item.id, selected);
		}.bind(this), '');

		return '<select name="'+ name +'">' + options + '</select>'
	},

	option: function(name, value, selected) {
		selected = selected ? ' selected' : '';
		return '<option value="'+ value +'" '+ selected +'>'+ name +'</option>'
	},

};