<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="s"><?php esc_html_e('Search for:', 'learts');?></label>
		<input type="text" value="" name="s" id="s" placeholder="<?php esc_html_e('Search&hellip;', 'learts'); ?>"/>
		<input type="hidden" name="post_type" value="<?php echo esc_attr( learts_get_option( 'search_post_type' ) ); ?>"/>
		<button type="submit" id="searchsubmit"><?php esc_html_e('Search', 'learts'); ?></button>
	</div>
</form>
