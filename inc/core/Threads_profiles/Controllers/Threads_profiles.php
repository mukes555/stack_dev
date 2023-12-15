<?php
namespace Core\Threads_profiles\Controllers;

class Threads_profiles extends \CodeIgniter\Controller
{
    public $ig;
    public $username;
    public $password;
    public $proxy;
    public $security_code;
    public $verification_code;
    public $choice;

    public function __construct(){
        $reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/Instagram_threads_unofficial.php');

        $this->proxy_item = FALSE;
        $this->proxy = NULL;
        $proxy_item = asign_proxy("threads", "profile", 2);
        if ($proxy_item) {
            $this->proxy_item = $proxy_item;
            $this->proxy = $proxy_item->proxy;
        }
    }
    
    public function index($page = "") {

        try {
            $team_id = get_team("id");
            $threads_instagram_id = get_session("threads_instagram_id");
            $threads_instagram_username = get_session("threads_instagram_username");
            $threads_instagram_password = get_session("threads_instagram_password");
            $threads_instagram_token = get_session("threads_instagram_token");

            if(!$threads_instagram_id || !$threads_instagram_username || !$threads_instagram_password || !$threads_instagram_token){
                redirect_to( get_module_url("oauth") );
            }

            $ig_auth = new \Instagram_threads_unofficial("", "", $team_id, $this->proxy);
            $response = $ig_auth->setAuth( $threads_instagram_id, $threads_instagram_token );
            $response = $ig_auth->getUserProfile();

            if(!empty($response)){
                $avatar = save_img( $response['profile_pic_url'], WRITEPATH.'avatar/' );

                $result = [];
                $result[] = (object)[
                    'id' => $response['pk'],
                    'name' => $response['full_name'],
                    'avatar' => get_file_url($avatar),
                    'desc' => $response['username']
                ];

                $profiles = [
                    "status" => "success",
                    "config" => $this->config,
                    "result" => $result,
                    "save_url" => get_module_url("save_unofficial")
                ];
            }else{
                $profiles = [
                    "status" => "error",
                    "config" => $this->config,
                    "message" => __('No profile to add'),
                    "save_url" => ""
                ];
            }
        } catch (\Exception $e) {
            $profiles = [
                "status" => "error",
                "config" => $this->config,
                "message" => __( $e->getMessage() ),
                "save_url" => ""
            ];
        }

        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Threads_profiles\Views\add', $profiles)
        ];

        return view('Core\Threads_profiles\Views\index', $data);
    }

    public function oauth(){
        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Threads_profiles\Views\oauth', [ "config" => $this->config ])
        ];

        return view('Core\Threads_profiles\Views\index', $data);
    }

    public function oauth_unofficial(){
        $team_id = get_team("id");
        $ig_username = post("ig_username");
        $ig_password = post("ig_password");
        
        validate('null', __('Instagram username'), $ig_username);
        validate('null', __('Instagram password'), $ig_password);

        set_session([
            "ig_username" => $ig_username,
            "ig_password" => $ig_password
        ]);

        try {
            $ig_auth = new \Instagram_threads_unofficial($ig_username, $ig_password, $team_id, $this->proxy);
            $login_data = $ig_auth->login();

            set_session([
                "threads_instagram_id" => $login_data['user_id'],
                "threads_instagram_username" => $login_data['username'],
                "threads_instagram_password" => $ig_password,
                "threads_instagram_token" => $login_data['token'],
            ]);

            ms([
                "status" => "success"
            ]);
        } catch (\Exception $e) {
            ms([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function save_unofficial()
    {
        try {
            $ids = post('id');
            $team_id = get_team("id");
            $threads_instagram_id = get_session("threads_instagram_id");
            $threads_instagram_username = get_session("threads_instagram_username");
            $threads_instagram_password = get_session("threads_instagram_password");
            $threads_instagram_token = get_session("threads_instagram_token");

            if(!$threads_instagram_id || !$threads_instagram_username || !$threads_instagram_password || !$threads_instagram_token){
                validate('empty', __('Cannot connect to your Instagram account'));
            }

            validate('empty', __('Please select a profile to add'), $ids);

            $ig_auth = new \Instagram_threads_unofficial($threads_instagram_username, $threads_instagram_password, $team_id, $this->proxy);
            $response = $ig_auth->setAuth( $threads_instagram_id, $threads_instagram_token );
            $response = $ig_auth->getUserProfile();

            if(empty($response)){
                validate('empty', __('Cannot connect to your Instagram account'));
            }

            if(in_array($response['pk'], $ids, FALSE)){
                $accessToken = json_encode([ "ig_username" => $threads_instagram_id, "ig_password" => encrypt_encode($threads_instagram_username), "token" => $threads_instagram_token, "user_id" => $threads_instagram_id ]);
                $item = db_get('*', TB_ACCOUNTS, "social_network = 'threads' AND login_type = 2 AND team_id = '{$team_id}' AND pid = '".$response['pk']."'");
                if(!$item){
                    //Check limit number 
                    check_number_account("instagram", "profile");
                    $avatar = save_img( $response['profile_pic_url'], WRITEPATH.'avatar/' );
                    $data = [
                        'ids' => ids(),
                        'module' => $this->module,
                        'social_network' => 'threads',
                        'category' => 'profile',
                        'login_type' => 2,
                        'can_post' => 1,
                        'team_id' => $team_id,
                        'pid' => $response['pk'],
                        'name' => $response['full_name'],
                        'username' => $response['username'],
                        'token' => $accessToken,
                        'avatar' => $avatar,
                        'url' => 'https://www.threads.net/@'.$response['username'],
                        'proxy' => $this->proxy_item?$this->proxy_item->id:"",
                        'data' => NULL,
                        'status' => 1,
                        'changed' => time(),
                        'created' => time()
                    ];

                    db_insert(TB_ACCOUNTS, $data);
                }else{
                    @unlink( get_file_path($item->avatar) );
                    $avatar = save_img( $response['profile_pic_url'], WRITEPATH.'avatar/' );
                    $data = [
                        'can_post' => 1,
                        'pid' => $response['pk'],
                        'name' => $response['full_name'],
                        'username' => $response['username'],
                        'token' => $accessToken,
                        'avatar' => $avatar,
                        'url' => 'https://www.threads.net/@'.$response['username'],
                        'proxy' => $this->proxy_item?$this->proxy_item->id:"",
                        'status' => 1,
                        'changed' => time(),
                    ];

                    db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
                }
            }

            remove_session(["threads_instagram_id", "threads_instagram_username", "threads_instagram_password", "threads_instagram_token"]);

            ms([
                "status" => "success",
                "message" => __("Success")
            ]);
        } catch (\Exception $e) {
            ms([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}