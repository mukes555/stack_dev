<?php
namespace Core\Facebook_analytics\Models;
use CodeIgniter\Model;

class Facebook_analyticsModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
    }

    public function block_plans(){
        return [
            "tab" => 20,
            "position" => 100,
            "label" => __("Analytics & report"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => $this->config['name'],
                ]
            ]
        ];
    }
    
    public function block_analytics(){
        $team_id = get_team("id");
        $data = [
            "config" => $this->config
        ];

        $accounts = db_fetch("*", TB_ACCOUNTS, [ "social_network" => "facebook", "category" => "page", "login_type" => 1, "team_id" => $team_id], "created", "ASC");
        permission_accounts($accounts);

        return array(
            "position" => 10000,
            "name" => $this->config['parent']['name'],
            "config" => $this->config,
            "menu" => view( 'Core\Facebook_analytics\Views\menu', $data ),
            "content" => view( 'Core\Facebook_analytics\Views\content', $data ),
            "accounts" => $accounts
        );
    }
}
