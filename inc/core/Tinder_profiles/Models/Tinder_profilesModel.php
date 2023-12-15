<?php
namespace Core\Tinder_profiles\Models;
use CodeIgniter\Model;

class Tinder_profilesModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
    }
    
	public function block_accounts($path = ""){
        $team_id = get_team("id");
        $accounts = db_fetch("*", TB_ACCOUNTS, "social_network = 'tinder' AND category = 'profile' AND team_id = '{$team_id}'");
        return [
        	"button" => view( 'Core\Tinder_profiles\Views\button', [ 'config' => $this->config ] ),
        	"content" => view( 'Core\Tinder_profiles\Views\content', [ 'config' => $this->config, 'accounts' => $accounts ] )
        ];
    }
}
