<?php
namespace Core\Reddit_profiles\Controllers;
use myPHPnotes\LinkedIn;

class Reddit_profiles extends \CodeIgniter\Controller
{
    public function __construct(){
        $reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/redditoauth.php');
        $client_id = get_option("reddit_client_id", "");
        $client_secret = get_option("reddit_client_secret", "");
        $callback_url = get_module_url();

        if($client_id == "" || $client_secret == ""){
            redirect_to( base_url("social_network_settings/index/".$this->config['parent']['id']) ); 
        }

        $this->reddit = new \redditoauth($client_id, $client_secret, $callback_url);
    }
    
    public function index() {

        try {
            if(!get_session("Reddit_AccessToken")){
                $accessToken = $this->reddit->getAccessToken( post('code') );
                if ( isset($accessToken->access_token) ) {
                    $accessToken = json_encode($accessToken);
                    set_session(["Reddit_AccessToken" => $accessToken]);
                }else{
                    $access_token = false;
                }
            }else{
                $accessToken = get_session("Reddit_AccessToken");
            }

            if($accessToken){
                $this->reddit->setAccessToken($accessToken);
                $response = $this->reddit->getUser();

                $result = [];
                $result[] = (object)[
                    'id' => $response->subreddit->display_name,
                    'name' => $response->name,
                    'avatar' => $response->icon_img,
                    'desc' => $response->name
                ];

                $response = $this->reddit->getSubscriptions();

                if($response->data->dist != 0){
                    foreach ($response->data->children as $key => $value) {
                        if($value->data->user_is_moderator){
                            $avatar = $value->data->icon_img;
                            if($avatar == ""){
                               $avatar = get_avatar( $value->data->display_name );
                            }

                            $result[] = (object)[
                                'id' => $value->data->display_name,
                                'name' => $value->data->display_name_prefixed,
                                'avatar' => $avatar,
                                'desc' => $value->data->public_description
                            ];
                        }
                    }
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
                    "message" => __('No profile to add')
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
            "content" => view('Core\Reddit_profiles\Views\add', $profiles)
        ];

        return view('Core\Reddit_profiles\Views\index', $data);
    }

    public function oauth(){
        remove_session(['Reddit_AccessToken']);
        $oauth_link = $this->reddit->getAuthorizeURL();
        redirect_to( $oauth_link );
    }

    public function save()
    {
        $ids = post('id');
        $team_id = get_team("id");
        $accessToken = get_session('Reddit_AccessToken');

        validate('empty', __('Please select a profile to add'), $ids);

        $this->reddit->setAccessToken($accessToken);
        $response = $this->reddit->getUser();

        if(!is_string($response)){

            if(in_array( $response->subreddit->display_name , $ids)){
                
                $item = db_get('*', TB_ACCOUNTS, "social_network = 'reddit' AND team_id = '{$team_id}' AND pid = '".$response->subreddit->display_name."'");
                if(!$item){
                    //Check limit number 
                    check_number_account("reddit", "profile");
                    $avatar = str_replace("&amp;", "&", $response->icon_img);
                    $avatar = save_img( $avatar, WRITEPATH.'avatar/' );
                    $data = [
                        'ids' => ids(),
                        'module' => $this->module,
                        'social_network' => 'reddit',
                        'category' => 'profile',
                        'login_type' => 1,
                        'can_post' => 1,
                        'team_id' => $team_id,
                        'pid' => $response->subreddit->display_name,
                        'name' => $response->name,
                        'username' => $response->subreddit->display_name,
                        'token' => $accessToken,
                        'avatar' => $avatar,
                        'url' => 'https://www.reddit.com/user/'.$response->name,
                        'data' => NULL,
                        'status' => 1,
                        'changed' => time(),
                        'created' => time()
                    ];

                    db_insert(TB_ACCOUNTS, $data);
                }else{
                    unlink( get_file_path($item->avatar) );
                    $avatar = str_replace("&amp;", "&", $response->icon_img);
                    $avatar = save_img( $avatar, WRITEPATH.'avatar/' );
                    $data = [
                        'can_post' => 1,
                        'pid' => $response->subreddit->display_name,
                        'name' => $response->name,
                        'username' => $response->subreddit->display_name,
                        'token' => $accessToken,
                        'avatar' => $avatar,
                        'url' => 'https://www.reddit.com/user/'.$response->name,
                        'status' => 1,
                        'changed' => time(),
                    ];

                    db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
                }
            }

            $response = $this->reddit->getSubscriptions();
            if($response->data->dist != 0){
                foreach ($response->data->children as $key => $value) {
                    if($value->data->user_is_moderator){
                        if(in_array( $value->data->display_name , $ids)){
                            $avatar = $value->data->icon_img;
                            if($avatar == ""){
                               $avatar = get_avatar( $value->data->display_name );
                            }

                            $item = db_get('*', TB_ACCOUNTS, "social_network = 'reddit' AND team_id = '{$team_id}' AND pid = '".$value->data->display_name."'");
                            if(!$item){
                                //Check limit number 
                                check_number_account("reddit", "profile");
                                $avatar = str_replace("&amp;", "&", $avatar);
                                $avatar = save_img( $avatar, WRITEPATH.'avatar/' );
                                $data = [
                                    'ids' => ids(),
                                    'social_network' => 'reddit',
                                    'category' => 'profile',
                                    'login_type' => 1,
                                    'can_post' => 1,
                                    'team_id' => $team_id,
                                    'pid' => $value->data->display_name,
                                    'name' => $value->data->display_name_prefixed,
                                    'username' => $value->data->display_name,
                                    'token' => $accessToken,
                                    'avatar' => $avatar,
                                    'url' => 'https://www.reddit.com/user/'.$value->data->name,
                                    'data' => NULL,
                                    'status' => 1,
                                    'changed' => time(),
                                    'created' => time()
                                ];

                                db_insert(TB_ACCOUNTS, $data);
                            }else{
                                unlink( get_file_path($item->avatar) );
                                $avatar = str_replace("&amp;", "&", $avatar);
                                $avatar = save_img( $avatar, WRITEPATH.'avatar/' );
                                $data = [
                                    'can_post' => 1,
                                    'pid' => $value->data->display_name,
                                    'name' => $value->data->display_name_prefixed,
                                    'username' => $value->data->display_name,
                                    'token' => $accessToken,
                                    'avatar' => $avatar,
                                    'url' => 'https://www.reddit.com/user/'.$value->data->name,
                                    'status' => 1,
                                    'changed' => time(),
                                ];

                                db_update(TB_ACCOUNTS, $data, ['id' => $item->id]);
                            }
                        }
                    }
                }
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
    }
}