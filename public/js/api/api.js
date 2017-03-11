var Api = {

	uri: '/',

	fetch: function(uri, fn, params) {
        var url = this.uri + uri;

        params = params || {};

        return $.post(url, params, function(data) {
            if (fn) {
                fn(data);
            }
        }).error(function(data) {
            console.log('api error', url, data);
        });
    },

    all: function(fn) {
        return this.fetch('all',fn);
    },

    find: function(id, fn) {
        return this.fetch('find/'+ id, fn);
    },

    store: function(data, fn) {
        return this.fetch('store', fn, data);
    },

    update: function(id, data, fn) {
        return this.fetch('update/'+ id, fn, data);
    },

    memoize: function() {

    },

    reload: function() {

    },

};