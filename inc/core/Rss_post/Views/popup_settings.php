<div class="modal fade" id="SettingsRssModal" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
	    	<form class="actionForm" action="<?php _ec( get_module_url("save_settings/".$id) ) ?>" method="POST" data-redirect="<?php _ec( get_module_url() ) ?>">
	      		<div class="modal-header">
			        <h5 class="modal-title"><i class="<?php _ec( $config['icon'] )?>" style="color: <?php _ec( $config['color'] )?>;"></i> <?php _ec("RSS Settings")?></h5>
			         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      	</div>
		      	<div class="modal-body shadow-none">
	        		<div class="mb-4 form-check form-switch form-check-custom form-check-solid d-flex d-flex justify-content-between">
		        		<label class="form-check-label form-label ms-0" for="publish_caption">
					        <?php _e("Publish caption")?>
					   	</label>
					    <input class="form-check-input" type="checkbox" name="publish_caption" id="publish_caption" value="1" <?php _ec( $publish_caption==1?"checked":"" )?> >
					</div>
					<div class="mb-4 form-check form-switch form-check-custom form-check-solid d-flex d-flex justify-content-between">
		        		<label class="form-check-label form-label ms-0" for="publish_without_link">
					        <?php _e("Publish without link")?>
					   	</label>
					    <input class="form-check-input" type="checkbox" name="publish_without_link" id="publish_without_link" value="1" <?php _ec( $publish_without_link==1?"checked":"" )?> >
					</div>
					<div class="mb-3">
			            <label for="frequency" class="form-label"><?php _e("Frequency per post (Ex: 60 - means once/hour)")?></label>
			            <input type="text" class="form-control form-control-solid" id="frequency" name="frequency" value="<?php _ec( $frequency )?>">
			        </div>
			        <div class="mb-3">
			            <label for="refferal_code" class="form-label"><?php _e("Refferal Code")?></label>
			            <input type="text" class="form-control form-control-solid" id="refferal_code" name="refferal_code" value="<?php _ec( $refferal_code )?>">
			        </div>
			        <div class="mb-3">
			            <label for="accept_words" class="form-label"><?php _e("Publish posts which contains these words")?></label>
			            <input type="text" class="form-control form-control-solid" id="accept_words" name="accept_words" value="<?php _ec( $accept_words )?>">
			        </div>
			        <div class="mb-3">
			            <label for="denied_words" class="form-label"><?php _e("Don't publish posts which contains these words")?></label>
			            <input type="text" class="form-control form-control-solid" id="denied_words" name="denied_words" value="<?php _ec( $denied_words )?>">
			        </div>
		      	</div>
		      	<div class="modal-footer d-flex justify-content-between">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e("Close")?></button>
	    			<button type="submit" class="btn btn-primary"><?php _e("Submit")?></button>
	            </div>
            </form>
	    </div>
  	</div>
</div>