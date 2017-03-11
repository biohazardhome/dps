var PlacemarkPolicy = function(user) {
	this.user = user;
	this.role = new Role(user);
	// console.log(this.role);
};

PlacemarkPolicy.prototype = {

	user: null,
	role: null,

	universal: function(placemark) {
		// console.log(placemark, user);
		// console.log(this.role.isAdmin(), this.role.isModer())
		return this.role && (this.role.isAdmin() || this.role.isModer()) ||
			(placemark && placemark.user_id === user.id);
	},

	store: function(placemark) {

	},

	update: function() {

	},

	delete: function() {

	},

};