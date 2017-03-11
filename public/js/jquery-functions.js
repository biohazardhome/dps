jQuery.fn.serializeObject = function() {
	return this.serializeArray().reduce(function(initial, item) {
	    initial[item.name] = item.value;
	    return initial;
	}, {});
};