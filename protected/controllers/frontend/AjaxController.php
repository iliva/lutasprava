<?php 
class AjaxController extends FrontEndController {

	function actionOrder(){

		$request = json_decode(file_get_contents("php://input"));
		$user = $request->user;
		$cart = $request->cart;
		
		$message = 'Имя: '.mysql_escape_string($user['name']).'<br>';
		$message .= 'Телефон: '.mysql_escape_string($user['phone']).'<br>';
		$message .= 'Email: '.mysql_escape_string($user['email']).'<br>';
		
		foreach($cart["products"] as $product){
			$message .= $product["category_name"].' '.$product["name"].', '.$product["price"].' UAH, '.$product["qnty"].' шт<br>';
		}
	
		Base::sendMail(Yii::app()->params['site_email'], Yii::app()->params['manager_email'], 'Замовлення на сайті army.lurasprava.com', $message);
			
	}
	
	function actionData(){
	
			$criteria 		 = new CDbCriteria;
			$criteria->select='t.name';
			$criteria->distinct=true;
			$sizesQuery = Size::model()->published()->findAll($criteria); 
			$sizes = array();
			foreach($sizesQuery as $s) {
				$sizes[] = $s->name;
			}
			
			$products = array();
			$productsQuery = Products::model()->published()->orderByRand()->findAll(); 
			
			foreach($productsQuery as $item) {
				$sizesQuery = Size::model()->published()->withProduct($item->id)->findAll();
				$size = array();				
				foreach($sizesQuery as $s) {
					$size[] = array('name' => $s->name);
				}
				$category_name = Products_categories::model()->published()->findByPk($item->category_id)->name; 
				if($category_name !== '') {
					$products[] = array(
						'id' => $item->id,
						'category_id' => $item->category_id,
						'category_name' => $category_name,					
						'image' => $item->file_image,
						'name' => $item->name,
						'price' => $item->price,
						'size' => $size
					);
				}
			}
			
			$result = array(
				'products' => $products,
				'sizes' => $sizes,
			);
			
			header('Content-type: application/json'); 
			echo json_encode($result);
	}
	function actionProduct(){
	
			$request = json_decode(file_get_contents("php://input"));

			$product = array();
			$item = Products::model()->published()->findByPk($request->id); 
			
			$sizesQuery = Size::model()->published()->withProduct($item->id)->findAll();
			$size = array();				
			foreach($sizesQuery as $s) {
				$size[] = array('name' => $s->name);
			}
			$category_name = Products_categories::model()->published()->findByPk($item->category_id)->name; 
			if($category_name !== '' && $item->id > 0) {
				// product
				$product = array(
					'id' => $item->id,
					'category_id' => $item->category_id,
					'category_name' => $category_name,					
					'image' => $item->file_image,
					'name' => $item->name,
					'price' => $item->price,
					'size' => $size
				);
				// products within this category
				$same_products = array();
				$productsQuery = Products::model()->published()->withCategory($item->category_id)->orderById()->hasNo($item->id)->findAll();
				foreach($productsQuery as $item) {
					$same_products[] = array(
						'id' => $item->id,
						'name' => $item->name,
					);
				}				
				
			}
			$result = array(
				'product' => $product,
				'same_products' => $same_products,
			);
			header('Content-type: application/json'); 
			echo json_encode($result);
	}	

	
}