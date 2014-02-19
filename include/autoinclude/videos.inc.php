<?php
function getVideos($debut=0,$nb=false,$exclude=false) {

	$data = getSortData('videos','date',true,'pub');	
	if($exclude)
		unset($data[$exclude]);

	$now = time();
	$out=array();
	foreach($data as $k=>$v){
		if($v['date'] < $now) {
			$out[]=$v;
		}
	}

	return $nb ? array_slice($out,$debut,$nb) : $data;
	
}
function getVideo($id) {

	$data = getSortData('videos','date',true,'pub');	
	return isset($data[$id]) ? $data[$id]: false;

}

function initVideos($project) {
	$v = trim($project['videos']);
	$videos = explode("\n",$project['videos']); 
	$out=array();
	foreach($videos as $video) {
		if(isVideo($video)){
			$out[]=$video;
		}
	}
	return $out;
}

function isVideo($url) {
	if(strstr($url,'youtube'))
		return true;
	else 
		return false;
}

function getIconeVideo($url,$parse=true) {
	return 'http://i3.ytimg.com/vi/'.($parse ? parseUrlVideo($url) : $url).'/default.jpg';
}
function getImageVideo($url,$parse=true) {
	return 'http://i3.ytimg.com/vi/'.($parse ? parseUrlVideo($url) : $url).'/0.jpg';
}

function parseUrlVideo($url) {
	list(,$code) = explode('v=',$url); 
	list($code) = explode('&',$code);
	return $code;
}


function getHtmlCodeVideo($url){
	$code = parseUrlVideo($url);
	
	//$html = '<object width="680" height="425"><param name="movie" value="http://www.youtube.com/v/'.$code.'&hl=fr_FR&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$code.'&hl=fr_FR&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="680" height="425"></embed></object>';
	
	$html='<iframe title="YouTube video player" autoplay="1" class="youtube-player" type="text/html" width="642" height="425" src="http://www.youtube.com/embed/'.$code.'?rel=0" "&amp;hd=1" frameborder="0"></iframe>';
	return $html;
}

function parseVideoEntry($url) {       
	$code = parseUrlVideo($url);
	$feedURL = 'http://gdata.youtube.com/feeds/api/videos/'. $code; 

    // read feed into SimpleXML object 
    $entry = simplexml_load_file($feedURL); 
	$obj= new stdClass; 
       
      // get nodes in media: namespace for media information 
      $media = $entry->children('http://search.yahoo.com/mrss/'); 
      $obj->title = utf8_decode($media->group->title); 
      $obj->description = utf8_decode($media->group->description); 
       
      // get video player URL 
      $attrs = $media->group->player->attributes(); 
      $obj->watchURL = $attrs['url'];  
       
      // get video thumbnail 
      $attrs = $media->group->thumbnail[0]->attributes(); 
      $obj->thumbnailURL = $attrs['url'];  
             
      // get <yt:duration> node for video length 
      $yt = $media->children('http://gdata.youtube.com/schemas/2007'); 
      $attrs = $yt->duration->attributes(); 
      $obj->length = $attrs['seconds'];  
       
      // get <yt:stats> node for viewer statistics 
      $yt = $entry->children('http://gdata.youtube.com/schemas/2007'); 
      $attrs = $yt->statistics->attributes(); 
      $obj->viewCount = $attrs['viewCount'];  
       
      // get <gd:rating> node for video ratings 
      $gd = $entry->children('http://schemas.google.com/g/2005');  
      if ($gd->rating) {  
        $attrs = $gd->rating->attributes(); 
        $obj->rating = $attrs['average'];  
      } else { 
        $obj->rating = 0;          
      } 
         
      // get <gd:comments> node for video comments 
      $gd = $entry->children('http://schemas.google.com/g/2005'); 
      if ($gd->comments->feedLink) {  
        $attrs = $gd->comments->feedLink->attributes(); 
        $obj->commentsURL = $attrs['href'];  
        $obj->commentsCount = $attrs['countHint'];  
      } 
       
      //Get the author 
      $obj->author = $entry->author->name; 
      $obj->authorURL = $entry->author->uri; 
       
       
      // get feed URL for video responses 
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom'); 
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/ 
      2007#video.responses']");  
      if (count($nodeset) > 0) { 
        $obj->responsesURL = $nodeset[0]['href'];       
      } 
          
      // get feed URL for related videos 
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom'); 
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/ 
      2007#video.related']");  
      if (count($nodeset) > 0) { 
        $obj->relatedURL = $nodeset[0]['href'];       
      } 
     
      // return object to caller   
      return $obj;       
    } 

?>