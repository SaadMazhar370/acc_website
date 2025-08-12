<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redesigned Course Card</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }
        .select-course-btn.selected {
            background-color: #22c55e;
        }
        .course-footer {
            margin-top: 15px;
        }
        .course-price {
            font-size: 16px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<?php
global $post;
$course_id      = get_the_ID();
$course_url     = get_the_permalink($course_id);
$course_title   = get_the_title($course_id);
$excerpt        = wp_trim_words(get_the_excerpt(), 20);
$thumbnail_url  = get_the_post_thumbnail_url($course_id, 'medium');
$placeholder    = 'https://placehold.co/600x400/cbd5e1/475569?text=No+Image&font=inter';

$is_purchasable = tutor_utils()->is_course_purchasable($course_id);
$product_id     = tutor_utils()->get_course_product_id($course_id);
$product        = $product_id ? wc_get_product($product_id) : null;

$price_html = 'Free';
if ($product) {
    if ($product->is_on_sale()) {
        $price_html = '<span class="text-gray-400 line-through mr-1">' . wc_price($product->get_regular_price()) . '</span>';
        $price_html .= wc_price($product->get_sale_price());
    } else {
        $price_html = wc_price($product->get_price());
    }
}

$enroll_url = tutor_utils()->get_course_enroll_link($course_id);

if ($product_id) {
    $add_to_cart_url = apply_filters(
        'tutor_course_add_to_cart_url',
        add_query_arg(
            array(
                'add-to-cart' => $product_id,
                'quantity'   => 1,
            ),
            wc_get_cart_url()
        ),
        $course_id
    );
} else {
    $add_to_cart_url = '';
}

// Category logic with parent, child, grandchild detection

$parent_category = '';
$child_category = '';
$grandchild_category = '';
$course_type = '';
$terms = get_the_terms($course_id, 'course-category');

if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        $term_parent = get_term($term->parent, 'course-category');
        if ($term->parent == 0) {
            $parent_category = esc_html($term->name);
        } elseif ($term_parent && $term_parent->parent == 0) {
            $child_category = esc_html($term->name);
            $parent_category = esc_html($term_parent->name);
        } elseif ($term_parent && $term_parent->parent != 0) {
            $grandchild_category = esc_html($term->name);
            $child_category = esc_html($term_parent->name);
            $grandparent_term = get_term($term_parent->parent, 'course-category');
            $parent_category = esc_html($grandparent_term->name);

            // Set course type if needed
            if (stripos($term->name, 'compulsory') !== false) {
                $course_type = 'compulsory';
            } elseif (stripos($term->name, 'elective') !== false) {
                $course_type = 'elective';
            }
        }
    }
}



?>

<!-- Course Card -->
<div class="bg-white shadow-xl rounded-2xl overflow-hidden max-w-sm transition-transform transform hover:-translate-y-1 hover:shadow-2xl duration-300">
    <!-- Thumbnail -->
    <div class="relative">
        <img src="<?php echo esc_url($thumbnail_url ?: $placeholder); ?>"
             onerror="this.onerror=null;this.src='<?php echo esc_url($placeholder); ?>';"
             alt="<?php echo esc_attr($course_title); ?>"
             class="w-full h-52 object-cover">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>

        <!-- Price -->
        <div class="absolute top-3 left-3 bg-white text-gray-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
            <?php echo $price_html; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="p-5 space-y-3">
        <!-- Category Breadcrumb -->
       <?php if ($parent_category): ?>
    <div class="text-xs text-gray-400 uppercase tracking-wide font-medium">
        <?php echo $parent_category; ?>
        <?php if ($child_category): ?> &rsaquo; <?php echo $child_category; ?><?php endif; ?>
        <?php if ($grandchild_category): ?> &rsaquo; <?php echo $grandchild_category; ?><?php endif; ?>
    </div>
<?php endif; ?>


        <!-- Title -->
        <h3 class="text-base font-bold text-gray-800 leading-tight line-clamp-2 hover:text-[#031f42] transition">
            <a href="<?php echo esc_url($course_url); ?>"><?php echo esc_html($course_title); ?></a>
        </h3>

        <!-- Excerpt -->
        <p class="text-sm text-gray-600 leading-relaxed line-clamp-3">
            <?php echo esc_html($excerpt); ?>
        </p>

        <!-- Footer -->


<div class="flex items-center justify-between pt-4">
    <a href="<?php echo esc_url($course_url); ?>" class="text-sm text-[#031f42] hover:underline font-medium">
        View Details
    </a>

    <?php if ($is_purchasable && $product_id): ?>
   <div class="flex items-center gap-2">
    <input type="checkbox" class="select-course-checkbox"
        id="course-<?php echo esc_attr($product_id); ?>"
        data-product-id="<?php echo esc_attr($product_id); ?>">
    <label for="course-<?php echo esc_attr($product_id); ?>" class="text-sm text-gray-800 font-medium">
        Enroll in this course
    </label>
</div>



      
    <?php else: ?>
        <a href="<?php echo esc_url($enroll_url); ?>" 
           class="bg-[#031f42] text-white text-sm font-medium px-4 py-2 rounded-full hover:opacity-90 transition">
           Enroll Now
        </a>
    <?php endif; ?>
</div>

    </div>
</div>


<div class="text-center mt-6">
    <button id="purchase-selected"
        class="hidden bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-full text-sm font-medium transition">
        Purchase Selected Courses
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.select-course-checkbox');
    const purchaseBtn = document.getElementById('purchase-selected');
    let selectedCourses = JSON.parse(localStorage.getItem('selectedCourses')) || [];

    console.log("✅ JS loaded");
    console.log("✅ Found", checkboxes.length, "checkboxes");
    console.log("✅ Found Purchase Button:", purchaseBtn !== null);

    // Restore checkbox state
    selectedCourses.forEach(id => {
        const checkbox = document.querySelector(`.select-course-checkbox[data-product-id="${id}"]`);
        if (checkbox) {
            checkbox.checked = true;
        }
    });

    toggleButton();

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const productId = this.dataset.productId;
            console.log("🟩 Checkbox toggled for product ID:", productId);

            if (this.checked) {
                if (!selectedCourses.includes(productId)) {
                    selectedCourses.push(productId);
                }
            } else {
                selectedCourses = selectedCourses.filter(id => id !== productId);
            }

            localStorage.setItem('selectedCourses', JSON.stringify(selectedCourses));
            toggleButton();
        });
    });

    purchaseBtn.addEventListener('click', function () {
        const courseQuery = selectedCourses.join(',');
        console.log("🚀 Redirecting with:", courseQuery);
        localStorage.removeItem('selectedCourses');

        window.location.href = '/purchase-selected-courses?selected=' + courseQuery;
    });

    function toggleButton() {
        console.log("📦 Selected Courses:", selectedCourses);
        if (selectedCourses.length > 0) {
            purchaseBtn.classList.remove('hidden');
        } else {
            purchaseBtn.classList.add('hidden');
        }
    }
});
</script>



</body>
</html>