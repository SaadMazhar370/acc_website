<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue child scripts
 */
if ( ! function_exists( 'edumall_child_enqueue_scripts' ) ) {
	function edumall_child_enqueue_scripts() {
		wp_enqueue_style( 'edumall-child-style', get_stylesheet_directory_uri() . '/style.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'edumall_child_enqueue_scripts', 15 );

//custom student registeration popup//


// Force Tutor LMS to regenerate its cache
add_action('init', function() {
    if (current_user_can('manage_options')) {
        delete_option('tutor_dashboard_pages');
        tutor_utils()->flush_rewrite_rules();
    }
});



add_action('init', function() {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, '_is_tutor_instructor', 1);
    }
});



add_action( 'user_register', 'save_custom_registration_fields', 10, 1 );
function save_custom_registration_fields( $user_id ) {
    // Save phone
    if ( isset( $_POST['phone'] ) ) {
        update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
    }

    // Save date of birth
    if ( isset( $_POST['date_of_birth'] ) || isset( $_POST['dob'] ) ) {
        $dob = isset( $_POST['date_of_birth'] ) ? $_POST['date_of_birth'] : $_POST['dob'];
        update_user_meta( $user_id, 'date_of_birth', sanitize_text_field( $dob ) );
    }

    // Save 'register_for'
    if ( isset( $_POST['register_for'] ) ) {
        update_user_meta( $user_id, 'register_for', sanitize_text_field( $_POST['register_for'] ) );
    }

    // Save IP address
    $ip = isset( $_POST['ip_address'] ) ? $_POST['ip_address'] : $_SERVER['REMOTE_ADDR'];
    update_user_meta( $user_id, 'registration_ip', sanitize_text_field( $ip ) );

    // Save status
    update_user_meta( $user_id, 'dashboard_access', 'inactive' );

    // Optional form type
    $form_type = isset( $_POST['form_type'] ) ? sanitize_text_field( $_POST['form_type'] ) : 'default';
    update_user_meta( $user_id, 'form_type', $form_type );


if (!isset($_POST['register_for']) || trim($_POST['register_for']) === '') {
    update_user_meta($user_id, 'is_inst_form', 'yes');
}

    // 🔐 Email verification token
    $token = bin2hex(random_bytes(16));
    update_user_meta( $user_id, 'email_verification_token', $token );
    update_user_meta( $user_id, 'email_verified', 'no' );

    // 📩 Send verification email
    $user = get_userdata( $user_id );
    $email = $user->user_email;
    $name = $user->display_name;

    $verify_link = add_query_arg([
        'verify_email' => '1',
        'user_id' => $user_id,
        'token' => $token
    ], site_url('/verify-email/'));

    $subject = 'Please Verify Your Email Address';

    $message = '
    <html>
    <head>
        <style>
            .button {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                color: #fff;
                background-color: #28a745;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 15px;
            }
        </style>
    </head>
    <body>
        <p>Hi, <strong>' . esc_html($name) . '</strong>,</p>

        <p>Thank you for signing up with us at <strong>Aisha Academy</strong>.</p>

        <p>We’re almost there! To proceed further, we need to verify your email address. Please click the button below to verify your account:</p>

        <p><a class="button" href="' . esc_url($verify_link) . '">Verify Email</a></p>

        <p>If the button doesn’t work, copy and paste the following URL into your browser:</p>
        <p><a href="' . esc_url($verify_link) . '">' . esc_html($verify_link) . '</a></p>

        <br><hr>
        <p>For more information, contact us at:</p>
        <p><strong>Email:</strong> admissions@aishaacademy.ca</p>
        <p><strong>Phone:</strong> +1 (905) – 303 – 2139</p>
    </body>
    </html>';

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    wp_mail($email, $subject, $message, $headers);
}



add_action('init', 'handle_email_verification');
function handle_email_verification() {
    if (isset($_GET['verify_email'], $_GET['user_id'], $_GET['token'])) {
        $user_id = intval($_GET['user_id']);
        $token = sanitize_text_field($_GET['token']);

        $saved_token = get_user_meta($user_id, 'email_verification_token', true);

        if ($token === $saved_token) {
            update_user_meta($user_id, 'email_verified', 'yes');
            delete_user_meta($user_id, 'email_verification_token');

            wp_redirect(home_url('/email-verified-success/'));
            exit;
        } else {
            wp_redirect(home_url('/email-verification-failed/'));
            exit;
        }
    }
}



// Show the Dashboard Access field in user profile
add_action( 'show_user_profile', 'custom_dashboard_access_field' );
add_action( 'edit_user_profile', 'custom_dashboard_access_field' );

function custom_dashboard_access_field( $user ) {
    if ( ! current_user_can( 'edit_users', $user->ID ) ) {
        return;
    }

    $access = get_user_meta( $user->ID, 'dashboard_access', true );
    ?>
    <h3>Dashboard Access</h3>
    <table class="form-table">
        <tr>
            <th><label for="dashboard_access">Access</label></th>
            <td>
                <select name="dashboard_access" id="dashboard_access">
                    <option value="active" <?php selected( $access, 'active' ); ?>>Active</option>
                    <option value="inactive" <?php selected( $access, 'inactive' ); ?>>Inactive</option>
                </select>
                <p class="description">Select "Inactive" to block this user from accessing the dashboard.</p>
            </td>
        </tr>
    </table>
    <?php
}


add_action( 'personal_options_update', 'save_custom_dashboard_access_field' );
add_action( 'edit_user_profile_update', 'save_custom_dashboard_access_field' );

function save_custom_dashboard_access_field( $user_id ) {
    if ( current_user_can( 'edit_user', $user_id ) && isset( $_POST['dashboard_access'] ) ) {
        update_user_meta( $user_id, 'dashboard_access', sanitize_text_field( $_POST['dashboard_access'] ) );
    }
}


add_action('wp_ajax_delete_student_user', 'handle_delete_student_user');

function handle_delete_student_user() {
    if (!current_user_can('delete_users')) {
        wp_send_json_error('You do not have permission to delete users.');
    }

    check_ajax_referer('delete_student_nonce', 'security');

    $user_id = intval($_POST['user_id']);
    if (!$user_id || get_userdata($user_id)->roles[0] === 'administrator') {
        wp_send_json_error('Invalid user or cannot delete admin.');
    }

    require_once ABSPATH . 'wp-admin/includes/user.php';
    wp_delete_user($user_id);

    wp_send_json_success('User deleted successfully.');
}

// Ajax handler

add_action('wp_ajax_submit_aisha_profile', 'handle_aisha_profile_ajax');

function handle_aisha_profile_ajax() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'You must be logged in.']);
    }

    if (!isset($_POST['aisha_profile_nonce']) || !wp_verify_nonce($_POST['aisha_profile_nonce'], 'submit_aisha_profile_ajax')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $user_id = get_current_user_id();
    $is_save_only = ($_POST['save_only'] ?? '') === '1';

    // Save combined phone
    if (isset($_POST['std_phone_code'], $_POST['std_phone_number'])) {
        $combined_phone = sanitize_text_field($_POST['std_phone_code']) . '|' . sanitize_text_field($_POST['std_phone_number']);
        update_user_meta($user_id, 'phone', $combined_phone);
    }

    // Update user core fields
    $user_data = ['ID' => $user_id];
    if (!empty($_POST['std_email'])) {
        $email = sanitize_email($_POST['std_email']);
        if (!is_email($email)) {
            wp_send_json_error(['message' => 'Invalid email address.']);
        }
        $user_data['user_email'] = $email;
    }
    if (!empty($_POST['std_first_name'])) {
        $user_data['first_name'] = sanitize_text_field($_POST['std_first_name']);
    }
    if (!empty($_POST['std_last_name'])) {
        $user_data['last_name'] = sanitize_text_field($_POST['std_last_name']);
    }

    $update_result = wp_update_user($user_data);
    if (is_wp_error($update_result)) {
        wp_send_json_error(['message' => 'Error updating profile: ' . $update_result->get_error_message()]);
    }

    // Save meta fields
    $meta_fields = [
        'std_phone_code', 'std_phone', 'std_dob', 'std_country', 'std_aims_code',
        'std_halqa', 'std_address', 'std_postal_code', 'std_city',
        'std_waqifa', 'std_waqif_number', 'std_returning', 'std_student_number',
        'std_emergency1_name','std_emergency1_phone','std_emergency1_email','std_emergency1_relation',
        'std_emergency2_name','std_emergency2_phone','std_emergency2_email','std_emergency2_relation',
        'std_current_program','std_awards','std_quran','std_current_office','std_current_office_details',
        'std_past_office','std_past_office_details','std_eng_sp','std_eng_wr','std_eng_rd',
        'std_urdu_sp','std_urdu_wr','std_urdu_rd','std_other_sp','std_other_wr','std_other_rd','std_other_languages'
    ];

    foreach ($meta_fields as $field) {
        if (isset($_POST[$field])) {
            $value = (strpos($field, '_email') !== false)
                ? sanitize_email($_POST[$field])
                : sanitize_text_field($_POST[$field]);
            update_user_meta($user_id, "aisha_{$field}", $value);
        }
    }

    // Save education history
    $education_history = [];
    $edu_index = 0;
    while (isset($_POST["std_education_institution_$edu_index"])) {
        $education_history[] = [
            'institution' => sanitize_text_field($_POST["std_education_institution_$edu_index"]),
            'from_date'   => sanitize_text_field($_POST["std_education_from_$edu_index"]),
            'to_date'     => sanitize_text_field($_POST["std_education_to_$edu_index"]),
            'degree'      => sanitize_text_field($_POST["std_education_degree_$edu_index"]),
        ];
        $edu_index++;
    }
    update_user_meta($user_id, 'aisha_std_education_history', maybe_serialize($education_history));

    // Save employment history
    $employment_history = [];
    $emp_index = 0;
    while (isset($_POST["std_employment_position_$emp_index"])) {
        $employment_history[] = [
            'position'     => sanitize_text_field($_POST["std_employment_position_$emp_index"]),
            'from_date'    => sanitize_text_field($_POST["std_employment_from_$emp_index"]),
            'to_date'      => sanitize_text_field($_POST["std_employment_to_$emp_index"]),
            'organization' => sanitize_text_field($_POST["std_employment_organization_$emp_index"]),
        ];
        $emp_index++;
    }
    update_user_meta($user_id, 'aisha_std_employment_history', maybe_serialize($employment_history));

    // Handle file uploads
    $upload_fields = ['std_government_id', 'std_education_certs', 'std_experience_letter', 'std_payment_receipt'];
    foreach ($upload_fields as $field_name) {
        if (isset($_FILES[$field_name]) && !empty($_FILES[$field_name]['name'])) {
            $upload = wp_handle_upload($_FILES[$field_name], ['test_form' => false]);
            if (!isset($upload['error']) && isset($upload['url'])) {
                update_user_meta($user_id, "aisha_{$field_name}", esc_url_raw($upload['url']));
            }
        }
    }

    // Save checkbox fields
    if (isset($_POST['admission_form_complete'])) {
        update_user_meta($user_id, 'aisha_admission_form_complete', 'yes');
        update_user_meta($user_id, 'aisha_form_submitted', 'yes');
        update_user_meta($user_id, 'is_profile', 'yes');
        generate_admission_pdf_for_user($user_id);

    }

    update_user_meta($user_id, 'aisha_std_last_saved', current_time('mysql'));

    // Final response
  if ($is_save_only) {
    update_user_meta($user_id, 'aisha_std_form_status', 'draft');

    wp_send_json_success([
        'message' => 'Progress saved successfully! You can return later to complete your application.',
        'status'  => 'draft',
    ]);
} else {
    update_user_meta($user_id, 'aisha_std_form_status', 'completed');

    // ✅ Send confirmation email
    if (function_exists('send_confirmation_emails')) {
        send_confirmation_emails($user_id);
    }

    // ✅ Generate admission PDF
    if (function_exists('generate_admission_pdf_for_user')) {
        generate_admission_pdf_for_user($user_id);
    }

    wp_send_json_success([
        'message' => 'Application submitted successfully!',
        'status'  => 'completed',
    ]);
}

  
  
  
  
  
  
}



// Register the AJAX handler for logged-in users
add_action('wp_ajax_update_is_profile', 'handle_update_is_profile');

function handle_update_is_profile() {
    // Check nonce
    if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce($_POST['_ajax_nonce'], 'submit_aisha_profile_ajax')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }

    // Check user login
    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => 'User not logged in']);
    }

    // Update user meta to mark profile status
    update_user_meta($user_id, 'is_profile_updated', 'yes');

    // Return success
    wp_send_json_success(['message' => 'Profile updated successfully']);
}




$form_submitted = isset($_POST['form_submitted']) && $_POST['form_submitted'] == '1';

if ($form_submitted && $form_complete) {
    $redirect_url = $is_update ? site_url('/dashboard/') : site_url('/profile-complete-successfullly/');

    wp_send_json_success([
        'status' => 'completed',
        'message' => 'Form submitted successfully.',
        'redirect' => $redirect_url
    ]);
}


add_action('wp_ajax_remove_user_document', 'handle_remove_user_document');
function handle_remove_user_document() {
    check_ajax_referer('aisha_admission_form', '_ajax_nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error('Unauthorized');
    }

    $user_id = get_current_user_id();
    $meta_key = sanitize_text_field($_POST['meta_key'] ?? '');

    if (!empty($meta_key)) {
        delete_user_meta($user_id, $meta_key);
        wp_send_json_success();
    }
    
    wp_send_json_error([
    'message' => 'Meta key missing'
]);


}


function send_confirmation_emails($user_id) {
    // Ensure permissions
    if (!current_user_can('edit_user', $user_id)) {
        error_log("Permission denied for user $user_id");
        return false;
    }

    // Load wp_mail
    if (!function_exists('wp_mail')) {
        require_once ABSPATH . WPINC . '/pluggable.php';
    }

    // Get user data
    $user = get_userdata($user_id);
    if (!$user) {
        error_log("Invalid user ID $user_id");
        return false;
    }

    // Extract name, email, and phone
    $first_name = $user->first_name ?: get_user_meta($user_id, 'aisha_std_first_name', true);
    $last_name  = $user->last_name  ?: get_user_meta($user_id, 'aisha_std_last_name', true);
    $email      = $user->user_email;
    $phone      = get_user_meta($user_id, 'phone', true);

    // Format phone
    if (strpos($phone, '|') !== false) {
        list($code, $number) = explode('|', $phone);
        $phone_display = "+$code $number";
    } else {
        $phone_display = $phone;
    }

    $subject = 'Aisha Academy – Admission Form Received';

    // Email headers
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    // Start email template with dynamic contact details
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admission Form Received</title>
    </head>
    <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; color: #333333; background-color: #f5f5f5;">
        <table width="100%" cellspacing="0" cellpadding="0" style="background-color: #f5f5f5;">
            <tr>
                <td align="center" valign="top">
                    <table width="600" cellspacing="0" cellpadding="0" style="margin: 30px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <tr>
                            <td style="padding: 30px 40px 20px; text-align: center; border-bottom: 1px solid #eeeeee;">
                                <h1 style="margin: 0; color: #2c3e50; font-size: 24px;">Aisha Academy</h1>
                                <p style="margin: 5px 0 0; color: #7f8c8d; font-size: 16px;">Admissions Team</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 30px 40px;">
                                <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6;">Dear Applicant,</p>
                                <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.6;"><?php echo esc_html($first_name); ?>-<?php echo esc_html($last_name); ?></p>
                                <p style="margin: 0 0 20px;">Thank you for submitting your admission application to Aisha Academy. We have successfully received your form and supporting documents.</p>
                                <h2 style="color: #2c3e50; font-size: 20px; margin: 25px 0 15px;">What Happens Next?</h2>
                                <!-- Steps -->
                                <table width="100%">
                                    <tr>
                                        <td width="30" valign="top"><div style="background-color: #3498db; color: white; width: 30px; height: 30px; border-radius: 50%; text-align: center; line-height: 30px;">1</div></td>
                                        <td style="padding: 0 0 15px 15px;"><strong>Application Review</strong><br>Our team will review your application within 5–7 business days.</td>
                                    </tr>
                                    <tr>
                                        <td width="30" valign="top"><div style="background-color: #3498db; color: white; width: 30px; height: 30px; border-radius: 50%; text-align: center; line-height: 30px;">2</div></td>
                                        <td style="padding: 0 0 15px 15px;"><strong>Additional Information</strong><br>We’ll contact you if more info is needed.</td>
                                    </tr>
                                    <tr>
                                        <td width="30" valign="top"><div style="background-color: #3498db; color: white; width: 30px; height: 30px; border-radius: 50%; text-align: center; line-height: 30px;">3</div></td>
                                        <td style="padding: 0 0 0 15px;"><strong>Final Decision</strong><br>We’ll notify you within 10–14 business days.</td>
                                    </tr>
                                </table>

                                <div style="background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 15px; margin: 25px 0;">
                                    <p style="margin: 0; font-size: 15px; line-height: 1.6; font-style: italic;">
                                        Please note that during peak admission periods, processing times may be slightly longer. We appreciate your patience.
                                    </p>
                                </div>

                                <h2 style="color: #2c3e50; font-size: 20px; margin: 25px 0 15px;">Your Contact Information</h2>
                                <p style="margin: 0 0 15px;">If we need to reach you during the review process, we will contact you at:</p>

                                <!-- Contact Info -->
                                <table width="100%" cellspacing="0" cellpadding="15" style="background-color: #f8f9fa; border-radius: 6px; margin: 0 0 25px;">
                                    <tr>
                                        <td>
                                            <p style="margin: 0 0 10px;">
                                                <strong>Email:</strong> <span style="color: #3498db;"><?php echo esc_html($email); ?></span>
                                            </p>
                                            <p style="margin: 0;">
                                                <strong>Phone:</strong> <?php echo esc_html($phone_display); ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="margin: 0 0 20px;">If your contact information changes, please reply to this email to let us know.</p>
                                <p style="margin: 0 0 20px;">Thank you for choosing Aisha Academy. We look forward to welcoming you!</p>
                                <p style="margin: 25px 0 0;">Best regards,</p>
                                <p style="margin: 5px 0 0;"><strong>Aisha Academy Admissions Team</strong></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 40px; background-color: #2c3e50; color: #ffffff; text-align: center; border-radius: 0 0 8px 8px;">
                                <p style="margin: 0 0 10px; font-size: 14px;">
                                    Have questions? Email us at <a href="mailto:admissions@aishaacademy.edu" style="color: #3498db; text-decoration: none;">admissions@aishaacademy.edu</a>                                </p>
                                <p style="margin: 0 0 10px; font-size: 14px;">
                                    Call Us <a href="tel:+19053032139" style="color: #3498db; text-decoration: none;">+1 (905) – 303 – 2139</a>                                </p>
                                <p style="margin: 0; font-size: 12px;">
                                    © <?php echo date('Y'); ?> Aisha Academy. All rights reserved.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    $message = ob_get_clean();

    // Send email
    $sent = wp_mail($email, $subject, $message, $headers);

    if (!$sent) {
        error_log("❌ Failed to send confirmation email to $email for user $user_id");
    }

    return $sent;
}

use Dompdf\Dompdf;
use Dompdf\Options;
function generate_admission_pdf_for_user($user_id) {
    require_once get_stylesheet_directory() . '/lib/dompdf/autoload.inc.php';

    $user = get_userdata($user_id);
    if (!$user) return false;

    $meta = [
        'first_name'               => $user->first_name ?: get_user_meta($user_id, 'aisha_std_first_name', true),
        'last_name'                => $user->last_name  ?: get_user_meta($user_id, 'aisha_std_last_name', true),
        'email'                    => $user->user_email,
        'dob'                      => get_user_meta($user_id, 'date_of_birth', true),
        'residency_country'        => get_user_meta($user_id, 'aisha_std_country', true),
        'aims_code'                => get_user_meta($user_id, 'aisha_std_aims_code', true),
        'halqa'                    => get_user_meta($user_id, 'aisha_std_halqa', true),
        'address'                  => get_user_meta($user_id, 'aisha_std_address', true),
        'postal_code'              => get_user_meta($user_id, 'aisha_std_postal_code', true),
        'city_province'            => get_user_meta($user_id, 'aisha_std_city', true),
        'waqifa'                   => get_user_meta($user_id, 'aisha_std_waqifa', true),
        'waqf_number'              => get_user_meta($user_id, 'aisha_std_waqif_number', true),
        'returning_student'        => get_user_meta($user_id, 'aisha_std_returning', true),
        'student_number'           => get_user_meta($user_id, 'aisha_std_student_number', true),
        
        'emergency_contact1_name'           => get_user_meta($user_id, 'aisha_std_emergency1_name', true),
        'emergency_contact1_relationship'   => get_user_meta($user_id, 'aisha_std_emergency1_relation', true),
        'emergency_contact1_email'          => get_user_meta($user_id, 'aisha_std_emergency1_email', true),
        'emergency_contact1_phone'          => get_user_meta($user_id, 'aisha_std_emergency1_phone', true),

        'emergency_contact2_name'           => get_user_meta($user_id, 'aisha_std_emergency2_name', true),
        'emergency_contact2_relationship'   => get_user_meta($user_id, 'aisha_std_emergency2_relation', true),
        'emergency_contact2_email'          => get_user_meta($user_id, 'aisha_std_emergency2_email', true),
        'emergency_contact2_phone'          => get_user_meta($user_id, 'aisha_std_emergency2_phone', true),


        'english_spoken'           => get_user_meta($user_id, 'aisha_std_eng_sp', true),
        'english_written'   => get_user_meta($user_id, 'aisha_std_eng_wr', true),
        'english_reading'          => get_user_meta($user_id, 'aisha_std_eng_rd', true),

        'urdu_spoken'           => get_user_meta($user_id, 'aisha_std_urdu_sp', true),
        'urdu_written'   => get_user_meta($user_id, 'aisha_std_urdu_wr', true),
        'urdu_reading'          => get_user_meta($user_id, 'aisha_std_urdu_rd', true),

        'other_lang_spoken'           => get_user_meta($user_id, 'aisha_std_other_sp', true),
        'other_lang_written'   => get_user_meta($user_id, 'aisha_std_other_wr', true),
        'other_lang_reading'          => get_user_meta($user_id, 'aisha_std_other_rd', true),



        'any_other_languages'          => get_user_meta($user_id, 'aisha_std_other_languages', true),

        
        'currently_enrolled'       => get_user_meta($user_id, 'aisha_std_current_program', true),
        'awards_scholarships'      => get_user_meta($user_id, 'aisha_std_awards', true),
        'quran_recitation'         => get_user_meta($user_id, 'aisha_std_quran', true),
        'current_office_bearer'    => get_user_meta($user_id, 'aisha_std_current_office', true),
        'current_office_details'   => get_user_meta($user_id, 'aisha_std_current_office_details', true),
        'past_office_bearer'       => get_user_meta($user_id, 'aisha_std_past_office', true),
        'past_office_details'      => get_user_meta($user_id, 'aisha_std_past_office_details', true),
        'government_id'            => get_user_meta($user_id, 'aisha_std_government_id', true),
        'educational_certificates' => get_user_meta($user_id, 'aisha_std_education_certs', true),
        'experience_letter'        => get_user_meta($user_id, 'aisha_std_experience_letter', true),
        'fee_receipt'              => get_user_meta($user_id, 'aisha_std_payment_receipt', true),
    ];

    $phone = get_user_meta($user_id, 'phone', true);
    $meta['country_code'] = '';
    $meta['phone_number'] = $phone;
    if (strpos($phone, '|') !== false) {
        list($meta['country_code'], $meta['phone_number']) = explode('|', $phone);
    }

    $educationHistory = maybe_unserialize(get_user_meta($user_id, 'aisha_std_education_history', true)) ?: [];
    $employmentHistory = maybe_unserialize(get_user_meta($user_id, 'aisha_std_employment_history', true));

    // Load template
    $template_path = plugin_dir_path(__FILE__) . 'includes/admission_form/Acc_form.html';
    if (!file_exists($template_path)) return false;
    $html = file_get_contents($template_path);

    // Simple placeholders
    foreach ($meta as $key => $value) {
        $html = str_replace('{{' . $key . '}}', esc_html($value), $html);
    }

    // Conditional Waqf
    $waqifa_text = ($meta['waqifa'] === 'yes') ? "Yes (Number: {$meta['waqf_number']})" : "No";
    $html = preg_replace('/\{\{#if waqifa\}\}(.*?)\{\{else\}\}(.*?)\{\{\/if\}\}/s', $waqifa_text, $html);

    // Education History
       // Education History
    $edu_rows = '';
    foreach ($educationHistory as $edu) {
        $edu_rows .= '<tr>
            <td>' . esc_html($edu['institution'] ?? '') . '</td>
            <td>' . esc_html($edu['from_date'] ?? '') . '</td>
            <td>' . esc_html($edu['to_date'] ?? '') . '</td>
            <td>' . esc_html($edu['degree'] ?? '') . '</td>
        </tr>';
    }
    $html = preg_replace('/\{\{#each educationHistory\}\}(.*?)\{\{\/each\}\}/s', $edu_rows ?: '<tr><td colspan="4">No education history</td></tr>', $html);
    // Employment History
$emp_rows = '';
foreach ($employmentHistory as $emp) {
    $emp_rows .= '<tr>
        <td>' . esc_html($emp['position'] ?? '') . '</td>
        <td>' . esc_html($emp['from_date'] ?? '') . '</td>
        <td>' . esc_html($emp['to_date'] ?? '') . '</td>
        <td>' . esc_html($emp['organization'] ?? '') . '</td>
    </tr>';
}

$html = preg_replace('/\{\{#each employmentHistory\}\}(.*?)\{\{\/each\}\}/s', $emp_rows ?: '<tr><td colspan="4">No employment history</td></tr>', $html);


    // Save PDF
    $filename = "{$user_id}_{$meta['first_name']}_{$meta['last_name']}.pdf";
    $upload_dir = wp_upload_dir();
    $pdf_dir = $upload_dir['basedir'] . '/aisha-pdfs/';
    if (!file_exists($pdf_dir)) mkdir($pdf_dir, 0755, true);
    
    $file_path = $pdf_dir . $filename;

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    file_put_contents($file_path, $dompdf->output());
    update_user_meta($user_id, 'aisha_admission_pdf_path', $file_path);
    update_user_meta($user_id, 'is_admission_form_pdf', 'yes');
    update_user_meta($user_id, 'aisha_admission_pdf_generated_at', current_time('mysql'));

    return $file_path;
}



// 1. Load dashicons & font awesome (optional)
// Enqueue dashicons and font-awesome
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('dashicons');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
});

// Add menu item to Tutor LMS dashboard
add_filter('tutor_dashboard/nav_items', function ($items) {
        $icon_url = get_stylesheet_directory_uri() . '/images/admission-icon.png';
        // var_dump($icon_url);exit;

    $items['admission_form'] = array(
        'title' => __('Application Form', 'tutor'),
        'icon_html'  => '<img src="' . esc_url($icon_url) . '" style="width:20px;height:20px;" alt="Icon" />',
        'url'   => tutor_utils()->get_tutor_dashboard_page_permalink('admission_form'),
        'order' => 40
    );
    return $items;
});

// Load the content from custom PHP file
add_action('tutor_dashboard/load_admission_form', function () {
    $file = get_stylesheet_directory() . '/tutor/dashboard/admission_form.php';

    if (file_exists($file)) {
        include_once $file;
    } else {
        echo '<p>❌ File not found: ' . esc_html($file) . '</p>';
    }
});



// Enqueue icons

// 1. REGISTER THE CUSTOM DASHBOARD PAGE


// Add a custom menu item for students


// Add "Select Course" tab to Tutor LMS dashboard
add_filter('tutor_dashboard/nav_items', function ($items) {
    $items['select_course'] = [
        'title'     => __('Select Course', 'tutor'),
        'icon_html' => '<span class="dashicons dashicons-welcome-learn-more"></span>',
        'url'       => tutor_utils()->get_tutor_dashboard_page_permalink('select_course'),
        'order'     => 3,
        'position'  => 'middle'
    ];
    return $items;
});


add_action('tutor_dashboard/load_select_course', function () {
    $file = get_stylesheet_directory() . '/tutor/dashboard/select_course.php';

    if (file_exists($file)) {
        include $file;
    } else {
        echo '<div class="tutor-dashboard-content-inner">';
        echo '<h3>⚠️ Page Not Found</h3>';
        echo '<p>Expected file not found:</p>';
        echo '<code>' . esc_html($file) . '</code>';
        echo '</div>';
    }
});


// Prevent 404 on "select_course" route
add_action('template_redirect', function () {
    global $wp_query;

    if (function_exists('tutor_utils') && tutor_utils()->is_dashboard_page()) {
        $route = tutor_utils()->get_current_dashboard_page();

        if ($route === 'select_course' && $wp_query->is_404()) {
            $wp_query->is_404 = false;
            status_header(200);
        }
    }
});











add_action('tutor_dashboard_content_admission_form', 'custom_load_admission_form_content');

function custom_load_admission_form_content() {
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $form_type = get_user_meta($user_id, 'form_type', true);
    $is_inst_form = get_user_meta($user_id, 'is_inst_form', true);

    $student_form_submitted = get_user_meta($user_id, 'aisha_form_submitted', true);
    $instructor_form_submitted = get_user_meta($user_id, 'is_emp_form_submitted', true);

    echo '<div class="tutor-dashboard-content">';

    if ($form_type === 'student_form') {
        if ($student_form_submitted === 'yes') {
            echo '<div style="padding: 20px; background: #e6ffed; color: #065f46; border: 1px solid #b7eb8f; font-size: 16px; font-weight: 500; border-radius: 5px;">
            🎉 You have already submitted the admission form. Thank you!
            </div>';
        } else {
            echo '<h2>Student Admission Form</h2>';
            echo do_shortcode('[student_profile_form]');
        }
    } elseif ($is_inst_form === 'yes') {
        if ($instructor_form_submitted === 'yes') {
            echo '<div style="padding: 20px; background: #e6ffed; color: #065f46; border: 1px solid #b7eb8f; font-size: 16px; font-weight: 500; border-radius: 5px;">
            🎉 You have already submitted the instructor application. Thank you!
            </div>';
        } else {
            echo '<h2>Instructor Application Form</h2>';
            echo do_shortcode('[instructor_career_form]');
        }
    } else {
        echo '<p style="color: red;">Unable to determine your registration type. Please contact support.</p>';
    }

    echo '</div>';
}



add_shortcode('admission_form', 'render_admission_form_shortcode');

function render_admission_form_shortcode() {
    if (!is_user_logged_in()) {
        return '<p>Please <a href="' . wp_login_url() . '">log in</a> to access the admission form.</p>';
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    $form_type = get_user_meta($user_id, 'form_type', true);
    $is_inst_form = get_user_meta($user_id, 'is_inst_form', true);

    $student_form_submitted = get_user_meta($user_id, 'aisha_form_submitted', true);
    $instructor_form_submitted = get_user_meta($user_id, 'is_emp_form_submitted', true);

    ob_start();

    echo '<div class="tutor-dashboard-content">';

    // Student flow
    if ($form_type === 'student_form') {
        if ($student_form_submitted === 'yes') {
            ?>
            <div id="submitted-message" style="padding: 15px; background-color: #e6ffed; border: 1px solid #b7eb8f; border-radius: 5px; color: #065f46;">
                🎉 You have already submitted the admission form.
                <br><br>
                <button id="edit-admission-form" style="background: #065f46; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">
                    ✏️ Edit Admission Form
                </button>
            </div>

            <div id="edit-form-container" style="margin-top: 20px; display: none;">
                <?php echo do_shortcode('[student_profile_form]'); ?>
            </div>

            <script>
            jQuery(document).ready(function($) {
                $('#edit-admission-form').on('click', function () {
                    $('#submitted-message').hide();
                    $('#edit-form-container').show();
                });
            });
            </script>
            <?php
        } else {
            echo do_shortcode('[student_profile_form]');
        }
    }

    // Instructor flow
    elseif ($is_inst_form === 'yes') {
        if ($instructor_form_submitted === 'yes') {
            echo '<div style="padding: 15px; background-color: #e6ffed; border: 1px solid #b7eb8f; border-radius: 5px; color: #065f46;">
            🎉 You have already submitted the instructor application.
            </div>';
        } else {
            echo do_shortcode('[instructor_career_form]');
        }
    }

    // Fallback
    else {
        echo '<p style="color: red;">Unable to determine your registration type. Please contact support.</p>';
    }

    echo '</div>';

    return ob_get_clean();
}




//EMPLOYEMENT FORM CODE

add_action('init', 'handle_employment_form_submission');

function handle_employment_form_submission() {
    // 🔒 Only run on actual form submission
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['custom_employment_form'])) {
        return;
    }

    error_log('Form handler triggered');

    if (!isset($_POST['employment_nonce']) || !wp_verify_nonce($_POST['employment_nonce'], 'employment_form_nonce')) {
        error_log('Form submission failed due to invalid nonce');
        wp_die('Access Denied. Invalid or missing security token.');
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    if (!$user_id) {
        error_log('User not logged in');
        wp_die('You must be logged in to submit the form.');
    }

    error_log("Processing employment form for user ID: $user_id");

    // Sanitize and save form fields
    $data = array(
        'first_name'    => sanitize_text_field($_POST['inst_firstName'] ?? ''),
        'middle_name'   => sanitize_text_field($_POST['inst_middleName'] ?? ''),
        'last_name'     => sanitize_text_field($_POST['inst_lastName'] ?? ''),
        'email'         => sanitize_email($_POST['inst_email'] ?? ''),
        'other_names'   => sanitize_text_field($_POST['inst_otherNames'] ?? ''),
        'eligible'      => sanitize_text_field($_POST['inst_eligible'] ?? ''),
        'worked_before' => sanitize_text_field($_POST['inst_worked'] ?? ''),
        'work_dates'    => sanitize_text_field($_POST['inst_workDates'] ?? ''),
        'convicted'     => sanitize_text_field($_POST['inst_convicted'] ?? ''),
        'position'      => sanitize_text_field($_POST['employment_inst_position'] ?? ''),
        'hours'         => isset($_POST['inst_hours']) ? array_map('sanitize_text_field', (array)$_POST['inst_hours']) : [],
        'bg_check'      => sanitize_text_field($_POST['inst_bg_check'] ?? ''),
        'print_name'    => sanitize_text_field($_POST['inst_print_name'] ?? ''),
        'signature'     => sanitize_text_field($_POST['inst_signature'] ?? ''),
        'date'          => sanitize_text_field($_POST['inst_date'] ?? ''),

        'inst_address'    => sanitize_text_field($_POST['inst_address'] ?? ''),
        'inst_majlis'     => sanitize_text_field($_POST['inst_majlis'] ?? ''),
        'inst_aimsCode'          => sanitize_text_field($_POST['inst_aimsCode'] ?? ''),
        'inst_homePhone'    => sanitize_text_field($_POST['inst_homePhone'] ?? ''),
        'inst_cellPhone'     => sanitize_text_field($_POST['inst_cellPhone'] ?? ''),
       
        'inst_waqifa'    => sanitize_text_field($_POST['inst_waqifa'] ?? ''),
        'inst_waqifaNumber'     => sanitize_text_field($_POST['inst_waqifaNumber'] ?? ''),
        'inst_jammatserving_status'          => sanitize_text_field($_POST['inst_jammatserving_status'] ?? ''),
        'inst_servingDetails'    => sanitize_text_field($_POST['employment_inst_servingDetails'] ?? ''),

        'inst_worked'    => sanitize_text_field($_POST['inst_worked'] ?? ''),
        'inst_workDates'     => sanitize_text_field($_POST['inst_workDates'] ?? ''),
        'inst_convicted'          => sanitize_text_field($_POST['inst_convicted'] ?? ''),
        'inst_position'    => sanitize_text_field($_POST['inst_position'] ?? ''),
        'interested_hour'    => sanitize_text_field($_POST['employment_inst_interested_hour'] ?? ''),

        'inst_preference1'    => sanitize_text_field($_POST['inst_preference1'] ?? ''),
        'inst_preference2'     => sanitize_text_field($_POST['inst_preference2'] ?? ''),
        'inst_preference3'          => sanitize_text_field($_POST['inst_preference3'] ?? ''),
        'inst_preference4'    => sanitize_text_field($_POST['inst_preference4'] ?? ''),
        'inst_preference5'    => sanitize_text_field($_POST['inst_preference5'] ?? ''),
        'inst_qualifications'    => sanitize_text_field($_POST['inst_qualifications'] ?? ''),
       
       
       
        'inst_ref_jamat_majlis'          => sanitize_text_field($_POST['inst_ref_jamat_majlis'] ?? ''),
        'inst_ref_jamat_president'    => sanitize_text_field($_POST['inst_ref_jamat_president'] ?? ''),
        'inst_ref_jamat_phone'    => sanitize_text_field($_POST['inst_ref_jamat_phone'] ?? ''),
        'inst_ref_jamat_email'    => sanitize_text_field($_POST['inst_ref_jamat_email'] ?? ''),


        'inst_ref_prof1_name'          => sanitize_text_field($_POST['inst_ref_prof1_name'] ?? ''),
        'inst_ref_prof1_title'    => sanitize_text_field($_POST['inst_ref_prof1_title'] ?? ''),
        'inst_ref_prof1_org'    => sanitize_text_field($_POST['inst_ref_prof1_org'] ?? ''),
        'inst_ref_prof1_phone'    => sanitize_text_field($_POST['inst_ref_prof1_phone'] ?? ''),
        'inst_ref_prof1_email'    => sanitize_text_field($_POST['inst_ref_prof1_email'] ?? ''),


        'inst_ref_prof2_name'          => sanitize_text_field($_POST['inst_ref_prof2_name'] ?? ''),
        'inst_ref_prof2_title'    => sanitize_text_field($_POST['inst_ref_prof2_title'] ?? ''),
        'inst_ref_prof2_org'    => sanitize_text_field($_POST['inst_ref_prof2_org'] ?? ''),
        'inst_ref_prof2_phone'    => sanitize_text_field($_POST['inst_ref_prof2_phone'] ?? ''),
        'inst_ref_prof2_email'    => sanitize_text_field($_POST['inst_ref_prof2_email'] ?? ''),


        'inst_ref_pers_name'          => sanitize_text_field($_POST['inst_ref_pers_name'] ?? ''),
        'inst_ref_pers_relation'    => sanitize_text_field($_POST['inst_ref_pers_relation'] ?? ''),
        'inst_ref_pers_phone'    => sanitize_text_field($_POST['inst_ref_pers_phone'] ?? ''),
        'inst_ref_pers_email'    => sanitize_text_field($_POST['inst_ref_pers_email'] ?? ''),

        'inst_sp_en'  => sanitize_text_field($_POST['inst_sp_en'] ?? ''),
        'inst_wr_en'  => sanitize_text_field($_POST['inst_wr_en'] ?? ''),
        'inst_rd_en'  => sanitize_text_field($_POST['inst_rd_en'] ?? ''),
        
        'inst_sp_ur'  => sanitize_text_field($_POST['inst_sp_ur'] ?? ''),
        'inst_wr_ur'  => sanitize_text_field($_POST['inst_wr_ur'] ?? ''),
        'inst_rd_ur'  => sanitize_text_field($_POST['inst_rd_ur'] ?? ''),        
            
        'inst_sp_other'  => sanitize_text_field($_POST['inst_sp_other'] ?? ''),
        'inst_wr_other'  => sanitize_text_field($_POST['inst_wr_other'] ?? ''),
        'inst_rd_other'  => sanitize_text_field($_POST['inst_rd_other'] ?? ''),
               
     
        
        'inst_employmen_other_lang'    => sanitize_text_field($_POST['employment_inst_employment_inst_sp_other'] ?? ''),
        'agree_terms'   => isset($_POST['inst_agree_terms']) ? 1 : 0,
    );

    foreach ($data as $key => $value) {
        update_user_meta($user_id, 'employment_' . $key, $value);
    }

    // Save Aisha courses
    if (!empty($_POST['inst_aisha_courses']) && is_array($_POST['inst_aisha_courses'])) {
        $aisha_courses = array_map(function ($course) {
            return [
                'name'           => sanitize_text_field($course['name'] ?? ''),
                'am'             => !empty($course['am']) ? 1 : 0,
                'pm'             => !empty($course['pm']) ? 1 : 0,
                'qualifications' => sanitize_text_field($course['qualifications'] ?? ''),
            ];
        }, $_POST['inst_aisha_courses']);
        update_user_meta($user_id, 'employment_aisha_courses', $aisha_courses);
    }

    // Save Hifz classes
    if (!empty($_POST['inst_hifz_classes']) && is_array($_POST['inst_hifz_classes'])) {
        $hifz_classes = array_map(function ($class) {
            return [
                'name'           => sanitize_text_field($class['name'] ?? ''),
                'qualifications' => sanitize_text_field($class['qualifications'] ?? ''),
            ];
        }, $_POST['inst_hifz_classes']);
        update_user_meta($user_id, 'employment_hifz_classes', $hifz_classes);
    }
    update_user_meta($user_id, 'form_type', 'employment_form');
    update_user_meta($user_id, 'is_emp_form_submited', 'yes');

 if (function_exists('send_confirmation_emails')) {
        send_confirmation_emails($user_id);
    }
    
    if (function_exists('generate_emp_form_pdf_for_user')) {
    generate_emp_form_pdf_for_user($user_id);
}

    
    
    error_log("Employment form saved for user $user_id");

    wp_redirect(home_url('/employment-form-received/'));
    exit;
}


function generate_emp_form_pdf_for_user($user_id) {
    require_once plugin_dir_path(__FILE__) . 'lib/dompdf/autoload.inc.php';

    $user = get_userdata($user_id);
    if (!$user) {
        error_log("❌ Invalid user.");
        return false;
    }

    $form_type = get_user_meta($user_id, 'form_type', true);
    $is_instructor = get_user_meta($user_id, 'is_inst_form', true);

    if ($form_type === 'student_form') return false;
    if ($is_instructor !== 'yes') return false;

    // Load HTML template
    $template_path = plugin_dir_path(__FILE__) . 'includes/employment_form/emp_form.html';
    if (!file_exists($template_path)) {
        error_log("❌ Template not found at: $template_path");
        return false;
    }

    $html = file_get_contents($template_path);

    // Load base user meta
    $meta = [

            'first_name'  => get_user_meta($user_id, 'employment_first_name', true),
            'middle_name' => get_user_meta($user_id, 'employment_middle_name', true),
            'last_name'   => get_user_meta($user_id, 'employment_last_name', true),
            'other_names' => get_user_meta($user_id, 'employment_other_names', true),
            
             // Contact Information
            'address'         => get_user_meta($user_id, 'employment_inst_address', true),
            'majlis'          => get_user_meta($user_id, 'employment_inst_majlis', true),
            'aims_code'       => get_user_meta($user_id, 'employment_inst_aimsCode', true),
            'home_phone'      => get_user_meta($user_id, 'employment_inst_homePhone', true),
            'cell_phone'      => get_user_meta($user_id, 'employment_inst_cellPhone', true),
            'email'           => get_user_meta($user_id, 'employment_email', true),

            // Additional Information
            'is_waqifa'           => get_user_meta($user_id, 'employment_inst_waqifa', true),
            'waqifa_number'       => get_user_meta($user_id, 'employment_inst_waqifaNumber', true),
            'serving_in_jamaat'   => get_user_meta($user_id, 'employment_inst_jammatserving_status', true),
            'serving_details'     => get_user_meta($user_id, 'employment_inst_servingDetails', true),

            // Employment Eligibility
            'canada_eligible'     => get_user_meta($user_id, 'employment_eligible', true),
            'worked_before'       => get_user_meta($user_id, 'employment_worked_before', true),
            'work_dates'          => get_user_meta($user_id, 'employment_work_dates', true),
            'convicted'           => get_user_meta($user_id, 'employment_convicted', true),
            'position'            => get_user_meta($user_id, 'employment_position', true),
            'hours_interested'    => get_user_meta($user_id, 'employment_interested_hour', true),




            // Preferences
            'preference_1' => get_user_meta($user_id, 'employment_inst_preference1', true),
            'preference_2' => get_user_meta($user_id, 'employment_inst_preference2', true),
            'preference_3' => get_user_meta($user_id, 'employment_inst_preference3', true),
            'preference_4' => get_user_meta($user_id, 'employment_inst_preference4', true),
            'preference_5' => get_user_meta($user_id, 'employment_inst_preference5', true),

            // Qualifications
            'qualifications' => get_user_meta($user_id, 'employment_inst_qualifications', true),

            // Language Proficiency
            'english_spoken'        => get_user_meta($user_id, 'employment_inst_sp_en', true),
            'english_written'       => get_user_meta($user_id, 'employment_inst_wr_en', true),
            'english_reading'       => get_user_meta($user_id, 'employment_inst_rd_en', true),
            'urdu_spoken'           => get_user_meta($user_id, 'employment_inst_sp_ur', true),
            'urdu_written'          => get_user_meta($user_id, 'employment_inst_wr_ur', true),
            'urdu_reading'          => get_user_meta($user_id, 'employment_inst_rd_ur', true),
            'other_language_name'   => get_user_meta($user_id, 'employment_inst_employmen_other_lang', true),
            'other_language_spoken' => get_user_meta($user_id, 'employment_inst_sp_other', true),
            'other_language_written'=> get_user_meta($user_id, 'employment_inst_wr_other', true),
            'other_language_reading'=> get_user_meta($user_id, 'employment_inst_rd_other', true),

            // References
            'jamat_majlis'         => get_user_meta($user_id, 'employment_inst_ref_jamat_majlis', true),
            'jamat_president_name' => get_user_meta($user_id, 'employment_inst_ref_jamat_president', true),
            'jamat_phone'          => get_user_meta($user_id, 'employment_inst_ref_jamat_phone', true),
            'jamat_email'          => get_user_meta($user_id, 'employment_inst_ref_jamat_email', true),

            'prof_ref1_name'       => get_user_meta($user_id, 'employment_inst_ref_prof1_name', true),
            'prof_ref1_title'      => get_user_meta($user_id, 'employment_inst_ref_prof1_title', true),
            'prof_ref1_org'        => get_user_meta($user_id, 'employment_inst_ref_prof1_org', true),
            'prof_ref1_phone'      => get_user_meta($user_id, 'employment_inst_ref_prof1_phone', true),
            'prof_ref1_email'      => get_user_meta($user_id, 'employment_inst_ref_prof1_email', true),

            'prof_ref2_name'       => get_user_meta($user_id, 'employment_inst_ref_prof2_name', true),
            'prof_ref2_title'      => get_user_meta($user_id, 'employment_inst_ref_prof2_title', true),
            'prof_ref2_org'        => get_user_meta($user_id, 'employment_inst_ref_prof2_org', true),
            'prof_ref2_phone'      => get_user_meta($user_id, 'employment_inst_ref_prof2_phone', true),
            'prof_ref2_email'      => get_user_meta($user_id, 'employment_inst_ref_prof2_email', true),

            'personal_ref_name'       => get_user_meta($user_id, 'employment_inst_ref_pers_name', true),
            'personal_ref_relation'   => get_user_meta($user_id, 'employment_inst_ref_pers_relation', true),
            'personal_ref_phone'      => get_user_meta($user_id, 'employment_inst_ref_pers_phone', true),
            'personal_ref_email'      => get_user_meta($user_id, 'employment_inst_ref_pers_email', true),

            // Final Section
            'background_check_consent' => get_user_meta($user_id, 'employment_bg_check', true),
            'print_name'               => get_user_meta($user_id, 'employment_print_name', true),
            'signature'                => get_user_meta($user_id, 'employment_signature', true),
            'signature_date'          => get_user_meta($user_id, 'employment_date', true),
            'terms_agreement'         => get_user_meta($user_id, 'employment_agree_terms', true),
    ];

    // Replace static placeholders
    foreach ($meta as $key => $value) {
        $html = str_replace('{{' . $key . '}}', esc_html($value), $html);
    }

    // Load Aisha courses
    $aisha_courses = maybe_unserialize(get_user_meta($user_id, 'employment_aisha_courses', true));
    error_log("🟢 Aisha Raw: " . print_r($aisha_courses, true));

    $aisha_rows_html = '';
    if (!empty($aisha_courses) && is_array($aisha_courses)) {
        foreach ($aisha_courses as $course) {
            $course_name = esc_html($course['name'] ?? '');
            $qualifications = esc_html($course['qualifications'] ?? '');
            $time = [];
            if (!empty($course['am'])) $time[] = 'AM';
            if (!empty($course['pm'])) $time[] = 'PM';
            $time_str = implode(', ', $time);

            $aisha_rows_html .= "<tr><td>{$course_name}</td><td>{$time_str}</td><td>{$qualifications}</td></tr>";
        }
    } else {
        $aisha_rows_html = '<tr><td colspan="3">No courses found</td></tr>';
    }
    $html = str_replace('{{aisha_courses_rows}}', $aisha_rows_html, $html);

    // Load Hifz classes
    $hifz_classes = maybe_unserialize(get_user_meta($user_id, 'employment_hifz_classes', true));
    error_log("🟢 Hifz Raw: " . print_r($hifz_classes, true));

    $hifz_rows_html = '';
    if (!empty($hifz_classes) && is_array($hifz_classes)) {
        foreach ($hifz_classes as $class) {
            $class_name = esc_html($class['name'] ?? '');
            $qualifications = esc_html($class['qualifications'] ?? '');
            $hifz_rows_html .= "<tr><td>{$class_name}</td><td>{$qualifications}</td></tr>";
        }
    } else {
        $hifz_rows_html = '<tr><td colspan="2">No classes found</td></tr>';
    }
    $html = str_replace('{{hifz_classes_rows}}', $hifz_rows_html, $html);

    // Generate file name and path
    $filename = "{$user_id}_{$meta['first_name']}_{$meta['last_name']}_employment.pdf";
    $upload_dir = wp_upload_dir();
    $pdf_dir = $upload_dir['basedir'] . '/emp-pdfs/';
    if (!file_exists($pdf_dir)) mkdir($pdf_dir, 0755, true);
    $file_path = $pdf_dir . $filename;

    // Generate PDF
    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    file_put_contents($file_path, $dompdf->output());

    // Save to user meta
    update_user_meta($user_id, 'employment_pdf_path', $file_path);
    update_user_meta($user_id, 'is_employment_form_pdf', 'yes');
    update_user_meta($user_id, 'employment_pdf_generated_at', current_time('mysql'));

    error_log("✅ PDF generated: $file_path");

    return $file_path;
}



add_action('wp_ajax_get_grandchild_terms', function () {
    check_ajax_referer('category_dropdown_nonce', 'nonce');

    $parent_id = intval($_POST['term_id']);
    $terms = get_terms([
        'taxonomy'   => 'course-category',
        'parent'     => $parent_id,
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms) && !empty($terms)) {
        $data = array_map(function($term) {
            return [
                'term_id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }, $terms);
        wp_send_json_success($data);
    } else {
        wp_send_json_success([]); // Not error — just empty
    }
});

add_action('wp_ajax_load_courses_by_slug', 'load_courses_by_slug_callback');
function load_courses_by_slug_callback() {
    check_ajax_referer('load_courses_nonce', 'nonce');

    $slug = sanitize_text_field($_POST['slug']);
    if (!$slug) wp_send_json_error();

    $courses = new WP_Query([
        'post_type' => 'courses',
        'posts_per_page' => -1,
        'tax_query' => [[
            'taxonomy' => 'course-category',
            'field' => 'slug',
            'terms' => $slug,
        ]]
    ]);

    ob_start();
    if ($courses->have_posts()) {
        echo '<div class="course-grid">'; // Start grid container
        while ($courses->have_posts()) {
            $courses->the_post();
            echo '<div class="course-card">'; // Grid item wrapper
            include get_stylesheet_directory() . '/tutor/global/course.php';
            echo '</div>';
        }
        echo '</div>'; // End grid container
        wp_reset_postdata();
    } else {
        echo '<div class="text-center text-gray-500">No courses found in this category.</div>';
    }

    wp_send_json_success(['html' => ob_get_clean()]);
}

add_action('wp_ajax_load_courses_grouped_by_grandchild', 'load_courses_grouped_by_grandchild');
add_action('wp_ajax_nopriv_load_courses_grouped_by_grandchild', 'load_courses_grouped_by_grandchild');

function load_courses_grouped_by_grandchild() {
    check_ajax_referer('load_courses_nonce', 'nonce');

    $child_term_id = intval($_POST['child_term_id']);
    $course_type   = sanitize_text_field($_POST['course_type']);

    $cache_key = 'courses_' . $child_term_id . '_' . $course_type;
    $cached_html = get_transient($cache_key);

    if ($cached_html !== false) {
        wp_send_json_success(['html' => $cached_html]);
    }

    $grandchild_terms = get_terms([
        'taxonomy'   => 'course-category',
        'parent'     => $child_term_id,
        'hide_empty' => false,
    ]);

    ob_start();

    if (!is_wp_error($grandchild_terms) && !empty($grandchild_terms)) {
        foreach ($grandchild_terms as $term) {
            echo '<h2 class="text-xl font-bold mb-4 mt-8 text-[#031f42] uppercase">' . esc_html($term->name) . '</h2>';

            $query = new WP_Query([
                'post_type'      => 'courses',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'tax_query'      => [[
                    'taxonomy' => 'course-category',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                    'include_children' => false,
                ]]
            ]);

            if ($query->have_posts()) {
                echo '<div class="course-grid grid md:grid-cols-3 gap-4">';
                while ($query->have_posts()) {
                    $query->the_post();
                    set_query_var('course_type_for_template', $course_type);
                    echo '<div class="course-card">';
                    get_template_part('tutor/loop/course');
                    echo '</div>';
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p class="text-gray-500">No courses found under this category.</p>';
            }
        }
    } else {
        // No grandchild terms, show all under child
        $query = new WP_Query([
            'post_type'      => 'courses',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => [[
                'taxonomy' => 'course-category',
                'field'    => 'term_id',
                'terms'    => $child_term_id,
                'include_children' => false,
            ]]
        ]);

        if ($query->have_posts()) {
            echo '<div class="course-grid grid md:grid-cols-3 gap-4">';
            while ($query->have_posts()) {
                $query->the_post();
                set_query_var('course_type_for_template', $course_type);
                echo '<div class="course-card">';
                get_template_part('tutor/loop/course');
                echo '</div>';
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<div class="text-center text-red-500">⚠️ No courses found for this specialization.</div>';
        }
    }

    $html = ob_get_clean();
    set_transient($cache_key, $html, HOUR_IN_SECONDS);

    wp_send_json_success(['html' => $html]);
}







function clear_course_cache_on_save($post_id) {
    if (get_post_type($post_id) === 'courses') {
        // Clear all course-related transients
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_courses_%'");
    }
}
add_action('save_post', 'clear_course_cache_on_save');


add_action('wp_ajax_check_has_grandchild', 'check_has_grandchild');
function check_has_grandchild() {
    check_ajax_referer('load_courses_nonce', 'nonce');

    $child_term_id = intval($_POST['child_term_id']);
    $has_grandchild = false;

    $grandchildren = get_terms([
        'taxonomy' => 'course-category',
        'parent' => $child_term_id,
        'hide_empty' => false
    ]);

    if (!empty($grandchildren) && !is_wp_error($grandchildren)) {
        $has_grandchild = true;
    }

    wp_send_json_success(['has_grandchild' => $has_grandchild]);
}




function enqueue_custom_cart_script() {
    // Load jQuery (already available in WordPress)
    wp_enqueue_script('jquery');

    // Register your JS file
    wp_register_script(
        'custom-cart',
        get_stylesheet_directory_uri() . '/js/custom-cart.js', // Adjust if path is different
        ['jquery'],
        null,
        true
    );

    // Send data to JS (like checkout redirect URL)
    wp_localize_script('custom-cart', 'addToCartData', [
        'redirect_url' => site_url('/purchase-selected-courses'), // Make sure this page exists
    ]);

    // Enqueue the script
    wp_enqueue_script('custom-cart');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_cart_script');

echo '<!-- Custom Cart Script Loaded -->';
