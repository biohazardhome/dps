var Role = function(user) {
	this.user = user;
	this.role = user.role;
};

Role.prototype = {

	ADMIN: 1,
	MODER: 2,
	USER: 3,

	user: null,
	role: null,

	is: function(value) {
		return this.role && this.role.role === value;
	},

	isAdmin: function() {
		return this.is(this.ADMIN);
	},

	isModer: function() {
		return this.is(this.MODER);
	},

	isUser: function() {
		return this.role.role === this.USER;
	},

};