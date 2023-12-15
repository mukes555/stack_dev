<?php
namespace Core\Facebook_groups\Models;
use CodeIgniter\Model;

class Facebook_groupsModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
    }
    
	public function block_accounts($path = ""){
        $team_id = get_team("id");
        $accounts = db_fetch("*", TB_ACCOUNTS, "social_network = 'facebook' AND category = 'group' AND team_id = '{$team_id}'");
        $user_proxy = db_fetch("id,team_id", TB_ACCOUNTS, "social_network = 'facebook' AND category = 'group' AND login_type != 1");

        return [
            "can_use_proxy" => $user_proxy,
        	"button" => view( 'Core\Facebook_groups\Views\button', [ 'config' => $this->config ] ),
        	"content" => view( 'Core\Facebook_groups\Views\content', [ 'config' => $this->config, 'accounts' => $accounts ] )
        ];
    }

    public function block_social_settings($path = ""){
        return [
            "menu" => view( 'Core\Facebook_groups\Views\settings\menu', [ 'config' => $this->config ] ),
            "content" => view( 'Core\Facebook_groups\Views\settings\content', [ 'config' => $this->config ] )
        ];
    }
}
