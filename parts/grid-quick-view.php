<?php
if (!$args['post']) {
	return;
}
?>
<div id="quick-view-wrap-overlay">
	<div class="quick-view-modal">
		<span class="quick-view-close"><i title="Close Quick View" class="fa-regular fa-rectangle-xmark"></i></span>
	  <div class="quick-view-content">
	  </div>
	</div>
</div>
<?php
wp_reset_postdata();
?>