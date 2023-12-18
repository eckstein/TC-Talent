<div class="talent-grid-item" >
	<div class="talent-card">
		
		<div class="talent-card-inner">
		  <div class="talent-card-image">
			<?php the_post_thumbnail('medium'); ?>
		  </div>
		  <div class="talent-card-content">
			<h4 class="talent-card-name"><?php the_title(); ?></h4>
			<?php if (topo_get_performance_type($post->ID) != false) { ?>
			<div class="talent-card-perf-type"><?php echo topo_get_performance_type($post->ID); ?></div>
			<?php } ?>
			<ul class="talent-card-taxes">
			<?php if (have_rows('actor-videos')) { ?>
				<li>
				<i title="Video reel(s) available" class="fa-solid fa-video"></i>
				</li>
				<?php } ?>
				<?php if (have_rows('actor-voiceover-samples')) { ?>
				<li>
				<i title="Audio sample(s) available" class="fa-solid fa-microphone"></i>
				</li>
				<?php } ?>
			</ul>
			<div class="talent-card-quick-view-link" data-postid="<?php echo get_the_ID(); ?>">Quick View</div>
		  </div>
		</div>
		<a href="<?php the_permalink(); ?>"></a>
	</div>
</div>