<div class="container mw-700 py-5">
    <div class="w-100 m-r-0 d-flex align-items-center justify-content-between">
        <h3 class="fw-bolder m-b-0 text-gray-800"><i class="<?php _ec( $config['icon'] )?>" style="color: <?php _ec( $config['color'] )?>;"></i> <?php _e("Instagram Threads OAuth")?></h3>
    </div>

    <form class="actionForm ig_unofficial_login_form m-t-40" action="<?php _ec( get_module_url("oauth_unofficial") )?>" method="POST" data-redirect="<?php _ec( get_module_url("index/unofficial") )?>">
        <div class="card b-r-10">
            <div class="card-body">
                <div class="mb-3">
                    <label for="ig_username" class="form-label"><?php _e('Instagram username')?></label>
                    <input type="text" class="form-control form-control-solid" id="ig_username" name="ig_username">
                </div>
                <div class="mb-3">
                    <label for="ig_password" class="form-label"><?php _e('Instagram password')?></label>
                    <input type="password" class="form-control form-control-solid" id="ig_password" name="ig_password">
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <a class="btn btn-light btn-active-light-primary me-2" href="<?php _ec( base_url("account_manager") )?>"><?php _e("Discard")?></a>
                <button type="submit" class="btn btn-primary"><?php _e("Submit")?></button>
            </div>
        </div>
    </form>  

    <form class="actionForm ig_unofficial_confirm_form d-none" action="<?php _ec( get_module_url("confirm_security_code") )?>" method="POST" data-redirect="<?php _ec( get_module_url("index/unofficial") )?>">
        <div class="card b-r-10">
            <div class="card-body">
                <div class="mb-3">
                    <label for="ig_api_path" class="form-label"><?php _e('Security code')?></label>
                    <input type="hidden" class="form-control form-control-solid" id="ig_api_path" name="ig_api_path">
                    <input type="text" class="form-control form-control-solid" id="ig_security_code" name="ig_security_code">
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <a class="btn btn-light btn-active-light-primary me-2" href="<?php _ec( base_url("account_manager") )?>"><?php _e("Discard")?></a>
                <button type="submit" class="btn btn-primary"><?php _e("Submit")?></button>
            </div>
        </div>
    </form>         
</div>