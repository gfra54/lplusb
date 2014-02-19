<?php
/**
 * Data PHP class
 */
Class Data{

	var $id;
	var $w;
	var $data;
	var $list;
	var $docs;
	/**
	 * Construtor
	 * @param [type]  $w data type (name of the table in the database)
	 * @param boolean $id id number
	 * @param boolean $data the data itself
	 */
	function __construct($w,$id=false,$data=false) {
		$this->w=$w;
		if($id && !$data) {
			if($id == 'new'){
				$data = array();
				foreach($this->fields() as $k=>$v) {
					if(isset($v['data'])) {
						$data[$k]=isset($v['default']) ? $v['default'] : '';
					}
				}

			} else {
				$data=$GLOBALS['bdd']->Get($w,$id);
			}
		}
		$this->id=$id;
		$this->data=$data;
		$this->docs=false;
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
			insertData('links',array(
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
		if(!is_array($params) && !empty($other)){
			$tmp = array();
			$tmp['where'] = $this->getSpec('where');
			$tmp['where'][$params]=$other;
			$params = $tmp;
		}
		echo "test";
		$params['orderby'] = !empty($params['orderby']) ? $params['orderby'] : $this->getSpec('order');
		return $this->populateData($GLOBALS['bdd']->Select($this->w,!empty($params['where']) ? $params['where']: false,$params['orderby'],(!empty($params['start']) ? $params['start'].',' : '').(!empty($params['limit']) ? $params['limit']:'')));
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
			if(false && isset($this->data['main_image'])){
				return str_replace($GLOBALS['CHEMIN_SITE'],'',$this->data['main_image']);
			} else {
				$docs = $this->getDocs();
				foreach($docs as $doc){
					if(Image::isImage($doc)){
						if($w || $h){
							return Url::image($doc,$w,$h);	
						} else {
							return $doc;	
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
			$dir = $GLOBALS['CHEMIN_SITE'].'data/'.$this->w.'/'.$this->id.'/';
			$image_orders = explode('|',@file_get_contents($dir.'images_order.txt'));
			$docs = array();
			foreach($image_orders as $entry){
				if(!empty($entry)) {
					$docs[$entry]=$dir.($entry);
				}
			}
			if($d = @dir($dir)) {
				while (false !== ($entry = $d->read())) {
					if($img===false || !isPdf($entry)) {
						if(fileIsOk($entry)) {
							if(!isset($docs[$entry])) {
							   $docs[$entry] = $dir.($entry);
							  }
						}
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
			$url = Rwurl::clean($lib).'-'.$this->w.'-'.$this->id.'.html';
		} else {
			$url = $this->w.'.php?id='.$this->id;
		}
		return $url;
	}
	/**
	 * Get description for the current item type
	 * @param  $field field we want
	 * @return ?
	 */
	function getSpec($field) {
		if(isset($GLOBALS['DESC'][$this->w][$field])) {
			return $GLOBALS['DESC'][$this->w][$field];
		} else 
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
		if($GLOBALS['bdd']->Insert($data ? $data : $this->data,$this->w)) {
			return $GLOBALS['bdd']->LastInsertID();
		} else return false;
	}

	function deleteData() {
		File::rmdir('data/'.$this->w.'/'.$this->id);
		return $GLOBALS['bdd']->Delete($this->w,array('id'=>$this->id));
	}

	function updateData($data=false) {
		return $GLOBALS['bdd']->Update($this->w,$data ? $data : $this->data,array('id'=>$this->id));
	}

}
