<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<div class="talent-profile">
	<div class="talent-header">
		<div class="talent-title">
		<h1><?php the_title(); ?></h1>
		<?php $sagAftra = get_field('sag-aftra');
		if ($sagAftra != 'none') {
			echo '<div class="sag-aftra">SAG/AFTRA';
			if ($sagAftra == 'sagaftra_ast') {
				echo '*';
			} 
			echo '</div>';
		}
		?>
		</div>
		<ul class="talent-taxes">
			<?php
			$talentTaxes = array(
				get_the_terms( $post->ID , 'performance-type' ),
				get_the_terms( $post->ID , 'gender' ),
				get_the_terms( $post->ID , 'age_range' ),
			);
			
			foreach ($talentTaxes as $terms) {
				if ($terms) {
					echo '<li>';
						$showTerms = array();
						
					foreach ($terms as $term) {
						$termTax = $term->taxonomy;
						$showTerms[] = $term->name;
					}
					$taxonomy = get_taxonomy($termTax);
						echo '<div class="tax-label">'.$taxonomy->labels->singular_name.'</div>';
						echo '<div class="tax-value">'.implode(', ',$showTerms).'</div>';
					echo '</li>';
				}
			}
			?>
		</ul>
	</div>
	<div class="talent-columns">
		<div class="talent-left">
		<div class="talent-gallery"> 
			<?php
			// Get the gallery field
			$gallery = get_field('talent_image_gallery');

			// Check if the gallery is not empty
			if ($gallery) {
				// Display all images as thumbnails
				echo '<div class="thumbnails">';
				for ($i = 0; $i < count($gallery); $i++) {
					echo '<img src="' . $gallery[$i]['sizes']['medium'] . '" alt="' . $gallery[$i]['alt'] . '" data-large="' . $gallery[$i]['url'] . '" />';
				}
				echo '</div>';
				
				// Display the first image in a large size
				echo '<div class="large-image">';
				echo '<img src="' . $gallery[0]['url'] . '" alt="' . $gallery[0]['alt'] . '" />';
				echo '</div>';

				
			}
			?>
		</div>
		
		</div>

		
		<div class="talent-details">
		  <?php
		  $appSlugs = ['height', 'weight', 'hair', 'eyes'];
		  $clothSlugs = ['shoe', 'shirt', 'pants', 'pant', 'jacket', 'hat', 'dress', 'blouse'];

		  // Use array_filter to only keep fields that return a value
		  $appearanceFields = array_filter(array_combine($appSlugs, array_map('get_field', $appSlugs)));
		  $clothFields = array_filter(array_combine($clothSlugs, array_map('get_field', $clothSlugs)));

		  if ($appearanceFields || $clothFields) {
		  ?>
			<div class="talent-attributes talent-details-section shadow">
			  <?php 
			  if ($appearanceFields) { 
			  ?>
				
				<table class="talent-table">
				  <?php
				  foreach ($appearanceFields as $appSlug => $appFieldValue) {
				  ?>
					<tr class="talent-table-row field-<?php echo $appSlug; ?>">
					  <td class="attribute-label"><?php echo get_field_object($appSlug)['label']; ?></td>
					  <td class="attribute-value"><?php echo $appFieldValue; ?></td>
					</tr>
				  <?php
				  }
				  ?>
				</table>
			  <?php
			  }

			  if ($clothFields) {
			  ?>
				
				<table class="talent-table">
				  <?php
				  foreach ($clothFields as $clothSlug => $clothFieldValue) {
				  ?>
					<tr class="talent-table-row field-<?php echo $clothSlug; ?>">
					  <td class="attribute-label"><?php echo get_field_object($clothSlug)['label']; ?></td>
					  <td class="attribute-value"><?php echo $clothFieldValue; ?></td>
					</tr>
				  <?php
				  }
				  ?>
				</table>
			  <?php
			  }
			  ?>
			</div>
		  <?php
		  }

		  $actor_website = get_field('actor-website');
		  $actor_social_links = get_field('actor-social-links');
		  if ($actor_website || $actor_social_links) {
		  ?>
			<div class="talent-social-links talent-details-section shadow">
			  <h2>External Links</h2>
			  <div class="social-links-wrap">
				<?php
				if ($actor_website) {
				?>
				  <a href="<?php echo esc_url($actor_website); ?>" target="_blank" class="social-link" title="Website">
					<i class="fas fa-globe"></i>
				  </a>
				<?php
				}

				if ($actor_social_links) {
				  while (have_rows('actor-social-links')) {
					the_row();
					$social_network = get_sub_field('choose_social_network');
					$social_url = get_sub_field('actor-social-url');
					$social_icon = '';

					switch ($social_network) {
					  case 'Facebook':
						$social_icon = 'fab fa-facebook-f';
						break;
					  case 'Twitter':
						$social_icon = 'fab fa-twitter';
						break;
					  case 'LinkedIn':
						$social_icon = 'fab fa-linkedin-in';
						break;
					  case 'Reddit':
						$social_icon = 'fab fa-reddit';
						break;
					  case 'Pinterest':
						$social_icon = 'fab fa-pinterest';
						break;
					}

					if ($social_network && $social_url && $social_icon) {
					?>
					  <a href="<?php echo esc_url($social_url); ?>" target="_blank" class="social-link" title="<?php echo $social_network; ?>">
						<i class="<?php echo esc_attr($social_icon); ?>"></i>
					  </a>
					<?php
					}
				  }
				}
				?>
			  </div>
			</div>
		  <?php
		  }
		  ?>
		  
		  <?php
	if( have_rows('actor-videos') || have_rows('actor-voiceover-samples')) { ?>
	<div class="talent-media talent-details-section shadow">
	<?php
		if( have_rows('actor-videos') ) {
			?>
	<div class="talent-videos">
		<?php
			echo '<h2>Videos</h2>';
			echo '<div class="talent-videos-wrapper">';
			$videos = array();
			while( have_rows('actor-videos') ) { the_row();
			$videoUrl = get_sub_field('actor-video-link');
			$videoTitle = get_sub_field('actor-video-title');
			?>
			<div class="talent-video">
				<h3><?php echo $videoTitle; ?></h3>
				<?php topo_display_video_player($videoUrl);?>
			</div>
			<?php
			 }
			 echo '</div>';
		?>
	</div>
	<?php }
		?>
	
	<?php
		if( have_rows('actor-voiceover-samples') ) {
			?>
	<div class="talent-audio-samples">
		<?php
			echo '<h2>Audio Samples</h2>';
			echo '<div class="talent-audio-samples-wrap">';
			while( have_rows('actor-voiceover-samples') ) { the_row();
			$audioURL = get_sub_field('actor-audio-file');
			$audioTitle = get_sub_field('actor-audio-title');
			?>
			<div class="talent-audio">
				<h3><?php echo $audioTitle; ?></h3>
				<?php topo_display_audio_player($audioURL);?>
			</div>
			<?php }
			echo '</div>';
		?>
	</div>
	<?php }
		?>
		
	
</div>
	<?php } ?>

		</div>


	
	
	</div>
	
<?php if (have_rows('resume_builder')) { ?>
<div class="sticky-stopper"></div>
<div class="talent-resume shadow">
	<h1>RESUME</h1>
<?php
$resume_builder = get_field('resume_builder');
if ($resume_builder) {
    foreach ($resume_builder as $section) {
        $layout_name = $section['acf_fc_layout'];
        $layout_name = str_replace('_', ' ', $layout_name);
        $layout_name = ucwords($layout_name);
		if (isset($section['section_row']) && is_array($section['section_row']) && !empty($section['section_row'])) {
        if ($layout_name != 'Custom Section') {
            echo '<h2>' . $layout_name . '</h2>';
        } else {
            echo '<h2>' . $section['section_title'] . '</h2>';
        }
        echo '<table class="resume-table">';
		
			foreach ($section['section_row'] as $row) {
				echo '<tr>';
				$column_count = count($row);
				foreach ($row as $column) {
					$colspan = ($column_count == 2 && $column == end($row)) ? ' colspan="2"' : '';
					echo '<td' . $colspan . '>' . $column . '</td>';
				}
				echo '</tr>';
			}
		}
        echo '</table>';
    }
}
?>


</div>
<?php } ?>
<div class="talent-reachout-button">
<a class="button" href="https://toposwopetalent.com/contact/">Reach Out to Book this Talent</a>
</div>
</div>

<?php endwhile; endif; ?>
<?php get_footer(); ?>