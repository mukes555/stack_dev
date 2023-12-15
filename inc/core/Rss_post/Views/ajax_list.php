<?php if ( !empty($result) ){ ?>
	
	<?php foreach ($result as $key => $value): ?>

		<?php
			$post_succeed = 0;
			$post_failed = 0;

			if($value->data){
				$data = json_decode($value->data);
				if(isset($data->post_succeed)){
					$post_succeed = $data->post_succeed;
				}

				if(isset($data->post_failed)){
					$post_failed = $data->post_failed;
				}
			}
		?>
		
		<div class="col-md-4 col-sm-12 col-xs-4 mb-4 rss-item" data-id="<?php _e($value->ids)?>">
		    <div class="card d-flex flex-column flex-row-auto card-custom card-custom-warning rounded">
		        <div class="card-header d-block position-relative mh-260">
		        	<div class="d-flex justify-content-end mt-4">
		        		<div class="me-3">
		        			<a href="<?php _ec( get_module_url("popup_rss_settings/".$value->ids) )?>" class="text-gray-100 opacity-75-hover actionItem" data-popup="SettingsRssModal">
		        				<i class="fad fa-cog"></i> <?php _e("Settings")?>
		        			</a>
		        		</div>
		        		<div class="me-3">
		        			<a href="<?php _ec( get_module_url("schedules/".$value->ids) )?>" class="text-gray-100 opacity-75-hover">
		        				<i class="fad fa-history"></i> <?php _e("History")?>
		        			</a>
		        		</div>
		        		<div class="dropdown dropdown-hide-arrow" data-dropdown-spacing="40">
	                        <a href="javascript:void(0);" class="dropdown-toggle d-block position-relative btn p-0 text-gray-100 opacity-75-hover" data-toggle="dropdown" aria-expanded="true">
	                            <i class="fad fa-th-large fs-14"></i>
	                        </a>
	                        <div class="dropdown-menu dropdown-menu-right p-20">
	                        	<a href="<?php _ec( $value->url )?>" class="dropdown-item" target="_blank" data-remove="rss-item" data-id="<?php _ec( $value->ids )?>"><i class="fad fa-external-link-square-alt text-warning"></i> <?php _e("Open RSS url")?></a>
	                        	<a href="<?php _ec( get_module_url("delete") )?>" class="dropdown-item actionItem" data-confirm="<?php _e("Are you sure to delete this items?")?>" data-remove="rss-item" data-id="<?php _ec( $value->ids )?>"><i class="fad fa-trash-alt text-danger"></i> <?php _e("Delete")?></a>
	                        </div>
	                    </div>
		        	</div>
		        	<div class="my-3 mt-5">
		        		<h3 class="text-gray-100 text-over"><i class="fad fa-rss-square"></i> <?php _e($value->name)?></h3>
		        		<div class="text-gray-100 text-over"><?php _e($value->description)?></div>
		        	</div>
		        	<div class="d-flex position-relative t-30">
		        		<div class="card-stats p-20 me-2 bg-white rounded border">
		        			<div class="text-success fs-20 mb-3">
		        				<i class="fad fa-check"></i>
		        			</div>
		        			<div class="fs-25 fw-6 text-gray-700"><?php _ec( $post_succeed )?></div>
		        			<div class="text-gray-500"><?php _e("Successed")?></div>
		        		</div>
		        		<div class="card-stats p-20 ms-2 bg-white rounded border">
		        			<div class="text-danger fs-20 mb-3">
		        				<i class="fad fa-exclamation-triangle"></i>
		        			</div>
		        			<div class="fs-25 fw-6 text-gray-700"><?php _ec( $post_failed )?></div>
		        			<div class="text-gray-500"><?php _e("Failed")?></div>
		        		</div>
		        	</div>
		        </div>
		        <div class="card-body p-t-90">
		        	<div class="card-status p-20 bg-light-danger1">
			        		<div class="form-check form-switch form-check-custom form-check-solid form-check-warning d-flex d-flex justify-content-between">
			        		<label class="form-check-label text-gray-600" for="rss_status">
						        <?php _e("Status")?>
						    </label>
						    <input class="form-check-input btnActiveRss" type="checkbox" value="1" id="rss_status" <?php _ec( $value->status==1?"checked":"" ) ?> >
						</div>
		        	</div>
		        </div>

		        <div class="card-footer d-flex justify-content-between">
		        	<div class="symbol-group symbol-hover">
		        		<?php if ( !empty($value->accounts) ): ?>

		        			<?php foreach ($value->accounts as $account): ?>
		        				<div class="symbol symbol-35px symbol-circle" title="<?php _e( ( ucfirst( str_replace("_", " ", $account->social_network) ) ) . ": " . $account->name)?>" data-toggle="tooltip" data-placement="top">
									<img alt="<?php _e($account->name)?>" src="<?php _e( get_file_url($account->avatar) )?>">
								</div>
		        			<?php endforeach ?>

		        			<?php if ($value->left_accounts > 0): ?>
		        				<div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip">
									<span class="symbol-label bg-light fw-bold">+<?php _ec( $value->left_accounts )?></span>
								</div>
		        			<?php endif ?>

		        		<?php else: ?>
		        			<span class="text-gray-500">
		        				<i class="fad fa-user-circle"></i> <?php _e("No profiles added")?>
		        			</span>
		        		<?php endif ?>
					</div>
					<a 
					href="<?php _ec( get_module_url("popup_add_account/".$value->ids) )?>" 
					class="btn btn-sm btn-light-primary rounded-circle w-40 h-40 p-t-10 px-0 text-center actionItem" 
					data-popup="addRssAccountsModal" 
					title="<?php _e("Add accounts")?>" 
					data-toggle="tooltip" 
					data-placement="top"
					><i class="fad fa-user-plus pe-0"></i></a>
		        </div>

		    </div>            
		</div>

	<?php endforeach ?>

<?php }else{ ?>
	<div class="mw-400 container d-flex align-items-center align-self-center h-100 py-5">
	    <div>
	        <div class="text-center px-4">
	            <img class="mw-100 mh-300px" alt="" src="<?php _e( get_theme_url() ) ?>Assets/img/empty.png">
	        </div>
	    </div>
	</div> 
<?php }?>
