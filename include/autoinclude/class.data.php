<?php
/**
 * Data PHP class
 */
Class Data{

	var $id;
	var $w;
	var $data;
	var $docs;
	var $list;

	/**
	 * Construtor
	 * @param [type]  $w data type (name of the table in the database)
	 * @param boolean $id id number
	 * @param boolean $data the data itself
	 */
	function __construct($w,$id=false,$data=false) {
/*		if(!isset($GLOBALS['DESC'][$w])){
			die('<p><b>'.htmlspecialchars($w).'</b> unknown</p>');
		}
*/		$this->w=$w;
		if($id && $data===false) {
			if($id == 'new'){
				$data = $this->proto();
			} else {
				$data=$GLOBALS['bdd']->Get($w,$id,$this->getSpec('class_field'));
			}
		} else if($data=='empty'){
			$data = $this->proto();
		}
		$this->data=$data;
		if(is_array($this->data)){
			foreach($this->data as $k=>$v){
				$field = $this->getField($k);
				if(!empty($field['options']['links'])) {
					$tmp = explode(',', $v);
					$v = array();
					foreach($tmp as $a=>$b){
						$v[$b]=true;
					}
				}
				$this->data[$k]=$v;
			}
		}
		$this->id=$id;
		$this->docs=false;
		$this->list=array();
	}
	function proto(){
		$data = array();
		foreach($this->fields() as $k=>$v) {
			$data[$k]=isset($v['default']) ? $v['default'] : '';
		}
		return $data;
	}
	/**
	 * link between two tables
	 * @param  $w2  data type to link
	 * @param  $ids ids to link to the n°1 item 
	 * @return string CVS id
	 */
	function createLinks($w2,$ids){
		$ids = is_array($ids) ? $ids : array($ids);
		$out='';
		$GLOBALS['bdd']->Delete('links',array(
				'w1'=>$this->w,
				'id1'=>$this->id,
				'w2'=>$w2
		));
		foreach($ids as $k=>$v) {
			$Link = new Data('link');
			$Link->insertData(array(
				'w1'=>$this->w,
				'id1'=>$this->id,
				'w2'=>$w2,
				'id2'=>$v
			));
			$out.=$out?',' : '';
			$out.=$v;
		}
		return $out;
	}

	/**
	 * returns the fields of the current items database to be edited
	 * @return array
	 */
	function fields(){
		$out = array();
		foreach($GLOBALS['DESC'][$this->w] as $k=>$v) {
			if($k!='specs') {
				$out[$k]=$v;
			}
		}
		return $out;
	}

	/**
	 * All purpose getter for current $this->w data
	 * @param  boolean $params params for the getter : where, orderby, start and limit
	 * @param  boolean $other used to call the getter with only to params ie $data->get('visible',1)
	 * @return array()
	 */
	function get($params=false,$other=false){
		$key = md5(serialize($params).serialize($other));
		if(!isset($this->list[$key])) {
			if(!is_array($params)){
				if(!empty($other)){
					$tmp = array();
					$tmp['where'] = $this->getSpec('where');
					$tmp['where'][$params]=$other;
					$params = $tmp;
				} else {
					$params = array('where' => $this->getSpec('where'));
				}
			}
			$params['orderby'] = !empty($params['orderby']) ? $params['orderby'] : $this->getSpec('order');
			$this->list[$key] = $this->populateData($GLOBALS['bdd']->Select($this->w,!empty($params['where']) ? $params['where']: false,$params['orderby'],(!empty($params['start']) ? $params['start'].',' : '').(!empty($params['limit']) ? $params['limit']:'')));
		}
		return $this->list[$key];
	}
	/**
	 * Used to transform the $data given to match the Data class structure
	 * @param [type] $list list of data items
	 * @param string $field what field is the id
	 */
	function populateData($list,$field='id'){
		$out=array();
		if(is_array($list)){foreach($list as $k=>$v){
			$out[$v[$field]]=new Data($this->w,$v[$field],$v);
		}}
		return $out;
	}
	/**
	 * Build the list line for the item
	 * @return line template
	 */
	function listLib(){
		$tpl = $this->getSpec('lib_list');
		foreach($this->data as $k=>$v){
			if(strstr($tpl, '#'.$k)!==false){
				if($k == 'pub') {
					$v = '<img src="images/pub_'.$v.'.png">';
				} else if($k == 'date') {
					$v = mefDate($v);
				}

				$tpl = str_replace('#'.$k,$v,$tpl);
			}
		}
		return $tpl;
	}
	/**
	 * Get the main image of the item
	 * @return string
	 */
	function getLogo($w=false,$h=false){
		if($this->getSpec('docs')){
			if(isset($this->data['main_image'])){
				return str_replace($GLOBALS['chemin_site'],'',$this->data['main_image']);
			} else {
				$docs = $this->getDocs();
				foreach($docs as $doc){
					if(Image::isImage($doc)){
						if($w || $h){
							return Url::image($doc,$w,$h);	
						} else {
							return str_replace($GLOBALS['chemin_site'],'',$doc);	
						}
					}
				}
			}
		}
		return false;
	}
	/**
	 * Get all documents attached to the item
	 * @return array()
	 */
	function getDocs(){
		if(!is_array($this->docs)){
			$dir = $GLOBALS['chemin_site'].'data/'.$this->w.'/'.$this->id.'/';
			if($images = @file_get_contents($dir.'images.json')){
				$images = json_decode($images,true);
			} else {
				$images = array();
			}
//			$image_orders = explode('|',@file_get_contents($dir.'images_order.txt'));
			$docs = array();
			foreach($images as $entry =>$legend){
				if(!empty($entry)) {
					$docs[$entry]=$dir.($entry);
				}
			}
			if($d = @dir($dir)) {
				while (false !== ($entry = $d->read())) {
					if(Image::isImage($entry)) {
						$docs[$entry] = new File($dir.$entry,$images[$entry]);
					}
				}
				$d->close();
			}
			$this->docs = $docs;
		}
		return $this->docs;

	}
	/**
	 * get the url of the item
	 * @param  $html return url with .html (or .php)
	 * @return string
	 */
	function url($html=true){
		if($html) {
			$url = Url::clean($this->data[$this->getSpec('lib_field')]).'.html';
//			$url = Url::clean($this->data[$this->getSpec('lib_field')]).'-'.$this->w.'-'.$this->id.'.html';
		} else {
			$url = $this->w.'.php?id='.$this->id;
		}
		return $url;
	}
	function isField($field){
		if($field !='specs' && ($field == 'id' || isset($GLOBALS['DESC'][$this->w][$field]))) {
			return true;
		} else return false;
	}
	/**
	 * Get field for the current item type
	 * @param  $field field we want
	 * @return ?
	 */
	function getField($field) {
		if(isset($GLOBALS['DESC'][$this->w][$field])) {
			return $GLOBALS['DESC'][$this->w][$field];
		} else 	return false;
	}

	/**
	 * Get description for the current item type
	 * @param  $field field we want
	 * @return ?
	 */
	function getSpec($field) {
		if(isset($GLOBALS['DESC'][$this->w]['specs'][$field])) {
			return $GLOBALS['DESC'][$this->w]['specs'][$field];
		} else 	return false;
	}

	public function __get($name){
		if($ret = $this->getSpec($name)){
			return $ret;
		} else if(!empty($this->data[$name])){
			return $this->data[$name];
		} else return false;
	}

	function insertData($data=false) {
		$data = $data ? $data : $this->data;
		foreach($data as $k=>$v){
			$field = $this->getField($k);
			if(is_array($v) && $field['options']['links']){
				$data[$k]=implode(',',array_keys($v));
			}
		}
		if($GLOBALS['bdd']->Insert($data,$this->w)) {
			return $GLOBALS['bdd']->LastInsertID();
		} else return false;
	}

	function deleteData() {
		$File = new File('data/'.$this->w.'/'.$this->id);
		$File->rmdir();
		return $GLOBALS['bdd']->Delete($this->w,array('id'=>$this->id));
	}

	function updateData($data=false) {
		$data = $data ? $data : $this->data;
		foreach($data as $k=>$v){
			$field = $this->getField($k);
			if(is_array($v) && $field['options']['links']){
				$data[$k]=implode(',',array_keys($v));
			}
		}
		return $GLOBALS['bdd']->Update($this->w,$data,array('id'=>$this->id));
	}

	public static function viderCache(){
		$File = new File();
		$File->rmdir($GLOBALS['chemin_site'].'CACHE');
		$File->rmdir($GLOBALS['chemin_site'].'IMG');
	}
	public static function htaccessRebuild(){
		$htaccess = file_get_contents($GLOBALS['chemin_site'].'.htaccess.edit');
		$parsed = parse_url($GLOBALS['url_site']);
		$htaccess = str_replace('%root%', $parsed['path'], $htaccess);
		$Pages = new Data('pages');
		foreach($Pages->get() as $k=>$v) {
			$htaccess.="\n".'RewriteRule   ^'.$v->data['uri'].'$						page.php?id='.$v->data['id'].'  [L,QSA]';
		}
		$Projects = new Data('projects');
		foreach($Projects->get() as $k=>$v){
			$htaccess.="\n".'RewriteRule   ^'.$v->url().'$								projects.php?id_project='.$k.'  [L,QSA]';
		}
		return file_put_contents($GLOBALS['chemin_site'].'.htaccess',$htaccess);
	}
	function save($data=false){
        if($this->id == 'new' || empty($this->id)) {
            $this->id = $this->insertData($data);
        } else {
            $this->updateData($data);
        }
        return $this->id;
	}

}
