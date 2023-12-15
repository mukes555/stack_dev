<?php
namespace Core\Tumblr_blogs\Models;
use CodeIgniter\Model;

class Tumblr_blogsModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
    }
    
	public function block_accounts($path = ""){
        $team_id = get_team("id");
        $accounts = db_fetch("*", TB_ACCOUNTS, "social_network = 'tumblr' AND category = 'blog' AND team_id = '{$team_id}'");
        return [
        	"button" => view( 'Core\Tumblr_blogs\Views\button', [ 'config' => $this->config ] ),
        	"content" => view( 'Core\Tumblr_blogs\Views\content', [ 'config' => $this->config, 'accounts' => $accounts ] )
        ];
    }

    public function block_social_settings($path = ""){
        return [
            "menu" => view( 'Core\Tumblr_blogs\Views\settings\menu', [ 'config' => $this->config ] ),
            "content" => view( 'Core\Tumblr_blogs\Views\settings\content', [ 'config' => $this->config ] )
        ];
    }
}
