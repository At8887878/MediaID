<?php

	function image_generation($file)
	{
	        $img = [];
	        $file= file_get_contents($file);
	        $sha256 = hash("sha256", $file);


	        $img_meta = array("filename" => "file.jpg","sha256" => $sha256);
	        
	        $HOST='https://'.$_SERVER['HTTP_HOST'];
	        $auth_service_url=$HOST.'/wx/wx_check_header_sign2.php';
	        $url = 'https://api.mch.weixin.qq.com/v3/merchant/media/upload';
	        $auth_service_url.='?method=POST&url='.urlencode($url).'&body='.json_encode($img_meta);

	                
	        $auth_token = get_url($auth_service_url);
	        $auth_data = json_decode($auth_token,true);   

	            
	        $header = array(
	            'Authorization:'.$auth_data['authorization'],                
	            'Content-Type:multipart/form-data;boundary=boundary'
	        );
	        

	        $img_body = "--boundary"."\r\n";
	        $img_body .= "Content-Disposition: form-data; name=\"meta\"\r\n";
	        $img_body .= "Content-Type: application/json"."\r\n"."\r\n";
	        $img_body .=json_encode($img_meta)."\r\n";
	        $img_body .= "--boundary"."\r\n";
	        $img_body .= "Content-Disposition: form-data; name=\"file\"; filename=\"file.jpg\"\r\n";
	        $img_body .= "Content-Type: image/jpg"."\r\n"."\r\n";
	        $img_body .=$file."\r\n";
	        $img_body .= "--boundary--";
	        $ret = post_url($url,$img_body, $header);
	                

	               
	        $data = [];
	        $data=json_decode($ret,true);
	        if(!isset($data['code']))
	        {
	            
	            $data['error']=1;
	        }
	        else
	        {
	            $data['error']=0;            
	            $data['message']=$data['message'];
	        }
	        return $data['media_id'];
	        
	}

	$img = "https://atcampus.cn/images/bj.jpg";

	$img = image_generation("$img");


?>