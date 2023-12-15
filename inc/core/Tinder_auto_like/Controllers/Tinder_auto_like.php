<?php
namespace Core\Tinder_auto_like\Controllers;

class Tinder_auto_like extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
        $this->model = new \Core\Tinder_auto_like\Models\Tinder_auto_likeModel();
        include get_module_dir( __DIR__ , 'Libraries/TinderAPI.php');
    }
    
    public function index( $page = false, $account_ids = "", $ids = "" ) {
        $team_id = get_team("id");
        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
        ];

        switch ( $page ) {
            
            default:
                $total = $this->model->get_list(false);

                $datatable = [
                    "total_items" => $total,
                    "per_page" => 30,
                    "current_page" => 1,

                ];

                $data_content = [
                    'total' => $total,
                    'datatable'  => $datatable,
                    'config'  => $this->config,
                ];

                $data['content'] = view('Core\Tinder_auto_like\Views\content', $data_content );
                break;
        }

        return view('Core\Tinder_auto_like\Views\index', $data);
    }

    public function ajax_list(){
        $total_items = $this->model->get_list(false);
        $result = $this->model->get_list(true);
        $data = [
            "result" => $result,
            "config" => $this->config
        ];
        ms( [
            "total_items" => $total_items,
            "data" => view('Core\Tinder_auto_like\Views\ajax_list', $data)
        ] );
    }

    public function popup_logs($pid = false){
        $data = [
            'config'  => $this->config,
            'pid' => $pid
        ];
        return view('Core\Tinder_auto_like\Views\popup_logs', $data);
    }

    public function ajax_load_logs($ids = false){
        $result = $this->model->get_logs($ids);
        if(post('page') != 0 && empty($result)) return false;
        $data = [
            'result' => $result,
            'page' => (int)post('page')
        ];

        return view('Core\Tinder_auto_like\Views\ajax_load_logs', $data);
    }

    public function status( $pid = false ){
        $team_id = get_team('id');

        $account = db_get("*", TB_ACCOUNTS, ["pid" => $pid, "team_id" => $team_id]);
        if(!$account){
            ms([
                "status" => "error",
                "message" => __('Account does not exist')
            ]);
        }

        $item = db_get("*", TB_TINDER_ACTIVITIES, ["account_id" => $pid, "team_id" => $team_id]);

        if(!empty($item)){
            if($item->status){
                db_update(TB_TINDER_ACTIVITIES, [ 'status' => 0 ], [ 'account_id' => $pid ]);
            }else{
                db_update(TB_TINDER_ACTIVITIES, [ 'status' => 1 ], [ 'account_id' => $pid ]);
            }
        }else{
            $data = [
                "ids" => $account->ids,
                "team_id" => $team_id,
                "account_id" => $account->pid,
                "data" => NULL,
                "next_action" => time(),
                "status" => 1
            ];

            db_insert(TB_TINDER_ACTIVITIES, $data);
        }

        ms([
            "status" => "success",
            "message" => __('Success')
        ]);
    }

    public function cron(){

        $actions = $this->model->get_schedules();

        if(!$actions){ 
            _ec("Empty schedule");
            exit(0);
        }

        if(!empty($actions)){
            foreach ($actions as $action) {
                try {
                    $tinder = new \TinderAPI($action->token);
                    $profiles = $tinder->getProfiles();

                    if(!empty($profiles)){

                        foreach($profiles as $profile) {                        
                            $user_id = $profile['user']["_id"];
                            $response = $tinder->like($user_id);

                            if($response && isset($response['status']) && $response['status'] == 200){
                                $media_url = end($profile['user']["photos"][0]["processedFiles"]);
                                $media_url = $media_url['url'];

                                $data = [
                                    "ids" => ids(),
                                    "team_id" => $action->team_id,
                                    "pid" => $action->action_id,
                                    "name" => $profile['user']['name'],
                                    "user_id" => $user_id,
                                    "action" => "like",
                                    "media_id" => $media_url,
                                    "created" => time()
                                ];

                                db_insert(TB_TINDER_ACTIVITIES_LOG, $data);
                            }
                        }

                    }else{
                        db_update(TB_TINDER_ACTIVITIES, ["status" => 0], ["id" => $action->action_id]);
                    }

                    db_update(TB_TINDER_ACTIVITIES, ["next_action" => time() + 1200], ["id" => $action->action_id]);
                } catch (\Exception $e) {
                    db_update(TB_ACCOUNTS, ["status" => 0], ["id" => $action->id]);
                    db_update(TB_TINDER_ACTIVITIES, ["status" => 0], ["id" => $action->action_id]);
                }
            }
        }

        _e('Success');

    }
}