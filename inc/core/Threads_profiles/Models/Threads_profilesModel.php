<?php
namespace Core\Threads_profiles\Models;
use CodeIgniter\Model;

class Threads_profilesModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
    }
    
	public function block_accounts($path = ""){
        $team_id = get_team("id");
        $accounts = db_fetch("*", TB_ACCOUNTS, "social_network = 'threads' AND category = 'profile' AND team_id = '{$team_id}'");
        $user_proxy = db_fetch("id", TB_ACCOUNTS, "social_network = 'threads' AND category = 'profile' AND team_id = '{$team_id}' AND login_type != 1");

        return [
            "can_use_proxy" => $user_proxy,
        	"button" => view( 'Core\Threads_profiles\Views\button', [ 'config' => $this->config ] ),
        	"content" => view( 'Core\Threads_profiles\Views\content', [ 'config' => $this->config, 'accounts' => $accounts ] )
        ];
    }
}
