<?php
class Products_categories extends BaseModel{
	
	public 	$transfer_type = true, // есть перевод
			$select_order = array('order_num', 'added_time DESC'), 
			$act = array('delete', 'active', 'de-active'),
			$search = array();	

			
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return $this->tablePrefix().'products_categories';
	}


	public function rules()	{
		return array(
			array('order_num, active', 'numerical', 'integerOnly'=>true),
			array('added_time, edited_time, modified_by, created_by, name', 'default'),
		);
	}

    protected function beforeValidate() {
		parent::beforeValidate();
			if($this->isNewRecord) {
				$this->added_time = date('Y-m-d H:i:s');
				$this->created_by = Yii::app()->user->id;
			}
			
			$this->edited_time = date('Y-m-d H:i:s'); 
			$this->modified_by = Yii::app()->user->id;

		return true;
	} 	

	public function attributeLabels(){
		return array(
			'id' 				=> 'ID',
			'products' 			=> 'товари',
			'order_num'			=> 'порядок',
			'name'				=> 'назва',
			'active'			=> 'актив',
		);
	}

	public function relations(){

		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
					'products' => array(self::HAS_MANY,'Products','category_id'),
					);
	}
	

	protected function beforeSave(){

		parent::beforeSave(); 
		
		
		return true;
	}	
	
	protected function afterSave(){ 

  		parent::afterSave();  
  

		return true;
  	}		

  	protected function beforeDelete(){

  		parent::beforeDelete();  

		// удаление товаров 
		$products = Products::model()->withCategory($this->id)->findAll();
		foreach($products as $products) {
			Products::model()->findByPk($products->id)->delete();
		}	
 																						
  		return true;
  	}
	
	protected function afterDelete(){

  		parent::afterDelete();    
											
  
		return true;
  	}

  	public function getUrl(){

  		return '/'.Base::findControllerAlias('C_products_categories').'/'; 
  	}	


}