<div class="modal fade" id="addRssModal" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
      		<div class="modal-header">
		        <h5 class="modal-title"><i class="<?php _ec( $config['icon'] )?>" style="color: <?php _ec( $config['color'] )?>;"></i> <?php _ec("Add New RSS")?></h5>
		         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      	</div>
	      	<div class="modal-body">
	        	<form class="actionForm" action="<?php _ec( get_module_url("save_rss") ) ?>" method="POST" data-redirect="<?php _ec( get_module_url() ) ?>">
	        		<div class="mb-3">
	                    <input type="text" class="form-control form-control-solid" id="rss_url" name="rss_url" placeholder="<?php _e("Enter rss url")?>" value="">
	                </div>
	                <div class="d-flex justify-content-end">
		        		<button type="submit" class="btn btn-primary"><?php _e("Add")?></button>
	                </div>
	        	</form>
	      	</div>
	    </div>
  	</div>
</div>