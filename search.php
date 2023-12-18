<?php get_header(); ?>
<header class="header">
<h1 class="entry-title" itemprop="name"><?php printf( esc_html__( 'Search Results for: %s', 'tctalent' ), get_search_query() ); ?></h1>
</header>
<?php 
//setup variables for template parts
$template_part_args = array(
	'post' => $post, // Pass the $post variable
);
get_template_part('parts/grid', 'filter', $template_part_args);
?>
<div class="talent-grid">
  <?php while (have_posts()) { the_post(); ?>
  <?php if (has_post_thumbnail()) { ?>
    <div class="talent-grid-item" data-postid="<?php echo get_the_ID(); ?>">
		<div class="talent-card">
			<a href="<?php the_permalink(); ?>">
			  <div class="talent-card-image">
				<?php the_post_thumbnail('medium'); ?>
			  </div>
			  <div class="talent-card-content">
				<?php the_title(); ?>
			  </div>
			</a>
		</div>
    </div>
  <?php } ?>
<?php } ?>
</div>
<?php get_footer(); ?>
<div id="quick-view-wrap-overlay">
  <div id="quick-view-modal">
	<span id="quick-view-close"><i class="fa fa-close"></i></span>
	<div id="quick-view-content"></div>
  </div>
</div>