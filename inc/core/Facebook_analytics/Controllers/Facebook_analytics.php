<?php
namespace Core\Facebook_analytics\Controllers;

class Facebook_analytics extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
        $this->model = new \Core\Facebook_analytics\Models\Facebook_analyticsModel();

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
        $daterange = post("daterange");
        if( $daterange != "" ){
            $daterange = explode(",", $daterange);
        }else{
            $daterange = [];
        }

        $account = db_get("*", TB_ACCOUNTS, [ "social_network" => "facebook", "category" => "page", "login_type" => 1, "ids" => $ids, "team_id" => $team_id]);

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
            $total_followers = 0;
            $total_fans = 0;
            $total_posts = 0;

            $average_page_reach = 0;
            $total_page_reach = 0;
            $page_reach = [];

            $total_page_reach_paid = 0;
            $page_reach_paid = [];

            $total_page_reach_organic = 0;
            $page_reach_organic = [];
            
            $average_page_impressions = 0;
            $total_page_impressions = 0;
            $page_impressions = [];

            $total_page_impressions_paid = 0;
            $page_impressions_paid = [];

            $total_page_impressions_organic = 0;
            $page_impressions_organic = [];

            $engagement_comments = [];
            $engagement_shares = [];
            $engagement_reactions = [];
            $engagement_link_clicks = [];
            $engagement_other_clicks = [];
            $total_all_engagements = 0;
            $total_engagement_comments = 0;
            $total_engagement_shares = 0;
            $total_engagement_reactions = 0;
            $total_engagement_link_clicks = 0;
            $total_engagement_other_clicks = 0;

            $video_organic_full = 0;
            $video_organic_partial = 0;
            $video_paid_partial = 0;
            $video_autoplayed = 0;
            $video_click_to_play = 0;

            $fans_history = [];
            $page_views = [];
            $total_engagements = 0;
            $engagements = [];
            $total_page_engaged_users = 0;
            $page_engaged_users = [];
            $total_page_views = 0;
            $lost_fans = [];
            $fans_online = [];
            $gained_fans = [];
            $engagement_rate = [];
            $average_fans_online = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
            $average_fans_online = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
            $total_days = 0;
            $new_fans_count = 0;
            $lost_fans_count = 0;
            $net_fans_count = 0;
            $fans_countries = [];
            $top_countries = [];
            $fans_cities= [];
            $top_cities = [];
            $fans_languages = [];
            $top_languages = [];
            $fans_countries_map = [];
            $fans_gender_age = [
                "13-17" => [0,0],
                "18-24" => [0,0],
                "25-34" => [0,0],
                "35-44" => [0,0],
                "45-54" => [0,0],
                "55-64" => [0,0],
                "65+"   => [0,0]
            ];

            try {
                $endpoint = "/".$account->pid."/insights/";
                $insights = $this->fb->get( $endpoint.'page_impressions,page_impressions_paid,page_impressions_organic_v2,page_impressions_unique,page_impressions_paid_unique,page_impressions_organic_unique_v2,page_positive_feedback_by_type,page_actions_post_reactions_total,page_consumptions_by_consumption_type,page_post_engagements,page_engaged_users,page_views_total,page_fan_adds_unique,page_fan_removes_unique,page_fans,page_fans_online,page_fans_gender_age,page_fans_country,page_fans_city,page_fans_locale,page_video_complete_views_30s_organic,page_video_views_organic,page_video_views_paid,page_video_views_autoplayed,page_video_views_click_to_play?since='.$date_since.'&until='.$date_until.'&period=day', $account->token)->getDecodedBody();
                $page_info = $this->fb->get('/'.$account->pid.'?fields=name,username,picture.type(large),cover,id,category,talking_about_count,fan_count,followers_count,rating_count', $account->token)->getDecodedBody();
                $posts = $this->fb->get('/'.$account->pid.'/published_posts?fields=message,created_time,full_picture,attachments,permalink_url,likes.summary(true),comments.summary(true),shares.summary(true),reactions.summary(true),links.summary(true),insights.metric(post_impressions,post_impressions_unique,post_impressions_paid,post_impressions_organic,post_impressions_paid_unique,post_impressions_organic_unique,post_clicks,post_clicks_unique,post_engaged_users)&summary=total_count&since='.$date_since.'&until='.$date_until, $account->token)->getDecodedBody();
                
                /*
                * TOTAL POSTS
                */
                if( !empty($posts) && isset($posts['summary']) && isset($posts['summary']['total_count']) ){
                    $total_posts = $posts['summary']['total_count'];
                }

                if( !empty($posts) ){
                    $posts = $posts['data'];
                }

                /*
                * PAGE INsIGHTS
                */
                if( !empty($insights) && 
                    isset($insights['data']) && 
                    !empty($insights['data'])
                ){
                    foreach ($insights['data'] as $value) {
                        if($value['name'] == "page_fans"){
                            $fans_history = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE IMPRESSIONS
                        */
                        if($value['name'] == "page_impressions"){
                            $total_page_impressions = array_sum(array_column($value['values'], 'value'));
                            $average_page_impressions = round( $total_page_impressions/count($value['values']), 2);
                            $page_impressions = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE IMPRESSIONS PAID
                        */
                        if($value['name'] == "page_impressions_paid"){
                            $total_page_impressions_paid = array_sum(array_column($value['values'], 'value'));
                            $page_impressions_paid = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE IMPRESSIONS ORGANIC
                        */
                        if($value['name'] == "page_impressions_organic"){
                            $total_page_impressions_organic = array_sum(array_column($value['values'], 'value'));
                            $page_impressions_organic = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE REACH
                        */
                        if($value['name'] == "page_impressions_unique"){
                            $total_page_reach = array_sum(array_column($value['values'], 'value'));
                            $average_page_reach = round( $total_page_reach/count($value['values']), 2);
                            $page_reach = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE REACH PAID
                        */
                        if($value['name'] == "page_impressions_paid_unique"){
                            $total_page_reach_paid = array_sum(array_column($value['values'], 'value'));
                            $page_reach_paid = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE REACH ORGANIC
                        */
                        if($value['name'] == "page_impressions_organic_unique"){
                            $total_page_reach_organic = array_sum(array_column($value['values'], 'value'));
                            $page_reach_organic = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * PAGE ENGAGEMENT (COMMENTS, SHARES)
                        */
                        if($value['name'] == "page_positive_feedback_by_type"){
                            $engagement_comments = FB_ANALYTICS_DATA_SUB($value['values'], "comment");
                            $engagement_shares = FB_ANALYTICS_DATA_SUB($value['values'], "link");

                            $total_engagement_comments = $engagement_comments['count'];
                            $total_engagement_shares = $engagement_shares['count'];
                        }

                        /*
                        * PAGE ENGAGEMENT (LINK CLICKS, OTHER CLICKS)
                        */
                        if($value['name'] == "page_consumptions_by_consumption_type"){
                            $engagement_other_clicks = FB_ANALYTICS_DATA_SUB($value['values'], "other clicks");
                            $engagement_link_clicks = FB_ANALYTICS_DATA_SUB($value['values'], "link clicks");

                            $total_engagement_other_clicks = $engagement_other_clicks['count'];
                            $total_engagement_link_clicks = $engagement_link_clicks['count'];
                        }

                        /*
                        * PAGE ENGAGEMENT (REACTIONS)
                        */
                        if($value['name'] == "page_actions_post_reactions_total"){
                            $engagement_reactions = FB_ANALYTICS_DATA_SUB_PLUS($value['values']);
                            $total_engagement_reactions = $engagement_reactions['count'];
                        }

                        /*
                        * PAGE ENGAGED USERS
                        */
                        if($value['name'] == "page_engaged_users"){
                            $total_page_engaged_users = array_sum(array_column($value['values'], 'value'));
                            $page_engaged_users = $value['values'];
                        }

                        /*
                        * ENGAGEMENTS
                        */
                        if($value['name'] == "page_post_engagements"){
                            $total_engagements = array_sum(array_column($value['values'], 'value'));
                            $engagements = $value['values'];
                        }

                        /*
                        * PAGE VIEWS
                        */
                        if($value['name'] == "page_views_total"){
                            $total_page_views = array_sum(array_column($value['values'], 'value'));
                            $page_views = FB_ANALYTICS_DATA($value['values']);
                        }

                        /*
                        * GAINED FANS
                        */
                        if($value['name'] == "page_fan_adds_unique"){
                            $gained_fans = $value['values'];
                            $new_fans_count = array_sum(array_column($value['values'], 'value'));
                        }

                        /*
                        * LOST FANS
                        */
                        if($value['name'] == "page_fan_removes_unique"){
                            $lost_fans = $value['values'];
                            if(!empty($lost_fans)){
                                foreach ($lost_fans as $k => $lost_fan) {
                                    $lost_fans[$k]['value'] = $lost_fan['value'] * -1;
                                }
                            }
                            $lost_fans_count = array_sum(array_column($value['values'], 'value'));
                        }

                        /*
                        * FANS ONLINE
                        */
                        if($value['name'] == "page_fans_online"){
                            $fans_online = FB_ANALYTICS_DATA_TYPE($value['values'][0]['value']);

                            foreach ($value['values'] as $hours) {
                                $total_days = $total_days + 1;
                                foreach ($hours['value'] as $key => $hour) {
                                    $average_fans_online[$key] += $hour;
                                }
                            }

                            foreach ($average_fans_online as $key => $hour) {
                                $average_fans_online[$key] = round($hour/$total_days);
                            }

                            $average_fans_online = FB_ANALYTICS_DATA_TYPE($average_fans_online);
                        }

                        /*
                        * FANS GENDER AND AGE
                        */
                        if($value['name'] == "page_fans_gender_age"){
                            foreach ($value['values'] as $key_genders => $genders) {
                                if($key_genders == 0){
                                    foreach ($genders['value'] as $key => $gender) {
                                        $key_arr = explode(".", $key);
                                        if( count($key_arr) == 2 ){
                                            if( isset( $fans_gender_age[ $key_arr[1] ] )){
                                                if($key_arr[0] == "M"){
                                                    $fans_gender_age[ $key_arr[1] ][0] = $gender;
                                                }else{
                                                    $fans_gender_age[ $key_arr[1] ][1] = $gender;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $fans_gender_age = FB_ANALYTICS_FANS_GENDER_AGE($fans_gender_age);
                        }

                        /*
                        * FANS COUNTRY
                        */
                        if($value['name'] == "page_fans_country"){
                            $fans_countries = $value['values'][0]['value'];
                            arsort($fans_countries);

                            $top_countries  = array_slice($fans_countries, 0, 5, true);
                            $top_countries = FB_ANALYTICS_DATA_TYPE($top_countries, "country");
                            $fans_countries_map = FB_ANALYTICS_DATA_MAP($fans_countries);
                        }

                        /*
                        * FANS CITY
                        */
                        if($value['name'] == "page_fans_city"){
                            $fans_cities = $value['values'][0]['value'];
                            arsort($fans_cities);

                            $top_cities  = array_slice($fans_cities, 0, 5, true);
                            $top_cities = FB_ANALYTICS_DATA_TYPE($top_cities);
                        }

                        /*
                        * FANS LANGUAGE
                        */
                        if($value['name'] == "page_fans_locale"){
                            $fans_languages = $value['values'][0]['value'];
                            arsort($fans_languages);

                            $top_languages  = array_slice($fans_languages, 0, 5, true);
                            $top_languages = FB_ANALYTICS_DATA_TYPE($top_languages);
                        }

                        /*
                        * VIDEO 
                        */
                        if($value['name'] == "page_video_complete_views_30s_organic"){
                            $video_organic_full = array_sum(array_column($value['values'], 'value'));
                        }

                        if($value['name'] == "page_video_views_organic"){
                            $video_organic_partial = array_sum(array_column($value['values'], 'value'));
                        }

                        if($value['name'] == "page_video_views_paid"){
                            $video_paid_partial = array_sum(array_column($value['values'], 'value'));
                        }

                        if($value['name'] == "page_video_views_autoplayed"){
                            $video_autoplayed = array_sum(array_column($value['values'], 'value'));
                        }

                        if($value['name'] == "page_video_views_click_to_play"){
                            $video_click_to_play = array_sum(array_column($value['values'], 'value'));
                        }
                    }
                    
                }

                /*
                * TOTAL FANS & FOLLOWERS
                */
                if( !empty($page_info) && isset($page_info['fan_count']) ){
                    $total_fans = $page_info['fan_count'];
                    $total_followers = $page_info['followers_count'];
                }

                /*
                * GAINED AND LOST FANS
                */
                $net_fans_count = $new_fans_count - $lost_fans_count;
                $gained_and_lost_fans = FB_ANALYTICS_GAINED_AND_LOST_FANS($gained_fans, $lost_fans);

                /*
                * ENGAGEMENTS
                */
                $total_all_engagements = $total_engagement_reactions + $total_engagement_comments + $total_engagement_shares + $total_engagement_link_clicks + $total_engagement_other_clicks;

                /*
                * ENGAGEMENT RATE
                */
                if(
                    !empty($engagement_reactions) &&
                    !empty($engagement_comments) &&
                    !empty($engagement_shares) &&
                    !empty($engagement_link_clicks) &&
                    !empty($engagement_other_clicks)
                ){
                    $engagement_rate = FB_ANALYTICS_ENGAGEMENT_RATE( $page_impressions, [
                        $engagement_reactions['data'],
                        $engagement_comments['data'],
                        $engagement_shares['data'],
                        $engagement_link_clicks['data'],
                        $engagement_other_clicks['data'],
                    ] );
                }

                /*
                * POST INSIGHTS
                */
                if(!empty($posts)){
                    foreach ($posts as $key => $post) {
                        $engagement_count = 0;
                        $posts[$key]['like_count'] = 0;
                        if( isset( $post['likes']) ){
                            $posts[$key]['like_count'] = $post['likes']['summary']['total_count'];
                        }
                        
                        $posts[$key]['comment_count'] = 0;
                        if( isset( $post['comments']) ){
                            $posts[$key]['comment_count'] = $post['comments']['summary']['total_count'];
                            $engagement_count += $post['comments']['summary']['total_count'];
                        }

                        $posts[$key]['reaction_count'] = 0;
                        if( isset( $post['reactions']) ){
                            $posts[$key]['reaction_count'] = $post['reactions']['summary']['total_count'];
                            $engagement_count += $post['reactions']['summary']['total_count'];
                        }

                        $posts[$key]['share_count'] = 0;
                        if( isset( $post['shares']) ){
                            $posts[$key]['share_count'] = $post['shares']['count'];
                            $engagement_count +=  $post['shares']['count'];
                        }

                        $posts[$key]['impressions'] = 0;
                        $posts[$key]['impressions_paid'] = 0;
                        $posts[$key]['impressions_organic'] = 0;
                        $posts[$key]['reach'] = 0;
                        $posts[$key]['reach_paid'] = 0;
                        $posts[$key]['reach_organic'] = 0;
                        $posts[$key]['click_count'] = 0;
                        $posts[$key]['engaged_users'] = 0;

                        if( isset( $post['insights']) ){
                            foreach ($post['insights']['data'] as $value) {
                                /*
                                * POST IMPRESSIONS
                                */
                                if($value['name'] == "post_impressions"){
                                    $posts[$key]['impressions'] = $value['values'][0]['value'];
                                }

                                /*
                                * POST IMPRESSIONS PAID
                                */
                                if($value['name'] == "post_impressions_paid"){
                                    $posts[$key]['impressions_paid'] = $value['values'][0]['value'];
                                }

                                /*
                                * POST IMPRESSIONS ORGANIC
                                */
                                if($value['name'] == "post_impressions_organic"){
                                    $posts[$key]['impressions_organic'] = $value['values'][0]['value'];
                                }

                                /*
                                * POST REACH
                                */
                                if($value['name'] == "post_impressions_unique"){
                                    $posts[$key]['reach'] = $value['values'][0]['value'];
                                }

                                /*
                                * POST REACH PAID
                                */
                                
                                if($value['name'] == "post_impressions_paid_unique"){
                                    $posts[$key]['reach_paid'] = $value['values'][0]['value'];
                                }

                                /*
                                * POST REACH ORGANIC
                                */
                                if($value['name'] == "post_impressions_organic_unique"){
                                    $posts[$key]['reach_organic'] = $value['values'][0]['value'];
                                }

                                /*
                                * POST CLICKS
                                */
                                if($value['name'] == "post_clicks"){
                                    $posts[$key]['click_count'] = $value['values'][0]['value'];
                                    $engagement_count +=  $value['values'][0]['value'];
                                }

                                /*
                                * POST ENGAGED USERS
                                */
                                $posts[$key]['engaged_users'] = 0;
                                if($value['name'] == "post_engaged_users"){
                                    $posts[$key]['engaged_users'] = $value['values'][0]['value'];
                                }
                            }
                        }

                        $posts[$key]['engagement_count'] = $engagement_count;
                    }
                }

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

                $data = [
                    "total_fans" => short_number($total_fans),
                    "total_followers" => short_number($total_followers),
                    "total_posts" => short_number($total_posts),
                    "total_engagements" => short_number($total_engagements),
                    "total_page_views" => short_number($total_page_views),
                    "new_fans_count" => short_number($new_fans_count),
                    "lost_fans_count" => short_number($lost_fans_count),
                    "net_fans_count" => short_number($net_fans_count),
                    "page_info" => $page_info,
                    "fans_online" => $fans_online,
                    "fans_history" => $fans_history,
                    "page_views" => $page_views,
                    "gained_and_lost_fans" => $gained_and_lost_fans,
                    "fans_gender_age" => $fans_gender_age,
                    "top_countries" => $top_countries,
                    "top_cities" => $top_cities,
                    "top_languages" => $top_languages,
                    "fans_countries" => $fans_countries,
                    "fans_cities" => $fans_cities,
                    "fans_languages" => $fans_languages,
                    "fans_countries_map" => $fans_countries_map,

                    "page_impressions_paid" => $page_impressions_paid,
                    "page_impressions_organic" => $page_impressions_organic,
                    "total_page_impressions_paid" => short_number($total_page_impressions_paid),
                    "total_page_impressions_organic" => short_number($total_page_impressions_organic),
                    
                    "page_impressions" => $page_impressions,
                    "total_page_impressions" => short_number($total_page_impressions),
                    "average_page_impressions" => $average_page_impressions,

                    "page_reach_paid" => $page_reach_paid,
                    "page_reach_organic" => $page_reach_organic,
                    "total_page_reach_paid" => short_number($total_page_reach_paid),
                    "total_page_reach_organic" => short_number($total_page_reach_organic),

                    "page_reach" => $page_reach,
                    "total_page_reach" => short_number($total_page_reach),
                    "average_page_reach" => $average_page_reach,

                    "engagement_rate" => $engagement_rate,
                    "engagement_reactions" => $engagement_reactions,
                    "engagement_comments" => $engagement_comments,
                    "engagement_shares" => $engagement_shares,
                    "engagement_link_clicks" => $engagement_link_clicks,
                    "engagement_other_clicks" => $engagement_other_clicks,

                    "total_all_engagements" => short_number($total_all_engagements),
                    "total_engagement_reactions" => short_number($total_engagement_reactions),
                    "total_engagement_comments" => short_number($total_engagement_comments),
                    "total_engagement_shares" => short_number($total_engagement_shares),
                    "total_engagement_link_clicks" => short_number($total_engagement_link_clicks),
                    "total_engagement_other_clicks" => short_number($total_engagement_other_clicks),

                    "top_posts" => $top_posts,
                    "recent_posts" => $recent_posts,
                    "posts" => $posts,

                    "video_organic_full" => $video_organic_full,
                    "video_organic_partial" => $video_organic_partial,
                    "video_view_organic" => ($video_paid_partial > 0 || $video_organic_partial > 0)?round($video_organic_partial/($video_paid_partial+$video_organic_partial)*100, 2):0,
                    "video_view_paid" => ($video_paid_partial > 0 || $video_organic_partial > 0)?round($video_paid_partial/($video_paid_partial+$video_organic_partial)*100, 2):0,
                    "video_autoplayed" => ($video_autoplayed > 0 || $video_click_to_play > 0)?round($video_autoplayed/($video_autoplayed+$video_click_to_play)*100, 2):0,
                    "video_click_to_play" => ($video_autoplayed > 0 || $video_click_to_play > 0)?round($video_click_to_play/($video_autoplayed+$video_click_to_play)*100, 2):0,

                    "config" => $this->config,
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

        return view( 'Core\Facebook_analytics\Views\insights', $data );
    }
}