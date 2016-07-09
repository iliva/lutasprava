	// Get number of products in category
	myApp.filter('numberInCategory', function(){
		return function(obj, category_id, chosenSize){
			var i = 0;
			angular.forEach(obj, function(val, key){
				if(val.category_id === category_id || category_id === 0) {
					if(chosenSize == false) i++; 
					else {
						val.size.forEach(function(s){ 
							if(chosenSize == s.name) i++; 
						});	
					}
				}
			});
			return i;
		}
	})
	// Get number of products with size
	.filter('numberWithSize', function(){
		return function(obj, size_id, chosenCategory){
			var i = 0;
			angular.forEach(obj, function(val, key){
				var addItem = false;
				val.size.forEach(function(s){ 
					if(size_id == s.name || size_id == 0) {
						if(chosenCategory == false) addItem = true;
						else if(val.category_id == chosenCategory) addItem = true;
					}  
				});
				if(addItem) i++;
			});
			return i;
		}
	})