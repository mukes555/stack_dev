<?php if ( !empty($result) ){ ?>
	
	<?php foreach ($result as $key => $value): ?>
		
		<div class="col-md-6 col-sm-12 col-xs-6 mb-6 item" data-id="<?php _e($value->ids)?>">
		    <div class="card d-flex flex-column flex-row-auto card-custom card-custom-danger rounded">
		        <div class="card-header d-block position-relative mh-260">
		        	<i class="fad fa-user-robot fs-90 position-absolute text-white opacity-25 t-15 r-35"></i>
		        	<div class="my-3 mt-5">
		        		<div class="d-flex align-items-center">
		        			<img src="<?php _ec( get_file_url($value->avatar) )?>" class="b-r-12 w-50 h-50 me-3">
		        			<div class="text-over">
		        				<h3 class="text-white text-over"> <?php _e($value->name)?></h3>
		        				<div class="text-white text-over"><?php _e($value->username)?></div>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="d-flex position-relative t-30">
		        		<div class="card-stats p-20 me-2 bg-white rounded w-100">
		        			<div class="text-danger fs-25 mb-3">
		        				<i class="fad fa-heart"></i>
		        			</div>
		        			<div class="fs-25 fw-6 text-gray-700"><?php _ec( $value->count )?></div>
		        			<div class="text-gray-500"><?php _e("Liked")?></div>
		        		</div>
		        	</div>
		        </div>
		        <div class="card-body p-t-90">
		        	<div class="card-status p-20 h-72 d-flex align-items-center">
		        		<?php if ($value->account_status): ?>
		        		<form class="actionForm w-100" method="POST" action="<?php _ec( get_module_url("status/".$value->pid) )?>">
			        		<div class="form-check form-switch form-check-custom form-check-solid form-check-primary d-flex d-flex justify-content-between">
				        		<label class="form-check-label text-gray-600" for="status_<?php _ec( $value->pid )?>">
							        <?php _e("Status")?>
							    </label>
							    <input class="form-check-input auto-submit" name="status" type="checkbox" value="<?php _ec( $value->pid )?>" id="status_<?php _ec( $value->pid )?>" <?php _ec( $value->status>0?"checked":"" ) ?> >
							</div>
		        		</form>
		        		<?php else: ?>
		        		<div class="text-center w-100 h-43 overflow-auto d-flex align-items-center fw-6 text-danger"><?php _e("Please re-login Tinder account again to can start")?> <a class="btn btn-sm btn-light-danger ms-2" href="<?php _e( base_url("tinder_profiles/oauth") )?>"><?php _e("Re-login")?></a></div>
		        		<?php endif ?>
		        	</div>
		        </div>
		        <div class="card-footer d-flex justify-content-end">
                    <a href="<?php _e( get_module_url("popup_logs/".$value->pid) )?>" class="btn btn-light-dark text-center me-2 w-100 actionItem" data-popup="TinderLogsModal"><i class="fal fa-list-alt"></i> <?php _e("Activity Logs")?></a>
		        </div>
		    </div>            
		</div>
	<?php endforeach ?>

<?php }else{ ?>
	<div class="mw-400 container d-flex align-items-center align-self-center h-100 py-5">
	    <div>
	        <div class="text-center px-4">
	            <img class="mw-100 mh-300px" alt="" src="<?php _e( get_theme_url() ) ?>Assets/img/empty2.png">
	        </div>
	    </div>
	</div> 
<?php }?>