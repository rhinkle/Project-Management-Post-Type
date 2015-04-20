<!-- Project Loop -->
<li class="project_item item_num-<?php echo $count; ?>">
	<?php do_action('before_image', get_the_ID()); ?>
		<?php echo get_the_post_thumbnail(get_the_ID(),apply_filters('mu_project_thumbnail_size','mu_project_default_thumbnail_size')); ?>
		<div class="caption">
			<div class="text"><?php the_title(); ?><?php do_action( 'caption_after_title', get_the_ID()); ?></div>
		</div>
	<?php do_action( 'after_caption', get_the_ID()); ?>
</li>