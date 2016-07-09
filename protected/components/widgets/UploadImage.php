<?php
/*
��� ������ �������:

jquery.form.js, 
jquery.imgareaselect.min.js, 
crop-image.js

bootstrap.css - ������ img width: 100%

class Image: 
	public function resizeThumbnailImage
	public function addBg
	public function scaleMin
	
AjaxController:
	utf-8 ��� boom
	public function actionImageCropUpload
	public function actionImageCropThumbnail
	public function actionImageDelete
	public function actionImageAddFields	
	
������:
	������ �������� �� ������� ���� �����,
	� ����� �������� ����: $form->hiddenField($model,'file_image',array('class' => 'upload_image'));

�����:
	rules - ��� image_delete, image
	beforeSave - ��� �������� �����������
	afterSave - $this->deleteTmpImages($this->file_image);
	afterDelete - �������� ����������� ������� �������� � public function deleteImages + deleteTmpImages	
	
������ /img/messagebox_info.png	
	
*/
class UploadImage extends CWidget {

	public $original;
	public $crop;
	public $scale;
	public $file_image;
	public $modelName;
	

	public function run() { 

		
        $this->render('uploadImage', array('modelName' => $this->modelName,
											'file_image' => $this->file_image,
											'original' => $this->original,
											'crop' => $this->crop,
											'scale' => $this->scale));
    }
} 