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