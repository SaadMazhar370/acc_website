<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

if (!$user_id) {
    wp_die('You must be logged in to submit this form.');
}
$plugin_root_file = dirname(__FILE__) . '/student-manager.php';
$plugin_root_url = plugin_dir_url($plugin_root_file);

// Get user data safely with fallbacks
$first_name = esc_attr($current_user->first_name);
$last_name = esc_attr($current_user->last_name);
$middle_name = esc_attr($current_user->nickname);
$email = esc_attr($current_user->user_email); // Directly from user object, not meta
$phone = esc_attr(get_user_meta($user_id, 'phone', true));
$other_names = get_user_meta($user_id, 'employment_other_names', true);

$inst_employment_inst_address     = get_user_meta($user_id, 'employment_inst_address', true);
$inst_employment_inst_majlis     = get_user_meta($user_id, 'employment_inst_majlis', true);
$inst_employment_inst_aimsCode     = get_user_meta($user_id, 'employment_inst_aimsCode', true);
$inst_employment_inst_homePhone     = get_user_meta($user_id, 'employment_inst_homePhone', true);
$inst_employment_inst_cellPhone     = get_user_meta($user_id, 'employment_inst_cellPhone', true);


$inst_employment_inst_waqifa     = get_user_meta($user_id, 'employment_inst_waqifa', true);
$inst_employment_inst_waqifaNumber     = get_user_meta($user_id, 'employment_inst_waqifaNumber', true);
$inst_employment_inst_jammatserving_status     = get_user_meta($user_id, 'employment_inst_jammatserving_status', true);
// $inst_servingDetails = get_user_meta($user_id, 'employment_inst_servingDetails', true);



$serving_details = get_user_meta($user_id, 'employment_inst_servingDetails', true);

$serving_inst_position = get_user_meta($user_id, 'employment_position', true);
$inst_interested_hour = get_user_meta($user_id, 'employment_inst_interested_hour', true);

$aisha_courses_saved = get_user_meta($user_id, 'employment_aisha_courses', true);
$aisha_courses_saved = maybe_unserialize($aisha_courses_saved);


$hfqs_courses_saved = get_user_meta($user_id, 'employment_hifz_classes', true);
$hfqs_courses_saved = maybe_unserialize($hfqs_courses_saved);



$eligible = get_user_meta($user_id, 'employment_eligible', true);
$worked = get_user_meta($user_id, 'employment_worked_before', true);
$bgcheck = get_user_meta($user_id, 'employment_bg_check', true);
$convicted = get_user_meta($user_id, 'employment_convicted', true);



$work_dates   = get_user_meta($user_id, 'employment_work_dates', true);



$inst_employment_inst_preference1    = get_user_meta($user_id, 'employment_inst_preference1', true);
$inst_employment_inst_preference2    = get_user_meta($user_id, 'employment_inst_preference2', true);
$inst_employment_inst_preference3    = get_user_meta($user_id, 'employment_inst_preference3', true);
$inst_employ_inst_preference4    = get_user_meta($user_id, 'employment_inst_preference4', true);
$inst_empp_inst_preference5    = get_user_meta($user_id, 'employment_inst_preference5', true);

$inst_employment_inst_qualifications    = get_user_meta($user_id, 'employment_inst_qualifications', true);




$inst_lang_en_sp = get_user_meta($user_id, 'employment_inst_sp_en', true);
$inst_lang_en_wr = get_user_meta($user_id, 'employment_inst_wr_en', true);
$inst_lang_en_rd = get_user_meta($user_id, 'employment_inst_rd_en', true);

$inst_lang_ur_sp = get_user_meta($user_id, 'employment_inst_sp_ur', true);
$inst_lang_ur_wr = get_user_meta($user_id, 'employment_inst_wr_ur', true);
$inst_lang_ur_rd = get_user_meta($user_id, 'employment_inst_rd_ur', true);

$inst_lang_oth_sp = get_user_meta($user_id, 'employment_inst_sp_other', true);
$inst_lang_oth_wr = get_user_meta($user_id, 'employment_inst_wr_other', true);
$inst_lang_oth_rd = get_user_meta($user_id, 'employment_inst_rd_other', true);

$inst_ref_jmt_mjlis    = get_user_meta($user_id, 'employment_inst_ref_jamat_majlis', true);
$inst_ref_jmt_president    = get_user_meta($user_id, 'employment_inst_ref_jamat_president', true);
$inst_ref_jmt_phone    = get_user_meta($user_id, 'employment_inst_ref_jamat_phone', true);
$inst_ref_jmt_email    = get_user_meta($user_id, 'employment_inst_ref_jamat_email', true);

$inst_ref_prof1_name    = get_user_meta($user_id, 'employment_inst_ref_prof1_name', true);
$inst_ref_prof1_title    = get_user_meta($user_id, 'employment_inst_ref_prof1_title', true);
$inst_ref_prof1_org    = get_user_meta($user_id, 'employment_inst_ref_prof1_org', true);
$inst_ref_prof1_phone    = get_user_meta($user_id, 'employment_inst_ref_prof1_phone', true);
$inst_ref_prof1_email    = get_user_meta($user_id, 'employment_inst_ref_prof1_email', true);

$inst_ref_prof2_name    = get_user_meta($user_id, 'employment_inst_ref_prof2_name', true);
$inst_ref_prof2_title    = get_user_meta($user_id, 'employment_inst_ref_prof2_title', true);
$inst_ref_prof2_org    = get_user_meta($user_id, 'employment_inst_ref_prof2_org', true);
$inst_ref_prof2_phone    = get_user_meta($user_id, 'employment_inst_ref_prof2_phone', true);
$inst_ref_prof2_email    = get_user_meta($user_id, 'employment_inst_ref_prof2_email', true);


$inst_ref_pers_name    = get_user_meta($user_id, 'employment_inst_ref_pers_name', true);
$inst_ref_pers_relation    = get_user_meta($user_id, 'employment_inst_ref_pers_relation', true);
$inst_ref_pers_phone    = get_user_meta($user_id, 'employment_inst_ref_pers_phone', true);
$inst_ref_pers_email    = get_user_meta($user_id, 'employment_inst_ref_pers_email', true);


$inst_emp_print_name    = get_user_meta($user_id, 'employment_print_name', true);
$inst_emp_sign    = get_user_meta($user_id, 'employment_signature', true);
$inst_emp_submission_date    = get_user_meta($user_id, 'employment_date', true);







$aisha_course_options = '<option value="">-- Select Course --</option>';
$args_aisha = array(
    'post_type'      => 'courses', // or 'tutor_course'
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'course-category',
            'field'    => 'slug',
            'terms'    => 'aisha_cademy_canada',
        ),
    ),
);
$aisha_courses = get_posts($args_aisha);
foreach ($aisha_courses as $course) {
    $aisha_course_options .= '<option value="' . esc_attr($course->post_title) . '">' . esc_html($course->post_title) . '</option>';
}

// Hifzul Qur'an School Course Options
$hzqs_course_options = '<option value="">-- Select Class --</option>';
$args_hifz = array(
    'post_type'      => 'courses',
    'posts_per_page' => -1,
    'tax_query'      => array(
        array(
            'taxonomy' => 'course-category',
            'field'    => 'slug',
            'terms'    => 'girls_hifzul_quran_school',
        ),
    ),
);
$hzqscourses = get_posts($args_hifz);
foreach ($hzqscourses as $hfzqcourse) {
    $hzqs_course_options .= '<option value="' . esc_attr($hfzqcourse->post_title) . '">' . esc_html($hfzqcourse->post_title) . '</option>';
}


?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Application Form - Aisha Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #495057;
            --success-color: #28a745;
            --warning-color: #ffc107;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .form-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, var(--primary-color), #1a252f);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .form-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: 700;
        }
        
        .form-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .form-section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 22px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--secondary-color);
            position: relative;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100px;
            height: 2px;
            background-color: var(--accent-color);
        }
        
        .institution-title {
            color: var(--primary-color);
            font-size: 18px;
            margin: 30px 0 15px 0;
            padding-left: 10px;
            border-left: 4px solid var(--secondary-color);
        }
        
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            gap: 20px;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
            margin-bottom: 15px;
        }
        
        .form-group.full-width {
            flex: 0 0 100%;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        label.required:after {
            content: " *";
            color: var(--accent-color);
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: white;
        }
        
        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .radio-group,
        .checkbox-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }
        
        .radio-option,
        .checkbox-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .radio-option input[type="radio"],
        .checkbox-option input[type="checkbox"] {
            width: auto;
            margin: 0;
        }
        
        .name-group {
            display: flex;
            gap: 15px;
            width: 100%;
            flex-wrap: wrap;
        }
        
        .name-group .form-group {
            flex: 1;
            min-width: 150px;
        }
        
        .two-columns {
            display: flex;
            gap: 30px;
        }
        
        .left-column, .right-column {
            flex: 1;
        }
        
        .courses-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 25px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .courses-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .courses-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--medium-gray);
            vertical-align: middle;
        }
        
        .courses-table tr:nth-child(even) {
            background-color: var(--light-gray);
        }
        
        .courses-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .checkbox-cell {
            text-align: center;
        }
        
        .add-row-btn, .remove-row-btn {
            padding: 8px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .add-row-btn {
            background-color: var(--success-color);
            margin-bottom: 20px;
        }
        
        .remove-row-btn {
            background-color: var(--accent-color);
        }
        
        .add-row-btn:hover {
            background-color: #218838;
        }
        
        .remove-row-btn:hover {
            background-color: #c82333;
        }
        
        .submit-btn {
            background: linear-gradient(to right, var(--primary-color), #1a252f);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            display: block;
            margin: 30px auto 0;
            width: 200px;
            text-align: center;
        }
        
        .submit-btn:hover {
            background: linear-gradient(to right, #1a252f, var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        fieldset {
            border: 1px solid var(--medium-gray);
            border-radius: 4px;
            padding: 20px;
            margin: 25px 0;
        }
        
        fieldset legend {
            padding: 0 10px;
            font-weight: bold;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        textarea[readonly] {
            background-color: var(--light-gray);
            border: 1px solid #ddd;
            resize: none;
            cursor: not-allowed;
        }
        
        .error {
            color: var(--accent-color);
            font-size: 13px;
            margin-top: 5px;
            display: none;
        }
        
        input.error-input, select.error-input, textarea.error-input {
            border-color: var(--accent-color);
        }
        
        .success-message {
            color: var(--success-color);
            font-weight: bold;
            margin: 20px 0;
            padding: 15px;
            background-color: #e8f8f0;
            border-radius: 4px;
            text-align: center;
            display: none;
        }
        
        .form-note {
            font-size: 14px;
            color: var(--dark-gray);
            margin-top: 5px;
            font-style: italic;
        }
        
        .rating-scale {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 12px;
            color: var(--dark-gray);
        }
        
        .rating-label {
            text-align: center;
            flex: 1;
        }
        
        @media (max-width: 768px) {
            .two-columns {
                flex-direction: column;
            }
            
            .name-group {
                flex-direction: column;
            }
            
            .name-group .form-group {
                min-width: 100%;
            }
            
            .radio-group, .checkbox-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .form-header h1 {
                font-size: 24px;
            }
            
            .section-title {
                font-size: 20px;
            }
        }
        
        /* Form step indicator */
        .form-steps {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--medium-gray);
            color: var(--dark-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 10px;
            position: relative;
        }
        
        .step.active {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .step.completed {
            background-color: var(--success-color);
            color: white;
        }
        
        .step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 2px;
            background-color: var(--medium-gray);
        }
        
        .step.completed:not(:last-child):after {
            background-color: var(--success-color);
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <input type="hidden" name="custom_employment_form" value="1">
        <?php wp_nonce_field('employment_form_nonce', 'employment_nonce'); ?>

        <div class="form-container">
            <div class="form-header">
                <h1>Employment Application Form</h1>
                <p>Aisha Academy Canada - Girls' Hifzul Qur'an School</p>
            </div>
            
            <div class="form-body">
             
                
                <div class="form-section">
                    <h2 class="section-title">Personal Details</h2>
                    
                    <div class="form-row">
                        <div class="name-group">
                            <div class="form-group">
                                <label for="inst_firstName" class="required">First Name</label>
                                <input type="text" id="inst_firstName" name="inst_firstName" value="<?php echo $first_name; ?>" required>
                                <div class="error">This field is required</div>
                            </div>
                            <div class="form-group">
                                <label for="inst_middleName">Middle Name</label>
                                <input type="text" id="inst_middleName" name="inst_middleName" value="<?php echo $middle_name; ?>">
                            </div>
                            <div class="form-group">
                                <label for="inst_lastName" class="required">Last Name</label>
                                <input type="text" id="inst_lastName" name="inst_lastName" value="<?php echo $last_name; ?>" required>
                                <div class="error">This field is required</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="inst_email" class="required">Email</label>
                            <input type="email" id="inst_email" name="inst_email" value="<?php echo $email; ?>" required>
                            <div class="error">Please enter a valid email address</div>
                        </div>
                        <div class="form-group">
                            <label for="inst_cellPhone" class="required">Cell Phone</label>
                            <input type="tel" id="inst_cellPhone" name="inst_cellPhone" value="<?php echo $inst_employment_inst_cellPhone; ?>" required>
                            <div class="error">This field is required</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="inst_otherNames">List any other name(s) used in the past (for background verification)</label>
                            <input type="text" name="inst_otherNames" value="<?php echo esc_attr($other_names); ?>">
                            <div class="form-note">Include maiden names or other legal names you've used</div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title">Contact Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="inst_address" class="required">Address</label>
                            <input type="text" id="inst_address" name="inst_address" value="<?php echo $inst_employment_inst_address; ?>" required>
                            <div class="error">This field is required</div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="inst_majlis">Majlis</label>
                            <input type="text" id="inst_majlis" name="inst_majlis" value="<?php echo $inst_employment_inst_majlis; ?>">
                        </div>
                        <div class="form-group">
                            <label for="inst_aimsCode">AIMS Member Code</label>
                            <input type="text" id="inst_aimsCode" name="inst_aimsCode" value="<?php echo $inst_employment_inst_aimsCode; ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="inst_homePhone">Home Number</label>
                            <input type="tel" id="inst_homePhone" name="inst_homePhone" value="<?php echo $inst_employment_inst_homePhone; ?>">
                        </div>
                        <div class="form-group">
                            <label for="inst_cellPhone">Cell Phone</label>
                            <input type="tel" id="inst_cellPhone" name="inst_cellPhone" value="<?php echo $inst_employment_inst_cellPhone; ?>">
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title">Additional Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Are you a Waqifa?</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="inst_waqifaYes" name="inst_waqifa" value="Yes" <?php checked($inst_employment_inst_waqifa, 'Yes'); ?>>
                                    <label for="inst_waqifaYes">Yes</label>
                                </div>
                                <div class="radio-option">
                                    <input type="radio" id="inst_waqifaNo" name="inst_waqifa" value="No" <?php checked($inst_employment_inst_waqifa, 'No'); ?>>
                                    <label for="inst_waqifaNo">No</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inst_waqifaNumber">If yes, Waqf-e-Nau number</label>
                            <input type="text" id="inst_waqifaNumber" name="inst_waqifaNumber" value="<?php echo esc_attr($inst_employment_inst_waqifaNumber); ?>">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label>Are you currently serving in Jama'at at:</label>
                            <select name="inst_jammatserving_status" class="form-control" style="width: 100%; padding: 12px 15px; border-radius: 4px;">
                                <option value="local" <?php selected($inst_employment_inst_jammatserving_status, 'local'); ?>>Local Level</option>
                                <option value="Regional" <?php selected($inst_employment_inst_jammatserving_status, 'Regional'); ?>>Regional Level</option>
                                <option value="National" <?php selected($inst_employment_inst_jammatserving_status, 'National'); ?>>National Level</option>
                                <option value="not_serving" <?php selected($inst_employment_inst_jammatserving_status, 'not_serving'); ?>>Currently not serving</option>
                            </select>
                        </div>
                    </div>
                    


<div class="form-row">
    <div class="form-group full-width">
        <label for="inst_servingDetails">If yes, please give details:</label>
<textarea name="employment_inst_servingDetails"><?php echo esc_textarea($serving_details); ?></textarea>
    </div>
</div>



                </div>
                
                <div class="form-section">
                    <h2 class="section-title">Employment Eligibility</h2>
                    
                    <div class="two-columns">
                        <!-- Left Column -->
                        <div class="left-column">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Are you legally eligible to work in Canada for any employer?</label>
                                    <div class="radio-group">
                                        <div class="radio-option">
                                            <input type="radio" name="inst_eligible" value="Yes" <?php checked($eligible, 'Yes'); ?> required>
                                            <label for="inst_eligibleYes">Yes</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" name="inst_eligible" value="No" <?php checked($eligible, 'No'); ?>>
                                            <label for="inst_eligibleNo">No</label>
                                        </div>
                                    </div>
                                    <div class="error">This field is required</div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Have you ever worked for 'Aisha Academy Canada'?</label>
                                    <div class="radio-group">
                                        <div class="radio-option">
                                            <input type="radio" id="inst_workedYes" name="inst_worked" value="Yes" <?php checked($worked, 'Yes'); ?>>
                                            <label for="inst_workedYes">Yes</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="inst_workedNo" name="inst_worked" value="No" <?php checked($worked, 'No'); ?>>
                                            <label for="inst_workedNo">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group full-width">
                                    <label for="inst_workDates">If yes, write the start and end dates:</label>
                                    <input type="text" id="inst_workDates" name="inst_workDates" placeholder="MM/YYYY - MM/YYYY" value="<?php echo esc_attr($work_dates); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="right-column">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Have you ever been convicted of an act for which pardon has not been granted?</label>
                                    <div class="radio-group">
                                        <div class="radio-option">
                                            <input type="radio" id="inst_convictedYes" name="inst_convicted" value="Yes" <?php checked($convicted, 'Yes'); ?>>
                                            <label for="inst_convictedYes">Yes</label>
                                        </div>
                                        <div class="radio-option">
                                            <input type="radio" id="inst_convictedNo" name="inst_convicted" value="No" <?php checked($convicted, 'No'); ?>>
                                            <label for="inst_convictedNo">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
<div class="form-row">
    <div class="form-group full-width">
        <label for="inst_position" class="required">Position you are applying for:</label>
        <input type="text" id="inst_position" name="employment_inst_position" 
               value="<?php echo esc_attr($serving_inst_position); ?>" required>
        <div class="error">This field is required</div>
    </div>
</div>
                          
                            
<div class="form-row">
    <div class="form-group full-width">
        <label>Hours you are interested in:</label>
        <select name="employment_inst_interested_hour" class="form-control" style="width: 100%; padding: 12px 15px; border-radius: 4px;">
            <option value="Full-time" <?php selected($inst_interested_hour, 'Full-time'); ?>>Full-time</option>
            <option value="Part-time" <?php selected($inst_interested_hour, 'Part-time'); ?>>Part-time</option>
            <option value="Temporary-seasonal" <?php selected($inst_interested_hour, 'Temporary-seasonal'); ?>>Temporary/Seasonal</option>
            <option value="other" <?php selected($inst_interested_hour, 'other'); ?>>Other</option>
        </select>
    </div>
</div>
                        
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h2 class="section-title">Teaching Qualifications</h2>
                    <p>Please select all courses/classes you are qualified to teach at each institution.</p>
                    
                    <!-- Aisha Academy Table -->
                    <h3 class="institution-title">Aisha Academy</h3>
                    
      

                    
<table class="courses-table" id="aisha-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>AM</th>
                                <th>PM</th>
                                <th>Qualifications</th>
                                <th>Action</th>
                            </tr>
                        </thead>

<tbody id="aisha-tbody">
<?php
if (!empty($aisha_courses_saved) && is_array($aisha_courses_saved)) {
    foreach ($aisha_courses_saved as $index => $course) {
        ?>
        <tr>
            <td>
                <select class="course-select" name="inst_aisha_courses[<?php echo $index; ?>][name]" required>
                    <?php
                    echo $aisha_course_options;
                    ?>
                </select>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        let select = document.getElementsByName("inst_aisha_courses[<?php echo $index; ?>][name]")[0];
                        if (select) select.value = <?php echo json_encode($course['name']); ?>;
                    });
                </script>
            </td>
            <td class="checkbox-cell">
                <input type="checkbox" name="inst_aisha_courses[<?php echo $index; ?>][am]" <?php checked($course['am'], 1); ?>>
            </td>
            <td class="checkbox-cell">
                <input type="checkbox" name="inst_aisha_courses[<?php echo $index; ?>][pm]" <?php checked($course['pm'], 1); ?>>
            </td>
            <td>
                <input type="text" name="inst_aisha_courses[<?php echo $index; ?>][qualifications]"
                       value="<?php echo esc_attr($course['qualifications']); ?>" required>
            </td>
            <td>
                <button type="button" class="remove-row-btn">Remove</button>
            </td>
        </tr>
        <?php
    }
} else {
    // fallback empty row if no data exists
    ?>
    <tr>
        <td>
            <select class="course-select" name="inst_aisha_courses[0][name]" required>
                <?php echo $aisha_course_options; ?>
            </select>
        </td>
        <td class="checkbox-cell">
            <input type="checkbox" name="inst_aisha_courses[0][am]">
        </td>
        <td class="checkbox-cell">
            <input type="checkbox" name="inst_aisha_courses[0][pm]">
        </td>
        <td>
            <input type="text" name="inst_aisha_courses[0][qualifications]" placeholder="Your qualifications" required>
        </td>
        <td>
            <button type="button" class="remove-row-btn">Remove</button>
        </td>
    </tr>
    <?php
}
?>
</tbody>


    </table>
                    
                    
                    <button type="button" class="add-row-btn" id="add-aisha-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Add Aisha Academy Course
                    </button>
                    
                    <!-- Girls' Hifzul Qur'an School Table -->
                    <h3 class="institution-title">Girls' Hifzul Qur'an School</h3>

                    
                    <table class="courses-table" id="hifz-table">
                        <thead>
                            <tr>
                                <th>Class</th>
                                <th>Qualifications</th>
                                <th>Action</th>
                            </tr>
                        </thead>

<tbody id="hifz-tbody">
<?php
if (!empty($hfqs_courses_saved) && is_array($hfqs_courses_saved)) {
    foreach ($hfqs_courses_saved as $index => $class) {
        ?>
        <tr>
            <td>
                <select class="course-select" name="inst_hifz_classes[<?php echo $index; ?>][name]" required>
                    <?php echo $hzqs_course_options; ?>
                </select>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        let select = document.getElementsByName("inst_hifz_classes[<?php echo $index; ?>][name]")[0];
                        if (select) select.value = <?php echo json_encode($class['name']); ?>;
                    });
                </script>
            </td>
            <td>
                <input type="text" name="inst_hifz_classes[<?php echo $index; ?>][qualifications]"
                       value="<?php echo esc_attr($class['qualifications']); ?>" required>
            </td>
            <td>
                <button type="button" class="remove-row-btn">Remove</button>
            </td>
        </tr>
        <?php
    }
} else {
    // Fallback if no saved data
    ?>
    <tr>
        <td>
            <select class="course-select" name="inst_hifz_classes[0][name]" required>
                <?php echo $hzqs_course_options; ?>
            </select>
        </td>
        <td>
            <input type="text" name="inst_hifz_classes[0][qualifications]" placeholder="Your qualifications" required>
        </td>
        <td>
            <button type="button" class="remove-row-btn">Remove</button>
        </td>
    </tr>
<?php } ?>
</tbody>



                    </table>
                    <button type="button" class="add-row-btn" id="add-hifz-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Add Hifz School Class
                    </button>
                </div>
                
                <div class="form-section">
                    <h3 class="institution-title">Teaching Preferences</h3>
                    <p>Based on your qualifications, identify your top 5 choices of courses to teach, in order of preference.</p>
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">S/No</th>
                                <th>Choices of Courses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01</td>
                                <td>
                                    <input type="text" name="inst_preference1" value="<?php echo esc_attr($inst_employment_inst_preference1); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                            </tr>
                            <tr>
                                <td>02</td>
                                <td>
                                    <input type="text" name="inst_preference2" value="<?php echo esc_attr($inst_employment_inst_preference2); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                            </tr>
                            <tr>
                                <td>03</td>
                                <td>
                                    <input type="text" name="inst_preference3" value="<?php echo esc_attr($inst_employment_inst_preference3); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                            </tr>
                            <tr>
                                <td>04</td>
                                <td>
                                    <input type="text" name="inst_preference4" value="<?php echo esc_attr($inst_employ_inst_preference4); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                            </tr>
                            <tr>
                                <td>05</td>
                                <td>
                                    <input type="text" name="inst_preference5" value="<?php echo esc_attr($inst_empp_inst_preference5); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="form-section">
                    <h3 class="institution-title">Skills & Qualifications</h3>
                    <p>Identify any relevant professional development or training which you have undergone in the last 5 years:</p>
                    <div class="form-group full-width">
                        <textarea name="inst_qualifications"><?php echo esc_textarea($inst_employment_inst_qualifications); ?></textarea>
                    </div>

                    <fieldset>
                        <legend>Language Proficiency</legend>
                        <p style="margin-bottom: 15px; font-style: italic;">Rate your proficiency from 1 (Basic) to 5 (Fluent/Native)</p>
                        
                        <div class="rating-scale">
                            <div class="rating-label">1 - Basic</div>
                            <div class="rating-label">2 - Limited</div>
                            <div class="rating-label">3 - Professional</div>
                            <div class="rating-label">4 - Advanced</div>
                            <div class="rating-label">5 - Fluent/Native</div>
                        </div>
                        
                        <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                            <thead>
                                <tr style="background-color: #f2f2f2;">
                                    <th style="padding: 12px; text-align: left;">Language</th>
                                    <th style="padding: 12px; text-align: center;">Spoken</th>
                                    <th style="padding: 12px; text-align: center;">Written</th>
                                    <th style="padding: 12px; text-align: center;">Reading</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 12px;">English</td>
                                    <td style="padding: 12px; text-align: center;">
                                        <select name="inst_sp_en" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_en_sp, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_en_sp, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_en_sp, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_en_sp, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_en_sp, '5'); ?>>5</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <select name="inst_wr_en" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_en_wr, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_en_wr, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_en_wr, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_en_wr, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_en_wr, '5'); ?>>5</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <select name="inst_rd_en" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_en_rd, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_en_rd, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_en_rd, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_en_rd, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_en_rd, '5'); ?>>5</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px;">Urdu</td>
                                    <td style="padding: 12px; text-align: center;">
                                        <select name="inst_sp_ur" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_ur_sp, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_ur_sp, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_ur_sp, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_ur_sp, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_ur_sp, '5'); ?>>5</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <select name="inst_wr_ur" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_ur_wr, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_ur_wr, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_ur_wr, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_ur_wr, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_ur_wr, '5'); ?>>5</option>
                                        </select>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <select name="inst_rd_ur" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_ur_rd, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_ur_rd, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_ur_rd, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_ur_rd, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_ur_rd, '5'); ?>>5</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px;">
                                        <input type="text" style="width: 100%; padding: 8px; border-radius: 4px;" name="inst_other_lang" placeholder="Other Language" value="<?php echo esc_attr($inst_employment_inst_sp_other ? explode('|', $inst_employment_inst_sp_other)[0] : ''); ?>"/>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                          <select name="inst_sp_other" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_oth_sp, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_oth_sp, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_oth_sp, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_oth_sp, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_oth_sp, '5'); ?>>5</option>
                                        </select>
                                        
                                        
                                        
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                          <select name="inst_rd_other" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_oth_rd, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_oth_rd, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_oth_rd, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_oth_rd, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_oth_rd, '5'); ?>>5</option>
                                        </select>
                                        
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                             <select name="inst_wr_other" style="width: 80px; padding: 8px; border-radius: 4px;">
                                            <option value="1" <?php selected($inst_lang_oth_wr, '1'); ?>>1</option>
                                            <option value="2" <?php selected($inst_lang_oth_wr, '2'); ?>>2</option>
                                            <option value="3" <?php selected($inst_lang_oth_wr, '3'); ?>>3</option>
                                            <option value="4" <?php selected($inst_lang_oth_wr, '4'); ?>>4</option>
                                            <option value="5" <?php selected($inst_lang_oth_wr, '5'); ?>>5</option>
                                        </select>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </div>
                
                <div class="form-section">
                    <h3 class="institution-title">References</h3>
                    <p>Provide the contact information for references</p>
                    
                    <h4 style="margin: 20px 0 10px 0; color: var(--primary-color);">Jama'at Reference</h4>
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th>Majlis</th>
                                <th>Local President Name</th>
                                <th>Phone Number</th>
                                <th>Jama'at Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="inst_ref_jamat_majlis" value="<?php echo esc_attr($inst_ref_jmt_mjlis); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_jamat_president" value="<?php echo esc_attr($inst_ref_jmt_president); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_jamat_phone" value="<?php echo esc_attr($inst_ref_jmt_phone); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="email" name="inst_ref_jamat_email" value="<?php echo esc_attr($inst_ref_jmt_email); ?>" required>
                                    <div class="error">Please enter a valid email</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h4 style="margin: 20px 0 10px 0; color: var(--primary-color);">Professional Reference #1</h4>
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Phone Number</th>
                                <th>Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="inst_ref_prof1_name" value="<?php echo esc_attr($inst_ref_prof1_name); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_prof1_title" value="<?php echo esc_attr($inst_ref_prof1_title); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_prof1_org" value="<?php echo esc_attr($inst_ref_prof1_org); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_prof1_phone" value="<?php echo esc_attr($inst_ref_prof1_phone); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="email" name="inst_ref_prof1_email" value="<?php echo esc_attr($inst_ref_prof1_email); ?>" required>
                                    <div class="error">Please enter a valid email</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h4 style="margin: 20px 0 10px 0; color: var(--primary-color);">Professional Reference #2</h4>
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Phone Number</th>
                                <th>Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="inst_ref_prof2_name" value="<?php echo esc_attr($inst_ref_prof2_name); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_prof2_title" value="<?php echo esc_attr($inst_ref_prof2_title); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_prof2_org" value="<?php echo esc_attr($inst_ref_prof2_org); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_prof2_phone" value="<?php echo esc_attr($inst_ref_prof2_phone); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="email" name="inst_ref_prof2_email" value="<?php echo esc_attr($inst_ref_prof2_email); ?>" required>
                                    <div class="error">Please enter a valid email</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h4 style="margin: 20px 0 10px 0; color: var(--primary-color);">Personal Reference</h4>
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Relationship</th>
                                <th>Phone Number</th>
                                <th>Email Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="inst_ref_pers_name" value="<?php echo esc_attr($inst_ref_pers_name); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_pers_relation" value="<?php echo esc_attr($inst_ref_pers_relation); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_ref_pers_phone" value="<?php echo esc_attr($inst_ref_pers_phone); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="email" name="inst_ref_pers_email" value="<?php echo esc_attr($inst_ref_pers_email); ?>" required>
                                    <div class="error">Please enter a valid email</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="form-section">
                    <h3 class="institution-title">Background Check Consent</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>If asked, do you consent to a background check?</label>
                            <div class="radio-group">
                                <div class="radio-option">
                                    <input type="radio" id="inst_bgCheckYes" name="inst_bg_check" value="Yes" <?php checked($bgcheck, 'Yes'); ?>>

                                    <label for="inst_bgCheckYes">Yes</label>
                                </div>
                                <div class="radio-option">
                                <input type="radio" id="inst_bgCheckYes" name="inst_bg_check" value="No" <?php checked($bgcheck, 'No'); ?>>

                                    <label for="inst_bgCheckNo">No</label>
                                </div>
                            </div>
                            <div class="error">This field is required</div>
                        </div>
                    </div>

                    <h3 class="institution-title">Disclaimer</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <textarea name="inst_disclaimer" readonly style="width: 100%; height: 100px; padding: 15px;">
I, the applicant, certify that the information provided is true to the best of my knowledge. If this application leads to my eventual employment, I understand that any false or misleading information in my application or interview may result in my employment being terminated.
                            </textarea>
                        </div>
                    </div>
                    
                    <table class="courses-table">
                        <thead>
                            <tr>
                                <th>Print Name</th>
                                <th>Signature</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" name="inst_print_name" value="<?php echo esc_attr($inst_emp_print_name); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="text" name="inst_signature" value="<?php echo esc_attr($inst_emp_sign); ?>" placeholder="Type your full name as signature" required>
                                    <div class="error">This field is required</div>
                                </td>
                                <td>
                                    <input type="date" name="inst_date" value="<?php echo esc_attr($inst_emp_submission_date); ?>" required>
                                    <div class="error">This field is required</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="margin: 20px 0 10px 0; font-style: italic;">Kindly submit this completed Application Form alongside your Resume and Cover Letter</p>
                    <p style="text-align: center; font-weight: 600; color: var(--primary-color);">Jazakumullah Ta'ala Ahsanal Jaza!</p>
                    
                    <div class="form-group full-width" style="margin-top: 30px;">
                        <div class="checkbox-group">
                            <div class="checkbox-option" style="display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" id="inst_agreeTerms" name="inst_agree_terms" required>
                                <label for="inst_agreeTerms" style="margin: 0;">
                                    I agree to the <a href="/terms-and-conditions" target="_blank" style="color: var(--secondary-color);">terms and conditions</a>.
                                </label>
                            </div>
                        </div>
                        <div class="error">You must agree to the terms and conditions</div>
                    </div>
                    
                    <div class="success-message">
                        Your application has been submitted successfully! We will contact you soon.
                    </div>
                    
                    <button type="submit" class="submit-btn">Submit Application</button>
                </div>
            </div>
        </div>
    </form>


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Elements
    const aishaTbody = document.getElementById('aisha-tbody');
    const addAishaRowBtn = document.getElementById('add-aisha-row');
    const hifzTbody = document.getElementById('hifz-tbody');
    const addHifzRowBtn = document.getElementById('add-hifz-row');

    let aishaRowCount = aishaTbody.querySelectorAll('tr').length;
    let hifzRowCount = hifzTbody.querySelectorAll('tr').length;

    // These must be defined in PHP before this script:
    const aishaCourseOptions = `<?php echo addslashes($aisha_course_options); ?>`;
    const hifzCourseOptions = `<?php echo addslashes($hzqs_course_options); ?>`;

    // Add Aisha Academy row
    function addAishaRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="course-select" name="inst_aisha_courses[${aishaRowCount}][name]" required>
                    ${aishaCourseOptions}
                </select>
            </td>
            <td class="checkbox-cell">
                <input type="checkbox" name="inst_aisha_courses[${aishaRowCount}][am]">
            </td>
            <td class="checkbox-cell">
                <input type="checkbox" name="inst_aisha_courses[${aishaRowCount}][pm]">
            </td>
            <td>
                <input type="text" name="inst_aisha_courses[${aishaRowCount}][qualifications]" placeholder="Your qualifications" required>
            </td>
            <td>
                <button type="button" class="remove-row-btn">Remove</button>
            </td>
        `;
        aishaTbody.appendChild(row);
        aishaRowCount++;

        // Remove row handler
        row.querySelector('.remove-row-btn').addEventListener('click', function () {
            if (aishaTbody.querySelectorAll('tr').length > 1) {
                row.remove();
            }
        });
    }

    // Add Hifz row
    function addHifzRow() {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="course-select" name="inst_hifz_classes[${hifzRowCount}][name]" required>
                    ${hifzCourseOptions}
                </select>
            </td>
            <td>
                <input type="text" name="inst_hifz_classes[${hifzRowCount}][qualifications]" placeholder="Your qualifications" required>
            </td>
            <td>
                <button type="button" class="remove-row-btn">Remove</button>
            </td>
        `;
        hifzTbody.appendChild(row);
        hifzRowCount++;

        // Remove row handler
        row.querySelector('.remove-row-btn').addEventListener('click', function () {
            if (hifzTbody.querySelectorAll('tr').length > 1) {
                row.remove();
            }
        });
    }

    // Button bindings
    if (addAishaRowBtn) {
        addAishaRowBtn.addEventListener('click', addAishaRow);
    }

    if (addHifzRowBtn) {
        addHifzRowBtn.addEventListener('click', addHifzRow);
    }

    // Initial remove button setup
    document.querySelectorAll('.remove-row-btn').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const tbody = row.closest('tbody');
            if (tbody.querySelectorAll('tr').length > 1) {
                row.remove();
            }
        });
    });
});
</script>

</body>
</html>