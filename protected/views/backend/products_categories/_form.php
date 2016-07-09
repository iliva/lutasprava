<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'products-categories',

	'enableAjaxValidation'=>false, 
	
	'htmlOptions'=>array(
         'enctype'=>'multipart/form-data',
         'class' => 'admin-form',
     ),

	
)); ?>
	


	<?php echo $form->errorSummary($model); ?>
 
 	<div class="row-top"> 
		<div class="row-elements">

			<div class="block">
				<?php echo $form->labelEx($model,'name'); ?>
				<?php echo $form->textField($model,'name'); ?>
				<?php echo $form->error($model,'name'); ?>
			</div>
			
			<div class="block">
				<?php echo $form->labelEx($model,'order_num'); ?>
				<?php echo $form->textField($model,'order_num'); ?>
				<?php echo $form->error($model,'order_num'); ?>
			</div>

			<div class="block">
				<?php echo $form->labelEx($model,'active'); ?>
				<?php echo $form->checkBox($model,'active',array('class' => 'ibutton nostyle')); ?>
				<?php echo $form->error($model,'active'); ?>
			</div>  

		</div>
	</div>	 
 

	<div class="clear"></div> 
    <button class="btn btn-primary"><?php echo ($model->isNewRecord ? Yii::t('app','add') : Yii::t('app','save')) ?></button> 
	
	<?php $this->widget('application.components.widgets.AdminAuthor', array( 'model' => $model )); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->