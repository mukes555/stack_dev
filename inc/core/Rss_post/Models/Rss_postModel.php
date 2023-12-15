<?php
namespace Core\Rss_post\Models;
use CodeIgniter\Model;

class Rss_postModel extends Model
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
    }

    public function block_plans(){
        return [
            "tab" => 30,
            "position" => 300,
            "label" => __("Advanced features"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => $this->config['name'],
                ]
            ]
        ];
    }

    public function get_list( $return_data = true )
    {
        $team_id = get_team("id");
        $current_page = (int)(post("current_page") - 1);
        $per_page = post("per_page");
        $total_items = post("total_items");
        $keyword = post("keyword");

        $db = \Config\Database::connect();
        $builder = $db->table(TB_RSS);
        $builder->select('*');

        $builder->where("( team_id = '{$team_id}' )");

        if( $keyword ){
            $builder->where("( name LIKE '%{$keyword}%' OR description LIKE '%{$keyword}%' OR url LIKE '%{$keyword}%' )") ;
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
		        	$db = \Config\Database::connect();
			        $builder = $db->table(TB_RSS_ACCOUNTS." as a");
			        $builder->join(TB_ACCOUNTS." as b", 'a.account_id = b.pid');
                    $builder->where("a.rss_id", $value->id);
                    $builder->where("b.team_id", $team_id);
			        $builder->where("b.status", 1);
			        $builder->select('b.avatar, b.name, b.social_network');
			        $query = $builder->get();
			        $accounts =  $query->getResult();
			        $total_accounts = count($accounts);
	            	$result[$key]->left_accounts = ($total_accounts>4)?($total_accounts - 4):0;
	            	$result[$key]->accounts = array_slice($accounts, 0, 4);
	            	$query->freeResult();
	        	}
	        }
        }

        return $result;
    }

    public function categories(){
        $db = \Config\Database::connect();
        $builder = $db->table(TB_RSS_POSTS);
        $builder->select("social_network,function");
        $builder->orderBy("social_network", "ASC");
        $builder->groupBy("social_network,function");
        $query = $builder->get();
        $result = $query->getResult();
        if(!empty($result)){
            foreach ($result as $key => $row)
            {

                $config = find_modules( $row->social_network."_post" );

                if($config)
                {
                    $result[$key]->name = $config['name'];
                    $result[$key]->icon = $config['icon'];
                    $result[$key]->color = $config['color'];
                }
                else
                {
                    $result[$key]->name = "";
                    $result[$key]->icon = "";
                    $result[$key]->color = "";
                }

            }
        }
        $query->freeResult();

        return $result;
    }

    public function calendar($rss_id, $type, $social_network)
    {
        $db = \Config\Database::connect();

        switch ($type) {
            case 'published':
                $status = 3;
                break;

            case 'unpublished':
                $status = 4;
                break;
            
            default:
                $status = 1;
                break;
        }

        $team_id = get_team("id");

        $builder = $db->table(TB_RSS_POSTS);
        $builder->select("from_unixtime(time_post,'%Y-%m-%d') as time_posts, from_unixtime(repost_until,'%Y-%m-%d') as repost_untils, social_network, COUNT(time_post) as total, category, function");
        $builder->where("status = '{$status}' AND team_id = '{$team_id}' AND rss_id = '{$rss_id}'");

        if(strip_tags($social_network) != "all"){
            $builder->where("social_network = '".$social_network."'");
        }

        $builder->groupBy("time_posts,repost_untils,social_network,category,function");
        $builder->orderBy("repost_untils", "DESC");
        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();


        if($result)
        {   
            foreach ($result as $key => $value) {
                if( !permission($value->social_network."_post") ){
                    unset( $result[$key] );
                    continue;
                }
            }
        }

        return $result;
    }

    public function list($rss_id, $type, $category, $time)
    {   
        $db = \Config\Database::connect();

        $time_check = explode("-", $time);
        
        if( count($time_check) != 3 || !checkdate( (int)$time_check[1], (int)$time_check[2], (int)$time_check[0]) ) return false;

        switch ($type) {
            case 'published':
                $status = 3;
                break;

            case 'unpublished':
                $status = 4;
                break;
            
            default:
                $status = 1;
                break;
        }

        $team_id = get_team("id");
        $date_start = $time . " 00:00:00";
        $date_end = $time . " 23:59:59";

        $builder = $db->table(TB_RSS_POSTS." as a");
        $builder->select("
            from_unixtime(a.time_post,'%Y-%m-%d %H:%i:%s') as time_posts, 
            from_unixtime(a.repost_until,'%Y-%m-%d %H:%i:%s') as repost_untils, 
            a.rss_id, 
            a.time_post, 
            a.repost_frequency, 
            a.repost_until, 
            a.team_id, 
            a.social_network, 
            a.category,
            a.type,
            a.id,
            a.ids,
            a.data,
            a.status,
            a.result,
            b.name,
            b.username,
            b.avatar,
            b.url
        ");

        $builder->join(TB_ACCOUNTS." as b", "a.account_id = b.id");

        $cate = "";
        if(strip_tags($category) != "all"){
            $cate = " a.social_network = '{$category}' AND ";
        }

        $builder->having(" ( {$cate} a.rss_id = '{$rss_id}' AND a.status = '{$status}' AND from_unixtime(a.time_post,'%Y-%m-%d %H:%i:%s') >= '{$date_start}' AND from_unixtime(a.time_post,'%Y-%m-%d %H:%i:%s') <= '{$date_end}' AND a.repost_until IS NULL AND a.team_id = '{$team_id}' ) ");
        $builder->orHaving(" ( {$cate} a.rss_id = '{$rss_id}' AND a.status = '{$status}' AND from_unixtime(a.time_post,'%Y-%m-%d 00:00:00') <= '{$date_end}' AND from_unixtime(a.repost_until,'%Y-%m-%d 23:59:59') >= '{$date_start}' AND a.team_id = '{$team_id}' ) ");
        
        $builder->orderBy("a.time_post ASC");
        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();

        if( $result ){
            foreach ($result as $key => $value) {
                $config = find_modules( $value->social_network );

                /*if( !_p($value->category."_enable") ){
                    unset( $result[$key] );
                    continue;
                }*/

                if($config)
                {
                    $result[$key]->name = $config['name'];
                    $result[$key]->icon = $config['icon'];
                    $result[$key]->color = $config['color'];

                }else{

                    $result[$key]->name = "";
                    $result[$key]->icon = "";
                    $result[$key]->color = "";
                }
            }
        }

        return $result;
    }

    public function validator($posts){
        $models = $this->post_models("post_validator");
        $errors = [];
        $social_post = [];
        $have_errors = false;
        $can_post = false;
        $html_errors = "";
        $count_errors = 0;
        $social_can_posts = [];
        $configs = [];

        foreach ($posts as $key => $post)
        {
            if(isset( $models[ $post->social_network ] )){
                $model = $models[ $post->social_network ];
                $result = $model->post_validator($post);

                if(!empty($result)){
                    $errors[ $post->social_network ] = $result;
                    $social_post[] = ucfirst( $post->social_network ); 
                }else{
                    $errors[ $post->social_network ] = [];
                    $social_post[] = ucfirst( $post->social_network );
                }
            }else{
                $errors[ $post->social_network ] = [];
                $social_post[] = ucfirst( $post->social_network );
            }
        }

        if(!empty($errors)){
            foreach ($errors as $social => $sub_errors) {
                if(empty($sub_errors)){
                    $can_post = true;
                    $social_can_posts[] = $social;
                }else{
                    $have_errors = true;

                    foreach ($sub_errors as $key => $error) {
                        $html_errors .= "<li>{$error}</li>";
                    }
                    $count_errors++;
                }
            }
        }

        $html_errors = "<p>".sprintf( __("%d profiles will be excluded from your publication in next step due to errors"),  $count_errors)." </p><ul>".$html_errors."</ul>";

        $message = "";
        $status = "";
        if(!$have_errors){
            $status = "success";
        }else{
            if($can_post){
                $status = "warning";
            }else{
                if( $count_errors == 1 ){
                    $status = "error";
                    $error = end( $errors );
                    $error = is_array($error)?$error[0]:$error;
                    $message = __($error);
                }else{
                    $social_post = array_unique($social_post);
                    $status = "error";
                    $message = sprintf( __("Missing content on the following social networks: %s"),  implode(", ", $social_post) );
                }

            }
        }

        return array(
            "status"   => $status,
            "errors"   => $html_errors,
            "message"  => $message,
            "can_post" => json_encode($social_can_posts) 
        );
    }

    public function post( $posts, $social_can_post = false ){
        $team_id = get_team("id");
        $post_by = post("post_by");
        $models = $this->post_models("post_handler");

        $post_id = 0;
        $count_error = 0;
        $count_success = 0;
        $count_schedule = 0; 
        $message = ""; 

        validate('empty', __('Accounts selected is inactive. Let re-login and try again'), $posts);

        foreach ($posts as $key => $post)
        {
           $team_id = $post->team_id;
            $social_network = $post->social_network;
            if( (is_array($social_can_post) && in_array($social_network, $social_can_post)) || !$social_can_post)
            {
                if( isset( $models[ $social_network ] ) )
                {
                    $model = $models[ $social_network ];

                    $account = db_get("*", TB_ACCOUNTS, ["id" => $post->account_id ]);
                    if(empty($account))
                    {
                        $count_error++;
                        $message = __("This profile not exist");

                        //Update
                        if( post("ids") )
                        {
                            $post->status = 4;
                            $post->result = json_encode([
                                "message" => trim($message)
                            ]);
                            db_update(TB_RSS_POSTS, $post, [ "id" => $post->id ]);
                        }
                    }else{
                        $post->account = $account;
                        $response = $model->post_handler($post);

                        if( $response['status'] == "success" )
                        {
                            $count_success++;
                            $message = $response["message"];
                            $post->status = 3;
                            $post->result = json_encode([
                                "id" => $response["id"],
                                "url" => $response["url"],
                                "message" => $response["message"]
                            ]);

                             ;

                            _urss("post_succeed", _grss("post_succeed", 0, $post->rss_id) + 1, $post->rss_id);
                            update_team_data($social_network."_rss_post_success_count", get_team_data($social_network."_rss_post_success_count", 0, $team_id) + 1, $team_id);
                            update_team_data($social_network."_rss_post_count", get_team_data($social_network."_rss_post_count", 0, $team_id) + 1, $team_id);
                            update_team_data($social_network."_rss_post_". $response["type"] ."_count", get_team_data($social_network."_rss_post_". $response["type"] ."_count", 0, $team_id) + 1, $team_id);
                        }
                        else
                        {
                            $count_error++;
                            $message = $response["message"];

                            $post->status = 4;
                            $post->result = json_encode([
                                "message" => $response["message"]
                            ]); 

                            _urss("post_failed", _grss("post_failed", 0, $post->rss_id) + 1, $post->rss_id);
                            update_team_data($social_network."_rss_post_error_count", get_team_data($social_network."_rss_post_error_count", 0, $team_id) + 1, $team_id);
                            update_team_data($social_network."_rss_post_count", get_team_data($social_network."_rss_post_count", 0, $team_id) + 1, $team_id);
                        }
                        

                        if(isset($post->account)){
                            unset( $post->account );
                        }

                        db_update(TB_RSS_POSTS, $post, [ "id" => $post->id ]);
                    }

                }
                else
                {
                    $count_error++;
                    $post->status = 4;
                    $post->result = json_encode([
                        "message" => __("Can't post to this social network")
                    ]);
                    db_update(TB_RSS_POSTS, $post);
                }
            }
        }

        if($post_by == 1 || isset($post->id))
        {
            if($count_error == 0)
            {
                return [
                    "status"  => "success",
                    "message" => sprintf(__("Content is being published on %d profiles"), $count_success)
                ];
            }
            else
            {
                if($count_error == 1 && $count_success == 0)
                {
                    return [
                        "status"  => "error",
                        "message" => $message
                    ];
                }
                else
                {
                    return [
                        "status"  => "error",
                        "message" => sprintf(__("Content is being published on %d profiles and %d profiles unpublished"), $count_success, $count_error)
                    ];
                }
            }
        }
        else
        {
            return [
                "status"  => "success",
                "message" => __("Content successfully scheduled")
            ];
        }
    }

    public function post_models($function = ""){
        $models = [];
        $module_paths = get_module_paths();

        if(!empty($module_paths))
        {
            if( !empty($module_paths) )
            {
                foreach ($module_paths as $key => $module_path) {
                    $model_paths = $module_path . "/Models/";
                    $model_files = glob( $model_paths . '*' );

                    if ( !empty( $model_files ) )
                    {
                        foreach ( $model_files as $model_file )
                        {
                            $model_content = file_get_contents($model_file);
                            if (preg_match("/".$function."/i", $model_content))
                            {
                                try {
                                    $model = run_class($model_file);

                                    if( isset($model->config) && isset($model->config['id']) ){
                                        $models[$model->config['parent']['id']] = $model;
                                    }
                                } catch (\Exception $e) {}
                            }
                        }
                    }
                }

            }
        }

        return $models;
    }

    public function get_rss(){
        $db = \Config\Database::connect();
        $builder = $db->table(TB_RSS." as a");
        $builder->select("a.id,c.token,a.url,a.team_id, c.pid, c.username, c.social_network, c.proxy, c.id as account_id, c.category as category, c.login_type as api_type,");
        $builder->join( TB_RSS_ACCOUNTS." as b", "a.id = b.rss_id");
        $builder->join( TB_ACCOUNTS." as c", "b.account_id = c.pid AND a.team_id = c.team_id" );
        $builder->where("c.status", 1);
        $builder->where("a.status", 1);
        $builder->where("a.next_action <=", time());
        $builder->orderBy("a.next_action", "asc");
        $builder->groupBy("c.id,c.token,c.pid,c.username,c.social_network,c.proxy,c.category,c.login_type,a.id,a.next_action,a.url,a.team_id");
        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();

        return $result;
    }

    public function get_posts(){
        $db = \Config\Database::connect();
        $builder = $db->table(TB_RSS_POSTS);
        $builder->select('id,ids,team_id,rss_id,function,type,data,time_post,delay,repost_frequency,repost_until,result,status,changed,created,account_id,social_network,api_type,category');
        $builder->where('status = 1');
        $builder->where("time_post <= '".time()."'");
        $builder->where("type != 'live'");
        $builder->orderBy("time_post", "ASC");
        $builder->limit(5, 0);
        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();
        return $result;
    }
}
