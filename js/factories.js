	myApp.factory('getData', function($http, $cookies){	
		var hash = Math.floor((Math.random()*9999)+1000);
		return {		
			// fetch data from json files
			read: function(result, file){
				$http.get('/json_'+hash+'/'+file+'.json')
					.success(function(data, status, headers, config){
						result(data);
					})
					.error(function(data, status, headers, config){
						$log.warn(data, status, headers, config);
					});	
			},		
			// fetch cart data from cookie
			cart: function(){
							
				var cookie_value = $cookies.get('cart_cookie');
				var products = cookie_value ? JSON.parse(cookie_value) : [];
				var price = 0;
				var qnty = 0;
				products.forEach(function(item){
					price += +item.price*item.qnty;
					qnty += +item.qnty;
				});
				var description = qnty > 0 ? qnty+' '+ this.titleNumber(qnty,['товар','товара','товарів'])+' на '+price+' грн' : 'пустий';
			
				return {
					products: products, // products array
					qnty: qnty,         // total quantity of products
					total: price,		// total sum
					description: description   // text for cart at header
				}
				
			},
			titleNumber: function(number, titles){
				cases = [2, 0, 1, 1, 1, 2];  
				return titles[ (number%100>4 && number%100<20) ? 2 : cases[(number%10<5) ? number%10:5] ]; 
			},

				
		}	
	});	