<?php
header("content-type:application/json; charset:utf-8");
date_default_timezone_set("Asia/Shanghai");
$firstAccess = null;
$rawData = file_get_contents("php://input");
$parameters = json_decode($rawData);
if($parameters){
	if(isset($parameters->url)){
		$currMd5 = md5($parameters->url);
		$handle = fopen("history.txt", "r+");
		if ($handle) {
			while (($line = fgets($handle, 4096)) !== false) {
				if(0===strpos($line, $currMd5)){
					//$firstAccess = trim(substr($line, 33));
					break;
				}
			}
			if(!$firstAccess){
				$firstAccess = date("Y-m-d H:i");
				fwrite($handle, $currMd5." ".$firstAccess."\r\n");
			}
			fclose($handle);
		}
		else {
			exit(json_encode(array ('error'=>'服务器意外错误.')));
		}
		exit(json_encode(array ('firstAccess'=>$firstAccess)));
	}
}
exit(json_encode(array ('error'=>'请求不正确.')));
?>
