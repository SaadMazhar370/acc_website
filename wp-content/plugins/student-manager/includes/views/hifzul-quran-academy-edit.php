<?php
/**
 * Aisha Academy Student Management Interface
 * @package TutorLMS
 * @subpackage Edumall
 */

if (!defined('ABSPATH')) exit;

if (!current_user_can('edit_users')) {
    wp_die(__('You do not have permission to access this page.'));
}

/**
 * Generates a unique enrollment number
 * Format: YYPLSNNN (Year + Program Letter + Semester Letter + Sequence Number)
 * Example: 25PS235 (2025, Program P, Summer S, #235)
 */

function generate_enrollment_number($program_slug = '', $semester = '') {
    $year = date('y'); // Last 2 digits of year (e.g., 25)

    // 1. Get semester letter first (now comes first in sequence)
    $semester_letter = '';
    $clean_semester = strtolower(trim((string)$semester));
    
    if ($clean_semester === 'Fall') {
        $semester_letter = 'F';
    } 
    elseif ($clean_semester === 'winter') {
        $semester_letter = 'W';
    }

    // 2. Then get program letter
    $program_letter = '';
    if (!empty($program_slug)) {
        $terms = get_terms([
            'taxonomy' => 'course-category',
            'slug' => $program_slug,
            'hide_empty' => false
        ]);
        if (!empty($terms) && !is_wp_error($terms)) {
            $program_letter = strtoupper(substr(trim($terms[0]->name), 0, 1));
        }
    }

    // 3. Combine prefix parts in new order: year + semester + program
    $prefix = $year . $semester_letter . $program_letter;

    // 4. Serial number logic (unchanged)
    $admission_serial_key = "admission_serial_{$year}{$program_letter}";
    
    if (empty(get_option("{$admission_serial_key}_generated"))) {
        $last = get_option($admission_serial_key, 234) + 1; // start from 235
        update_option($admission_serial_key, $last);
        update_option("{$admission_serial_key}_generated", true);
    } else {
        $last = get_option($admission_serial_key);
    }

    // Debug output (temporary)
    error_log("Generated enrollment number parts:");
    error_log("Year: $year");
    error_log("Semester: $semester_letter");
    error_log("Program: $program_letter");
    error_log("Serial: $last");
    error_log("Final: " . $prefix . $last);

    return $prefix . $last;
}

if (!function_exists('tutor_utils')) {
    if (!file_exists(WP_PLUGIN_DIR . '/tutor/tutor.php')) {
        wp_die(__('Tutor LMS plugin is not installed.'));
    } elseif (!is_plugin_active('tutor/tutor.php')) {
        wp_die(__('Tutor LMS plugin is not active.'));
    }
    include_once WP_PLUGIN_DIR . '/tutor/classes/Utils.php';
}

// Initialize variables
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (!current_user_can('edit_user', $user_id)) wp_die(__('No access'));

$user = get_userdata($user_id);
if (!$user) wp_die(__('User not found'));

// Get existing files
$existing_files = get_user_meta($user_id, 'std_files', true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aisha_student_nonce'])) {
    if (!wp_verify_nonce($_POST['aisha_student_nonce'], 'update_aisha_student')) {
        wp_die('Security check failed.');
    }

    // Handle file uploads
    if (!empty($_FILES['std_files']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded_files = [];
        
        foreach ($_FILES['std_files']['name'] as $key => $name) {
            if ($_FILES['std_files']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => sanitize_file_name($name),
                    'type' => $_FILES['std_files']['type'][$key],
                    'tmp_name' => $_FILES['std_files']['tmp_name'][$key],
                    'error' => $_FILES['std_files']['error'][$key],
                    'size' => $_FILES['std_files']['size'][$key]
                ];
                
                $upload = wp_handle_upload($file, ['test_form' => false]);
                if (!isset($upload['error']) && isset($upload['url'])) {
                    $uploaded_files[] = esc_url_raw($upload['url']);
                }
            }
        }
        
        if (!empty($uploaded_files)) {
            $merged_files = array_merge($existing_files, $uploaded_files);
            update_user_meta($user_id, 'std_files', $merged_files);
            $existing_files = $merged_files; // Update for display
        }
    }

    // Update user data
    wp_update_user([
        'ID'         => $user_id,
        'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
        'last_name'  => sanitize_text_field($_POST['last_name'] ?? ''),
        'user_email' => sanitize_email($_POST['email'] ?? ''),
    ]);

    // Update user meta
    $meta_fields = [
        'secondary_email' => sanitize_email($_POST['secondary_email'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'date_of_birth' => sanitize_text_field($_POST['dob'] ?? ''),
        'register_for' => sanitize_text_field($_POST['register_for'] ?? ''),
        'course_category' => sanitize_text_field($_POST['course_category'] ?? ''),
        'sub_category' => sanitize_text_field($_POST['sub_category'] ?? ''),
        'semester' => sanitize_text_field($_POST['semester'] ?? ''),
        'dashboard_access' => sanitize_text_field($_POST['status'] ?? ''),
        'admin_remarkes' => sanitize_textarea_field($_POST['admin_remarkes'] ?? '')
    ];

    foreach ($meta_fields as $key => $value) {
        update_user_meta($user_id, $key, $value);
    }

    // Generate enrollment number if not exists (fixed version)
    if (empty(get_user_meta($user_id, 'enrollment_number', true))) {
        $enrollment_number = generate_enrollment_number(
            sanitize_text_field($_POST['register_for'] ?? ''),
            sanitize_text_field($_POST['semester'] ?? '')
        );
        update_user_meta($user_id, 'enrollment_number', $enrollment_number);
    }

    // Update instructors
    $instructors = isset($_POST['instructors']) ? array_map('intval', $_POST['instructors']) : [];
    update_user_meta($user_id, 'assigned_instructors', $instructors);

    // Enroll/Unenroll Courses
    if (isset($_POST['courses']) && is_array($_POST['courses'])) {
        $courses = array_map('intval', $_POST['courses']);
        $existing = tutor_utils()->get_enrolled_courses_ids_by_user($user_id);

        // Unenroll from removed courses
        foreach ($existing as $course_id) {
            if (!in_array($course_id, $courses)) {
                tutor_utils()->cancel_course_enrol($course_id, $user_id);
            }
        }

        // Enroll in new courses
        foreach ($courses as $course_id) {
            $post = get_post($course_id);
            if ($post && in_array($post->post_type, ['courses', 'tutor_courses']) && $post->post_status === 'publish') {
                if (!tutor_utils()->is_enrolled($course_id, $user_id)) {
                    $result = tutor_utils()->do_enroll([
                        'user_id' => $user_id,
                        'course_id' => $course_id,
                        'status' => 'completed',
                        'post_type' => $post->post_type,
                        'enrollment_time' => current_time('mysql'),
                        'enrolled_by' => get_current_user_id()
                    ]);
                    
                    if (!is_wp_error($result) && $result) {
                        $meta_courses = get_user_meta($user_id, '_aisha_enrolled_courses', true) ?: [];
                        if (!in_array($course_id, $meta_courses)) {
                            $meta_courses[] = $course_id;
                            update_user_meta($user_id, '_aisha_enrolled_courses', $meta_courses);
                        }
                    }
                }
            }
        }
    }

    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Student updated successfully!</p></div>';
    });
}

// Get user data for form
$user_data = [
    'first_name' => $user->first_name,
    'last_name' => $user->last_name,
    'email' => $user->user_email,
    'secondary_email' => get_user_meta($user_id, 'secondary_email', true),
    'phone' => get_user_meta($user_id, 'phone', true),
    'dob' => get_user_meta($user_id, 'date_of_birth', true),
    'register_for' => get_user_meta($user_id, 'register_for', true),
    'course_category' => get_user_meta($user_id, 'course_category', true),
    'sub_category' => get_user_meta($user_id, 'sub_category', true),
    'semester' => get_user_meta($user_id, 'semester', true),
    'status' => get_user_meta($user_id, 'dashboard_access', true),
    'enrollment_number' => get_user_meta($user_id, 'enrollment_number', true),
    'admin_remarkes' => get_user_meta($user_id, 'admin_remarkes', true)
];

$assigned_courses = tutor_utils()->get_enrolled_courses_ids_by_user($user_id);
$assigned_instructors = get_user_meta($user_id, 'assigned_instructors', true) ?: [];

// Get categories and courses
$program_categories = get_terms(['taxonomy' => 'course-category', 'parent' => 0, 'hide_empty' => false]);
$all_courses = tutor_utils()->get_courses();
$course_titles = [];
$courses_by_category = [];
$category_tree = [];

foreach ($all_courses as $course) {
    $title = get_the_title($course->ID);
    $course_titles[$course->ID] = $title;
    $terms = wp_get_post_terms($course->ID, 'course-category');
    foreach ($terms as $term) {
        $courses_by_category[$term->term_id][] = $course->ID;
    }
}

function build_category_tree($parent = 0) {
    $terms = get_terms([
        'taxonomy' => 'course-category',
        'parent' => $parent,
        'hide_empty' => false
    ]);
    $output = [];
    foreach ($terms as $term) {
        $output[] = [
            'term_id' => $term->term_id,
            'slug' => $term->slug,
            'name' => $term->name,
            'children' => build_category_tree($term->term_id)
        ];
    }
    return $output;
}

$category_tree = build_category_tree();
$instructors = get_users([
    'meta_key' => '_is_tutor_instructor',
    'fields' => ['ID', 'display_name']
]);

// Enqueue scripts
wp_enqueue_script('select2');
wp_enqueue_style('select2');
wp_register_script('aisha-admin', false);
wp_enqueue_script('aisha-admin');

wp_localize_script('aisha-admin', 'aisha_admin', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'current_program' => $user_data['register_for'],
    'current_category' => $user_data['course_category'],
    'current_subcategory' => $user_data['sub_category'],
    'current_courses' => array_map('strval', $assigned_courses),
    'category_data' => $category_tree,
    'courses_data' => $courses_by_category,
    'course_titles' => $course_titles,
]);
?>

<div class="wrap tutor-admin-wrap">
    <form method="post" class="tutor-form" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
        <?php wp_nonce_field('update_aisha_student', 'aisha_student_nonce'); ?>

        <!-- PERSONAL INFO -->
        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Personal Info</h3></div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-6">
                    <label>First Name</label>
                    <input name="first_name" value="<?php echo esc_attr($user_data['first_name']); ?>" class="tutor-form-control" required>
                </div>
                <div class="tutor-col-6">
                    <label>Last Name</label>
                    <input name="last_name" value="<?php echo esc_attr($user_data['last_name']); ?>" class="tutor-form-control" required>
                </div>
                <div class="tutor-col-6">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo esc_attr($user_data['email']); ?>" class="tutor-form-control" required>
                </div>
                <div class="tutor-col-6">
                    <label>Secondary Email</label>
                    <input type="email" name="secondary_email" value="<?php echo esc_attr($user_data['secondary_email']); ?>" class="tutor-form-control">
                </div>
                <div class="tutor-col-6">
                    <label>Phone</label>
                    <input type="tel" name="phone" value="<?php echo esc_attr($user_data['phone']); ?>" class="tutor-form-control">
                </div>
                <div class="tutor-col-6">
                    <label>DOB</label>
                    <input type="date" name="dob" value="<?php echo esc_attr($user_data['dob']); ?>" class="tutor-form-control">
                </div>
            </div>
        </div>

        <!-- ACADEMIC INFO -->
        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Academic Info</h3></div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-3">
                    <label>Program Category</label>
                    <select name="register_for" id="program_category" class="tutor-form-control" required>
                        <option value="">Select Program</option>
                        <?php foreach ($program_categories as $cat): ?>
                            <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($user_data['register_for'], $cat->slug); ?>>
                                <?php echo esc_html($cat->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="tutor-col-3">
                    <label>Course Category</label>
                    <select name="course_category" id="course_category" class="tutor-form-control">
                        <option value="">Select Course Category</option>
                    </select>
                </div>

                <div class="tutor-col-3">
                    <label>Sub Category</label>
                    <select name="sub_category" id="sub_category" class="tutor-form-control">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>
                <div class="tutor-col-3">
                    <label>Courses</label>
                    <select name="courses[]" id="courses_dropdown" class="tutor-form-control" multiple>
                        <!-- Loaded via JS -->
                    </select>
                </div>
            </div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-3">
                    <label>Semester</label>
                    <select name="semester" class="tutor-form-control" required>
                        <option value="winter" <?php selected($user_data['semester'], 'winter'); ?>>Winter</option>
                        <option value="fall" <?php selected($user_data['semester'], 'fall'); ?>>Fall</option>
                    </select>
                </div>
                <div class="tutor-col-3">
                    <label>Instructors</label>
                    <select name="instructors[]" class="tutor-form-control select2" multiple>
                        <?php foreach ($instructors as $inst): ?>
                            <option value="<?php echo esc_attr($inst->ID); ?>" <?php selected(in_array($inst->ID, $assigned_instructors)); ?>>
                                <?php echo esc_html($inst->display_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="tutor-col-3">
                    <label>Enrollment No.</label>
                    <input name="enrollment_number" value="<?php echo esc_attr($user_data['enrollment_number']); ?>" class="tutor-form-control" readonly>
                </div>
                <div class="tutor-col-3">
                    <label>Status</label>
                    <select name="status" class="tutor-form-control" required>
                        <option value="active" <?php selected($user_data['status'], 'active'); ?>>Active</option>
                        <option value="inactive" <?php selected($user_data['status'], 'inactive'); ?>>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ADMIN REMARKS -->
        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Admin Remarks</h3></div>
            <div class="tutor-card-body">
                <label>Admin Remarks</label>
                <textarea class="tutor-form-control" name="admin_remarkes" rows="3"><?php echo esc_textarea($user_data['admin_remarkes']); ?></textarea>
            </div>
        </div>

        <!-- FILE UPLOADS -->
        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Upload Documents</h3></div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-6">
                    <label>Upload Files (Images or PDFs)</label>
                    <input type="file" name="std_files[]" multiple class="tutor-form-control" accept=".jpg,.jpeg,.png,.pdf">
                    <p class="description">Max file size: <?php echo esc_html(size_format(wp_max_upload_size())); ?></p>
                </div>
                <div class="tutor-col-6">
                    <label>Previously Uploaded Files</label>
                    <?php if (!empty($existing_files)) : ?>
                        <ul style="list-style-type: none; padding-left: 0;">
                            <?php foreach ($existing_files as $index => $file_url) : ?>
                                <li style="margin-bottom: 5px;">
                                    <a href="<?php echo esc_url($file_url); ?>" target="_blank" style="text-decoration: none;">
                                        <i class="dashicons dashicons-media-default"></i> <?php echo esc_html(basename($file_url)); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>No files uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="tutor-mt-24">
            <button type="submit" class="tutor-btn tutor-btn-primary">Update Student</button>
            <a href="<?php echo esc_url(admin_url('admin.php?page=hifzul-quran-management')); ?>" class="tutor-btn tutor-btn-outline-primary tutor-ml-16">Back</a>
        </div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.select2').select2({
        placeholder: "Select instructors",
        allowClear: true,
        width: '100%'
    });

    const data = window.aisha_admin || {};
    const $programSelect = $('#program_category');
    const $courseCategorySelect = $('#course_category');
    const $subCategorySelect = $('#sub_category');
    const $courseDropdown = $('#courses_dropdown');

    const programOptions = [];
    const courseOptions = [];
    const subCategoryOptions = [];

    function buildCategoryTree(categories, level = 0, parentName = '') {
        categories.forEach(cat => {
            if (!cat?.term_id) return;

            const option = {
                slug: cat.slug,
                name: level > 0 ? `${parentName} → ${cat.name}` : cat.name,
                term_id: cat.term_id,
                parent_id: cat.parent || null
            };

            [programOptions, courseOptions, subCategoryOptions][level]?.push(option);

            if (cat.children?.length) {
                buildCategoryTree(cat.children, level + 1, level === 0 ? cat.name : parentName);
            }
        });
    }

    if (Array.isArray(data.category_data) && data.category_data.length) {
        buildCategoryTree(data.category_data);
    }

    function initDropdown($el, options, currentValue, placeholder = 'Select option') {
        $el.empty().append(`<option value="">${placeholder}</option>`);
        options.forEach(opt => {
            $el.append(new Option(opt.name, opt.slug, false, opt.slug === currentValue));
        });
    }

    initDropdown($programSelect, programOptions, data.current_program, 'Select Program');
    initDropdown($courseCategorySelect, courseOptions, data.current_category, 'Select Course Category');
    initDropdown($subCategorySelect, subCategoryOptions, data.current_subcategory, 'Select Sub Category');

    function populateCourses(categoryId) {
        $courseDropdown.empty();

        if (categoryId && data.courses_data?.[categoryId]) {
            const selectedCourses = Array.isArray(data.current_courses) ? data.current_courses : [];
            
            data.courses_data[categoryId].forEach(courseId => {
                const title = data.course_titles?.[courseId] || `Course ${courseId}`;
                const $option = new Option(title, courseId, false, selectedCourses.includes(String(courseId)));
                $courseDropdown.append($option);
            });
        }

        $courseDropdown.select2({
            width: '100%',
            placeholder: "Select courses",
            closeOnSelect: false
        });
    }

    function initializeForm() {
        if (data.current_program) {
            $programSelect.val(data.current_program).trigger('change');
        }

        if (data.current_category) {
            $courseCategorySelect.val(data.current_category).trigger('change');
        }

        if (data.current_subcategory) {
            $subCategorySelect.val(data.current_subcategory).trigger('change');
            const initialSub = subCategoryOptions.find(opt => opt.slug === data.current_subcategory);
            if (initialSub) {
                populateCourses(initialSub.term_id);
            }
        } else if (data.current_category) {
            const fallbackCat = courseOptions.find(opt => opt.slug === data.current_category);
            if (fallbackCat) {
                populateCourses(fallbackCat.term_id);
            }
        }
    }

    $subCategorySelect.on('change', function() {
        const selectedCategory = subCategoryOptions.find(opt => opt.slug === $(this).val());
        if (selectedCategory) {
            populateCourses(selectedCategory.term_id);
        }
    });

    $courseCategorySelect.on('change', function() {
        $subCategorySelect.val('').trigger('change');
    });

    initializeForm();
});
</script>