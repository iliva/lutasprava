// body scroll
myApp.directive("scroll", function ($window, $document, $location) {
	return {
		controller: function($scope, $element, $attrs){
			
			// home sections 
			$scope.sections = [
				{code:'terms', name:'Умови акції'},
				{code:'mission', name:'Місія'},
				{code:'sizes', name:'Оберіть розміри'},
				{code:'payment', name:'Оплата і доставка'}
			];
			// site pages
			$scope.pages = [
				{code:'shop', name:'Магазин'},
				{code:'with_us', name:'Уже з нами'},
			];			
						
			$scope.$root.goToStart = false;
			$scope.fixed = false; 
			
			angular.element('body').show();	

			// check if selected item is from home page
			$scope.homePage = function(page){
				var home = true;
				$scope.pages.forEach(function(item, i){
				   if(page == item.code) home = false;
				});				
				return home;
			}
			
			
			// main menu click
			$scope.goTo = function(elm){
				
				// from home page
				if($scope.homePage($scope.$root.menuActive)) {
					
					$scope.$root.goToStart = true;	
					angular.element("html, body").animate({
						scrollTop: ( (elm == 'home') ? 0 : document.getElementById(elm).offsetTop - 150)
					}, 500, 0, function(){ 
						$scope.$root.goToStart = false; 
					});
					$scope.$root.menuActive = elm;	
				// from site page	
				} else {				
					$location.path('/section/'+elm);
				}
			}

			// scroll on home page
			angular.element($window).bind("scroll", function() {
				
				var pageTop = angular.element($document).scrollTop();
				// stuck up top menu while scrolling
				(pageTop > 50 && !$scope.fixed || pageTop > 20 && $scope.fixed) ? $scope.fixed = true : $scope.fixed = false;
				if($scope.homePage($scope.$root.menuActive) && !$scope.fixed){
					$scope.$root.menuActive = 'home';					
				}		
				$scope.$broadcast('scroll-window');	
				$scope.$apply();
			});
		}
	}
})

// home texts 	
.directive("homeText", function($window, $document){
	return {
		restrict: 'EA',
		replace: true,	
		transclude: true,
		require: '^scroll',
		templateUrl: '/protected/views/frontend/templates/homeText.html',
		scope: {
			position: "@",
			title: "@",
			logo: "@",			
		},
		link: function(scope, element, attrs, ctrl){	
			
			var window_height = angular.element($window).height();
			
			if(angular.element($document).scrollTop() + window_height > element.prop('offsetTop'))	{
				scope.show = true;	
			}
			// change selected menu while scrolling
			scope.$on('scroll-window', function() {	
				if(angular.element($document).scrollTop() + (window_height/2) > element.prop('offsetTop'))	{
					scope.show = true;
					if(!scope.$root.goToStart)	{
						scope.$root.menuActive = attrs.id;
					}
				} 
			});	
		}
	}
})

// with us photos 	
.directive("withUs", function($window, $document){
	return {
		restrict: 'EA',	
		replace: true,	
		transclude: true,
		require: '^scroll',
		templateUrl: '/protected/views/frontend/templates/withUsItem.html',
		scope: {
			picture: "@",
			name: "@",			
		},
		link: function(scope, element, attrs, ctrl){
			
			var window_height = angular.element($window).height();
			
			if(250 > element.prop('offsetTop'))	{
				scope.show = true;	
			}
			scope.$on('scroll-window', function() {	
				if(angular.element($document).scrollTop() + (window_height/2) > element.prop('offsetTop'))	{
					scope.show = true;	
				}
			});	
	
		}
	}
})			