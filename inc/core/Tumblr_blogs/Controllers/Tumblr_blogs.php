<?php
namespace Core\Tumblr_blogs\Controllers;

class Tumblr_blogs extends \CodeIgniter\Controller
{
    public function __construct(){
        $reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/tumblroauth.php');

        $this->consumer_key = get_option('tumblr_consumer_key', '');
        $this->consumer_secret = get_option('tumblr_consumer_secret', '');
        $this->callback_url = get_module_url();

        if($this->consumer_secret == "" || $this->consumer_secret == ""){
            redirect_to( base_url("social_network_settings/index/".$this->config['parent']['id']) ); 
        }

        $this->tumblr = new \TumblrOAuth($this->consumer_key, $this->consumer_secret);
    }
    
    public function index() {

        try {
            if( !get_session("Tumblr_AccessToken") ){
                $connection = new \TumblrOAuth($this->consumer_key, $this->consumer_secret, get_session("tumblr_oauth_token"), get_session("tumblr_oauth_token_secret"));
                $accessToken = $connection->getAccessToken( post("oauth_verifier") );
                set_session(["Tumblr_AccessToken" => json_encode($accessToken) ]);
                remove_session(["tumblr_oauth_token"]);
                remove_session(["tumblr_oauth_token_secret"]);
            }else{
                $accessToken = get_session("Tumblr_AccessToken");
                $accessToken = json_decode($accessToken, true);
            }

            $connection = new \TumblrOAuth( $this->consumer_key, $this->consumer_secret, $accessToken['oauth_token'], $accessToken['oauth_token_secret'] );
            $response = $connection->get( 'https://api.tumblr.com/v2/user/info' );

            if(!is_string($response)){

                if ( !empty( $response->response->user->name ) )
                {
                    $result = [];
                    $response = $response->response->user->blogs;
                    foreach ($response as $value) {
                        $result[] = (object)[
                            'id' => $value->name.".tumblr.com",
                            'name' => $value->name,
                            'avatar' => "https://api.tumblr.com/v2/blog/".$value->name.".tumblr.com/avatar/512",
                            'desc' => $value->title
                        ];
                    }

                    $profiles = [
                        "status" => "success",
                        "config" => $this->config,
                        "result" => $result
                    ];
                }else{
                    $profiles = [
                        "status" => "error",
                        "config" => $this->config,
                        "message" => __('Invalid Tumblr user')
                    ];
                }
            }else{
                $profiles = [
                    "status" => "error",
                    "config" => $this->config,
                    "message" => $response
                ];
            }
        } catch (\Exception $e) {
            $profiles = [
                "status" => "error",
                "config" => $this->config,
                "message" => $e->getMessage()
            ];
        }

        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            "content" => view('Core\Tumblr_blogs\Views\add', $profiles)
        ];

        return view('Core\Tumblr_blogs\Views\index', $data);
    }

    public function oauth(){
        remove_session(['Tumblr_AccessToken']);
        $request_token = $this->tumblr->getRequestToken( $this->callback_url );
        $token = $request_token['oauth_token'];
        $oauth_token_secret = $request_token['oauth_token_secret'];
        set_session(["tumblr_oauth_token" => $token]);
        set_session(["tumblr_oauth_token_secret" => $oauth_token_secret ]);
        $oauth_link =  $this->tumblr->getAuthorizeURL($token);
        redirect_to($oauth_link);
    }

    public function save()
    {
        try {
            $ids = post('id');
            $team_id = get_team("id");
            $accessToken = get_session('Tumblr_AccessToken');
            $accessToken = json_decode($accessToken, true);

            validate('empty', __('Please select a profile to add'), $ids);

            $connection = new \TumblrOAuth( $this->consumer_key, $this->consumer_secret, $accessToken['oauth_token'], $accessToken['oauth_token_secret'] );
            $response = $connection->get( 'https://api.tumblr.com/v2/user/info' );

            if(!is_string($response)){

                if ( !empty( $response->response->user->name ) )
                {
                    $result = [];
                    $response = $response->response->user->blogs;
                    foreach ($response as $value) {
                        $tumblr_id = $value->name.".tumblr.com";
                        if(in_array( $tumblr_id , $ids, true)){
                            $avatar = "https://api.tumblr.com/v2/blog/".$value->name.".tumblr.com/avatar/512";
                            $item = db_get('*', TB_ACCOUNTS, "social_network = 'tumblr' AND team_id = '{$team_id}' AND pid = '".$tumblr_id."'");
                            if(!$item){
                                //Check limit number 
                                check_number_account("tumblr", "blog");
                                $avatar = save_img( $avatar, WRITEPATH.'avatar/' );
                                $data = [
                                    'ids' => ids(),
                                    'module' => $this->module,
                                    'social_network' => 'tumblr',
                                    'category' => 'blog',
                                    'login_type' => 1,
                                    'can_post' => 1,
                                    'team_id' => $team_id,
                                    'pid' => $tumblr_id,
                                    'name' => $value->name,
                                    'username' => $value->name,
                                    'token' => json_encode($accessToken),
                                    'avatar' => $avatar,
                                    'url' => $value->url,
                                    'data' => NULL,
                                    'status' => 1,
                                    'changed' => time(),
                                    'created' => time()
                                ];

                                db_insert(TB_ACCOUNTS, $data);
                            }else{
                                unlink( get_file_path($item->avatar) );
                                $avatar = save_img( $avatar, WRITEPATH.'avatar/' );
                                $data = [
                                    'can_post' => 1,
                                    'pid' => $tumblr_id,
                                    'name' => $value->name,
                                    'username' => $value->name,
                                    'token' => json_encode($accessToken),
                                    'avatar' => $avatar,
                                    'url' => $value->url,
                                    'status' => 1,
                                    'changed' => time(),
                                ];

                                db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
                            }

                        }
                    }
                }else{
                    ms([
                        "status" => "error",
                        "message" => __('Invalid Tumblr user')
                    ]);
                }
                    
                ms([
                    "status" => "success",
                    "message" => __("Success")
                ]);
       
            }else{
                ms([
                    "status" => "error",
                    "message" => $response
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