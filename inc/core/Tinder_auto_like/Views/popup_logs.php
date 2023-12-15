<div class="modal fade" id="TinderLogsModal" tabindex="-1" role="dialog">
  	<div class="modal-dialog modal-dialog-centered">
	    <div class="modal-content">
      		<div class="modal-header">
		        <h5 class="modal-title"><i class="<?php _ec( $config['icon'] )?>" style="color: <?php _ec( $config['color'] )?>;"></i> <?php _ec("Tinder Activity Logs")?></h5>
		         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      	</div>
	      	<div class="modal-body">
	        	<div class="fm-list row px-2 py-4 ajax-load-scroll m-l-0 m-r-0 align-content-start overflow-auto" style="height: 669px;" data-url="<?php _e( get_module_url("ajax_load_logs/".$pid) )?>" data-scroll="ajax-load-scroll" >
					<div class="fm-empty text-center fs-90 text-muted h-100 d-flex flex-column align-items-center justify-content-center">
						<img class="mh-190 mb-4" alt="" src="<?php _e( get_theme_url() ) ?>Assets/img/empty2.png">
					</div>
				</div>
	      	</div>
	    </div>
  	</div>
</div>
<script type="text/javascript">
	$(function(){
		Core.call_load_scroll();
		Core.ajax_load_scroll();
	});
</script>