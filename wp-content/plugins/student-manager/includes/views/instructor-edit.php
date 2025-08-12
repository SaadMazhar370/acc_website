<?php
/**
 * Aisha Academy Student Management Interface
 * @package TutorLMS
 * @subpackage Edumall
 */

if (!defined('ABSPATH')) exit;

// Enhanced capability check
if (!current_user_can('edit_users')) {
    wp_die(__('You do not have permission to access this page.'));
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if (!current_user_can('edit_user', $user_id)) {
    wp_die(__('No access'));
}

$user = get_userdata($user_id);
if (!$user) {
    wp_die(__('User not found'));
}

// Initialize all fields
$existing_files = get_user_meta($user_id, 'std_files', true) ?: [];
$user_data = [
    'first_name'    => $user->first_name,
    'middle_name'   => get_user_meta($user_id, 'employment_middle_name', true),
    'last_name'     => $user->last_name,
    'email'         => $user->user_email,
    'phone'         => get_user_meta($user_id, 'employment_inst_cellPhone', true),
    'address'       => get_user_meta($user_id, 'employment_inst_address', true),
    'majlis'        => get_user_meta($user_id, 'employment_inst_majlis', true),
    'aims_code'     => get_user_meta($user_id, 'employment_inst_aimsCode', true),
    'home_phone'    => get_user_meta($user_id, 'employment_inst_homePhone', true),
    'admin_remarks' => get_user_meta($user_id, 'admin_remarkes', true),
    'status'        => get_user_meta($user_id, 'dashboard_access', true)
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aisha_student_nonce'])) {
    if (!wp_verify_nonce($_POST['aisha_student_nonce'], 'update_aisha_student')) {
        wp_die('Security check failed.');
    }

    // Update user basic info
    $user_args = [
        'ID'         => $user_id,
        'first_name' => sanitize_text_field($_POST['employment_first_name'] ?? ''),
        'last_name'  => sanitize_text_field($_POST['employment_last_name'] ?? ''),
        'user_email' => sanitize_email($_POST['employment_email'] ?? '')
    ];
    
    // Only update password if provided
    if (!empty($_POST['password'])) {
        $user_args['user_pass'] = $_POST['password'];
    }
    
    wp_update_user($user_args);

    // Update meta fields
    $meta_fields = [
        'employment_middle_name'    => sanitize_text_field($_POST['employment_middle_name'] ?? ''),
        'employment_inst_cellPhone' => sanitize_text_field($_POST['employment_inst_cellPhone'] ?? ''),
        'employment_inst_address'   => sanitize_text_field($_POST['employment_inst_address'] ?? ''),
        'employment_inst_majlis'    => sanitize_text_field($_POST['employment_inst_majlis'] ?? ''),
        'employment_inst_aimsCode'  => sanitize_text_field($_POST['employment_inst_aimsCode'] ?? ''),
        'employment_inst_homePhone' => sanitize_text_field($_POST['employment_inst_homePhone'] ?? ''),
        'admin_remarkes'            => sanitize_textarea_field($_POST['admin_remarkes'] ?? ''),
        'dashboard_access'          => sanitize_text_field($_POST['dashboard_access'] ?? 'active')
    ];

    foreach ($meta_fields as $key => $value) {
        update_user_meta($user_id, $key, $value);
    }

    // Handle file uploads
    if (!empty($_FILES['std_files']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $uploaded_files = [];

        foreach ($_FILES['std_files']['name'] as $key => $name) {
            if ($_FILES['std_files']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name'     => sanitize_file_name($name),
                    'type'     => $_FILES['std_files']['type'][$key],
                    'tmp_name' => $_FILES['std_files']['tmp_name'][$key],
                    'error'    => $_FILES['std_files']['error'][$key],
                    'size'     => $_FILES['std_files']['size'][$key]
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

    // Update success notice
    add_action('admin_notices', function() {
        echo '<div class="notice notice-success is-dismissible"><p>Student updated successfully!</p></div>';
    });
    
    // Refresh the data after update
    $user = get_userdata($user_id);
    $user_data = [
        'first_name'    => $user->first_name,
        'middle_name'   => get_user_meta($user_id, 'employment_middle_name', true),
        'last_name'     => $user->last_name,
        'email'         => $user->user_email,
        'phone'         => get_user_meta($user_id, 'employment_inst_cellPhone', true),
        'address'       => get_user_meta($user_id, 'employment_inst_address', true),
        'majlis'        => get_user_meta($user_id, 'employment_inst_majlis', true),
        'aims_code'     => get_user_meta($user_id, 'employment_inst_aimsCode', true),
        'home_phone'    => get_user_meta($user_id, 'employment_inst_homePhone', true),
        'admin_remarks' => get_user_meta($user_id, 'admin_remarkes', true),
        'status'        => get_user_meta($user_id, 'dashboard_access', true)
    ];
}
?>

<div class="wrap tutor-admin-wrap">
    <form method="post" class="tutor-form" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo esc_attr($user_id); ?>">
        <?php wp_nonce_field('update_aisha_student', 'aisha_student_nonce'); ?>

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Personal Info</h3></div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-4">
                    <label>First Name</label>
                    <input name="employment_first_name" value="<?php echo esc_attr($user_data['first_name']); ?>" class="tutor-form-control" required>
                </div>
                <div class="tutor-col-4">
                    <label>Middle Name</label>
                    <input name="employment_middle_name" value="<?php echo esc_attr($user_data['middle_name']); ?>" class="tutor-form-control">
                </div>
                <div class="tutor-col-4">
                    <label>Last Name</label>
                    <input name="employment_last_name" value="<?php echo esc_attr($user_data['last_name']); ?>" class="tutor-form-control" required>
                </div>
                <div class="tutor-col-6">
                    <label>Email</label>
                    <input type="email" name="employment_email" value="<?php echo esc_attr($user_data['email']); ?>" class="tutor-form-control" required>
                </div>
                <div class="tutor-col-6">
                    <label>Phone</label>
                    <input type="tel" name="employment_inst_cellPhone" value="<?php echo esc_attr($user_data['phone']); ?>" class="tutor-form-control">
                </div>
            </div>
        </div>

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Additional Info</h3></div>
            <div class="tutor-card-body tutor-row">
                <div class="tutor-col-3">
                    <label>Address</label>
                    <input name="employment_inst_address" value="<?php echo esc_attr($user_data['address']); ?>" class="tutor-form-control">
                </div>
                <div class="tutor-col-3">
                    <label>Majlis</label>
                    <input name="employment_inst_majlis" value="<?php echo esc_attr($user_data['majlis']); ?>" class="tutor-form-control">
                </div>
                <div class="tutor-col-3">
                    <label>Aims Code</label>
                    <input name="employment_inst_aimsCode" value="<?php echo esc_attr($user_data['aims_code']); ?>" class="tutor-form-control">
                </div>
                <div class="tutor-col-3">
                    <label>Home Number</label>
                    <input name="employment_inst_homePhone" value="<?php echo esc_attr($user_data['home_phone']); ?>" class="tutor-form-control">
                </div>
            </div>
        </div>

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Admin Remarks</h3></div>
            <div class="tutor-card-body">
                <label>Admin Remarks</label>
                <textarea class="tutor-form-control" name="admin_remarkes" rows="3"><?php echo esc_textarea($user_data['admin_remarks']); ?></textarea>
            </div>
        </div>

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Account Status</h3></div>
            <div class="tutor-card-body">
                <label>Status</label>
                <select name="dashboard_access" class="tutor-form-control" required>
                    <option value="active" <?php selected($user_data['status'], 'active'); ?>>Active</option>
                    <option value="inactive" <?php selected($user_data['status'], 'inactive'); ?>>Inactive</option>
                </select>
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
                                    <a href="<?php echo esc_url(add_query_arg(['action' => 'delete_file', 'file_index' => $index, 'user_id' => $user_id, '_wpnonce' => wp_create_nonce('delete_file_' . $index)])); ?>" 
                                       class="tutor-btn tutor-btn-outline-primary tutor-btn-sm tutor-ml-16" 
                                       onclick="return confirm('Are you sure you want to delete this file?');">
                                        Delete
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

        <div class="tutor-card tutor-mb-24">
            <div class="tutor-card-header"><h3>Change Password</h3></div>
            <div class="tutor-card-body">
                <label>New Password (leave blank to keep current)</label>
                <input type="password" name="password" class="tutor-form-control">
            </div>
        </div>

        <div class="tutor-mt-24">
            <button type="submit" class="tutor-btn tutor-btn-primary">Update Student</button>
            <a href="<?php echo esc_url(admin_url('admin.php?page=teacher-management')); ?>" class="tutor-btn tutor-btn-outline-primary tutor-ml-16">Back</a>
        </div>
    </form>
</div>