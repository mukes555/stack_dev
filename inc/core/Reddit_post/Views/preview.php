<div class="pv-header mb-3 d-flex align-items-center"><i class="<?php _ec($config['icon'])?> pe-2 fs-20" style="color: <?php _ec($config['color'])?>;"></i> <?php _ec($config['name'])?></div>
<div class="pv-body border rounded">
	<div class="preview-item preview-reddit">
		
		<div class="pvi-header d-flex p-13">
			<div class="symbol symbol-20px me-2">
				<img src="<?php _ec( get_theme_url()."Assets/img/avatar.jpg" )?>" class="align-self-center rounded-circle" alt="">
			</div>
			<div class="d-flex flex-stack">
				<div class="d-flex align-items-center flex-row-fluid flex-wrap">
					<div class="flex-grow-1 me-2 text-over-all">
						<a href="javascript:void(0);" class="text-gray-800 text-hover-primary fs-12 fw-bold"><?php _e("/r/username")?></a>
						<span class="text-gray-700 fs-10"><?php _e("Just now")?></span>
					</div>
				</div>
			</div>

		</div>

		<div class="pvi-body">
			<div class="piv-img w-100">
				<img src="<?php _ec( get_theme_url()."Assets/img/default.jpg" )?>" class="w-100">
			</div>

			<div class="piv-text px-3 p-t-13 p-b-13"></div>
		</div>

		<div class="pvi-footer border-top px-3 py-2">
			<div class="d-flex justify-content-start">
				<div class="d-flex flex-stack">
					<div class="fs-10 me-2">
						<div class="symbol symbol-45px me-2">
							<i class="far fa-comment-alt"></i> <?php _e("0 comments")?>
						</div>
					</div>
					<div class="fs-10 me-2">
						<div class="symbol symbol-45px me-2">
							<i class="far fa-gift"></i> <?php _e("Award")?>
						</div>
					</div>
					<div class="fs-10 me-2">
						<div class="symbol symbol-45px me-2">
							<i class="far fa-share"></i> <?php _e("Share")?>
						</div>
					</div>
					<div class="fs-10 me-2">
						<div class="symbol symbol-45px me-2">
							<i class="fal fa-bookmark"></i> <?php _e("Save")?>
						</div>
					</div>
					<div class="fs-10 me-2">
						<div class="symbol symbol-45px me-2">
							<i class="far fa-ellipsis-h"></i>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>