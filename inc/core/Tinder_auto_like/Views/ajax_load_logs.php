<?php if ( !empty($result) ){ ?>
	
	<?php foreach ($result as $key => $value): ?>
		
		<div class="d-flex align-items-center m-b-15">
			<div class="w-50 h-50 b-r-6 me-3" style="background-image: url('<?php _e($value->media_id)?>'); background-size: cover;" ></div>
			<div class="">
				<div class="fw-6 text-gray-900"><?php _ec( $value->name )?></div>				
				<div class="text-gray-500"><?php _ec( $value->user_id )?></div>				
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