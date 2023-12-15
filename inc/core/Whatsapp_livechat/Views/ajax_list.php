<?php if (!empty($result)) { ?>

    <?php foreach ($result as $key => $value) : ?>

        <div class="col-md-6 col-sm-12 col-xs-6 mb-6 item" data-id="<?php _e($value->ids) ?>">
            <div class="card d-flex flex-column flex-row-auto card-custom card-custom-success rounded">
                <div class="card-header d-block position-relative mh-260">
                    <i class="fad fa-comments text-primary fs-90 position-absolute text-white opacity-25 t-15 r-35"></i>
                    <div class="my-3 mt-5">
                        <div class="d-flex align-items-center">
                            <img src="<?php _ec(get_file_url($value->avatar)) ?>" class="b-r-12 w-50 h-50 me-3">
                            <div class="text-over">
                                <h3 class="text-white text-over"> <?php _e($value->name) ?></h3>
                                <div class="text-white text-over"><?php _e($value->username) ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex position-relative t-30">
                        <div class="card-stats p-20 me-2 bg-white rounded">
                            <div class="text-primary fs-20 mb-3">
                                <i class="fad fa-address-book"></i>
                            </div>
                            <div class="fs-25 fw-6 text-gray-700"><?php _e($value->subscribers) ?></div>
                            <div class="text-gray-500"><?php _e("Subscribers") ?></div>
                        </div>
                        <div class="card-stats p-20 ms-2 bg-white rounded">
                            <div class="text-danger fs-20 mb-3">
                                <i class="fad fa-comment-exclamation"></i>
                            </div>
                            <div class="fs-25 fw-6 text-gray-700"><?php _e($value->unreadMessages) ?></div>
                            <div class="text-gray-500"><?php _e("Unread Messages") ?></div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-center p-t-50">
                    <a href="<?php _e(get_module_url("index/chats/" . $value->token)) ?>" class="btn btn-light-dark text-center me-2 wp-50"><i class="fal fa-list-alt"></i> <?php _e("LiveChat") ?></a>
                </div>
            </div>
        </div>
    <?php endforeach ?>

<?php } else { ?>
    <div class="mw-400 container d-flex align-items-center align-self-center h-100 py-5">
        <div>
            <div class="text-center px-4">
                <img class="mw-100 mh-300px" alt="" src="<?php _e(get_theme_url()) ?>Assets/img/empty2.png">
            </div>
        </div>
    </div>
<?php } ?>