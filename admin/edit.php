<?php 
		$Data = new Data($w,$id);
        ?>
        <form id="form_upload" action="admin.php?w=<?php echo $w;?>" method="post" enctype="multipart/form-data">
        <div>
        <input type="submit" class="bouton" value="Valider">
        <?php if(!$Data->getSpec('unique')){?>
	        <input type="button" class="bouton" value="Retour" onclick="window.open('admin.php?w=<?php echo $w;?>','_self')">        
	    <?php }?>
	   	</div>
        <?php echo getInputForm('hidden','id',$id);?>
        <?php $cpt=0;foreach($Data->fields() as $field => $desc) {
			$cpt++;$_id='form_'.$cpt;?>                
                <div class="both">
                <div class="admin_c1"><?php echo $desc['lib'];?></div>

                <?php $val = $Data->data[$field];?>
                <div class="admin_c2">
			<?php echo getInputForm($desc['type'],$field,$val,$_id,'form',false,array('id'=>$id) + (isset($desc['options']) ? $desc['options'] : array()));?>
			<?php dohelp($desc['help']);?>
		</div>

                <div class="both"></div>
                </div>
        <?php }?>
    <?php if(getSpec($w,'docs')){?>
	    <?php if($id == 'new') {?>
	        <div class="admin_c1">Upload de documents</div>
	        <div class="admin_c2">
	                <input type="submit" class="bouton" name="open_upload" value="Cliquez ici pour uploader des documents">	
		</div>
		<?php } else {?>
			<?php if(isset($Data->data['main_image'])){?>
		        <div class="admin_c1">Vignette principale</div>
		        <div class="admin_c2">
				<?php if($Data->mainImage()){?>
					<img src="<?php echo Url::image($Data->mainImage(),100,100);?>">
				<?php }?>
				</div>
			<?php }?>
	        <div class="admin_c3">
		</div>
	        <div class="both"></div>
	
	        <div class="admin_c1">Upload de documents</div>
	        <div class="admin_c2">
			<?php if($id=='new'){?>	
	               		<input type="button" class="bouton" value="Cliquez ici pour uploader des documents" onclick="this.style.display='none';g('upload').style.display='block';">
	                	<div id="upload" class="hidden">
			<?php } else {?>
				<div>
			<?php }?>

					<input type="file" name="file_upload" id="file_upload" />
					</div>


	         
	                <div class="both">
	                <b>Images</b> <!-- <a href="dld.php?d=<?php echo $w;?>&id=<?php echo $id;?>&img">zip</a> -->
			<?php dohelp('Glisser et d&eacute;placer pour changer l\'ordre');?>
			<?php dohelp('Cocher la case pour choisir la vignette principale');?>
	                </div>
	                <div class="margin">
	                <ul id="boxes">
	                        <?php 
	                        $order='';
	                        $cpt=0;
	                        foreach($Data->getDocs() as $idx=>$Doc) {$cpt++;?>
	                                <?php if($Doc->is('image')) {
											if(!$Data->mainImage()) {
												$Data->mainImage($Doc->basename());
											}
//											$order.=$Doc->basename().'|';
											?>
	                                        <li class="block left margin small" id="li_<?php echo $cpt;?>" file="<?php echo $Doc->basename();?>">
	                                        <div class="box-in">
		                                        <img src="<?php echo Url::image($Doc->path(),150,150);?>" class="img">
		                                        <div class="box-text">
		                                        <b><?php echo $Doc->shortName();?></b>
		                                        <div><?php echo $Doc->type();?></div>
		                                        <a href="javascript:delFichier(<?php echo $cpt;?>)"><img src="images/trash.gif"></a>
		                                        <a href="<?php echo $Doc->url();?>" class="fancybox"><img src="images/eye.gif"></a>
		                                        <a href="<?php echo Image::pixlrUrl($Doc->path());?>" <?php echo popOpen(800,600,'edit');?>><img src="images/edit.gif"></a>
												<input type="radio" name="form[main_image]" value="<?php echo $Doc->basename();?>" <?php echo $Data->isMainImage($Doc->basename()) ? 'checked' : '';?>>
												</div>
											</div>

											<div class="box-legende"><input name="form[images][<?php echo $Doc->basename();?>]" tabindex="<?php echo $cpt;?>" value="<?php echo htmlspecialchars($Doc->text);?>" placeholder="Légende de la photo" type="text"></div>
	                                        </li>
	                                <?php }?>
	                        <?php }?>
	                </ul>
	                <input type="hidden" name="form[images_order]" value="<?php echo $order;?>" id="images_order">
	                <input type="hidden" name="form[files_to_delete]" value="" id="files_to_delete">
	                </div>
	        </div>
	        
	        <?php }?>
        <?php }?>
        <div class="both"><p></p>
        <input type="submit" class="bouton" value="Valider">
        <?php if(!$Data->getSpec('unique')){?>
	        <input type="button" class="bouton" value="Retour" onclick="window.open('admin.php?w=<?php echo $w;?>','_self')">
			<?php if($id!='new') {?>
				 &nbsp; <small><a href="admin.php?w=<?php echo $w;?>&id=<?php echo $id;?>&del" <?php echo confirmLink();?>>Effacer cet item</a></small>
			<?php }?>
        <?php }?>
        </div>
        </form>

<script>initUploadify("<?php echo $w;?>","<?php echo $id;?>");</script>