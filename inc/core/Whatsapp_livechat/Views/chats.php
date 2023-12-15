<?php
$utz = get_user("timezone");

?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js"></script>
<script src="https://unpkg.com/dom-autoscroller@2.2.3/dist/dom-autoscroller.js"></script>


<style>
    .dropdown-toggle::before,
    .dropdown-toggle::after {
        display: none !important;
    }

    .cursor-grab {
        cursor: -webkit-grab;
        cursor: grab;
    }

    .cursor-grab .card-body {
        padding: 3px;
    }

    .tasks {
        min-height: 450px;
    }

    .chat-item img {
        float: left;
        width: 45px;
        height: 45px;
        margin-right: 10px;
    }

    .chat-item .chat-cont {
        position: relative;
        display: block;
        padding: 10px;
        transition: all .2s ease;
        -webkit-transition: all .2s ease;
        -moz-transition: all .2s ease;
        -ms-transition: all .2s ease;
        -o-transition: all .2s ease;
    }

    .chat-item .chat-cont .friend-name,
    .chat-item .chat-cont .friend-name:hover {
        color: #777;
    }

    .chat-item .chat-cont .friend-name {
        width: 55%;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .chat-item .chat-cont .last-message {
        width: 65%;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .chat-item .chat-cont .time {
        position: absolute;
        top: 10px;
        right: 8px;
    }

    .chat-item .chat-cont small,
    .chat-item .chat-cont .small {
        font-size: 85%;
    }

    .chat-item .chat-cont .chat-alert {
        position: absolute;
        right: 8px;
        top: 33px;
        font-size: 10px;
        padding: 3px 5px;
    }

    .horizontal-scrollable>.row {
        overflow-x: auto;
        white-space: nowrap;
        display: block !important;
    }

    .horizontal-scrollable>.row>.child {
        display: inline-block;
        float: none;
    }

    .tasks {
        max-height: calc(100vh - 200px);
        height: calc(100vh - 200px);
        overflow: scroll;
    }

    .tasks::-webkit-scrollbar {
        display: none;
    }

    .tasks {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .icono-scrollable {
        position: sticky;
        bottom: 0;
        left: 50%;
        /* transform: translateX(-50%); */
        background-color: rgba(255, 255, 255, 0.8);
        padding: 5px;
        display: none;
    }

    .icono-scrollable.scrollable {
        display: block;
    }

    .autoresponder_preview {
        width: 100%;
        height: auto;
        display: flex;
        position: relative;
        align-items: center;
        justify-content: center;
    }

    .autoresponder_preview .marvel-device .screen {
        text-align: left;
    }

    .screen-container {
        height: 100%;
    }

    /* Status Bar */

    .autoresponder_preview .status-bar {
        height: 25px;
        background: #004e45;
        color: #fff;
        font-size: 14px;
        padding: 0 8px;
    }

    .autoresponder_preview .status-bar:after {
        content: "";
        display: table;
        clear: both;
    }

    .autoresponder_preview .status-bar div {
        float: right;
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 0 0 8px;
        font-weight: 600;
    }

    /* Chat */

    .autoresponder_preview .chat {
        height: calc(100% - 69px);
    }

    .autoresponder_preview .chat-container {
        height: 100%;
    }

    /* User Bar */

    .autoresponder_preview .user-bar {
        height: 55px;
        background: #005e54;
        color: #fff;
        padding: 0 8px;
        font-size: 24px;
        position: relative;
        z-index: 1;
        box-shadow: 0px 10px 10px rgba(0, 0, 0, 0.5);
    }

    .autoresponder_preview .user-bar:after {
        content: "";
        display: table;
        clear: both;
    }

    .autoresponder_preview .user-bar div {
        float: left;
        transform: translateY(-50%);
        position: relative;
        top: 50%;
    }

    .autoresponder_preview .user-bar .actions {
        float: right;
        margin: 0 0 0 20px;
    }

    .autoresponder_preview .user-bar .actions.more {
        margin: 0 12px 0 32px;
    }

    .autoresponder_preview .user-bar .actions.attachment {
        margin: 0 0 0 30px;
    }

    .autoresponder_preview .user-bar .actions.attachment i {
        display: block;
        transform: rotate(-45deg);
    }

    .autoresponder_preview .user-bar .avatar {
        margin: 0 0 0 5px;
        width: 36px;
        height: 36px;
    }

    .autoresponder_preview .user-bar .avatar img {
        border-radius: 50%;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1);
        display: block;
        width: 100%;
    }

    .autoresponder_preview .user-bar .name {
        font-size: 17px;
        font-weight: 600;
        text-overflow: ellipsis;
        letter-spacing: 0.3px;
        margin: 0 0 0 8px;
        overflow: hidden;
        white-space: nowrap;
        width: 70%;
        min-width: 150px;
    }

    .autoresponder_preview .user-bar .status {
        display: block;
        font-size: 13px;
        font-weight: 400;
        letter-spacing: 0;
    }

    /* Conversation */

    .autoresponder_preview .conversation {
        position: relative;
        background: #efe7dd url("https://cloud.githubusercontent.com/assets/398893/15136779/4e765036-1639-11e6-9201-67e728e86f39.jpg") repeat;
        z-index: 0;
        padding-bottom: 10px;
    }



    .autoresponder_preview .conversation ::-webkit-scrollbar {
        transition: all .5s;
        width: 5px;
        height: 1px;
        z-index: 10;
    }

    .autoresponder_preview .conversation ::-webkit-scrollbar-track {
        background: transparent;
    }

    .autoresponder_preview .conversation ::-webkit-scrollbar-thumb {
        background: #b3ada7;
    }

    .autoresponder_preview .conversation .conversation-container {
        padding: 0 16px;
        margin-bottom: 5px;
        position: relative;
    }

    .autoresponder_preview .conversation .conversation-container:after {
        content: "";
        display: table;
        clear: both;
    }

    .autoresponder_preview .conversation-wrap {
        height: 450px;
        margin-bottom: 15px;
        position: relative;
        box-shadow: inset 0 10px 10px -10px #000000;

        height: calc(80vh - 65px);
        min-height: 400px;
        max-height: calc(80vh - 65px);
        overflow: scroll;
    }

    @media (max-width: 991.98px) {
        .autoresponder_preview .conversation-wrap {
            height: calc(90vh - 130px);
            max-height: calc(90vh - 130px);
        }

        .autoresponder_preview .conversation {
            height: (100vh - 30px) !important
        }
    }

    /* Messages */

    .autoresponder_preview .message {
        color: #000;
        clear: both;
        line-height: 18px;
        font-size: 14px;
        padding: 8px;
        position: relative;
        margin: 8px 0;
        max-width: 85%;
        word-wrap: break-word;
        z-index: -1;
    }

    .autoresponder_preview .message:after {
        position: absolute;
        content: "";
        width: 0;
        height: 0;
        border-style: solid;
    }

    .autoresponder_preview .metadata {
        display: inline-block;
        float: right;
        padding: 0 0 0 7px;
        position: relative;
        bottom: -4px;
    }

    .autoresponder_preview .metadata .time {
        color: rgba(0, 0, 0, .45);
        font-size: 11px;
        display: inline-block;
    }

    .autoresponder_preview .message img {
        max-width: 100%;
    }

    .autoresponder_preview .metadata .tick {
        display: inline-block;
        margin-left: 2px;
        position: relative;
        top: 4px;
        height: 16px;
        width: 16px;
        color: #4fc3f7;
    }

    .autoresponder_preview .metadata .tick svg,
    .autoresponder_preview .metadata .tick .fad {
        position: absolute;
        transition: .5s ease-in-out;
    }

    .autoresponder_preview .metadata .tick svg:first-child {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-transform: perspective(800px) rotateY(180deg);
        transform: perspective(800px) rotateY(180deg);
    }

    .autoresponder_preview .metadata .tick svg:last-child {
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-transform: perspective(800px) rotateY(0deg);
        transform: perspective(800px) rotateY(0deg);
    }

    .autoresponder_preview .metadata .tick-animation svg:first-child {
        -webkit-transform: perspective(800px) rotateY(0);
        transform: perspective(800px) rotateY(0);
    }

    .autoresponder_preview .metadata .tick-animation svg:last-child {
        -webkit-transform: perspective(800px) rotateY(-179.9deg);
        transform: perspective(800px) rotateY(-179.9deg);
    }

    .autoresponder_preview .message:first-child {
        margin: 16px 0 8px;
    }

    .autoresponder_preview .message.received {
        background: #fff;
        border-radius: 0px 5px 5px 5px;
        float: left;
    }

    .autoresponder_preview .message.received .metadata {
        padding: 0 0 0 16px;
    }

    .autoresponder_preview .message.received:after {
        border-width: 0px 10px 10px 0;
        border-color: transparent #fff transparent transparent;
        top: 0;
        left: -10px;
    }

    .autoresponder_preview .message.sent {
        background: #e1ffc7;
        border-radius: 5px 0px 5px 5px;
        float: right;
    }

    .autoresponder_preview .message.sent:after {
        border-width: 0px 0 10px 10px;
        border-color: transparent transparent transparent #e1ffc7;
        top: 0;
        right: -10px;
    }

    /* Compose */

    .autoresponder_preview .conversation-compose {
        display: flex;
        flex-direction: row;
        align-items: flex-end;
        overflow: hidden;
        height: 50px;
        width: 100%;
        z-index: 2;
    }

    .autoresponder_preview .conversation-compose div,
    .autoresponder_preview .conversation-compose input {
        background: #fff;
        height: 100%;
    }

    .autoresponder_preview .conversation-compose .emoji {
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 5px 0 0 5px;
        flex: 0 0 auto;
        margin-left: 8px;
        width: 48px;
    }

    .autoresponder_preview .conversation-compose .input-msg {
        border: 0;
        flex: 1 1 auto;
        font-size: 16px;
        margin: 0;
        outline: none;
        min-width: 50px;
    }

    .autoresponder_preview .conversation-compose .photo {
        flex: 0 0 auto;
        border-radius: 0 0 5px 0;
        text-align: center;
        position: relative;
        width: 48px;
    }

    .autoresponder_preview .conversation-compose .photo:after {
        border-width: 0px 0 10px 10px;
        border-color: transparent transparent transparent #fff;
        border-style: solid;
        position: absolute;
        width: 0;
        height: 0;
        content: "";
        top: 0;
        right: -10px;
    }

    .autoresponder_preview .conversation-compose .photo i {
        display: block;
        color: #7d8488;
        font-size: 24px;
        transform: translate(-50%, -50%);
        position: relative;
        top: 50%;
        left: 50%;
    }

    .autoresponder_preview .conversation-compose .send {
        background: transparent;
        border: 0;
        cursor: pointer;
        flex: 0 0 auto;
        margin-left: 8px;
        margin-right: 8px;
        padding: 0;
        position: relative;
        outline: none;
    }

    .autoresponder_preview .conversation-compose .send .circle {
        background: #008a7c;
        border-radius: 50%;
        color: #fff;
        position: relative;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .autoresponder_preview .conversation-compose .send .circle i {
        font-size: 24px;
        margin-left: 5px;
    }

    /* Small Screens */

    @media (max-width: 768px) {
        .autoresponder_preview .marvel-device.nexus5 {
            border-radius: 0;
            flex: none;
            padding: 0;
            max-width: none;
            overflow: hidden;
            height: 100%;
            width: 100%;
        }

        .autoresponder_preview .marvel-device>.screen .chat {
            visibility: visible;
        }

        .autoresponder_preview .marvel-device {
            visibility: hidden;
        }

        .autoresponder_preview .marvel-device .status-bar {
            display: none;
        }

        .autoresponder_preview .screen-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
    }
</style>

<?php if($items): ?>

<div class="container-fluid p-t-5 vertical-scrollable">
    <div class="horizontal-scrollable">
        <div class="row">

            <!-- Start default unasigned lane -->
            <div class="col-auto child">
                <div class="card mb-3 border border-light shadow-sm w-300">
                    <div class="card-header bg-light flex-column text-truncate justify-content-around px-3" style="text-overflow: ellipsis; overflow: hidden; width: 90%;">
                        <h3 class="card-title h5" title="<?php _e('Unasigned') ?>">
                            <i class="fad fa-question-circle m-r-3 text-warning"></i> <?php _e('Unasigned') ?>
                        </h3>
                        <small class="mb-0 text-muted text-over" data-toggle="tooltip" title="<?php _e('This Chats not are assigned to a funnel') ?>">
                            <?php _e('This Chats not are assigned to a funnel') ?>
                        </small>
                    </div>
                    <div class="card-body p-2">
                        <div class="tasks" id="backlog" data-tabid="0">
                            <?php
                            $unasigned_ = array_filter(
                                $items,
                                function ($obj) {
                                    return empty($obj->kanban_group);
                                }
                            );
if($unasigned_):
    
                            usort($unasigned_, function ($a, $b) {
                                return $a->kanban_order - $b->kanban_order;
                            });


                            ?>
                            
                            

                            <?php foreach ($unasigned_ as $key => $value) : ?>
                                <?php
                                $contact_data = json_decode($value->contact_data ?? '{}');
                                $uid = get_user("id");
                                $m = $value->lastMessageTime ? str_replace('Last', '', (new \Moment\Moment($value->lastMessageTime, $utz))->format('Y-m-d H:i')) : '';
                                ?>

                                <div class="card mb-1 cursor-grab chatController" data-token="<?php _e($account->token) ?>" data-chatid="<?php _ec($value->id) ?>">
                                    <div class="card-body border border-light rounded">
                                        <div class="chat-item">
                                            <div href="#" class="clearfix chat-cont">
                                                <img src="<?php _ec(($contact_data->profilePicUrl ?? '') == '' ? "https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=" . substr(str_replace('@s.whatsapp.net', '', $value->chatid), -2)   : $contact_data->profilePicUrl) ?>" alt="" class="rounded-circle drag-handle-class" id="chat-profile-<?php _ec($value->id) ?>">
                                                <div class="friend-name" id="friend-name-<?php _ec($value->id) ?>">
                                                    <strong><?php _e(($contact_data->name ?? '') == '' ? str_replace('@s.whatsapp.net', '', $value->chatid) : $contact_data->name) ?></strong>
                                                </div>
                                                <div class="last-message text-muted" id="chat-last-message-<?php _ec($value->id) ?>"><?php _e($value->lastMessage ?? '-') ?></div>
                                                <small class="time text-muted fs-8" id="chat-time-<?php _ec($value->id) ?>"><?php _e($m) ?></small>
                                                <div id="chat-status-<?php _ec($value->id) ?>">
                                                    <?php if ($value->unreadMessages) : ?>
                                                        <small class="chat-alert badge bg-danger"><?php _e($value->unreadMessages) ?></small>
                                                    <?php else : ?>
                                                        <small class="chat-alert text-success"><i class="fa fa-check text-primary"></i></small>
                                                    <?php endif ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            <?php endforeach ?>
                            
                            <?php endif ?>
                        </div>
                        <div class="icono-scrollable icono-scrollable-0 text-center p-0"><i class="fad fa-chevron-down text-primary"></i></div>
                    </div>
                </div>
            </div>
            <!-- End default unasigned lane -->

            <?php if (!empty($cards)) : ?>
                <?php foreach ($cards as $key => $card) : ?>
                    <!-- Start default unasigned lane -->
                    <div class="col-auto child">
                        <div class="card mb-3 border border-light shadow-sm w-300" style="border-top: 3px solid <?php echo $card->color != '' ? $card->color : 'white' ?> !important;">
                            <div class="card-header bg-light flex-column text-truncate justify-content-around px-3" style="text-overflow: ellipsis; overflow: hidden; width: 90%;">
                                <h3 class="card-title h5 text-truncate" title="<?php _e($card->name) ?>">
                                    <?php _e($card->name) ?>
                                </h3>
                                <small class="mb-0 text-muted text-over text-truncate" data-toggle="tooltip" title="<?php _e($card->desc) ?>">
                                    <?php _e($card->desc) ?>
                                </small>
                            </div>
                            <div class="card-body p-2">
                                <div class="tasks" id="board_<?php _e($card->id) ?>" data-tabid="<?php _e($card->id) ?>">
                                    <?php
                                    $board_items = array_filter(
                                        $items,
                                        function ($obj) use ($card) {
                                            return $obj->kanban_group == $card->id;
                                        }
                                    );

                                    usort($board_items, function ($a, $b) {
                                        return $a->kanban_order - $b->kanban_order;
                                    });

                                    foreach ($board_items as $value) :
                                        $contact_data = json_decode($value->contact_data);
                                        $uid = get_user("id");
                                        $m = $value->lastMessageTime ? str_replace('Last', '', (new \Moment\Moment($value->lastMessageTime, $utz))->format('Y-m-d H:i')) : '';
                                    ?>

                                        <div class="card mb-1 cursor-grab chatController" data-token="<?php _e($account->token) ?>" data-chatid="<?php _ec($value->id) ?>">
                                            <div class="card-body border border-light rounded">
                                                <div class="chat-item">
                                                    <div href="#" class="clearfix chat-cont">
                                                        <img src="<?php _ec(($contact_data->profilePicUrl ?? '') == '' ? "https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=" . substr(str_replace('@s.whatsapp.net', '', $value->chatid), -2)   : $contact_data->profilePicUrl) ?>" alt="" class="rounded-circle drag-handle-class" id="chat-profile-<?php _ec($value->id) ?>">
                                                        <div class="friend-name" id="friend-name-<?php _ec($value->id) ?>">
                                                            <strong><?php _e(($contact_data->name ?? '') == '' ? str_replace('@s.whatsapp.net', '', $value->chatid) : $contact_data->name) ?></strong>
                                                        </div>
                                                        <div class="last-message text-muted" id="chat-last-message-<?php _ec($value->id) ?>"><?php _e($value->lastMessage ?? '-') ?></div>
                                                        <small class="time text-muted fs-8" id="chat-time-<?php _ec($value->id) ?>"><?php _e($m) ?></small>
                                                        <div id="chat-status-<?php _ec($value->id) ?>">
                                                            <?php if ($value->unreadMessages) : ?>
                                                                <small class="chat-alert badge bg-danger"><?php _e($value->unreadMessages) ?></small>
                                                            <?php else : ?>
                                                                <small class="chat-alert text-success"><i class="fa fa-check text-primary"></i></small>
                                                            <?php endif ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    <?php endforeach ?>
                                </div>
                                <div class="icono-scrollable icono-scrollable-<?php _e($card->id) ?> text-center p-0"><i class="fad fa-chevron-down text-primary"></i></div>
                            </div>

                            <div class="dropdown dropdown-fixed dropdown-hide-arrow" style="position: absolute; right:0px;">
                                <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fad fa-ellipsis-v pe-0"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <button class="dropdown-item btnEditCard" data-id="<?php _e($card->id) ?>" data-token="<?php _e($account->token) ?>" type="button">
                                            <i class="fas fa-edit fa-fw pe-2" aria-hidden="true"></i><?php _e('Edit') ?>
                                        </button>
                                    </li>
                                    <li>
                                        <a href="<?php _e(get_module_url("delete_card/" . $card->id)) ?>" data-id="<?php _ec($card->id) ?>" data-redirect="<?php _ec(get_module_url("index/chats/" . $account->token)) ?>" class="actionItem dropdown-item" data-confirm="<?php _e('Are you sure to delete this items?') ?>" data-remove="item" data-active="bg-light-primary">
                                            <i class="fas fa-trash-alt fa-fw pe-2" aria-hidden="true"></i><?php _e("Delete") ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>
</div>


<div class="position-fixed p-0 m-0" style="top:80px; right: 20px;">
    <button class="btn btn-primary w-40 h-40 p-0 m-0 rounded-circle shadow text-center" data-bs-toggle="modal" data-bs-target="#saveCardModal" data-toggle="tooltip" title="<?php _e('Add Tab') ?>">
        <i class="fad fa-plus p-0 m-0"></i>
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="saveCardModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _ec("Add Board") ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="actionForm" action="<?php _ec(get_module_url("create_card/" . $account->token)) ?>" method="POST" data-redirect="<?php _ec(get_module_url("index/chats/" . $account->token)) ?>">

                    <div class="mb-3">
                        <input type="text" class="form-control form-control-solid" id="funnel_name" name="name" placeholder="<?php _e("Enter Name") ?>" value="">
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control form-control-solid" id="funnel_desc" name="desc" placeholder="<?php _e("Enter Description") ?>" value="">
                    </div>

                    <div class="mb-3">
                        <input type="number" class="form-control form-control-solid" id="funnel_order" name="order" placeholder="<?php _e("Enter Order") ?>" value="0">
                    </div>

                    <div class="mb-3">
                        <label>Color</label>
                        <input type="color" class="form-control form-control-solid" id="funnel_color" name="color" placeholder="<?php _e("Select color") ?>" value="#FFFFFF">
                    </div>


                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary"><?php _e("Save") ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    //* Inicializacion de Dragula

    var chat_socket = null;
    var chat_socket_list = null;
    var drake = null;

    function verificarScroll() {

        $('.tasks').each((i, e) => {
            let id = $(e).data('tabid');
            if (e.scrollHeight > e.clientHeight) {
                $('.icono-scrollable-' + id).addClass('scrollable');
            } else {
                $('.icono-scrollable-' + id).removeClass('scrollable');
            }
        });


    }

    var init_dragula = () => {

        if (drake) {
            drake.destroy();
        }

        if (/Android|iPhone/i.test(navigator.userAgent)) {
            drake = dragula({
                isContainer: function(el) {
                    return el.classList.contains('tasks');
                },
                moves: (element, container, handle) => {
                    return handle.classList.contains('drag-handle-class');
                }
            });

            const moveList = document.querySelectorAll('div.drag-handle-class');

            if (moveList) {
                moveList.forEach(move => {
                    move.addEventListener('touchmove', event => event.preventDefault());
                });
            }
        } else {
            drake = dragula({
                isContainer: function(el) {
                    return el.classList.contains('tasks');
                }
            });
        }

        //* Auto scroll  cuando se arrastra un elemento de forma horizontal
        var scroll = autoScroll([
            window,
            document.querySelector('.horizontal-scrollable .row')
        ], {
            margin: 30,
            autoScroll: function() {
                return this.down && drake.dragging;
            }
        });


        var tasks = $('.tasks').each((i, e) => {

            var scroll_v = autoScroll([
                window,
                e
            ], {
                direction: 'vertical',
                margin: 30,
                maxSpeed: 10,
                scrollWhenOutside: true,
                autoScroll: function() {
                    return this.down && drake.dragging;
                }
            });
        });

        //* Funcion que actualiza al subscriber cuando se dispara el evento drop
        drake.on('drop', (el, target, source, sibling) => {

            Core.overplay();

            var chatid = $(el).data('chatid');
            var token = $(el).data('token');
            var newTab = $(target).data('tabid');
            var id = $(target).attr('id');
            var index = Array.prototype.slice.call(document.getElementById(id).children).findIndex((item) => {
                console.log(item);
                return el === item
            });


            $.post(PATH + "whatsapp_livechat/update_subs_board", {
                csrf: csrf,
                newTab: newTab,
                chatid: chatid,
                index: index,
                token: token
            }, function(result) {
                verificarScroll();
                Core.overplay("hide");
                Core.notify(result.message, result.status);
            }, 'json')
        })

    }

    init_dragula();
    verificarScroll();


    //* Modal para mostrar el formulario de edicion
    jQuery(document).ready(function($) {
        $(document).on("click", ".btnEditCard", function() {
            var that = $(this);
            var id = that.data('id');
            var token = that.data('token');
            Core.overplay();
            $.post(PATH + "whatsapp_livechat/edit_card_modal", {
                csrf: csrf,
                id: id,
                token: token
            }, function(result) {
                $("body").append(result);
                $('#editCardModal').modal('show').on('hidden.bs.modal', function(e) {
                    $(this).remove();
                });
                Core.overplay("hide");
            });
            return false;
        });
    });

    //* Modal de chat
    jQuery(document).ready(function($) {
        $(document).on("click", ".chatController", function() {
            var that = $(this);
            var chatid = that.data('chatid');
            var token = that.data('token');

            console.log(chatid, token)

            Core.overplay();
            $.post(PATH + "whatsapp_livechat/chat_modal", {
                csrf: csrf,
                chatid: chatid,
                token: token
            }, function(result) {
                $("body").append(result);
                $('#chatModal').modal('show').on('hidden.bs.modal', function(e) {
                    $(this).remove();
                    if (chat_socket) {
                        chat_socket.disconnect();
                        chat_socket = null;
                    }
                });
                Core.overplay("hide");
            });
            return false;
        });
    });

    // Socket io
    jQuery(document).ready(function($) {
        setTimeout(function() {
            var wa_server = '<?php echo get_option('whatsapp_server_url', '') ?>';
            var instance_id = '<?php _e($account->token) ?>';

            if (wa_server != '') {

                if (chat_socket_list) {
                    chat_socket_list.disconnect();
                    chat_socket_list = null;
                }

                chat_socket_list = io(wa_server, {
                    transports: ['polling'],
                    forceNew: true
                });

                chat_socket_list.on("connect_error", () => {
                    setTimeout(() => {
                        chat_socket_list.connect();
                    }, 1000);
                });

                chat_socket_list.on('connect', () => {
                    console.log(instance_id, 'connected chat_socket_list' + chat_socket_list.id + '...');
                });

                chat_socket_list.on(`instance-${instance_id}-appMessage-create`, (args) => {

                    console.log(args.subscriber.contact_data);

                    $(`#chat-last-message-${args.subscriber.id}`).html(args.subscriber.lastMessage);
                    var time = moment().tz('<?php _e($utz) ?>').format('Y-m-d H:mm');
                    $(`#chat-time-${args.subscriber.id}`).html(time);

                    var unread = `<small class="chat-alert badge bg-danger">${args.subscriber.unreadMessages}</small>`;
                    var read = `<small class="chat-alert text-success"><i class="fa fa-check text-primary"></i></small>`;

                    $(`#chat-status-${args.subscriber.id}`).html(args.subscriber.unreadMessages ? unread : read);

                    /*$.post(PATH + "whatsapp_livechat/load_more", {
                        csrf: csrf,
                        chatid: chatid,
                        token: token,
                        message_id: args.message.id
                    }, function(result) {

                        console.log(result)
                    })*/

                });
            }
        }, 500);
    })
</script>

<?php else: ?>
<div class="container">
    <div class="d-flex align-items-center align-self-center h-100 mih-500">
        <div class="w-100">
            <div class="text-center px-4">
                <img class="mh-190 mb-4" alt="" src="<?php _e( get_theme_url() ) ?>Assets/img/empty2.png">
            </div>
        </div>
    </div>
</div>
<?php endif ?>