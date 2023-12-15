<div class="mb-3 wrap-input-emoji">
	<textarea class="form-control input-emoji fw-4" name="<?php _e($name) ?>" placeholder="<?php _e($placeholder) ?>"><?php _ec($value) ?></textarea>
	<ul class="caption-option d-flex overflow-x-auto">
		<li class="count-word px-3 py-2 d-block d-flex align-items-center justify-content-center text-gray-700"><i class="fad fa-text-width"></i><span class="p-l-5"></span></li>
		<li class="get-caption">
			<a href="javascript:void(0);" class="btnGetCaption px-3 py-2 d-block btn btn-active-light text-gray-700" title="<?php _e("Get Caption") ?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-comment-alt-lines p-0"></i></a>
		</li>
		<li class="save-caption">
			<a href="javascript:void(0);" class="btnSaveCaption px-3 py-2 d-block btn btn-active-light text-gray-700" title="<?php _e("Save caption") ?>" data-toggle="tooltip" data-placement="top"><i class="fal fa-save p-0"></i></a>
		</li>
		<li class="spintax-caption">
			<button type="button" class="spintax-caption-btn px-3 py-2 d-block btn btn-active-light text-gray-700" title="<?php _e("Auto spintax") ?>" data-toggle="tooltip" data-placement="top">
				<i class="fal fa-brackets-curly p-0"></i>
			</button>
		</li>
		<?php echo view_cell('\Core\Openai\Controllers\Openai::widget', ["name" => $name]) ?>
		<li>
			<?php echo view_cell('\Core\Shortlink\Controllers\Shortlink::widget') ?>
		</li>
	</ul>
</div>
<script type="text/javascript">
	$(function() {
		Core.select2();

		$(".spintax-caption-btn").click(function() {
			var paragraph = $(".input-emoji").val();


			paragraph = paragraph
				.replaceAll('{с|c}', 'c')
				.replaceAll('{а|a}', 'a')
				.replaceAll('{е|e}', 'e')
				.replaceAll('{р|p}', 'p')
				.replaceAll('{у|y}', 'y')
				.replaceAll('{о|o}', 'o')
				.replaceAll('{С|C}', 'C')
				.replaceAll('{А|A}', 'A')
				.replaceAll('{Е|E}', 'E')
				.replaceAll('{Р|P}', 'P')
				.replaceAll('{О|O}', 'O');


			var regex = /(http|ftp|https):\/\/([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])/g;
			const found = paragraph.match(regex);

			urls = [];
			counter = 0;

			if (found)
				found.forEach(url => {
					counter++;
					urls.push({
						key: `_%@${counter}@%_`,
						value: url
					})

					paragraph = paragraph.replaceAll(url, `_%@${counter}@%_`)
				});

			paragraph = paragraph
				.replaceAll('c', '{с|c}')
				.replaceAll('a', '{а|a}')
				.replaceAll('e', '{е|e}')
				.replaceAll('p', '{р|p}')
				.replaceAll('y', '{у|y}')
				.replaceAll('o', '{о|o}')
				.replaceAll('C', '{С|C}')
				.replaceAll('A', '{А|A}')
				.replaceAll('E', '{Е|E}')
				.replaceAll('P', '{Р|P}')
				.replaceAll('O', '{О|O}');

			urls.forEach(url => {
				paragraph = paragraph.replaceAll(url.key, url.value)
			});

			console.log(paragraph);
			$(".input-emoji").data("emojioneArea").setText(paragraph);
		});

	});
</script>