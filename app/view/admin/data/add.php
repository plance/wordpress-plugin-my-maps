<div class="wrap">
	<h2>
		<?php echo $page_title ?>
		<a href="?page=<?php echo $_page ?>" class="page-title-action"><?php echo __('List maps', 'plance') ?></a>
	</h2>
	<form method="post" action="<?php echo $form_actiion ?>" class="form-msm" id="form-msm">
		<?php wp_nonce_field('form-msm'); ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php echo $Validate -> getLabel('msm_title') ?></th>
				<td>
					<input name="msm_title" type="text" class="f-text" value="<?php echo esc_attr($Validate -> getData('msm_title')) ?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><?php echo $Validate -> getLabel('msm_address') ?></th>
				<td>
					<input name="msm_address" type="text" class="f-text" id="msm_address" value="<?php echo esc_attr($Validate -> getData('msm_address')) ?>">
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<div id="msm" style="width: 800px; height: 600px"></div>
</div>