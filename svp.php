<?php

#   Name: SVP
#    Package:  SOFTWARE VERSION PARITY
#    Version: 1.0
#   Author: Abdul Ibad (abdul.ibad@yahoo.com)  

class SVP{

	// file
	var $file;
	
	// SVP part
	var $part;
	
	function SVP($file=''){
		
		$this->setfile($file);
		
	}
		
	function setfile($file){
	
		$this->file = $file;
		
	}
	
	function getfile(){
	
		return $this->file;
		
	}
			
	function getdata(){
		
		$file = $this->file;
			
		if(empty($file)){
			return;
		}
			
		if($this->is_curl()){
			$ch = curl_init();	
			curl_setopt($ch,CURLOPT_URL,$file);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
			$data = curl_exec($ch);
			curl_close($ch);
					
		}else{
			
			$fp = @fopen($file,'r');

			if($fp){
				while (!feof($fp)){
					$data .= fgets($fp, 1024);
				}
			}
			 
			fclose($fp);
			
		}
		
		  return $data;
	}

	function parse_text(){

		$data = $this->getdata();
			
		$pieces = explode("\r\n",$data);

		foreach($pieces as $piece){
			$part = explode(':',$piece);
			$key = trim($part[0]);
			$value = trim($part[1]);
			$props[$key] = $value;
		}
						
			$svp_part = new SVP_PART;
		
			$svp_part->id = md5($props['Name']);
		
			$svp_part->name = $props['Name'];
			
			$svp_part->url = urldecode($props['URL']);
			
			$svp_part->author = $props['Author'];
			
			$svp_part->release = $props['Release'];
			
			$svp_part->version = $props['Version'];
		
			$svp_part->hash = $props['Hash'];
		
			$svp_part->description = base64_decode($props['Description']);
			
			$this->part = $svp_part;
	}
	
	function getpart(){
	
		return $this->part;
		
	}
	
	function is_curl(){
	
		if(function_exists('curl_init')){
			return true;
		}
	
		return false;
	}
	
}


class SVP_PART{

		// Softwaare name
		var $name;

		// Software download url
		var $url;
		
		// Author name
		var $author;
		
		// Release date
		var $release;
	
		// Software version
		var $version;
			
		// Software description
		var $description;
		
		// Software hash
		var $hash;
		
}	
?>