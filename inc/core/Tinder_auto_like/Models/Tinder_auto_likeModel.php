<?php
namespace Core\Tinder_auto_like\Models;
use CodeIgniter\Model;

class Tinder_auto_likeModel extends Model
{
	public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
    }

    public function block_plans(){
        return [
            "tab" => 15,
            "position" => 200,
            "label" => __("Tinder auto like"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => $this->config['name'],
                ],
            ]
        ];
    }

    public function block_whatsapp(){
        $data = [
            "config" => $this->config
        ];

        return array(
            "position" => 6000,
            "config" => $this->config
        );
    }

    public function get_list( $return_data = true )
    {
        $team_id = get_team("id");
        $current_page = (int)(post("current_page") - 1);
        $per_page = post("per_page");
        $total_items = post("total_items");
        $keyword = post("keyword");

        $db = \Config\Database::connect();
        $builder = $db->table(TB_ACCOUNTS." as a");
        $builder->select('a.name, a.username, a.pid, a.ids, a.url, a.avatar, a.status as account_status, b.status, b.id');
        $builder->join(TB_TINDER_ACTIVITIES." as b", "a.pid = b.account_id", "left");
        $builder->where("( a.team_id = '{$team_id}' AND a.social_network = 'tinder' AND a.login_type = 2 )");

        if( $keyword ){
            $builder->where("( a.name LIKE '%{$keyword}%' OR a.username LIKE '%{$keyword}%' )") ;
        }
        
        if( !$return_data )
        {
            $result =  $builder->countAllResults();
        }
        else
        {
            $builder->limit($per_page, $per_page*$current_page);
            $builder->orderBy("created", "DESC");
            $query = $builder->get();
            $result = $query->getResult();
            $query->freeResult();

            if(!empty($result)){
                foreach ($result as $key => $value) {
                    $item = db_get("COUNT(id) as count", TB_TINDER_ACTIVITIES_LOG, ["pid" => $value->id]);
                    $result[$key]->count = (int)$item->count;
                }
                return $result;
            }
        }
        

        return $result;
    }

    public function get_schedules(){
        $db = \Config\Database::connect();
        $builder = $db->table(TB_TINDER_ACTIVITIES." as a");
        $builder->select('b.*, a.id as action_id');
        $builder->join(TB_ACCOUNTS." as b", "a.account_id = b.pid");
        $builder->where("( a.next_action <= '".time()."' AND a.status = 1 )");
        $builder->orderBy("a.next_action", "ASC");
        $builder->limit(5, 0);
        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();
        return $result;
    }

    public function get_logs($ids = ""){
        $item = db_get("*", TB_TINDER_ACTIVITIES, ["account_id" => $ids]);
        if(!$item){
            return false;
        }

        $page = (int)post("page");
        $team_id = (int)get_team("id");

        $db = \Config\Database::connect();
        $builder = $db->table(TB_TINDER_ACTIVITIES_LOG);

        $builder->select("*");
        $builder->where('team_id', $team_id);
        $builder->where('pid', $item->id);

        $builder->orderBy("id DESC");
        $builder->limit(30, $page * 30);

        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();
        return $result;
    }
}
