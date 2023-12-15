<?php
namespace Core\Tinder_profiles\Controllers;

class Tinder_profiles extends \CodeIgniter\Controller
{
    public function __construct(){
        $reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/TinderAPI.php');
    }
    
    public function index() {

        try {
            if(!get_session("Tinder_AccessToken")){
                redirect_to( get_module_url("oauth") );
            }

            $result = [];
            $access_token = get_session("Tinder_AccessToken");
            $tinder = new \TinderAPI($access_token);
            $profile = $tinder->userinfo();

            if(isset($profile["name"])){
                $result = [];
                $result[] = (object)[
                    'id' => $profile["_id"],
                    'name' => $profile["name"],
                    'avatar' => $profile["photos"][0]["url"],
                    'desc' => $profile["email"]
                ];                      

                $profiles = [
                    "status" => "success",
                    "config" => $this->config,
                    "result" => $result
                ];
            }else{
                $profiles = [
                    "status" => "error",
                    "config" => $this->config,
                    "message" => __('No profile to add')
                ];
            }
        } catch (\Exception $e) {
            pr($e,1);
            $profiles = [
                "status" => "error",
                "config" => $this->config,
                "message" => $e->getMessage()
            ];
        }

        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Tinder_profiles\Views\add', $profiles)
        ];

        return view('Core\Tinder_profiles\Views\index', $data);
    }

    public function oauth(){
        remove_session(['Tinder_AccessToken']);
        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Tinder_profiles\Views\oauth', [ "config" => $this->config ])
        ];

        return view('Core\Tinder_profiles\Views\index', $data);
    }

    public function token()
    {
        try {
            $accessToken = post("access_token");

            if(empty($accessToken)){
                ms([
                    "status" => "error",
                    "message" => __('Please enter access token')
                ]);
            }
            
            $tinder = new \TinderAPI($accessToken);
            $check_token = $tinder->FBAuth($accessToken);

            if($check_token){
                set_session(["Tinder_AccessToken" => $accessToken]);

                ms([
                    "status" => "success",
                    "message" => __("Success")
                ]);
            }else{
                ms([
                    "status" => "error",
                    "message" => __('Invalid X-Auth-Token')
                ]);
            }
        } catch (\Exception $e) {
            ms([
                "status" => "error",
                "message" => __( $e->getMessage() )
            ]);
        }
    }

    public function save()
    {
        try {
            $ids = post('id');
            $team_id = get_team("id");

            validate('empty', __('Please select a profile to add'), $ids);

            $accessToken = get_session("Tinder_AccessToken");
            $tinder = new \TinderAPI($accessToken);
            $response = $tinder->userinfo();

            if(is_array($response) && isset( $response['name'] )){

                if(in_array($response["_id"], $ids)){
                    $item = db_get('*', TB_ACCOUNTS, "social_network = 'tinder' AND team_id = '{$team_id}' AND pid = '".$response['_id']."'");
                    if(!$item){
                        //Check limit number 
                        check_number_account("tinder", "profile");
                        $avatar = save_img( $response["photos"][0]["url"], WRITEPATH.'avatar/' );
                        $data = [
                            'ids' => ids(),
                            'module' => $this->module,
                            'social_network' => 'tinder',
                            'category' => 'profile',
                            'login_type' => 2,
                            'can_post' => 0,
                            'team_id' => $team_id,
                            'pid' => $response['_id'],
                            'name' => $response['name'],
                            'username' => $response['email'],
                            'token' => $accessToken,
                            'avatar' => $avatar,
                            'url' => 'https://tinder.com/app/profile',
                            'data' => NULL,
                            'status' => 1,
                            'changed' => time(),
                            'created' => time()
                        ];

                        db_insert(TB_ACCOUNTS, $data);
                    }else{
                        unlink( get_file_path($item->avatar) );
                        $avatar = save_img( $response["photos"][0]["url"], WRITEPATH.'avatar/' );
                        $data = [
                            'can_post' => 0,
                            'pid' => $response['_id'],
                            'name' => $response['name'],
                            'username' => $response['email'],
                            'token' => $accessToken,
                            'avatar' => $avatar,
                            'url' => 'https://tinder.com/app/profile',
                            'status' => 1,
                            'changed' => time(),
                        ];

                        db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
                    }
                }

                ms([
                    "status" => "success",
                    "message" => __("Success")
                ]);
       
            }else{
                ms([
                    "status" => "error",
                    "message" => __('No profile to add')
                ]);
            }
        } catch (\Exception $e) {
            ms([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}