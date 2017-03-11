var PlacemarkApi = {

    uri: '/placemark/',

    updateCoords: function(id, params, fn) {
    	return this.fetch('update-coords/'+ id, fn, params);
    },

    type: function(type, fn) {
        return this.fetch('type/'+ type, fn);
    },

};

PlacemarkApi = $.extend({}, Api, PlacemarkApi);