<?php
namespace Core\Payments\Models;
use CodeIgniter\Model;

class PaymentsModel extends Model
{
    public function get_list( $return_data = true )
    {
        $current_page = (int)(post("current_page") - 1);
        $per_page = post("per_page");
        $total_items = post("total_items");
        $keyword = post("keyword");

        $db = \Config\Database::connect();
        $builder = $db->table(TB_PAYMENT_HISTORY." as a");
        $builder->join(TB_PLANS." as b", "a.plan = b.id", "LEFT");
        $builder->join(TB_USERS." as c", "a.uid = c.id", "LEFT");
        $builder->select('a.*,b.name as plan_name,c.username,c.fullname,c.email,c.avatar');

        if( $keyword ){
            $array = [
                'c.username' => $keyword, 
                'c.fullname' => $keyword, 
                'c.email' => $keyword,
                'b.name' => $keyword,
                'a.transaction_id' => $keyword,
                'a.type' => $keyword,
            ];
            $builder->orLike($array);
        }
        
        if( !$return_data )
        {
            $result =  $builder->countAllResults();
        }
        else
        {
            $builder->limit($per_page, $per_page*$current_page);
            $query = $builder->get();
            $result = $query->getResult();
            $query->freeResult();
        }
        
        return $result;
    }

    public function get_report(){
        //Recrent registers
        $recently_payments = false;
        $db = \Config\Database::connect();
        $builder = $db->table(TB_PAYMENT_HISTORY." as a");
        $builder->select("a.*, c.fullname, c.email, b.name, c.avatar");
        $builder->join(TB_PLANS." as b", "a.plan = b.id");
        $builder->join(TB_USERS." as c", "a.uid = c.id");
        $builder->orderBy("a.id", "DESC");
        $builder->limit(10, 0);
        $query = $builder->get();
        if($query->getResult()){
            $recently_payments = $query->getResult();
        }

        //Stats by date
        $today = db_get("count(*) as count", TB_PAYMENT_HISTORY, " created > ". (time() - 86400))->count;
        $week = db_get("count(*) as count", TB_PAYMENT_HISTORY, " created > ". (time() - 86400*7))->count;
        $month = db_get("count(*) as count", TB_PAYMENT_HISTORY, " created > ". (time() - 86400*30))->count;
        $year = db_get("count(*) as count", TB_PAYMENT_HISTORY, " created > ". (time() - 86400*365))->count;

        $count_by_day = [
            "today" => $today,
            "week" => $week,
            "month" => $month,
            "year" => $year
        ];

        //Total by date
        $today = db_get("SUM(amount) as total", TB_PAYMENT_HISTORY, " created > ". (time() - 86400))->total;
        $week = db_get("SUM(amount) as total", TB_PAYMENT_HISTORY, " created > ". (time() - 86400*7))->total;
        $month = db_get("SUM(amount) as total", TB_PAYMENT_HISTORY, " created > ". (time() - 86400*30))->total;
        $year = db_get("SUM(amount) as total", TB_PAYMENT_HISTORY, " created > ". (time() - 86400*365))->total;

        $total_by_day = [
            "today" => (float)$today,
            "week" => (float)$week,
            "month" => (float)$month,
            "year" => (float)$year
        ];

        //Chart
        $value_string = "";
        $date_string = "";

        $date_list = array();
        $date = strtotime(date('Y-m-d', strtotime( now() )));
        for ($i=29; $i >= 0; $i--) { 
            $left_date = $date - 86400 * $i;
            $date_list[date('M j, Y', $left_date)] = 0;
        }

        $query = $db->query("SELECT COUNT(*) as count, DATE(created) as created FROM ".TB_PAYMENT_HISTORY." WHERE created > ". (time() - 86400*30). " GROUP BY DATE(created);");
        if($query->getResult()){
            foreach ($query->getResult() as $key => $value) {
                if(isset($date_list[$value->created])){
                    $date_list[$value->created] = $value->count;
                }
            }
        }

        foreach ($date_list as $date => $value) {
            $value_string .= "{$value},";
            $date_string .= "'{$date}',";
        }

        $value_string = "[".substr($value_string, 0, -1)."]";
        $date_string  = "[".substr($date_string, 0, -1)."]";

        $chart = [
            "value" => $value_string,
            "date" => $date_string
        ];

        return (object)[
            "count_by_day" => (object)$count_by_day,
            "total_by_day" => (object)$total_by_day,
            "recently_payments" => $recently_payments,
            "chart" => (object)$chart,
        ];
    }
}
