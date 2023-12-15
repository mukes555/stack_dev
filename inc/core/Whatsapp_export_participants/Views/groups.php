<div class="card b-r-6">
	<div class="card-body p-0">
		<table class="table align-middle mb-0">
			<tbody>
				<?php if ($status == "success") : ?>
					<?php if ($result->status == "success" && !empty($result->data)) : ?>
						<?php foreach ($result->data as $key => $value) : ?>
							
							<?php
							$filtered_arr = array_filter(
								$value->participants,
								function ($obj) use ($account) {
									return isset($obj->admin) && str_replace('@s.whatsapp.net', '', $obj->id) == $account->username;
								}
							);
							?>

							<tr>
								<td class="p-25 border-bottom">
									<div class="fs-14 mb-1 fw-6 text-gray-800"><?php _e($value->name) ?></div>
									<div class="fs-12 mb-1 text-gray-600"><?php _e("Group ID: ") ?><?php _e($value->id) ?></div>
									<div class="fs-10 mb-1 text-white bg-dark d-inline p-l-8 p-r-8 p-t-3 p-b-3 b-r-10"><?php _e(sprintf(__("%s participants"), $value->size)) ?></div>
									<?php if (count($filtered_arr) > 0) : ?>
										<div class="fs-10 mb-1 text-white bg-success d-inline p-l-8 p-r-8 p-t-3 p-b-3 b-r-10"><?php _e('Admin') ?></div>
									<?php endif ?>
								</td>
								<td class="p-25 border-bottom text-end">

									<button class="btn btn-dark btn-sm btn-copy-id" data-clipboard-text="<?php _e($value->id) ?>"><?php _e("Copy Id") ?></button>

									<a href="<?php _e(get_module_url("export_group/{$account->ids}/{$value->id}")) ?>" class="btn btn-dark btn-sm"><?php _e("Download") ?></a>
								</td>
							</tr>
						<?php endforeach ?>
					<?php else : ?>
						<tr>
							<td class="p-20">
								<?php _ec($this->include('Core\Whatsapp\Views\empty'), false); ?>
							</td>
						</tr>
					<?php endif ?>
				<?php else : ?>
					<tr>
						<td class="p-20">
							<?php _ec($this->include('Core\Whatsapp\Views\empty'), false); ?>
						</td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
	</div>
</div>