<?php if(empty($list)): ?>

		<p><?=Yii::t('app','no_entries');?></p> 

<?php else: ?> 

	<div class="content noPad">
	    <table class="responsive table table-bordered" >
	        <thead>
	          <tr>
	            <th width="1%"><?=Products_categories::model()->getAttributeLabel('id');?></th>
	            <th><?=Products_categories::model()->getAttributeLabel('name');?></th>
				<th><?=Products_categories::model()->getAttributeLabel('products');?></th> 
				<th><?=Products_categories::model()->getAttributeLabel('order_num');?></th> 
				
	            <th width="15px"><?=Yii::t('app','does');?></th>
	            <th width="1%" class="ch"><input id="checkAll" type="checkbox" name="checkbox" value="all" class="styled" /></th> 
	            
	          </tr>
	        </thead>
	        <tbody id="sortable" >
				<?php foreach($list as $item):
					
						if($item->active == 0) 
							$unactive = "unactive";
						else 
							$unactive = "";	
				?>
				<tr class="<?=$unactive;?>" data-id="<?=$item->id;?>" data-order="<?=$item->order_num;?>"  >
						
						<td><?=$item->id;?></td>								
					 	<td><?=$item->name;?></td>
		
						<td>
						<?php
							$countProducts = Products::model()->withCategory($item->id)->count();				 
							echo CHtml::link(Products_categories::model()->getAttributeLabel('products').' ('.$countProducts.')', array('support/products/?category_id='.$item->id));
						?>
						</td>						
						<td><?=$item->order_num;?></td> 
						<td>					
						    <div class="controls center">
						        <a href="<?=$this->createUrl('support/update/'.$model_name.'/'.$item->id)?>" title="Edit task" class="tip">
						        	<span class="icon12 icomoon-icon-pencil"></span>
						        </a>
						        <a href="<?=$this->createUrl('support/delete/'.$model_name.'/'.$item->id)?>" title="Remove task" class="tip">
						        	<span class="icon12 icomoon-icon-remove"></span>
						        </a>
						    </div>
						</td>
						<td >
							<input class="chChildren" type="checkbox" name="checkbox" value="<?=$item->id?>" class="styled" />
						</td>
				</tr>
				<?php endforeach; ?>
	        </tbody>
	    </table>
	</div>
<?php endif; ?>	 