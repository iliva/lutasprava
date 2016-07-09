var myApp = angular.module('MyApp', ['ngRoute', 'angular.filter', 'ngCookies'])
	myApp.config(function($routeProvider, $locationProvider){
		$routeProvider
			.when('/', {
				controller: 'HomeController',
				templateUrl: '/protected/views/frontend/templates/home.html',
			})		
			.when('/section/:name', {
				controller: 'HomeController',
				templateUrl: '/protected/views/frontend/templates/home.html',
			})			
			.when('/with_us', {
				controller: 'WithUsController',
				templateUrl: '/protected/views/frontend/templates/withUs.html',
			})			
			.when('/shop', {
				controller: 'ShopController',
				templateUrl: '/protected/views/frontend/templates/shop.html',
			})	
			.when('/product/:id', {
				controller: 'ProductController',
				templateUrl: '/protected/views/frontend/templates/product.html',
			})
			.when('/cart', {
				controller: 'CartController',
				templateUrl: '/protected/views/frontend/templates/cart.html',
			})			
			.otherwise({
				redirectTo: '/'
			});
	
		$locationProvider.html5Mode({
			enabled: true,
			requireBase: false
		}), 
		$locationProvider.html5Mode(true);	
	});	
