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

function generate_enrollment_number($program_slug = '', $semester = '') {
    $year = date('y'); // e.g., '25'

    // 1. Semester letter
    $semester_letter = '';
    $clean_semester = strtolower(trim((string)$semester));
    if ($clean_semester === 'fall') {
        $semester_letter = 'F';
    } elseif ($clean_semester === 'winter') {
        $semester_letter = 'W';
    } else {
        $semester_letter = strtoupper(substr($clean_semester, 0, 1));
    }

    // 2. Program letter
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

    // 3. Prefix (e.g., 25FW)
    $prefix = $year . $semester_letter . $program_letter;

    // 4. Dynamic serial key (e.g., admission_serial_25F)
    $admission_serial_key = "admission_serial_{$year}{$program_letter}";

    // ✅ Always increment the serial
    $last = get_option($admission_serial_key, 234) + 1;
    update_option($admission_serial_key, $last);

    // 5. Return full enrollment number
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

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (!current_user_can('edit_user', $user_id)) wp_die(__('No access'));

$user = get_userdata($user_id);
if (!$user) wp_die(__('User not found'));

$existing_files = get_user_meta($user_id, 'std_files', true);
if (empty($existing_files) || !is_array($existing_files)) {
    $existing_files = [];
}

$assigned_instructors = get_user_meta($user_id, 'assigned_instructors', true) ?: [];
$instructors = get_users([
    'meta_key' => '_is_tutor_instructor',
    'fields' => ['ID', 'display_name']
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aisha_student_nonce'])) {
    if (!wp_verify_nonce($_POST['aisha_student_nonce'], 'update_aisha_student')) {
        wp_die('Security check failed.');
    }

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
            $existing_files = $merged_files;
        }
    }

    wp_update_user([
        'ID' => $user_id,
        'first_name' => sanitize_text_field($_POST['first_name'] ?? ''),
        'last_name' => sanitize_text_field($_POST['last_name'] ?? ''),
        'user_email' => sanitize_email($_POST['email'] ?? ''),
    ]);

    $meta_fields = [
        'secondary_email' => sanitize_email($_POST['secondary_email'] ?? ''),
        'phone' => sanitize_text_field($_POST['phone'] ?? ''),
        'date_of_birth' => sanitize_text_field($_POST['dob'] ?? ''),
        'semester' => sanitize_text_field($_POST['semester'] ?? ''),
        'dashboard_access' => sanitize_text_field($_POST['status'] ?? ''),
        'admin_remarkes' => sanitize_textarea_field($_POST['admin_remarkes'] ?? '')
    ];

    foreach ($meta_fields as $key => $value) {
        update_user_meta($user_id, $key, $value);
    }

    $instructors = isset($_POST['instructors']) ? array_map('intval', $_POST['instructors']) : [];
    update_user_meta($user_id, 'assigned_instructors', $instructors);

    if (empty(get_user_meta($user_id, 'enrollment_number', true))) {
        $enrollment_number = generate_enrollment_number(
            sanitize_text_field(get_user_meta($user_id, 'register_for', true)),
            sanitize_text_field($_POST['semester'] ?? '')
        );
        update_user_meta($user_id, 'enrollment_number', $enrollment_number);
    }

    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Student updated successfully!</p></div>';
    });
}

$user_data = [
    'first_name' => $user->first_name,
    'last_name' => $user->last_name,
    'email' => $user->user_email,
    'secondary_email' => get_user_meta($user_id, 'secondary_email', true),
    'phone' => get_user_meta($user_id, 'phone', true),
    'dob' => get_user_meta($user_id, 'date_of_birth', true),
    'register_for' => get_user_meta($user_id, 'register_for', true),
    'semester' => get_user_meta($user_id, 'semester', true),
    'status' => get_user_meta($user_id, 'dashboard_access', true),
    'enrollment_number' => get_user_meta($user_id, 'enrollment_number', true),
    'admin_remarkes' => get_user_meta($user_id, 'admin_remarkes', true)
];
?>

<div class="wrap tutor-admin-wrap">
    <form method="post" class="tutor-form" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
        <?php wp_nonce_field('update_aisha_student', 'aisha_student_nonce'); ?>

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

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Enrollment Info</h3></div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-4">
                    <label>Registered For</label>
                    <input name="register_for" value="<?php echo esc_attr($user_data['register_for']); ?>" class="tutor-form-control" readonly>
                </div>
                <div class="tutor-col-4">
                    <label>Semester</label>
                    <select name="semester" class="tutor-form-control" required>
                        <option value="winter" <?php selected($user_data['semester'], 'winter'); ?>>Winter</option>
                        <option value="fall" <?php selected($user_data['semester'], 'fall'); ?>>fall</option>
                    </select>
                </div>
                <div class="tutor-col-4">
                    <label>Status</label>
                    <select name="status" class="tutor-form-control" required>
                        <option value="active" <?php selected($user_data['status'], 'active'); ?>>Active</option>
                        <option value="inactive" <?php selected($user_data['status'], 'inactive'); ?>>Inactive</option>
                    </select>
                </div>
                <div class="tutor-col-4">
                    <label>Enrollment No.</label>
                    <input name="enrollment_number" value="<?php echo esc_attr($user_data['enrollment_number']); ?>" class="tutor-form-control" readonly>
                </div>
                <div class="tutor-col-4">
                    <label>Assign Instructors</label>
                    <select name="instructors[]" class="tutor-form-control select2" multiple>
                        <?php foreach ($instructors as $inst): ?>
                            <option value="<?php echo esc_attr($inst->ID); ?>" <?php selected(in_array($inst->ID, $assigned_instructors)); ?>>
                                <?php echo esc_html($inst->display_name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Admin Remarks</h3></div>
            <div class="tutor-card-body">
                <label>Admin Remarks</label>
                <textarea class="tutor-form-control" name="admin_remarkes" rows="3"><?php echo esc_textarea($user_data['admin_remarkes']); ?></textarea>
            </div>
        </div>

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

        <div class="tutor-mt-24">
            <button type="submit" class="tutor-btn tutor-btn-primary">Update Student</button>
            <a href="<?php echo esc_url(admin_url('admin.php?page=aisha-academy-management')); ?>" class="tutor-btn tutor-btn-outline-primary tutor-ml-16">Back</a>
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
});
</script>
