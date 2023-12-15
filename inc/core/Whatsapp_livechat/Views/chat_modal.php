<div class="modal fade" id="chatModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-body p-0">

                <div class="autoresponder_preview h-100">
                    <div class="marvel-device nexus5 w-100 h-100">
                        <div class="screen h-100">
                            <div class="screen-container">
                                <div class="chat">
                                    <div class="chat-container">
                                        <div class="user-bar">
                                            <div class="back wa-back-submenu">
                                                <i class="ri-arrow-left-line"></i>
                                            </div>
                                            <div class="avatar">
                                                <?php
                                                $contact_data = json_decode($subscriber->contact_data);

                                                ?>
                                                <img src="<?php _ec(($contact_data->profilePicUrl ?? '') == '' ? "https://ui-avatars.com/api/?background=0D8ABC&color=fff&name=" . substr(str_replace('@s.whatsapp.net', '', $subscriber->chatid), -2)   : $contact_data->profilePicUrl) ?>">
                                            </div>
                                            <div class="name">
                                                <span><?php _e(($contact_data->name ?? '') == '' ? str_replace('@s.whatsapp.net', '', $subscriber->chatid) : $contact_data->name) ?></span>
                                            </div>
                                            <div class="actions more">
                                                <i class="zmdi zmdi-more-vert"></i>
                                            </div>
                                            <div class="actions attachment">
                                                <i class="zmdi zmdi-attachment-alt"></i>
                                            </div>
                                            <div class="actions">
                                                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn m-0 p-0 w-20 text-white">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="conversation h-100">
                                            <div class="conversation-wrap">

                                                <div class="conversation-container" id="conversation-container-<?php _e($subscriber->id) ?>" style="z-index: 0;">

                                                    <?php foreach (array_reverse($messages) as $key => $msg) : ?>


                                                        <div class="message <?php echo !$msg['fromMe'] ? 'received' : 'sent' ?> item-autoresponder-preview" style="z-index: 10; min-width: 220px;">

                                                            <div class="img m-b-5">

                                                                <?php if ($msg['mediaType'] == 'image') : ?>
                                                                    <a href="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" target="_blank" class="href">
                                                                        <img src="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" style="max-width: 200px;" alt="">
                                                                    </a>
                                                                <?php elseif ($msg['mediaType'] == 'video') : ?>
                                                                    <video width="200" height="150" controls style="max-width: 200px;">
                                                                        <source src="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" type="video/mp4">
                                                                        <source src="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" type="video/ogg">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                <?php elseif ($msg['mediaType'] == 'audio') : ?>
                                                                    <audio controls style="max-width: 200px;">
                                                                        <source src="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" type="audio/ogg">
                                                                        <source src="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" type="audio/mpeg">
                                                                        Your browser does not support the audio element.
                                                                    </audio>
                                                                <?php elseif ($msg['mediaType'] == 'application') : ?>
                                                                    <a href="<?php _e(get_option('whatsapp_server_url', '') . 'files/' . $msg['mediaUrl']) ?>" style="max-width: 200px;" download target="_blank" class="btn btn-primary w-100">
                                                                        <?php _e('Download') ?>
                                                                    </a>
                                                                <?php endif ?>

                                                            </div>
                                                            <div class="text"><?php _e($msg['body']) ?> </div>
                                                            <span class="metadata">
                                                                <span class="time">
                                                                    <?php
                                                                    $utz = get_user("timezone");
                                                                    $m = $msg['createdAt'] ? str_replace('Last', '', (new \Moment\Moment($msg['createdAt'], $utz))->format('Y-m-d H:i')) : '';
                                                                    _e($m)
                                                                    ?>
                                                                </span>
                                                                <span class="tick">
                                                                </span>
                                                            </span>

                                                        </div>

                                                    <?php endforeach ?>






                                                </div>

                                            </div>
                                            <div class="conversation-compose">
                                                <div class="emoji w-15">
                                                </div>
                                                <input class="input-msg conversation-input-message" name="input" placeholder="<?php _e("Type a message") ?>" autocomplete="off" autofocus></input>
                                                <div class="photo">
                                                    <i class="zmdi zmdi-camera"></i>
                                                </div>
                                                <button class="btn-send-message send">
                                                    <div class="circle text-center">
                                                        <i class="fad fa-paper-plane fa-fw p-0 m-0"></i>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>
    setTimeout(() => {
        var elem = $('.conversation-wrap');
        var alto = elem.prop("scrollHeight");
        elem.scrollTop(alto);

    }, 500);

    var timer = null;
    var page = 0;
    var isLoading = false;
    var isEnd = false;

    var chatid = <?php _e($subscriber->id) ?>;
    var token = '<?php _e($subscriber->instance_id) ?>';

    $('.conversation-wrap').scroll(function(e) {
        if ($('.conversation-wrap').scrollTop() <= 30) {
            if (!isLoading && !isEnd) {
                if (timer) {
                    clearTimeout(timer);
                }
                timer = setTimeout(function() {
                    isLoading = true;
                    page++;
                    $.post(PATH + "whatsapp_livechat/load_more", {
                        csrf: csrf,
                        chatid: chatid,
                        token: token,
                        page: page
                    }, function(result) {
                        if (result) {
                            var elem = $('.conversation-wrap');
                            var current_scroll = elem.prop("scrollHeight");
                            $('.conversation-container').prepend(result);
                            var new_scroll = elem.prop("scrollHeight");
                            $('.conversation-wrap').scrollTop(new_scroll - current_scroll);

                        } else {
                            isEnd = true;
                        }
                        isLoading = false;
                    });

                }, 500);
            }
        }
    });


    var send_message_fn = () => {
        var message = $('.conversation-input-message').val();
        $('.conversation-input-message').val('');
        $.post(PATH + "whatsapp_livechat/send_message", {
            csrf: csrf,
            chatid: chatid,
            token: token,
            message: message
        }, function(result) {

        });
    }

    $('.btn-send-message').on('click', () => {
        send_message_fn();
    })
    $('.conversation-input-message').on('keypress', (e) => {
        if (e.key === "Enter") {
            send_message_fn();
        }
    })

    setTimeout(function() {
        var wa_server = '<?php echo get_option('whatsapp_server_url', '') ?>';
        var instance_id = '<?php _e($subscriber->instance_id) ?>';
        if (wa_server != '') {

            if (chat_socket) {
                chat_socket.disconnect();
            }

            chat_socket = io(wa_server, {
                transports: ['polling'],
                forceNew: true
            });

            chat_socket.on("connect_error", () => {
                setTimeout(() => {
                    chat_socket.connect();
                }, 1000);
            });

            chat_socket.on('connect', () => {
                console.log('connected ' + chat_socket.id + '...');
            });

            chat_socket.on(`instance-${instance_id}-appMessage-create`, (args) => {
                $.post(PATH + "whatsapp_livechat/load_more", {
                    csrf: csrf,
                    chatid: chatid,
                    token: token,
                    message_id: args.message.id
                }, function(result) {
                    $(`#conversation-container-${chatid}`).append(result);
                    var elem = $('.conversation-wrap');
                    var alto = elem.prop("scrollHeight");
                    elem.scrollTop(alto);
                })

            });
        }
    }, 500);
</script>