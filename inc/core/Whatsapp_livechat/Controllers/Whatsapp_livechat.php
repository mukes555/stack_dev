<?php

namespace Core\Whatsapp_livechat\Controllers;

class Whatsapp_livechat extends \CodeIgniter\Controller
{
    public function __construct()
    {
        include get_module_dir(__DIR__, 'Libraries/vendor/autoload.php');

        $this->config = parse_config(include realpath(__DIR__ . "/../Config.php"));
        $this->model = new \Core\Whatsapp_livechat\Models\Whatsapp_livechatModel();
    }



    public function index($page = false, $ids = false)
    {
        if (!permission("whatsapp_livechat")) {
            redirect_to(base_url());
        }

        $data = [
            "title" => $this->config['name'],
            "desc" => $this->config['desc'],
            'config' => $this->config
        ];

        //echo 'page:' . $page;

        switch ($page) {
            case 'chats':
                $team_id = get_team("id");

                $account = db_get('*', TB_ACCOUNTS, ['token' => $ids]);
                $phone = $account->username;

                $items = db_fetch('*', TB_WHATSAPP_SUBSCRIBERS, "instance_id = '$ids' AND chatid NOT LIKE '%g.us' AND chatid NOT LIKE '%$phone%'", "lastMessageTime");

                $cards = db_fetch('*', 'sp_whatsapp_funnels', "instance_id = '$ids' and team_id = '$team_id'", "order, id", "ASC");

                $data['content'] = view('Core\Whatsapp_livechat\Views\chats', ["items" => $items, 'cards' => $cards, "account" => $account]);

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

                $data['content'] = view('Core\Whatsapp_chatbot\Views\content', $data_content);
                break;
        }

        return view('Core\Whatsapp_livechat\Views\index', $data);
    }

    public function ajax_list()
    {
        $total_items = $this->model->get_list(false);
        $result = $this->model->get_list(true);


        $data = [
            "result" => $result,
            "config" => $this->config,
        ];
        ms([
            "total_items" => $total_items,
            "data" => view('Core\Whatsapp_livechat\Views\ajax_list', $data)
        ]);
    }

    public function create_card($instance_id)
    {
        $team_id = get_team("id");
        $name = post("name");
        $desc = post("desc");
        $order = post("order");
        $color = post("color");

        validate('null', __('name'), $name);
        validate('null', __('desc'), $desc);

        $data = [
            "team_id" => $team_id,
            "instance_id" => $instance_id,
            "name" => $name,
            "desc" => $desc,
            "order" => $order,
            "color" => $color,

        ];

        $result = db_insert('sp_whatsapp_funnels', $data);

        ms([
            "status" => "success",
            "message" => __("Success")
        ]);
    }

    public function edit_card_modal()
    {
        $id = post("id");
        $token = post("token");

        $account = db_get('*', TB_ACCOUNTS, ['token' => $token]);
        $card = db_get('*', 'sp_whatsapp_funnels', ['id' => $id]);

        $data = [
            'id' => $id,
            'account' => $account,
            'card' => $card
        ];
        return view('Core\Whatsapp_livechat\Views\edit_card_modal', $data);
    }

    public function chat_modal()
    {
        $team_id = get_team("id");
        $chatid = post("chatid");
        $token = post("token");

        $subscriber = db_get('*', TB_WHATSAPP_SUBSCRIBERS, ['id' => $chatid,  'instance_id' => $token, 'team_id' => $team_id]);
        if (isset($subscriber)) {

            $messages = db_fetch('*', 'sp_whatsapp_messages', ['remoteJid' => $subscriber->chatid, 'instance_id' => $token], 'createdAt', 'DESC', 0, 40, true);

            $data = [
                'subscriber' => $subscriber,
                'messages' => $messages,
                'chatid' => $chatid,
                'token' => $token
            ];

            return view('Core\Whatsapp_livechat\Views\chat_modal', $data);
        } else {
            $data = [
                'subscriber' => $subscriber,
                'messages' => [],
                'chatid' => $chatid,
                'token' => $token
            ];
            return view('Core\Whatsapp_livechat\Views\chat_modal', $data);
        }
    }

    public function load_more()
    {
        $team_id = get_team("id");
        $chatid = post("chatid");
        $token = post("token");
        $page = post("page");
        $message_id = post("message_id") ?? '';

        $subscriber = db_get('*', TB_WHATSAPP_SUBSCRIBERS, ['id' => $chatid,  'instance_id' => $token, 'team_id' => $team_id]);

        if (isset($subscriber)) {
            if ($message_id != '') {
                $messages = db_fetch('*', 'sp_whatsapp_messages', ['remoteJid' => $subscriber->chatid, 'instance_id' => $token, 'id' => $message_id],  return_array: true);
            } else {
                $messages = db_fetch('*', 'sp_whatsapp_messages', ['remoteJid' => $subscriber->chatid, 'instance_id' => $token], 'createdAt', 'DESC', $page * 40, 40, true);
            }

            if (count($messages) > 0) {
                $data = [
                    'messages' => $messages,
                    'chatid' => $chatid,
                    'token' => $token
                ];
                return view('Core\Whatsapp_livechat\Views\load_more', $data);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }



    public function send_message()
    {
        $access_token = get_team("ids");
        $team_id = get_team("id");
        $chatid = post("chatid");
        $token = post("token");
        $message = post("message");

        if (trim($message) == '') {
            ms([
                "status" => "error",
                "message" => __("Message can't be empty")
            ]);
        }

        $session = db_get("*", TB_WHATSAPP_SESSIONS, ["team_id" => $team_id, "instance_id" => $token]);

        if (!$session) {
            ms([
                "status" => "error",
                "message" => __("Instance ID Invalidated")
            ]);
        }

        if ($session->status == 0) {
            ms([
                "status" => "error",
                "message" => __("This instance ID has not been activated yet")
            ]);
        }

        $account = db_get("*", TB_ACCOUNTS, ["team_id" => $team_id, "token" => $token]);

        if (!$account) {
            ms([
                "status" => "error",
                "message" => __("Account does not exist")
            ]);
        }

        if ($account->status == 0) {
            ms([
                "status" => "error",
                "message" => __("This WhatsApp account relogin required")
            ]);
        }

        $subscriber = db_get('*', TB_WHATSAPP_SUBSCRIBERS, ['id' => $chatid,  'instance_id' => $token, 'team_id' => $team_id]);

        if (isset($subscriber)) {

            $response = wa_post_curl("send_message", [
                "instance_id" => $token,
                "access_token" => $access_token
            ], [
                "media_url" => '',
                "chat_id" => $subscriber->chatid,
                "caption" => $message,
                "filename" => ''
            ]);
            ms([
                "status" => "success",
                "message" => __("Success"),
                "data" => $response
            ]);
        } else {
            ms([
                "status" => "error",
                "message" => __("unknow error")
            ]);
        }

        //return $this->respond((array)$response);
    }

    public function update_card($id)
    {
        $name = post("name");
        $desc = post("desc");
        $order = post("order");
        $color = post("color");

        validate('null', __('name'), $name);
        validate('null', __('desc'), $desc);

        $data = [
            "name" => $name,
            "desc" => $desc,
            "order" => $order,
            "color" => $color,

        ];

        $result = db_update('sp_whatsapp_funnels', $data, ['id' => $id]);

        ms([
            "status" => "success",
            "message" => __("Success")
        ]);
    }

    public function delete_card($ids)
    {
        if ($ids == '') {
            $ids = post('id');
        }

        if (empty($ids)) {
            ms([
                "status" => "error",
                "message" => __('Please select an item to delete')
            ]);
        }

        if (is_array($ids)) {
            foreach ($ids as $id) {
                $data = [
                    "kanban_group" => null
                ];
                db_update(TB_WHATSAPP_SUBSCRIBERS, $data, ['kanban_group' => $id]);
                db_delete('sp_whatsapp_funnels', ['id' => $id]);
            }
        } elseif (is_string($ids)) {
            $data = [
                "kanban_group" => null
            ];
            db_update(TB_WHATSAPP_SUBSCRIBERS, $data, ['kanban_group' => $ids]);
            db_delete('sp_whatsapp_funnels', ['id' => $ids]);
        }

        ms([
            "status" => "success",
            "message" => __('Success')
        ]);
    }

    public function update_subs_board()
    {
        $newTab = (int)post("newTab");
        $token = post("token");
        $chatid = post("chatid");
        $index = post("index");

        if ($newTab == 0) {
            $newTab = '';
        }

        $data = [
            "kanban_group" => $newTab,
            "kanban_order" => $index
        ];


        db_update(TB_WHATSAPP_SUBSCRIBERS, $data, ['id' => $chatid]);

        if ($newTab != '') {
            $items = db_fetch('id, kanban_order', TB_WHATSAPP_SUBSCRIBERS, ['kanban_group' => $newTab, 'instance_id' =>  $token], 'kanban_order', 'ASC');
        } else {
            //$items = db_fetch('id, kanban_order', TB_WHATSAPP_SUBSCRIBERS, "(kanban_group IS NULL or REPLACE(kanban_group, ' ', '') = '') AND instance_id = '$token'", 'kanban_order', 'ASC');
            $items = db_fetch('id, kanban_order', TB_WHATSAPP_SUBSCRIBERS, "instance_id = '$token' AND (kanban_group = '' or kanban_group IS NULL) AND chatid NOT LIKE '%g.us'", 'kanban_order', 'ASC');
        }


        $newOrder = $index;
        //$existingOrders = array_column($items, 'kanban_order');

        $counter = $index;
        //if (in_array($newOrder, $existingOrders)) {
        foreach ($items as &$record) {


            if (($record->kanban_order ?? 0) >= $newOrder && $record->id != $chatid) {

                $counter++;

                $record->kanban_order = $counter;
                db_update(TB_WHATSAPP_SUBSCRIBERS, ['kanban_order' => $record->kanban_order], ['id' => $record->id]);
            }
        }
        //}


        ms([
            "status" => "success",
            "message" =>  __('Success')
        ]);
    }
}
