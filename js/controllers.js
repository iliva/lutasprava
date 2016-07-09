
	// home page
	myApp.controller('HomeController', function($scope, $routeParams, $route, $window){ 

		// banner images
		$scope.banner = ['1.png','2.png','3.png','4.png','5.png','6.png'];		
		$('#banner').carousel();
		
		// active menu & page title by default
		$scope.$root.menuActive = 'home';	
		$scope.$root.pageTitle = 'Люта Справа | Ukrainian Armed Forces | Хлопцям багато футболок не буває';	
		
		// when section is selected
		if($routeParams.name && document.getElementById($routeParams.name)) {
			// set menu active
			$scope.$root.menuActive = $routeParams.name;

			// scroll to the section
			setTimeout(function(){ 
				angular.element("html, body").animate({
					scrollTop: (document.getElementById($routeParams.name).offsetTop - 150)
				}, 500);
			},1000);			
		}

		

	})
	
	// with us page
	.controller('WithUsController', function($scope, getData){ 

		// active menu & page title
		$scope.$root.menuActive = 'with_us';
		$scope.$root.pageTitle = 'Уже з нами | Ukrainian Armed Forces';	
		
		// fetch with us photos and sizes
		getData.read(function(data){
			$scope.with_us = data;	
		}, "withus");	
	
	})
	
	// shop page
	.controller('ShopController', function($scope, getData, $cookies){ 
		
		// active menu & page title
		$scope.$root.menuActive = 'shop';
		$scope.$root.pageTitle = 'Магазин | Ukrainian Armed Forces';	

		// fetch products and sizes		
		getData.read(function(data){
			$scope.products = data;	
		}, "products");		
		getData.read(function(data){
			$scope.sizes = data;	
		}, "sizes");				
	
		// choose/reset category and size
		$scope.chosenCategory = false;
		$scope.chosenSize = false;
		$scope.ResetCategories = function(){
			$scope.chosenCategory = false;
		}
		$scope.ResetSizes = function(){
			$scope.chosenSize = false;
		}		
		$scope.ChooseCategory = function(id){
			$scope.chosenCategory = id;
			$scope.chosenSize = false;
		}
		$scope.ChooseSize = function(name){
			$scope.chosenSize = name;
			$scope.chosenCategory = false;
		}	
		
		// product filters
		// category or size is chosen
		$scope.ProductShow = function(item){
			var show = false;
			if($scope.chosenCategory == false && $scope.chosenSize == false) show = true;
			item.size.forEach(function(s){ 
				if($scope.chosenSize == s.name) show = true;  
			});
			if($scope.chosenCategory == item.category_id) show = true;
			return show; 
		}
		
	})
	
	
	// product page
	.controller('ProductController', function($scope, $routeParams, $cookies, $rootScope, $location, $timeout, getData){			
		// active menu
		$scope.$root.menuActive = 'shop';
		
		getData.read(function(data){
			// get current product
			$scope.product;
			$scope.same_products = new Array;
			data.forEach(function(item){
				if(item.id == $routeParams.id) {					
					$scope.product = item;
					// page title
					$scope.$root.pageTitle = item.category_name + ' ' + item.name + ' | Ukrainian Armed Forces';	
					// select first size
					$scope.selectedSize = item.size[0].name;
					
				}
			});	

			// find connected products (another colours)
			data.forEach(function(item){
				if(item.category_name == $scope.product.category_name && item.id != $scope.product.id) 
					$scope.same_products.push(item);	
			});				
		}, "products");
		
		// select size
		$scope.selectSize = function(item){
			$scope.selectedSize = item;
		}		

		// add to cart
		$scope.BuyProduct = function(item, selectedSize){
			
			item.selectedSize = selectedSize;

			var cart = getData.cart();
			var new_item = true
			// add quantity if the same product exists
			cart.products.forEach(function(product){
				if(product.id == item.id && product.selectedSize == item.selectedSize) { 
					product.qnty++;	
					new_item = false; 
				}	
			});			
			
		
			// add new item if it doesn't exist
			if(new_item) cart.products.push(item);
			// put cookie
			$cookies.put('cart_cookie', JSON.stringify(cart.products));
			$rootScope.$broadcast("set-cookie");
		}
		
		// go to cart from modal window
		$scope.goToCart = function(){
			$('#addProductModal').modal('hide');
			var myTimeout = $timeout(function(){
				$location.path('/cart');
			}, 500);	
		}
		
			
	})
	// cart info at the top
	.controller('CartHeader', function($scope, $rootScope, getData){
		
		$scope.cart = getData.cart();
		$rootScope.$on('set-cookie', function() { 
			$scope.cart = getData.cart();
		});
	})
	// cart page
	.controller('CartController', function($scope, $cookies, $rootScope, $timeout, $http, getData){

		// active menu & page title
		$scope.$root.menuActive = 'shop';
		$scope.$root.pageTitle = 'Кошик | Ukrainian Armed Forces';

		// show cart data
		$scope.cart = getData.cart();
		
		// delete item from cart
		$scope.deleteCartItem = function(id, selectedSize){
			$scope.cart = getData.cart();
			$scope.cart.products.forEach(function(product, i){
				if(product.id == id && product.selectedSize == selectedSize) { 	
					product.qnty--;	
					$scope.cart.total = $scope.cart.total - product.price;
					if(product.qnty == 0) {
						$scope.cart.products.splice(i, 1);
					}	
				}	
			});		
			$cookies.put('cart_cookie', JSON.stringify($scope.cart.products));
			$rootScope.$broadcast("set-cookie");
		}
	
		// send order
		$scope.orderProcessing = false;
		$scope.formSubmit = function(){
			$scope.orderError = false;
			$scope.orderFillError = false;

			if ($scope.order.$valid) {
				$scope.orderProcessing = true;
				$http({
				  method: 'POST',
				  url: '/ajax/order',
				  data: {'user': $scope.user, 'cart': $scope.cart },
				}).success(function(data, status, headers, cfg) {
					// empty cookie array if success
					$cookies.put('cart_cookie', []);
					$rootScope.$broadcast("set-cookie");
					$scope.cart = getData.cart();
					$scope.orderSent = true;
					$scope.orderProcessing = false;
				}).error(function(data, status, headers, cfg) {
					$scope.orderError = true;
					$scope.orderProcessing = false;
				});	
			} else {
				$scope.orderFillError = true;
			}

		}		
	})
