var UserApi = {

    uri: '/user/',

    auth: function(fn) {
        return this.fetch('auth', fn);
    },

    isAuth: function() {

    },

};

UserApi = $.extend({}, Api, UserApi);