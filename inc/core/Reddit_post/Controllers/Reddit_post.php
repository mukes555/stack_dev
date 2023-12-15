<?php
namespace Core\Reddit_post\Controllers;

class Reddit_post extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
    }
}