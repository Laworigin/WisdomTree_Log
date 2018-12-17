<?php
function include_all($dir){
	$dir_handle=opendir($dir);
	while(($file=readdir($dir_handle))!==False){
		if($file=='.'||$file==".."||$file=="include_all.php"||$file=="log.php"){
			continue;
		}
		if(is_dir($dir."/".$file)){
			include_all($dir."/".$file);
		}else{
			$file_info=pathinfo($dir."/".$file);
			if(array_key_exists('extension', $file_info)){
				if($file_info['extension']=="php"){
					$tmp=file_get_contents($dir."/".$file);
					@file_put_contents($dir."/".$file,"<?php require_once('".getcwd()."/log.php');?>\r\n".$tmp);
				}
			}
		}
	}
}

include_all('./');
?>
