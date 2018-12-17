<?php
class log{

	public $log_name;

	public $log_dir="/tmp";

	public function __construct(){
		date_default_timezone_set('Asia/Shanghai');
		$this->log_name=date('Y_m_d-G',time()).".log";
		if(!is_dir($this->log_dir)){
			@mkdir($this->log_dir,0755);
		}
		if(!is_writable($this->log_dir)){
			@chmod($this->log_dir,0755);
		}
		@chdir($this->log_dir);
		if(!file_exists($this->log_name)){
			file_put_contents($this->log_name, "");
			if(!file_exists($this->log_name)){
				die("Create log file false......");
			}
		}
	}

	public function write_log(){
		$log_handle=fopen($this->log_name,"a+");
		if(!$log_handle){
			die("Log file open error.....");
		}
		$log_data="------------------------ ".date("Y-m-d G:i:s")." --------------------------\r\n\r\n";
		$log_data.=$_SERVER['REQUEST_METHOD']." ".$_SERVER['REQUEST_URI']." ".$_SERVER['SERVER_PROTOCOL']."\r\n";
		foreach(getallheaders() as $head=>$value){
			$log_data.=$head.": ".$value."\r\n";
		}
		$log_data.="\r\n\r\n";
		if(!empty($_POST)){
			$log_data.=file_get_contents("php://input")."\r\n";
		}elseif(!empty($_FILES)){
			$log_data.="upload file:\r\n\r\n";
			foreach ($_FILES as $file_name=>$file) {
				foreach ($file as $file_key => $file_value) {
					$log_data.=$file_key.": ".$file_value."\r\n";
				}
				$log_data.=$file['name']." contents is: \r\n\r\n".file_get_contents($file['tmp_name'])."\r\n";
			}
			$log_data.="\r\n\r\n";
		}
		fwrite($log_handle, $log_data);
		fclose($log_handle);
	}
}

$log=new log();
$log->write_log();
?>