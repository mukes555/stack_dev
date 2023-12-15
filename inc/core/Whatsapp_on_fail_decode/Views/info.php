<?php if ( $status == "success" ): ?>
<div class="card b-r-6 h-100 post-schedule wrap-caption">
	<div class="card-header">
		<?php if ( !empty($account) ): ?>
			<h3 class="card-title"><?php _e( sprintf("On Fail Decode for %s", $account->name) )?></h3>
		<?php else: ?>
			<h3 class="card-title"><?php _ec("Set On Fail Decode for all account")?></h3>
		<?php endif ?>
        	<div class="card-toolbar"></div>
	</div>
	<div class="card-body position-relative">
		<input type="text" class="form-control form-control-solid d-none" id="instance_id" name="instance_id" value="<?php _ec( get_data($account, "token") )?>">

		<div class="mb-4">
			<label class="form-label"><?php _e("Status")?></label>
			<div>
               <div class="form-check form-check-inline">
                   <input class="form-check-input" type="radio" name="status" <?php _ec( (get_data($result, "status")==1 || get_data($result, "status") == "")?"checked='true'":"" )?> id="status_enable" value="1">
                   <label class="form-check-label" for="status_enable"><?php _e('Enable')?></label>
               </div>
               <div class="form-check form-check-inline">
                   <input class="form-check-input" type="radio" name="status" <?php _ec(get_data($result, "status")==0?"checked='true'":"" )?> id="status_disable" value="0">
                   <label class="form-check-label" for="status_disable"><?php _e('Disable')?></label>
               </div>
           </div>
		</div>


		<ul class="nav nav-pills mb-3 bg-white rounded fs-14 nx-scroll overflow-x-auto d-flex text-over b-r-6 border" id="pills-tab">
            <li class="nav-item me-0">
                 <label for="type_text_media" class="nav-link bg-active-primary text-gray-700 px-4 py-3 b-r-6 text-active-white <?php _ec( (get_data($result, "type") == 1 || get_data($result, "type") == "")?"active":"" ) ?>" data-bs-toggle="pill" data-bs-target="#wa_text_and_media" type="button" role="tab"><?php _e("Text & Media")?></label>
                 <input class="d-none" type="radio" name="type" id="type_text_media" <?php _ec( (get_data($result, "type") == 1 || get_data($result, "type") == "")?"checked='true'":"" ) ?> value="1">
            </li>
            <?php echo view_cell('\Core\Whatsapp_button_template\Controllers\Whatsapp_button_template::widget_menu', ["result" => $result]) ?>
            <?php echo view_cell('\Core\Whatsapp_list_message_template\Controllers\Whatsapp_list_message_template::widget_menu', ["result" => $result]) ?>
        </ul>

	 	<div class="tab-content" id="pills-tabContent">
			<div class="tab-pane fade show <?php _ec( (get_data($result, "type") == 1 || get_data($result, "type") == "")?" active":"" ) ?>" id="wa_text_and_media">
			<?php echo view_cell('\Core\Whatsapp\Controllers\Whatsapp::widget_content', ["result" => $result]) ?>

			<label class="form-label"><?php _e("Caption")?></label>
			<?php echo view_cell('\Core\Caption\Controllers\Caption::block', ['value' => get_data($result, "caption")]) ?>

			<ul class="text-gray-400 fs-12">
				<li><?php _e("Random message by Spintax")?></li>
				<li><?php _e("Ex: {Hi|Hello|Hola}")?></li>
			</ul>
			</div>
			<?php echo view_cell('\Core\Whatsapp_button_template\Controllers\Whatsapp_button_template::widget_content', ["result" => $result]) ?>
            <?php echo view_cell('\Core\Whatsapp_list_message_template\Controllers\Whatsapp_list_message_template::widget_content', ["result" => $result]) ?>
		</div>

	</div>
	<div class="card-footer">
		<div class="d-flex justify-content-end">
			<a class="btn btn-primary btn-hover-scale actionMultiItem" href="<?php _ec( get_module_url("save") )?>">
				<i class="fal fa-paper-plane"></i> <?php _e("Submit")?>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){
	Core.tagsinput();
});
</script>
	
<?php else: ?>
	
	<div class="text-center py-5">
		<div class="fs-70 text-danger">
			<i class="fad fa-exclamation-triangle"></i>
		</div>
		<h3><?php _e("An Unexpected Error Occurred")?></h3>
		<div class="text-gray-700"><?php _e( $message )?></div>
	</div>

<?php endif ?>