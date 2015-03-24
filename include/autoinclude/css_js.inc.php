<?php


function include_js($files=false){
	$time = time();
	if(!$files) {
		if(ENV == 'DEV') {
			$files = glob('build/js/*.js');
			$add='?'.$time;
		} else {
			$files = glob('build/global.*.js');
			$add='';
		}
	} else {
		$files =  glob($files);
	}
	foreach($files as $file) {
		?>
<script type="text/javascript" src="<?php echo $file.$add;?>"></script>
<?php
	}
}


function include_css($files=false){
	$time = time();
	if(!$files) {
		if(ENV == 'DEV') {
			$files = glob('build/css/*.css');
			$add='?'.$time;
		} else {
			$files = glob('build/global.*.css');
			$add='';
		}
	} else {
		$files =  glob($files);
	}
	foreach($files as $file) {
		?>
<link rel="stylesheet" type="text/css" href="<?php echo $file.$add;?>"/>
<?php
	}
}