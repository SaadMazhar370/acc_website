<?php

/**
 * Display loop thumbnail
 *
 * @since         v.1.0.0
 * @author        themeum
 * @url https://themeum.com
 *
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-version 4.2.0
 */

defined( 'ABSPATH' ) || exit;

$course_thumb_image_size = apply_filters( 'edumall_course_thumb_image_size', '480x304' );
?>

<div class="course-thumbnail edumall-image">
	<a href="<?php the_permalink(); ?>">
		<?php if ( has_post_thumbnail() ): ?>
			<?php Edumall_Image::the_post_thumbnail( [ 'size' => $course_thumb_image_size ] ); ?>
		<?php else: ?>
			<?php
			echo Edumall_Image::build_img_tag( [
				'src'   => tutor_placeholder_img_src(),
				'width' => 480,
			] )
			?>
		<?php endif; ?>
	</a>
</div>
