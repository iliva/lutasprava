<?php
class Size extends BaseModel{
	
	public 	$transfer_type = true, // есть перевод
			$select_order = array('added_time DESC'), 
			$act = array('delete', 'active', 'de-active'),
			$search = array(),
			$modelName = 'Size';

			
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	public function tableName(){
		return $this->tablePrefix().'size';
	}


	public function rules()	{
		return array(
			array('active', 'numerical', 'integerOnly'=>true),
			array('name', 'required'),
			array('product_id, added_time, edited_time, modified_by, created_by', 'default'),
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
			'product_id' 		=> 'товар',
			'active'			=> 'актив',
		);
	}

	public function relations(){

		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
					'product' => array(self::BELONGS_TO,'Products','product_id'),
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
	
	protected function afterDelete(){

  		parent::afterDelete();    
											
  	   
		return true;
  	}	
	
  	public function getUrl(){

  		return '/'.Base::findControllerAlias('C_size').'/'; 
  	}		
	

			
}