<div class="modal fade" id="addRssAccountsModal" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
	    	<form class="actionForm" action="<?php _ec( get_module_url("do_add_account/".$id) ) ?>" method="POST" data-redirect="<?php _ec( get_module_url() ) ?>">
	      		<div class="modal-header">
			        <h5 class="modal-title"><i class="fad fa-user-plus pe-0" style="color: <?php _ec( $config['color'] )?>;"></i> <?php _ec("Add accounts")?></h5>
			         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      	</div>
		      	<div class="modal-body shadow-none">
		        	<?php echo view_cell('\Core\Account_manager\Controllers\Account_manager::widget_multi_select', [ 'accounts' => $current_accounts ]) ?>
		      	</div>

		      	<div class="modal-footer d-flex justify-content-end">
		      		<button type="submit" class="btn btn-primary"><?php _e("Add")?></button>
		      	</div>
	      	</form>
	    </div>
  	</div>
</div>