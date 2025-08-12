<?php
/* Template Name: Purchase Selected Courses */

get_header();

if (!empty($_GET['selected'])) {
    $course_ids = explode(',', sanitize_text_field($_GET['selected']));

    if (function_exists('wc_load_cart')) {
        wc_load_cart();
    }

    WC()->cart->empty_cart();

    foreach ($course_ids as $id) {
        WC()->cart->add_to_cart(intval($id));
    }

    wp_redirect(wc_get_checkout_url());
    exit;
}

echo '<div class="text-center py-10 text-lg text-red-600">❌ No courses selected.</div>';

get_footer();
