<?php
/**
 *	Template Name: Resources
*/
?>

<?php get_header(); ?>

<div id="core" class="columns">
	<div style="margin-bottom: 20px">
		<h2>Downloadable Resources</h2>
		<?php query_posts( 'post_type=peace_resource&post_status=publish&posts_per_page=10&order=DESC'); ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php
			$attachment_link = '#';
			$args = array(
				'post_type' => 'attachment',
				'numberposts' => null,
				'post_status' => null,
				'post_parent' => $post->ID
			);
			$attachments = get_posts($args);
			if ($attachments) {
				foreach ($attachments as $attachment) {
					$attachment_link = wp_get_attachment_link($attachment->ID, false, false, false, 'Download (Adobe PDF)');
				}
			}
		?>
   			<div class="post-single">
   				<h3 class='download-item'><?php the_title(); ?></h3>
   				<?php echo $attachment_link; ?>
   				<div class="post-content">
					<?php the_content(__('Read more'));?>
				</div>
   			</div><!--.post-single-->
   		<?php endwhile; endif; ?>
	</div>
</div> <!-- / core -->

<?php get_footer(); ?>