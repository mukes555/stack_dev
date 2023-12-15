<?php
namespace Core\Twitter_analytics\Controllers;

class Twitter_analytics extends \CodeIgniter\Controller
{
    public function __construct(){
        $this->config = parse_config( include realpath( __DIR__."/../Config.php" ) );
        $this->model = new \Core\Twitter_analytics\Models\Twitter_analyticsModel();
    }
    
    public function insights( $ids = false ) {
        try {
            $stats = $this->model->get_stats($ids);

            if($stats){
                $data = [
                    "status" => "success", 
                    "stats" => $stats
                ];
            }else{
                $data = [
                    "status" => "error", 
                    "message" => __("This Twitter account could not be analyzed. Please login and try again")
                ];
            }
        } catch (\Exception $e) {
            $data = [
                "status" => "error", 
                "message" => __("This Twitter account could not be analyzed. Please login and try again")
            ];
        }

        return view( 'Core\Twitter_analytics\Views\insights', $data );
    }

    public function cron()
    {
        $time = strtotime(date("Y-m-d"));
        $actions = db_fetch("*", TB_TWITTER_ANALYTICS, "next_action <= {$time}", "next_action", "ASC", 0, 5);
        if(!$actions){ 
            _e("Empty schedule");
            exit(0);
        }

        foreach ($actions as $action) {
            $this->model->save_stats($action->account, $action->team_id);
        }

        _e("Success");
    }
}