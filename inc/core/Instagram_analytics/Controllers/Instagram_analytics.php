<?php
namespace Core\Instagram_analytics\Controllers;

class Instagram_analytics extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
        $app_id = get_option('facebook_client_id', '');
        $app_secret = get_option('facebook_client_secret', '');
        $app_version = get_option('facebook_app_version', 'v16.0');

        $fb = new \JanuSoftware\Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => $app_version,
        ]);

        $this->fb = $fb;
    }
    
    public function insights( $ids = false ) {
        $team_id = get_team("id");
        $user_timezone = get_user("timezone");
        $daterange = post("daterange");
        if( $daterange != "" ){
            $daterange = explode(",", $daterange);
        }else{
            $daterange = [];
        }

        $account = db_get("*", TB_ACCOUNTS, [ "social_network" => "instagram", "category" => "profile", "login_type" => 1, "ids" => $ids, "team_id" => $team_id]);

        if(empty($account)){
            $data = [
                "status" => "error",
                "message" => __("Accounts selected is inactive. Let re-login and try again"),
                "config" => $this->config
            ];
        }

        if(count($daterange) == 2 && !empty($account)){
            $date_since = urlencode($daterange[0]." 00:00:00");
            $date_until = urlencode($daterange[1]." 23:59:59");
        
            $total_posts = 0;
            $total_followers = 0;
            $total_follows = 0;
            $total_media = 0;

            $page_impressions = [];
            $total_page_impressions = 0;

            $page_reach = [];
            $total_page_reach = 0;

            $total_page_followers = 0;
            $page_followers = [];

            $total_page_profile_visits = 0;
            $page_profile_visits = [];

            $total_page_profile_visits = 0;
            $page_profile_visits = [];

            $top_countries = [];
            $top_cities = [];
            $top_languages = [];
            $fans_countries_map = [];
            $fans_online = [];

            $page_fans_gender_age = [
                "13-17" => [0,0],
                "18-24" => [0,0],
                "25-34" => [0,0],
                "35-44" => [0,0],
                "45-54" => [0,0],
                "55-64" => [0,0],
                "65+"   => [0,0]
            ];

            $reach_by_time = [];
            $impressions_by_time = [];
            $engagement_by_type = [];
            $reach_by_weekdays = [];
            $impressions_by_weekdays = [];
            $reach_by_type = [
                "Image" => 0,
                "Carousel" => 0,
                "Video" => 0,
                "Reel" => 0,
            ];

            $impressions_by_type = [
                "Image" => 0,
                "Carousel" => 0,
                "Video" => 0,
                "Reel" => 0,
            ];

            $engagement_by_type = [
                "Image" => 0,
                "Carousel" => 0,
                "Video" => 0,
                "Reel" => 0,
            ];

            try {
                $endpoint = "/".$account->pid."/insights/";
                $insights_by_day = $this->fb->get( $endpoint.'impressions,follower_count,website_clicks,text_message_clicks,reach,profile_views,phone_call_clicks,get_directions_clicks,email_contacts?since='.$date_since.'&until='.$date_until.'&period=day', $account->token)->getDecodedBody();
                $insights_by_day2 = $this->fb->get( $endpoint.'total_interactions,reach,accounts_engaged,follows_and_unfollows,likes,comments,profile_links_taps,website_clicks,profile_views?metric_type=total_value&since='.$date_since.'&until='.$date_until.'&period=day', $account->token)->getDecodedBody();
                $insights_by_lifetime = $this->fb->get( $endpoint.'audience_city,audience_country,audience_gender_age,audience_locale,online_followers?since='.$date_since.'&until='.$date_until.'&period=lifetime', $account->token)->getDecodedBody();
                $posts = $this->fb->get('/'.$account->pid.'/media?fields=id,ig_id,caption,media_url,media_type,video_url,comments_count,like_count,shortcode,timestamp,username,thumbnail_url,owner,permalink,insights.metric(engagement,impressions,reach,saved,video_views,comments,likes,shares,total_interactions,plays)&since='.$date_since.'&until='.$date_until, $account->token)->getDecodedBody();
                $page_info = $this->fb->get('/'.$account->pid.'?fields=id,name,username,profile_picture_url,ig_id,followers_count,follows_count,media_count', $account->token)->getDecodedBody();
                
                if( !empty($posts) ){
                    $posts = $posts['data'];
                }

                //pr($posts,1);

                 /*
                * PAGE INsIGHTS
                */
                if( !empty($insights_by_day) && 
                    isset($insights_by_day['data']) && 
                    !empty($insights_by_day['data'])
                ){
                    foreach ($insights_by_day['data'] as $value) {
                        /*
                        * PAGE REACH
                        */
                        if($value['name'] == "reach"){
                            $total_page_reach = array_sum(array_column($value['values'], 'value'));
                            $page_reach = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE IMPRESSIONS
                        */
                        if($value['name'] == "impressions"){
                            $total_page_impressions = array_sum(array_column($value['values'], 'value'));
                            $page_impressions = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE FOLLOWERS
                        */
                        if($value['name'] == "follower_count"){
                            $total_page_followers = array_sum(array_column($value['values'], 'value'));
                            $page_followers = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE PROFILE VISITS
                        */
                        if($value['name'] == "profile_views"){
                            $total_page_profile_visits = array_sum(array_column($value['values'], 'value'));
                            $page_profile_visits = FB_ANALYTICS_DATA($value['values']);
                        }

                    }
                }

                if( !empty($insights_by_lifetime) && 
                    isset($insights_by_lifetime['data']) && 
                    !empty($insights_by_lifetime['data'])
                ){
                    foreach ($insights_by_lifetime['data'] as $value) {
                        /*
                        * AUDIENCE GENDER AND AGE
                        */
                        if($value['name'] == "audience_gender_age"){
                            foreach ($value['values'] as $key_genders => $genders) {
                                if($key_genders == 0){
                                    foreach ($genders['value'] as $key => $gender) {
                                        $key_arr = explode(".", $key);
                                        if( count($key_arr) == 2 ){
                                            if( isset( $page_fans_gender_age[ $key_arr[1] ] )){
                                                if($key_arr[0] == "M"){
                                                    $page_fans_gender_age[ $key_arr[1] ][0] = $gender;
                                                }else{
                                                    $page_fans_gender_age[ $key_arr[1] ][1] = $gender;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $page_fans_gender_age = FB_ANALYTICS_FANS_GENDER_AGE($page_fans_gender_age);
                        }

                        /*
                        * AUDIENCE COUNTRY
                        */
                        if($value['name'] == "audience_country"){
                            $fans_countries = $value['values'][0]['value'];
                            arsort($fans_countries);

                            $top_countries  = array_slice($fans_countries, 0, 5, true);
                            $top_countries = IG_ANALYTICS_DATA_TYPE($top_countries, "country");
                            $fans_countries_map = IG_ANALYTICS_DATA_MAP($fans_countries);
                        }

                        /*
                        * AUDIENCE CITY
                        */
                        if($value['name'] == "audience_city"){
                            $fans_cities = $value['values'][0]['value'];
                            arsort($fans_cities);

                            $top_cities  = array_slice($fans_cities, 0, 5, true);
                            $top_cities = IG_ANALYTICS_DATA_TYPE($top_cities);
                        }

                        /*
                        * AUDIENCE LANGUAGE
                        */
                        if($value['name'] == "audience_locale"){
                            $fans_languages = $value['values'][0]['value'];
                            arsort($fans_languages);

                            $top_languages  = array_slice($fans_languages, 0, 5, true);
                            $top_languages = IG_ANALYTICS_DATA_TYPE($top_languages);
                        }

                        /*
                        * AUDIENCE ONLINE
                        */
                        if($value['name'] == "online_followers"){
                            $fans_online = IG_ANALYTICS_DATA_TYPE($value['values'][0]['value']);
                        }
                    }
                }

                if( !empty($posts) ){

                    foreach ($posts as $key => $post) {
                        date_default_timezone_set("UTC");
                        $timestamp = strtotime($post['timestamp']);

                        try {
                            date_default_timezone_set($user_timezone);
                            $created = date("Y-m-d H:i:s", $timestamp);
                        } catch (\Exception $e) {
                            $created = date("Y-m-d H:i:s", $timestamp);
                        }
                        
                        $type = $post['media_type'];
                        $hour = date("H", strtotime($created));
                        $weekdays = date("D", strtotime($created));
                        if( !empty( $post['insights']['data'] ) ){
                            foreach ($post['insights']['data'] as $value) {
                                $posts[$key]["reach_count"] = 0;
                                if($value['name'] == "reach"){
                                    $posts[$key]["reach_count"] = $value['values'][0]['value'];

                                    if( isset($reach_by_time[$hour]) ){
                                        $reach_by_time[$hour] += $value['values'][0]['value'];
                                    }else{
                                        $reach_by_time[$hour] = $value['values'][0]['value'];
                                    }

                                    if( isset($reach_by_weekdays[$weekdays]) ){
                                        $reach_by_weekdays[$weekdays] += $value['values'][0]['value'];
                                    }else{
                                        $reach_by_weekdays[$weekdays] = $value['values'][0]['value'];
                                    }

                                    switch ($type) {
                                        case 'CAROUSEL_ALBUM':
                                            $reach_by_type["Carousel"] += $value['values'][0]['value'];
                                            break;

                                        case 'REEL':
                                            $reach_by_type["Reel"] += $value['values'][0]['value'];
                                            break;

                                        case 'VIDEO':
                                            $reach_by_type["Video"] += $value['values'][0]['value'];
                                            break;
                                        
                                        case 'IMAGE':
                                            $reach_by_type["Image"] += $value['values'][0]['value'];
                                            break;
                                    }
                                }

                                $posts[$key]["impressions_count"] = 0;
                                if($value['name'] == "impressions"){
                                    $posts[$key]["impressions_count"] = $value['values'][0]['value'];

                                    if( isset($impressions_by_time[$hour]) ){
                                        $impressions_by_time[$hour] += $value['values'][0]['value'];
                                    }else{
                                        $impressions_by_time[$hour] = $value['values'][0]['value'];
                                    }

                                    if( isset($impressions_by_weekdays[$weekdays]) ){
                                        $impressions_by_weekdays[$weekdays] += $value['values'][0]['value'];
                                    }else{
                                        $impressions_by_weekdays[$weekdays] = $value['values'][0]['value'];
                                    }

                                    switch ($type) {
                                        case 'CAROUSEL_ALBUM':
                                            $impressions_by_type["Carousel"] += $value['values'][0]['value'];
                                            break;

                                        case 'REEL':
                                            $impressions_by_type["Reel"] += $value['values'][0]['value'];
                                            break;

                                        case 'VIDEO':
                                            $impressions_by_type["Video"] += $value['values'][0]['value'];
                                            break;
                                        
                                        case 'IMAGE':
                                            $impressions_by_type["Image"] += $value['values'][0]['value'];
                                            break;
                                    }
                                }

                                $posts[$key]["engagement_count"] = 0;
                                if($value['name'] == "engagement"){
                                    $posts[$key]["engagement_count"] = $value['values'][0]['value'];

                                    switch ($type) {
                                        case 'CAROUSEL_ALBUM':
                                            $engagement_by_type["Carousel"] += $value['values'][0]['value'];
                                            break;

                                        case 'REEL':
                                            $engagement_by_type["Reel"] += $value['values'][0]['value'];
                                            break;

                                        case 'VIDEO':
                                            $engagement_by_type["Video"] += $value['values'][0]['value'];
                                            break;
                                        
                                        case 'IMAGE':
                                            $engagement_by_type["Image"] += $value['values'][0]['value'];
                                            break;
                                    }
                                }

                                $posts[$key]["saved_count"] = 0;
                                if($value['name'] == "saved"){
                                    $posts[$key]["saved_count"] = $value['values'][0]['value'];
                                }

                                $posts[$key]["plays_count"] = "-";
                                if($value['name'] == "plays"){
                                    $posts[$key]["plays_count"] = $value['values'][0]['value'];
                                }
                            }

                        }

                    }

                }

                $reach_by_time = IG_ANALYTICS_BY_HOUR($reach_by_time);
                $reach_by_weekdays = IG_ANALYTICS_BY_WEEKDAYS($reach_by_weekdays);
                $impressions_by_time = IG_ANALYTICS_BY_HOUR($impressions_by_time);
                $impressions_by_weekdays = IG_ANALYTICS_BY_WEEKDAYS($impressions_by_weekdays);
                $reach_by_type = IG_ANALYTICS_BY_TYPE($reach_by_type);
                $impressions_by_type = IG_ANALYTICS_BY_TYPE($impressions_by_type);
                $engagement_by_type = IG_ANALYTICS_BY_TYPE($engagement_by_type);

                /*
                * TOP POSTS
                */
                $top_posts = $posts;
                usort($top_posts, fn($a, $b) => $a['engagement_count'] <=> $b['engagement_count']);
                $top_posts = array_reverse($top_posts);
                $top_posts = array_slice($top_posts, 0, 5);

                /*
                * RECENT POSTS
                */
                $recent_posts = array_slice($posts, 0, 5);

                /*
                * TOTAL FANS & FOLLOWERS
                */
                if( !empty($page_info) && isset($page_info['followers_count']) ){
                    $total_follows = $page_info['follows_count'];
                    $total_followers = $page_info['followers_count'];
                    $total_media = $page_info['media_count'];
                }

                $data = [
                    "total_followers" => $total_followers,
                    "total_media" => $total_media,
                    "total_follows" => $total_follows,
                    "page_impressions" => $page_impressions,
                    "total_page_impressions" => $total_page_impressions,
                    "page_reach" => $page_reach,
                    "total_page_reach" => $total_page_reach,
                    "page_followers" => $page_followers,
                    "total_page_followers" => $total_page_followers,
                    "page_profile_visits" => $page_profile_visits,
                    "total_page_profile_visits" => $total_page_profile_visits,
                    "top_countries" => $top_countries,
                    "top_cities" => $top_cities,
                    "top_languages" => $top_languages,
                    "fans_countries_map" => $fans_countries_map,
                    "fans_online" => $fans_online,
                    "page_fans_gender_age" => $page_fans_gender_age,
                    "reach_by_time" => $reach_by_time,
                    "reach_by_weekdays" => $reach_by_weekdays,
                    "reach_by_type" => $reach_by_type,
                    "impressions_by_time" => $impressions_by_time,
                    "impressions_by_weekdays" => $impressions_by_weekdays,
                    "impressions_by_type" => $impressions_by_type,
                    "engagement_by_type" => $engagement_by_type,
                    "top_posts" => $top_posts,
                    "recent_posts" => $recent_posts,
                    "posts" => $posts,
                    "page_info" => $page_info,
                    "status" => "success",
                ];

            } catch (\Exception $e) {
                $data = [
                    "status" => "error",
                    "message" => __($e->getMessage()),
                    "config" => $this->config
                ];
            }
        }else{
            $data = [
                "status" => "error",
                "message" => __("No data found"),
                "config" => $this->config
            ];
        }

        return view( 'Core\Instagram_analytics\Views\insights', $data );
    }
}