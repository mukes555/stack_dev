<?php 
if(!function_exists("get_hashtags")){
    function get_hashtags($string) {
        preg_match_all('/#([^\s#]+)/', $string, $array);
        return $array[1];
    }
}

if(!function_exists("get_mentions")){
    function get_mentions($string) {
        preg_match_all('/@([^\s@]+)/', $string, $array);
        return $array[1];
    }
}