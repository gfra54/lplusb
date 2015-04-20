<?php
	$list = $Data->get();
        $total = count($list);
?>
       
        <a href="?w=<?php echo $w;?>&amp;id=new"><?php echo $Data->lib_new;?></a>
        <?php if($total) {?>
                <div class="margin">
                <form method="post" action="admin.php?w=<?php echo $w;?>">
                <ul <?php echo $Data->getSpec('sortable') ? 'id="sortable"' :'';?>>
                <?php $cpt=0;

                if(is_array($list)){foreach($list as $_id=>$_data) {?>
                        <li> &nbsp;  
                        <input type="checkbox" name="del_list[]" id="check_<?php echo $cpt++;?>" value="<?php echo $_id;?>">
                        <a href="?w=<?php echo $w;?>&amp;id=<?php echo $_id;?>"><?php echo $_data->listLib();?></a>
                        </li>
                <?php }}?>
                </ul>
                <br>
                <small><a href="javascript:sel('all')">Tout</a> / <a href="javascript:sel('none')">Rien</a></small>
                <input type="submit" value="Effacer les items coch&eacute;s" class="bouton">
                </form>
                <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                <?php if($_SESSION['login']['login'] == 'admin'){?>
                <input type="button" value="Effacer tous les éléments de ce type" onclick="if(confirm('Etes vous sÃ»r ?')) { window.open('admin.php?d=<?php echo $w;?>&delall','_self')}" class="bouton">
				<?php }?>
                </div>

        <?php }?>