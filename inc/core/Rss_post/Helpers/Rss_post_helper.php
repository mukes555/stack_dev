<?php

if (!function_exists('parse_rss_feed')) {

    function parse_rss_feed($url) {
        $xmlDoc = new DOMDocument();
        @$xmlDoc->load($url);

        if($xmlDoc->textContent){
        	if (!$xmlDoc->getElementsByTagName('channel')->length) {

        		$channel_title = @$xmlDoc->getElementsByTagName('title')->item(0);
        		$channel_description = @$xmlDoc->getElementsByTagName('description')->item(0);
        		$rss_title = @strip_tags($channel_title->nodeValue);
        		$rss_description = @strip_tags($channel_description->nodeValue);

        		if ($rss_title) {
        			$data = [];

        			for ($key = 0; $key < $xmlDoc->getElementsByTagName('entry')->length; $key++) {
        				$channel = $xmlDoc->getElementsByTagName('entry')->item($key);
        				$data[$key]['title'] = @stripslashes(strip_tags($channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue));

        				$text = $xmlDoc->saveHtml($channel);
	                    $xml = @simplexml_load_string($text);
	                    $linkAttributes = @$xml->link->attributes();
	                    $u = @$linkAttributes->href[0];
        				$data[$key]['url'] = @strip_tags($u[0]);

        				if (@$channel->getElementsByTagName('description')->length) {
	                       $data[$key]['description'] = @stripslashes(strip_tags($channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue));
	                    } elseif (@$channel->getElementsByTagName('summary')->length) {
	                       $data[$key]['description'] = @stripslashes(strip_tags($channel->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue));
	                    } elseif (@$channel->getElementsByTagName('encoded')->length) {
	                       $data[$key]['description'] = @stripslashes(strip_tags($channel->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue));
	                    } elseif (@$channel->getElementsByTagName('content')->length) {
	                       $data[$key]['description'] = @stripslashes(strip_tags($channel->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue));
	                    }
        			}

        			return ['title' => $rss_title, "description" => $rss_description, "data" => $data];
        		}
        	}else{
        		if (@$xmlDoc->getElementsByTagName('channel')) {

	            	$channel = $xmlDoc->getElementsByTagName('channel')->item(0);
	            	$rss_title = @strip_tags($channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue);
	            	$rss_description = @strip_tags($channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue);
	            	$data = [];
	            	if ($rss_title) {
	            		$rss = $xmlDoc->getElementsByTagName('item');
		            	foreach ($rss as $key => $item) {
		            		$data[$key]['title'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue));
		            		$data[$key]['url'] = @strip_tags($rss->item($key)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue);
		            		if (@$rss->item($key)->getElementsByTagName('description')->length) {
		                        $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue));
		                    } elseif (@$rss->item($key)->getElementsByTagName('encoded')->length) {
		                        $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue));
		                    } elseif (@$rss->item($key)->getElementsByTagName('content')->length) {
		                        $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue));
		                    } elseif (@$rss->item($key)->getElementsByTagName('summary')->length) {
		                        $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue));
		                    }
		            	}
		            }else{
		            	$rss = $xmlDoc->getElementsByTagName('item');
		            	if ($rss) {
		            		$parse = parse_url($url);
	                        $rss_title = $parse['host'];

			            	foreach ($rss as $key => $item) {
				            	$data[$key]['title'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue));
			                    $data[$key]['url'] = @strip_tags($rss->item($key)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue);
			                    if (@$rss->item($key)->getElementsByTagName('description')->length) {
		                            $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue));
		                        } elseif (@$rss->item($key)->getElementsByTagName('encoded')->length) {
		                            $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('encoded')->item(0)->childNodes->item(0)->nodeValue));
		                        } elseif (@$rss->item($key)->getElementsByTagName('content')->length) {
		                            $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('content')->item(0)->childNodes->item(0)->nodeValue));
		                        } elseif (@$rss->item($key)->getElementsByTagName('summary')->length) {
		                            $data[$key]['description'] = @stripslashes(strip_tags($rss->item($key)->getElementsByTagName('summary')->item(0)->childNodes->item(0)->nodeValue));
		                        }
			                }
		            	}
		            }

		            return ['title' => $rss_title, "description" => $rss_description, "data" => $data];
		        }

        	}
        }

        return false;
    }
}

if(!function_exists("read_rss")){
	function read_rss($params) {
		include get_module_dir( __DIR__ , 'Libraries/vendor/autoload.php');

	    if ( empty($params['url']) ) {
	        return array(
	            "status" => "error",
                "message" => __("Rss url is not found")
	        );
	        
	    }

	    $sanitized_url = filter_var($params['url'], FILTER_SANITIZE_URL);
	    if (filter_var($sanitized_url, FILTER_VALIDATE_URL) === false) {
	        return array(
	            "status" => "error",
                "message" => __("Rss url is not found")
	        );
	    }

	    $rss_content = rss_curl_request($sanitized_url);
	    if ( empty($rss_content) ) {
	    	return array(
	            "status" => "error",
                "message" => __("Rss url is incorrect")
	        );
	    }

	    $xml = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $rss_content);
	    $content = array();

        try {
            $xml_content = new \SimpleXmlElement($xml, LIBXML_NOERROR);

	        $content = Feed::loadRss( $params['url'] );
	        if ( empty($content) ) {
	            return array(
		            "status" => "error",
	                "message" => __("Rss url is not support")
		        );
	        }
        } catch (\Throwable $t) {
            return array(
                "status" => "error",
                'message' => $t->getMessage()
            );                
        }

	    return array(
	        "status" => "success",
	        'content' => $content
	    );        
	}
}

if(!function_exists("rss_curl_request")){
    function rss_curl_request($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $url_content = curl_exec($curl);
        curl_close($curl);
        if ( !empty($url_content) ) {
            if ( !rss_is_valid($url_content) ) {
                $the_content = @file_get_contents($url);
                if ( !empty($the_content) ) {
                    if ( rss_is_valid($the_content) ) {
                        $url_content = $the_content;
                    }
                }
            }
        }

        return $url_content;
    }
}
    
if(!function_exists("rss_is_valid")){
    function rss_is_valid($xml) {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument('1.0', 'utf-8');
        $doc->loadXML($xml);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        return empty($errors)?TRUE:FALSE;
    }
}

//Get Settings
if(!function_exists("_grss")){
    function _grss($key, $value = "", $uid = 0){
        if($uid != 0){
            $data = db_get("data", TB_RSS, ["id" => $uid])->data;
            if($data != ""){
                try {
                    $option = json_decode($data);
                } catch (\Exception $e) {
                    $option = [];
                }
            }else{
                $option = [];
            }

            if(is_array($option) || is_object($option)){
                $option = (array)$option;

                if( isset($option[$key]) ){
                    return $option[$key];
                }else{
                    $option[$key] = $value;
                    db_update(TB_RSS, ["data" => json_encode($option)], [ "id" => $uid ] );
                    return $value;
                }
            }else{ 
                $option = json_encode(array($key => $value));
                db_update(TB_RSS, ["data" => $option ], [ "id" => $uid ] );
                return $value;
            }
        }
    }
}

//Update Settingz
if(!function_exists("_urss")){
    function _urss($key, $value, $uid = 0){
        if($uid != 0){
            $data = db_get("data", TB_RSS, ["id" => $uid])->data;
            if($data != ""){
                try {
                    $option = json_decode($data);
                } catch (\Exception $e) {
                    $option = [];
                }
            }else{
                $option = [];
            }

            if(is_array($option) || is_object($option)){
                $option = (array)$option;
                if( isset($option[$key]) ){
                    $option[$key] = $value;
                    db_update(TB_RSS, [ "data" => json_encode($option) ], [ "id" => $uid ] );
                    return true;
                }
            }
        }
        return false;
    }
}