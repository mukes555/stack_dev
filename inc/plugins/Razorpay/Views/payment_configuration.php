<div class="card mb-4">
    <div class="card-header">
        <div class="card-title"><?php _e( $config['name'] )?></div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="razorpay_one_time_status" class="form-label"><?php _e('One-time payment status')?></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="razorpay_one_time_status" <?php _e( get_option("razorpay_one_time_status", 0)==1?"checked='true'":"" )?> id="razorpay_one_time_status_enable" value="1">
                            <label class="form-check-label" for="razorpay_one_time_status_enable"><?php _e('Enable')?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="razorpay_one_time_status" <?php _e( get_option("razorpay_one_time_status", 0)==0?"checked='true'":"" )?> id="razorpay_one_time_status_disable" value="0">
                            <label class="form-check-label" for="razorpay_one_time_status_disable"><?php _e('Disable')?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <label for="razorpay_recurring_status" class="form-label"><?php _e('Recurring payment status')?></label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="razorpay_recurring_status" <?php _e( get_option("razorpay_recurring_status", 0)==1?"checked='true'":"" )?> id="razorpay_recurring_status_enable" value="1">
                            <label class="form-check-label" for="razorpay_recurring_status_enable"><?php _e('Enable')?></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="razorpay_recurring_status" <?php _e( get_option("razorpay_recurring_status", 0)==0?"checked='true'":"" )?> id="razorpay_recurring_status_disable" value="0">
                            <label class="form-check-label" for="razorpay_recurring_status_disable"><?php _e('Disable')?></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="razorpay_client_id" class="form-label"><?php _e('Client ID')?></label>
            <input type="text" class="form-control form-control-solid" id="razorpay_client_id" name="razorpay_client_id" value="<?php _ec( get_option("razorpay_client_id", "") )?>">
        </div>
        <div class="mb-4">
            <label for="razorpay_client_secret_key" class="form-label"><?php _e('Client Secret Key')?></label>
            <input type="text" class="form-control form-control-solid" id="razorpay_client_secret_key" name="razorpay_client_secret_key" value="<?php _ec( get_option("razorpay_client_secret_key", "") )?>">
        </div>

        <div class="alert alert-primary">
            <span class="fw-6"><?php _e("Webhook URL:")?></span> 
            <a href="<?php _ec( base_url("razorpay_recurring/webhook") )?>" target="_blank"><?php _ec( base_url("razorpay_recurring/webhook") )?></a> 
            <br/>
            <span class="fw-6"><?php _e("Required events:")?></span>
            <span class="e"><?php _e('Payment sale completed, Billing subscription cancelled')?></span>
        </div>

        <div class="mb-4">
            <label for="razorpay_webhook_id" class="form-label"><?php _e('Webhook ID')?></label>
            <input type="text" class="form-control form-control-solid" id="razorpay_webhook_id" name="razorpay_webhook_id" value="<?php _ec( get_option("razorpay_webhook_id", "") )?>">
        </div>
    </div>
</div>