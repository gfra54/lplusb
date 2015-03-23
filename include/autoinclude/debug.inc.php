<?php

$QUERIES=array();
function logQuery($q) {
	global $QUERIES;
	$QUERIES[] = $q;
}
function showDebug() {
	global $debug, $QUERIES;
	if(!$debug) {
		return false;
	}
?>
<script language=JavaScript>
<!-- Script courtesy of http://www.web-source.net - Your Guide to Professional Web Site Design and Development
function ViewSource() {
window.open("view-source:" + window.location.href,'source_'+window.location.href);
}
// -->
</script>

<a href=javascript:ViewSource()>View Source</a>
<div id="debug" style="margin:2px;padding:3px;border:1px solid black;font-family:sans-serif;text-align:left;font-size:12px;background:white;color:black;">
<table width="100%" cellpadding=5>
<?php echo debugItem('_GET',$_GET);?>
<?php echo debugItem('_POST',$_POST);?>
<?php echo debugItem(count($QUERIES).' QUERIES',$QUERIES);?>
<?php echo debugItem('SESSION',$_SESSION,200);?>
<?php echo debugItem('COOKIES',$_COOKIE);?>
</table>
</div>
<?php

}

function debugItem($lib,$d,$h=false) {
?><tr>
	<th valign=top style="border-bottom:1px solid #555;border-right:1px solid #555;width:180px;">
		<?php echo $lib;?>
	<td valign=top style="border-bottom:1px solid #555;">
		<div style="overflow:auto;width:100%;<?php echo $h ? 'height:'.$h.'px;' : '';?>"><?php pr($d);?></div>
<?php
}
function mvd(){
	$args = func_get_args();
	mpr_mvd('mvd',$args);
}
function mpr(){
	$args = func_get_args();
	mpr_mvd('mpr',$args);
}
function mpre(){
	$args = func_get_args();
	mpr_mvd('mpr',$args);
	exit;
}
function mpr_mvd($action, $args){
	global $debug;
		$do=false;
	foreach($args as $arg) {
		if($arg == $_SERVER['REMOTE_ADDR']) {
			$do=true;
		}
	}
		foreach($args as $arg) {
			if($arg != $_SERVER['REMOTE_ADDR']) {
				_dump($arg, $action == 'mpr');
			}
		}
}
function _dump($v,$action) {
	if($action) {
		$content = print_r($v,true);
 	}
 else {
		ob_start();
		var_dump($v);
		$content = ob_get_contents();
		ob_end_clean();
	}
	?>	<div style="border:3px dotted #555;background:#ccc;color:black;text-align:left;padding:5px;overflow:auto;_height:500px;
"><pre><?php echo htmlspecialchars($content);
?>	</pre>	</div>	<?php }
if (!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if (!$f) {
            return false;
        }
 else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

function pr($d) {
	echo '<span style="font-size:10px;font-family:Courier new;">'.nl2br(str_replace("  ","&nbsp; ",htmlspecialchars(print_r($d,true)))).'</>';
}
?>