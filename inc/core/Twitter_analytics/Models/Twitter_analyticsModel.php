<?php
namespace Core\Twitter_analytics\Models;
use CodeIgniter\Model;

class Twitter_analyticsModel extends Model
{
	public function __construct(){
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/TwitterAPI.php');
        include get_module_dir( __DIR__ , 'Libraries/TwitterCookieApi.php');
    }

    public function block_plans(){
        return [
            "tab" => 20,
            "position" => 300,
            "label" => __("Analytics & report"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => $this->config['name'],
                ]
            ]
        ];
    }
    
    public function block_analytics(){
        $team_id = get_team("id");
        $data = [
            "config" => $this->config
        ];

        $accounts = db_fetch("*", TB_ACCOUNTS, [ "social_network" => "twitter", "category" => "profile", "login_type" => 3, "team_id" => $team_id], "created", "ASC");
        permission_accounts($accounts);

        return [
            "position" => 10000,
            "name" => $this->config['parent']['name'],
            "config" => $this->config,
            "menu" => view( 'Core\Twitter_analytics\Views\menu', $data ),
            "content" => view( 'Core\Twitter_analytics\Views\content', $data ),
            "accounts" => $accounts
        ];
    }

    public function get_stats($ids = ""){

        $team_id = get_team("id");
        $account = db_get("*", TB_ACCOUNTS, ["ids" => $ids, "team_id" => $team_id, "status" => 1]);
        if(!empty($account)){
            self::save_stats($account->pid, $account->login_type, $account->team_id);
        }

        $db = \Config\Database::connect();
        $builder = $db->table(TB_TWITTER_ANALYTICS_STATS." as a");
        $builder->select("a.*");
        $builder->join(TB_ACCOUNTS." as b", "a.account_id = b.pid");
        $builder->where("b.ids", $ids);
        $builder->where("b.social_network", "twitter");
        $builder->orderBy("a.date", "desc");
        $builder->limit(15, 0);
        $query = $builder->get();
        $result = $query->getResult();
        $query->freeResult();


        $builder = $db->table(TB_TWITTER_ANALYTICS." as a");
        $builder->select("a.*");
        $builder->join(TB_ACCOUNTS." as b", "a.account_id = b.pid");
        $builder->where("b.ids", $ids);
        $builder->where("b.social_network", "twitter");
        $query = $builder->get();
        $profile_info = $query->getRow();
        $query->freeResult();

        $result_tmp = array_reverse($result);   
        $list = [];
        $followers_tmp = -1;
        $following_tmp = -1;
        $posts_tmp = -1;
        
        $followers_value_string = "";
        $following_value_string = "";
        $engagement_value_string = "";
        $date_string = "";
        $count_date = 0;

        if(!empty($result_tmp)){
            foreach ($result_tmp as $key => $row) {
                //List summary
                $data = json_decode($row->data);
                
                $follower_count = $data->follower_count;
                $following_count = $data->following_count;
                $engagement = $data->engagement;
                $media_count = $data->media_count;

                $list[date_show($row->date)] = (object)[
                    "followers" => $follower_count,
                    "following" => $following_count,
                    "posts" => $media_count,
                    "followers_sumary" => ($followers_tmp == -1)?"":($follower_count-$followers_tmp),
                    "following_sumary" => ($following_tmp == -1)?"":($following_count-$following_tmp),
                    "posts_sumary" => ($posts_tmp == -1)?"":($media_count-$posts_tmp),
                    "date" => date_show($row->date)
                ];

                $followers_tmp = $follower_count;
                $following_tmp = $following_count;
                $posts_tmp = $media_count;

                //Followers chart
                $followers_value_string .= "{$follower_count},";

                //Followers chart
                $following_value_string .= "{$following_count},";

                //Followers chart
                $engagement_value_string .= "{$engagement},";

                //Date chart
                $date_string .= "'".date_show($row->date)."',";
            }

            //Cound Date
            $start_date = strtotime($result_tmp[0]->date);
            $end_date = strtotime($result_tmp[count($result_tmp) - 1]->date);
            $datediff = $end_date - $start_date;
            $count_date = round($datediff / (60 * 60 * 24));

            $followers_value_string = "[".substr($followers_value_string, 0, -1)."]";
            $following_value_string = "[".substr($following_value_string, 0, -1)."]";
            $engagement_value_string = "[".substr($engagement_value_string, 0, -1)."]";
            $date_string  = "[".substr($date_string, 0, -1)."]";

            //Following chart
            $result = (object)[
                "list_summary" => $list,
                "followers_chart" => $followers_value_string,
                "following_chart" => $following_value_string,
                "engagement_chart" => $engagement_value_string,
                "date_chart" => $date_string,
                "data" => isset($profile_info->data)?json_decode($profile_info->data):"",
                "total_days" => $count_date
            ];

            return $result;
        }

        return false;

        return (object)[
            "list_summary" => [],
            "followers_chart" => "",
            "following_chart" => "",
            "engagement_chart" => "",
            "date_chart" => "",
            "data" => (object)[
                "feeds" => [],
                "profile_info" => [

                ],
                "engagement" => [],
                "average_likes" => [],
                "average_comments" => [],
                "top_hashtags" => [],
                "top_mentions" => [],
                "average_comments" => [],
                "feeds" => [],
                "status" => "success"
            ],
            "total_days" => 0
        ];
    }

    public function save_stats($pid, $login_type, $team_id)
    {
        $date = strtotime(date("Y-m-d"));
        $check_stats = db_get("id", TB_TWITTER_ANALYTICS_STATS, ["account_id" => $pid, "team_id" => $team_id, "date" => $date]);
        if(!$check_stats)
        {
            $result = self::get_data($pid, $login_type, $team_id);
            if( $result['status'] == "success" ){
                $result = (object)$result;

                //Save data
                $user_data = [
                    "media_count" => $result->profile_info->statuses_count,
                    "follower_count" => $result->profile_info->followers_count,
                    "following_count" => $result->profile_info->friends_count,
                    "engagement" => $result->engagement
                ];

                $data = [
                    "ids" => ids(),
                    "team_id" => $team_id,
                    "account_id" => $pid,
                    "data" => json_encode($user_data),
                    "date" => $date
                ];

                db_insert(TB_TWITTER_ANALYTICS_STATS, $data);

                $save_info = [
                    "engagement" => $result->engagement,
                    "average_likes" => $result->average_likes,
                    "average_comments" => $result->average_comments,
                    "top_hashtags" => $result->top_hashtags,
                    "top_mentions" => $result->top_mentions,
                    "feeds" => $result->feeds,
                    "profile_info" => $result->profile_info,
                ];

                //Next Action
                $check_action = db_get("id", TB_TWITTER_ANALYTICS, ["team_id" => $team_id, "account_id" => $pid]);
                if(!$check_action)
                {
                    $next_action = strtotime(date("Y-m-d")) + (86400 * 1);
                    $data_next_action = [
                        "ids" => ids(),
                        "team_id" => $team_id,
                        "account_id" => $pid,
                        "data" => json_encode($save_info),
                        "next_action" => $next_action
                    ];

                    db_insert(TB_TWITTER_ANALYTICS, $data_next_action);
                }
                else
                {
                    $next_action = strtotime(date("Y-m-d")) + (86400 * 1);
                    $data_next_action = [
                        "data" => json_encode($save_info),
                        "next_action" => $next_action
                    ];
                    db_update(TB_TWITTER_ANALYTICS, $data_next_action, ["account_id" => $pid, "team_id" => $team_id]);
                }
            }
        }
    }

    public function get_data($pid, $login_type, $team_id){
        try {
            $account = db_get("*", TB_ACCOUNTS, ["social_network" => "twitter", "login_type" => $login_type, "pid" => $pid, "team_id" => $team_id]);

            if($account){
                if($account->tmp == ""){
                    return [
                        "status" => "error",
                        "message" => __( "You have not authorized your Twitter account yet. Please re-login and try again" )
                    ];
                }

                $accessToken = json_decode($account->tmp);
                if( !is_array($accessToken) && (!isset($accessToken->twitter_csrf_token) || !isset($accessToken->twitter_auth_token) || !isset($accessToken->twitter_session)) ){
                    return [
                        "status" => "error",
                        "message" => __( "You have not authorized your Twitter account yet. Please re-login and try again" )
                    ];
                }

                $proxy = get_proxy($account->proxy);

                $tw_auth = new \TwitterCookieApi($accessToken->twitter_csrf_token, $accessToken->twitter_auth_token, $accessToken->twitter_session, $proxy);
                $profile_info = $tw_auth->myInfo();

                //GET DATA
                $follower_count = (int)$profile_info->followers_count;

                //Get user medias
                $feeds = [];
                $media_count = 0;
                $total_likes = 0;
                $total_comments = 0;
                $average_likes = 0;
                $average_comments = 0;
                $hashtags_array = [];
                $mentions_array = [];

                $medias = $tw_auth->getTimeline($profile_info->id);

                if(!empty($medias)){
                    foreach ($medias as $key => $row) {

                        $total_likes += (int)$row->favorite_count;
                        $total_comments += (int)$row->retweet_count;
                        $engagement = (int)$row->favorite_count + (int)$row->retweet_count;

                        $rate = 0;
                        if($engagement != 0 && $follower_count != 0){
                            $rate = $engagement/$follower_count*100;
                        }

                        $feeds[] = [
                            'engagement' => $rate,
                            'media_id' => $row->id_str
                        ];
                        
                        if($row->full_text != ""){
                            $hashtags = get_hashtags($row->full_text);
                            foreach ($hashtags as $hashtag) {
                                if (!isset($hashtags_array[$hashtag])) {
                                    $hashtags_array[$hashtag] = 1;
                                } else {
                                    $hashtags_array[$hashtag]++;
                                }
                            }

                            $mentions = get_mentions($row->full_text);
                            foreach ($mentions as $mention) {
                                if (!isset($mentions_array[$mention])) {
                                    $mentions_array[$mention] = 1;
                                } else {
                                    $mentions_array[$mention]++;
                                }
                            }
                        }

                        $media_count++;

                        if ($key >= 10) break;

                    }

                    usort($feeds, function($a, $b) {
                        return $b['engagement'] - $a['engagement'];
                    });
                }

                $engagement = array_sum(array_column($feeds, 'engagement'));

                if ($engagement != 0 && !empty($feeds)) {
                    $engagement = number_format($engagement / sizeof($feeds), 2);
                }

                if ($total_comments != 0 && $media_count != 0) {
                    $average_comments = number_format($total_comments / $media_count, 2);
                }

                if ($total_likes != 0 && $media_count != 0) {
                    $average_likes = number_format($total_likes / $media_count, 2);
                }

                arsort($hashtags_array);
                arsort($mentions_array);
                $feeds = array_slice($feeds, 0, 3);
                $top_hashtags_array = array_slice($hashtags_array, 0, 15);
                $top_mentions_array = array_slice($mentions_array, 0, 15);
                return [
                    "feeds" => $feeds,
                    "profile_info" => $profile_info,
                    "engagement" => $engagement,
                    "average_likes" => $average_likes,
                    "average_comments" => $average_comments,
                    "top_hashtags" => $top_hashtags_array,
                    "top_mentions" => $top_mentions_array,
                    "average_comments" => $average_comments,
                    "feeds" => $feeds,
                    "status" => "success"
                ];
                //END GET DATA
            }   
        } catch (\Exception $e) {
            pr($e,1);
            return [
                "status" => "error",
                "message" => __( $e->getMessage() )
            ];
        }
    }
}
