<?php
$w = !empty($_GET['w']) && isset($GLOBALS['DESC'][$_GET['w']]) ? $_GET['w'] : false;
$id = !empty($_GET['id']) ? $_GET['id'] : false;

/*
Trier les items
 */
if($_POST['w'] == 'sort'){
	$ids = explode(',',$_POST['ids']);
	foreach($ids as $k=>$v){
		$tmp = new Data($w,$v);
		$tmp->data['ordre']=$k;
		$tmp->save();
	}
	exit;
}

/*
Effacer un item
 */
if(isset($_GET['del'])){
	$Data = new Data($w,$id);
	$Data->deleteData();	
    redir('admin.php?w='.$w);
}

/*
Vider le cache des images et reconstruire le fichier htaccess
 */
if(isset($_GET['vidercache'])) {
	Data::viderCache();
	Data::htaccessRebuild();
	redir('back');
}

/*
Formulaire principal (enregistrement des données)
 */
if(isset($_POST['form'])) {

        $form = $_POST['form'];
        $Object = new Data($w,$form['id'],'empty');
        $id = $form['id'];
        foreach($Object->data as $k=>$v) {
			$v = $form[$k];
			if($k == 'date') {
					$v = @mktime($v['H'],$v['i'],0,$v['m'],$v['d'],$v['Y']);
			}
	        if(is_array($v)) {
				if(isset($v['links'])) {
					list($_w,$ids) = each($v['links']);
					$v = $Object->createLinks($_w,$ids);
				}
		    }
	        $Object->data[$k] = $v;
        }
		if($Object->getSpec('docs')) {
			if(!empty($form['main_image'])){
				$Object->data['main_image']=$form['main_image'];
			} else {
				$Object->data['main_image']=$Object->getLogo();
			}
		}
		$id = $Object->save();

		initDir($w,$id);
        $dir = 'data/'.$w.'/'.$id.'/';
        if($form['files_to_delete']) {
                $tab_delete = explode('|',$form['files_to_delete']);
                foreach($tab_delete as $file) {
                        if(!empty($file)) {
                                unlink($dir.$file);
				
                                $form['images_order'] = str_replace($file.'|','',$form['images_order']);
                        }
                }
        }


        file_put_contents($dir.'images.json',json_encode($form['images']));
        if(!empty($form['images_order'])) {
	        file_put_contents($dir.'images_order.txt',$form['images_order']);
	    }

		Data::htaccessRebuild();

        redir('admin.php?w='.$w.'&id='.$id);
}

if(isset($_GET['create'])){
	foreach($GLOBALS['DESC'] as $k=>$v) {

		$datas = getData($k);

		$table = array('name'=>$k,'comment'=>$v['specs']['help']);

		$champs = array('id'=>array('type'=>'INT(10)','ai'=>true));
		foreach($v as $champ=>$infos){
			if($champ!='specs') {
				$tmp = array();
				switch($infos['type']) {
					default:
						$tmp['type']='VARCHAR(255)';
					break;
					case "textarea":
					case "smalltextarea":
						$tmp['type']='TEXT';
					break;
					case "checkbox":
						$tmp['type']='TINYINT(1)';
					break;
				}
				$tmp['comment']=$infos['lib'].($infos['help']?': '.$infos['help']:'');
				$champs[$champ]=$tmp;
			} else {
				if(!empty($infos['docs'])) {
					$champs['main_image']=array('type'=>'VARCHAR(255)','comment'=>'Image principale associée');
				}
			}
		}

		$GLOBALS['bdd']->Create($table,$champs,true);

		if(is_array($datas) && count($datas)) {foreach($datas as $a=>$b) {
			insertData($k, $b);
		}}
	}

	redir('admin.php');
}