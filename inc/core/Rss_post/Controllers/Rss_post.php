<?php
namespace Core\Rss_post\Controllers;

class Rss_post extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
        $this->class_name = get_class_name($this);
        $this->model = new \Core\Rss_post\Models\Rss_postModel();
    }
    
    public function index( $page = false ) {
        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
        ];

        switch ( $page ) {
            case 'update':
                $item = false;
                $ids = uri('segment', 4);
                if( $ids ){
                    $item = db_get("*", TB_BLOGS, [ "ids" => $ids ]);
                }

                $data['content'] = view('Core\Rss_post\Views\update', ["result" => $item]);
                break;

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

                $data['content'] = view('Core\Rss_post\Views\list', $data_content);
                break;
        }

        return view('Core\Rss_post\Views\index', $data);
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
            "data" => view('Core\Rss_post\Views\ajax_list', $data)
        ] );
    }

    public function delete( ){
        $team_id = get_team("id");
        $ids = post('id');

        $item = db_get("*", TB_RSS, [ "ids" => $ids, "team_id" => $team_id ]);

        if( empty($item) ){
            ms([
                "status" => "error",
                "message" => __('Please select an item to delete')
            ]);
        }

        db_delete(TB_RSS, ['id' => $item->id]);
        db_delete(TB_RSS_ACCOUNTS, ['rss_id' => $item->id]);
        db_delete(TB_RSS_POSTS, ['rss_id' => $item->id]);

        ms([
            "status" => "success",
            "message" => __('Success')
        ]);
    }

    /*
    * ADD RSS
    */
    public function popup_add_rss(){
        $data = [
            'config'  => $this->config
        ];
        return view('Core\Rss_post\Views\popup_add_rss', $data);
    }

    public function save_rss(){
        $team_id = get_team("id");
        $rss_url = post("rss_url");

        if (!$this->validate([
            'rss_url' => 'required'
        ])) {
            ms([
                "status" => "error",
                "message" => __("Rss url is required")
            ]);
        }

        $result = read_rss([
            "url" => $rss_url
        ]);

        if($result['status'] == "error"){
            ms($result);
        }


        $item = db_get("*", TB_RSS, [ "url" => $rss_url, "team_id" => $team_id ]);
        if( $item ){
            ms([
                "status" => "error",
                "message" => __("This rss feed url already exist")
            ]);
        }

        $content = (array)$result['content'];

        $content = end($content);
        $title = $content->title;
        $description = $content->description;

        $data = [
            "ids" => ids(),
            'team_id' => $team_id,
            "name" => $title,
            "description" =>  $description,
            "url" =>  $rss_url,
            "next_action" => 0,
            "status" => 0,
            "changed" => now(),
            "created" => now()
        ];

        $id = db_insert( TB_RSS , $data);

        $publish_caption = _grss("publish_caption", 1, $id);
        $publish_without_link = _grss("publish_without_link", 0, $id);
        $refferal_code = _grss("refferal_code", "", $id);
        $frequency = _grss("frequency", 60, $id);
        $accept_words = _grss("accept_words", "", $id);
        $denied_words = _grss("denied_words", "", $id);

        ms([
            "status" => "success",
            "message" => __("Success")
        ]);
    }
    /*
    * END ADD RSS
    */

    /*
    * RSS SETTINGS
    */
    public function popup_rss_settings( $ids = "" ){
        $team_id = get_team("id");
        $item = db_get("*", TB_RSS, [ "ids" => $ids, "team_id" => $team_id ]);

        if( empty( $item ) ){
            ms([
                "status" => "error",
                "message" => __("Rss item not exists")
            ]);
        }

        $publish_caption = _grss("publish_caption", 1, $item->id);
        $publish_without_link = _grss("publish_without_link", 0, $item->id);
        $refferal_code = _grss("refferal_code", "", $item->id);
        $frequency = _grss("frequency", 60, $item->id);
        $accept_words = _grss("accept_words", "", $item->id);
        $denied_words = _grss("denied_words", "", $item->id);

        $data = [
            'id' => $ids,
            'publish_caption' => $publish_caption,
            'publish_without_link' => $publish_without_link,
            'frequency' => $frequency,
            'refferal_code' => $refferal_code,
            'accept_words' => $accept_words,
            'denied_words' => $denied_words,
            'config'  => $this->config
        ];
        return view('Core\Rss_post\Views\popup_settings', $data);
    }

    public function save_settings( $ids = "" ){
        $team_id = get_team("id");
        $item = db_get("*", TB_RSS, [ "ids" => $ids, "team_id" => $team_id ]);

        if( empty( $item ) ){
            ms([
                "status" => "error",
                "message" => __("Rss item not exists")
            ]);
        }

        $refferal_code = post("refferal_code");
        $publish_caption = (int)post("publish_caption");
        $publish_without_link = (int)post("publish_without_link");
        $frequency = (int)post("frequency");
        $accept_words = post("accept_words");
        $denied_words = post("denied_words");

        _urss("refferal_code", $refferal_code, $item->id);
        _urss("publish_caption", $publish_caption, $item->id);
        _urss("publish_without_link", $publish_without_link, $item->id);
        _urss("frequency", $frequency, $item->id);
        _urss("accept_words", $accept_words, $item->id);
        _urss("denied_words", $denied_words, $item->id);

        ms([
            "status" => "success",
            "message" => __("Success")
        ]);
    }
    /*
    * END RSS SETTINGS
    */

    /*
    * ACTIVE RSS
    */
    public function do_active_rss( $ids = "" ){
        $team_id = get_team("id");
        $item = db_get("*", TB_RSS, [ "ids" => $ids, "team_id" => $team_id ]);
        if( empty( $item ) ){
            ms([
                "status" => "error",
                "message" => __("Rss item not exists")
            ]);
        }

        db_update(TB_RSS, [ "status" => $item->status==1?0:1, "next_action" => 0 ], [ "ids" => $ids ]);

        ms([
            "status" => "success",
            "message" => __("Success")
        ]);
    }
    /*
    * END ACTIVE RSS
    */

    /*
    * RSS ACCOUNTS
    */
    public function popup_add_account( $ids = "" ){
        $team_id = get_team("id");
        $item = db_get("*", TB_RSS, [ "ids" => $ids, "team_id" => $team_id ]);

        if( empty( $item ) ){
            ms([
                "status" => "error",
                "message" => __("Rss item not exists")
            ]);
        }

        $accounts = db_fetch("*", TB_ACCOUNTS, ["team_id" => $team_id]);
        $rss_accounts = db_fetch("*", TB_RSS_ACCOUNTS, ["rss_id" => $item->id]);
        $current_accounts = [];
        if(!empty($rss_accounts)){
            foreach ($rss_accounts as $key => $value) {
                $current_accounts[] = $value->account_id;
            }
        }

        $data = [
            'id' => $ids,
            "accounts" => $accounts,
            "current_accounts" => $current_accounts,
            "config" => $this->config
        ];
        
        return view('Core\Rss_post\Views\popup_add_account', $data);
    }

    public function do_add_account($ids = ""){
        $team_id = get_team("id");
        $accounts = post("accounts");

        $rss = db_get("*", TB_RSS, [ "ids" => $ids, "team_id" => $team_id ]);
        if( !$rss ){
            ms([
                "status" => "error",
                "message" => __("This rss feed not already exist")
            ]);
        }

        validate('empty', __('Please select at least a profile'), $accounts);

        $current_accounts = db_fetch("account_id", TB_RSS_ACCOUNTS, [ "rss_id" => $rss->id ]);
        $remove_accounts = [];
        if( !empty($current_accounts) ){
            foreach ($current_accounts as $key => $value) {
                $remove_accounts[] = $value->account_id;
            }
        }

        foreach ($accounts as $key => $account)
        {
            $result = [];
            $account = db_get("*", TB_ACCOUNTS, [ "pid" => $account, "team_id" => $team_id, "status" => 1 ]);
            if($account){
                $item = db_get("*", TB_RSS_ACCOUNTS, [ "rss_id" => $rss->id, "account_id" => $account->pid ]);

                if (($key = array_search($account->pid, $remove_accounts)) !== false) {
                    unset($remove_accounts[$key]);
                }

                if(!$item){
                    $data = [
                        "ids" => ids(),
                        "rss_id" => $rss->id,
                        "account_id" => $account->pid
                    ];
                    db_insert( TB_RSS_ACCOUNTS, $data);
                }
            }
        }

        if(!empty($remove_accounts)){
            foreach ($remove_accounts as $key => $value) {
                db_delete( TB_RSS_ACCOUNTS, [ "account_id" => $value, "rss_id" => $rss->id ] );
            }
        }

        ms([
            "status" => "success",
            "message" => __("Success")
        ]);
    }
    /*
    * END RSS ACCOUNTS
    */

    /*
    * RSS SCHEDULES
    */
    public function schedules( $rss_ids = "", $type = "", $social_network = "", $time = "" ) {

        $rss = db_get("*", TB_RSS, [ "ids" => $rss_ids ]);
        if(empty($rss)){
            redirect_to( get_module_url() );
        }

        if(!in_array($type, ["queue", "published", "unpublished"]) || $social_network == "") redirect_to( get_module_url("schedules/".$rss_ids."/published/all/") );

        $categories = $this->model->categories();
        $result = $this->model->list($rss->id, $type, $social_network, $time);

        $list_schedules = view('Core\Rss_post\Views\schedule_list', ['result' => $result]);

        if(!is_ajax()){
            $data = [
                "title" => $this->config['name'],
                "desc" => $this->config['desc'],
                "config" => $this->config,
                "categories" => $categories,
                "content" => view('Core\Rss_post\Views\calendar', ['result' => $result, 'list_schedules' => $list_schedules])
            ];

            return view('Core\Rss_post\Views\schedule_main', $data);
        }else{
            return $list_schedules;
        }
    }

    public function schedules_get($rss_id = "", $type = "", $social_network = ""){

        $rss = db_get("*", TB_RSS, [ "ids" => $rss_id ]);
        if(empty($rss)){
            echo json_encode([ 'monthly' => [] ]);
        }

        $posts = $this->model->calendar($rss->id, $type, $social_network);

        if($posts)
        {
            $data = [];
            foreach ($posts as $key => $post)
            {
                $config = find_modules( $post->social_network."_post" );

                if($config)
                {
                    $module_name = $config['name'];
                    $module_icon = $config['icon'];
                    $module_color = $config['color'];

                    $data[] = [
                        "id" => 1,
                        "name" => "<i class='{$module_icon}'></i> {$module_name} ({$post->total})",
                        "startdate" => $post->time_posts,
                        "enddate" => $post->repost_untils,
                        "color" => "{$module_color}",
                    ];

                }

            }

            $data = [
                "monthly" => $data
            ];

            echo json_encode($data);

        }
        else
        {
            echo json_encode([ 'monthly' => [] ]);
        }

    }

    public function schedules_delete( $type ="single" ){
        $team_id = get_team("id");
        switch ($type) {
            case 'multi':
                $type = post("type");
                $social_network = post("social_network");

                switch ($type) {
                    case 'queue':
                        $status = 1;
                        break;

                    case 'published':
                        $status = 3;
                        break;

                    case 'unpublished':
                        $status = 4;
                        break;
                    
                    default:
                        ms([
                            "status" => "error",
                            "message" => __("Delete failed")
                        ]);
                        break;
                }

                $data = [ "team_id" => $team_id, "status" => $status ];
                if($social_network != "all"){
                    $data["social_network"] = $social_network;
                }
                db_delete( TB_RSS_POSTS, $data );

                ms([
                    "status" => "success",
                    "message" => __("Success")
                ]);
                break;
            
            default:
                $ids = post("id");
                db_delete( TB_RSS_POSTS,  [ "ids" => $ids, "team_id" => $team_id ]);
                ms([
                    "status" => "success",
                    "message" => __("Success")
                ]);
                break;
        }

    }
    /*
    * END RSS SCHEDULES
    */

    /*
    * RSS CRON
    */
    public function cron(){
        $result = $this->model->get_rss();
        if(!empty( $result )){
            foreach ($result as $key => $rss) {
                $publish_caption = _grss("publish_caption", 1, $rss->id);
                $publish_without_link = _grss("publish_without_link", 0, $rss->id);
                $refferal_code = _grss("refferal_code", "", $rss->id);
                $frequency_per_post = _grss("frequency", 60, $rss->id);
                $accept_words = _grss("accept_words", "", $rss->id);
                $denied_words = _grss("denied_words", "", $rss->id);
                $post_succeed = _grss("post_succeed", 0, $rss->id);
                $post_failed = _grss("post_failed", 0, $rss->id);

                $medias = [];

                $rss_data = read_rss([
                    "url" => $rss->url
                ]);

                $rss_feeds = $rss_data['content'];
                $post = [];
                foreach ($rss_feeds->item as $key => $item) {
                    $title = "";
                    $caption = "";
                    $link = "";

                    if( isset($item->title) ){
                        $title = strip_tags( htmlspecialchars_decode($item->title, ENT_QUOTES) );
                    }

                    if( isset($item->description) ){
                        $caption = strip_tags( htmlspecialchars_decode($item->description, ENT_QUOTES) );
                    }

                    if( isset($item->link) ){
                        $link = addslashes( $item->link );
                    }

                    if($caption == "" && $title != ""){
                        $caption = $title;
                    }

                    //Check Accept Words
                    $can_post = true;
                    if($accept_words != ""){
                        $can_post = false;
                        $accept_words_arr = explode(",", strtolower($accept_words));
                        foreach ($accept_words_arr as $word) {
                            if(stripos( strtolower($title), $word) !== FALSE || stripos( strtolower($caption) , $word) !== FALSE){
                                $can_post = true;
                            }
                        }

                    }

                    //Check Deneid Words
                    if($denied_words != ""){
                        $denied_words_arr = explode(",", $denied_words);
                        foreach ($denied_words_arr as $word) {
                            if(stripos( strtolower($title), $word) !== FALSE || stripos( strtolower($caption), $word) !== FALSE){
                                $can_post = false;
                            }
                        }
                    }

                    //Check link posted
                    $check_post = db_get("id", TB_RSS_POSTS, [
                        "link" => $link,
                        "social_network" => $rss->social_network, 
                        "rss_id" => $rss->id,
                        "account_id" => $rss->account_id
                    ]);

                    if($check_post){
                        $can_post = false;
                    }

                    if($can_post){
                        if( $link != "" && !filter_var($link, FILTER_VALIDATE_URL) ){
                            $parse_link = get_link_info( $link );
                            if( isset($parse_link['image']) && $parse_link['image'] != "" ){
                                $medias[] = $parse_link['image'];
                            }
                        }

                        //Add Refferal Code
                        if($refferal_code != ""){
                            if(stripos($link, "&") === FALSE){
                                $link .=  "?".$refferal_code;
                            }else{
                                $link .=  "&".$refferal_code;
                            }
                        }

                        db_update(TB_RSS, [ "next_action" => time() + $frequency_per_post*60 ], [ "id" => $rss->id ]);


                        $post = (object)[
                            "ids" => ids(),
                            "rss_id" => $rss->id,
                            "team_id" => $rss->team_id,
                            "account_id" => $rss->account_id,
                            "social_network" => $rss->social_network,
                            "category" => $rss->category,
                            "api_type" => $rss->api_type,
                            "function" => "post",
                            "type" => "link",
                            "data" => json_encode([
                                "caption" => trim($caption),
                                "link" => $link,
                                "medias" => $medias
                            ]),
                            "time_post" => time(),
                            "delay" => 5,
                            "repost_frequency" => 0,
                            "result" => "",
                            "link" => $link,
                            "status" => 1,
                            "changed" => time(),
                            "created" => time()
                        ];

                        break 1;
                    }
                  
                }

                if(!empty($post)){
                    $list_data = [(object)$post];
                    $validator = $this->model->validator($list_data);
                    $social_can_post = json_decode($validator["can_post"]);
                    if( !empty($social_can_post) || $validator["status"] == "success" ){
                        db_insert(TB_RSS_POSTS, $post);
                    }
                }
                
            }
        }

        $posts = $this->model->get_posts();
        if($posts){ 
            foreach ($posts as $post) {
                db_update( TB_RSS_POSTS, [
                    "status" => 4,
                    "result" => json_encode([ "message" => __("Unknow error") ])
                ], ["id" => $post->id]);

                $list_data = [$post];
                $validator = $this->model->validator($list_data);
                $social_can_post = json_decode($validator["can_post"]);
                if( !empty($social_can_post) || $validator["status"] == "success" ){
                    $result = $this->model->post($list_data, $social_can_post);
                    _ec( strtoupper( __( ucfirst($result['status']) ) ).": ".__( $result['message']) . "<br/>" , false);
                }
            }
        }else{
            _ec("Empty schedule");
            exit(0);
        }

    }
    /*
    * END RSS CRON
    */
}