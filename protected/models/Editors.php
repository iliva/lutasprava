<?php 
 
class Editors extends BaseModel {
 

	public 	$transfer_type = false,
			$confirm_password,
			$password_new,
			$search = array('email','fullname'),
			$act = array(),
			$select_order = array(),
			$role;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){

		return $this->tablePrefix().'editors';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules(){

		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, fullname', 'required'),
			array('active', 'numerical', 'integerOnly'=>true),
			array('email, fullname', 'length', 'max'=>255),
			array('password', 'length', 'max'=>32),
			array('added_time, edited_time, password, confirm_password, password_new, role', 'safe'),

			 
		);
	}


	
	/**
	 * @return array relational rules.
	 */
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	} 
	 

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels(){

		return array(
			'id' 			=> 'ID',
			'email' 		=> 'Email',
			'password' 		=> 'Password',
			'fullname' 		=> 'Fullname',
			'added_time' 	=> 'Added Time',
			'edited_time' 	=> 'Edited Time',
			'active' 		=> 'Active',
		);
	}

	protected function beforeSave(){

		parent::beforeSave(); 

		if($this->isNewRecord){

			$result = $this->find('email = :email', array(':email' => $this->email));

			if($result){
				return false;
			} 
		}

		if($this->password_new != '' && $this->password_new == $this->confirm_password){
			$this->password = md5($this->password_new);
		} 
        return true;
    }


    private function getRole(){

    	$result = Yii::app()->db->createCommand()
			    ->select('*')
			    ->from($this->tablePrefix().'auth_assignment')  
			    ->where('userid = '.$this->id)
			    ->queryRow();  

		return $result; 
    }

    public static function getUserRole($userid){

    	$result = Yii::app()->db->createCommand()
			    ->select('itemname')
			    ->from(Editors::model()->tablePrefix().'auth_assignment') 
			    ->where('userid = '.$userid)
			    ->queryRow();  

		return $result['itemname']; 
    }

    protected function afterFind(){

    	parent::afterFind();

    	$result = $this->getRole();

		if($result){
			$this->role = $result['itemname'];
		}
			    
    	return true;
    }

    protected function afterSave(){ 

  		parent::afterSave();  

		$sql="SELECT userid FROM ".$this->tablePrefix()."auth_assignment WHERE userid = ".$this->id;
		$command = Yii::app()->db->createCommand($sql);
		$c = $command->queryRow();
		if($c['userid'] > 0) {
		
			// update
			
  			$result = $this->getRole();
	  		if($result){
	  			if($this->role != $result['itemname']){
	  				$this->changeRole($this->role);
	  			}
			}	
			
		} else {
		
			// insert
			
  			$sql="INSERT INTO ".$this->tablePrefix()."auth_assignment(itemname, userid) VALUES(:itemname,:userid)";

			$command=Yii::app()->db->createCommand($sql);  
			$command->bindParam(":itemname",$this->role); 
			$command->bindParam(":userid",$this->id);
			$command->execute(); 			
		}

  		return true;
  	}

    public function getRolesList(){

    	$results = Yii::app()->db->createCommand()
			    ->select('*')
			    ->from($this->tablePrefix().'auth_item items') 
			    ->where('active = 1') 
			    ->queryAll();  

  		$result = array();
  		if(count($results) > 0){
  			foreach($results as $items){
  				$result[$items['name']] = $items['name'].'('.$items['description'].')';
  			} 
  		}
		return 	$result;   
    }

    public function changeRole($new_role){
 
		$sql="UPDATE ".$this->tablePrefix()."auth_assignment 
					SET itemname = :itemname
					WHERE userid = :userid";

		$command=Yii::app()->db->createCommand($sql); 

		$command->bindParam(":itemname",$new_role,PDO::PARAM_STR); 
		$command->bindParam(":userid",$this->id,PDO::PARAM_STR);
		$command->execute();
 
    } 

    protected function beforeValidate(){
    	
    	parent::beforeValidate();
		
		if($this->isNewRecord) {
			$this->added_time = date('Y-m-d H:i:s');
		}	
		$this->edited_time = date('Y-m-d H:i:s'); 		

    	if($this->role == NULL){
    		return false;
    	} 
    			
    	if($this->isNewRecord && ($this->password_new == NULL && $this->confirm_password == NULL)){
    		return false;	
    	} 

    	return true;
 	}

    protected function beforeDelete(){

 		parent::beforeDelete(); 
 		
 		$sql="DELETE FROM ".$this->tablePrefix()."auth_assignment 
 					WHERE userid = :userid";

		$command=Yii::app()->db->createCommand($sql);   
		$command->bindParam(":userid",$this->id,PDO::PARAM_STR);
		$command->execute(); 

 		return true;	
 	}

 	public function getList(){ 

 		$editorItems = Editors::model()->published()->findAll();

 		$editorsArray = array();
 		if($editorItems){
 			foreach($editorItems as $Editors){

 				$editorsArray[$Editors->email] = $Editors->fullname.' ('.$Editors->email.')';
 			}
 		}

 		return $editorsArray;
 	}

}