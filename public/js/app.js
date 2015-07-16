app.config(function configure($routeProvider) {
	$routeProvider.when('/', {controller: 'UsersController', templateUrl: './Users/Login'})
});