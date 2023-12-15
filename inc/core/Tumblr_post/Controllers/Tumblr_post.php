<?php
namespace Core\Tumblr_post\Controllers;

class Tumblr_post extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
    }
}