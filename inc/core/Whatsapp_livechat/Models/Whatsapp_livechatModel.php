<?php

namespace Core\Whatsapp_livechat\Models;

use CodeIgniter\Model;

class Whatsapp_livechatModel extends Model
{
    public function __construct()
    {
        $this->config = parse_config(include realpath(__DIR__ . "/../Config.php"));
    }

    public function block_plans()
    {
        return [
            "tab" => 15,
            "position" => 700,
            "label" => __("Whatsapp tool"),
            "items" => [
                [
                    "id" => $this->config['id'],
                    "name" => $this->config['name'],
                ],
            ]
        ];
    }

    public function get_list($return_data = true)
    {
        $team_id = get_team("id");
        $current_page = (int)(post("current_page") - 1);
        $per_page = post("per_page");
        $total_items = post("total_items");
        $keyword = post("keyword");

        $db = \Config\Database::connect();
        $builder = $db->table(TB_ACCOUNTS);
        $builder->select('*');
        $builder->where("( team_id = '{$team_id}' AND social_network = 'whatsapp' AND login_type = 2 )");

        if ($keyword) {
            $builder->where("( name LIKE '%{$keyword}%' OR username LIKE '%{$keyword}%' )");
        }

        if (!$return_data) {
            $result =  $builder->countAllResults();
        } else {
            $builder->limit($per_page, $per_page * $current_page);
            $builder->orderBy("created", "DESC");
            $query = $builder->get();
            $result = $query->getResult();
            $query->freeResult();

            if (!empty($result)) {
                foreach ($result as $key => $value) {
                    $item = db_get("sum(unreadMessages) as unreadMessages, count(id) as subscribers", TB_WHATSAPP_SUBSCRIBERS, ["instance_id" => $value->token, ]);
                    $result[$key]->subscribers = (int)$item->subscribers;
                    $result[$key]->unreadMessages = (int)$item->unreadMessages;
                    //$result[$key]->run = (int)$item->run;
                    //$result[$key]->chatbot_status = (int)$item->chatbot_status;
                }
                return $result;
            }
        }


        return $result;
    }
}
