<?php
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login')); // Redirect to your login page
    exit;
}
$plugin_root_file = WP_PLUGIN_DIR . '/student-manager/student-manager.php';
$plugin_root_url = plugin_dir_url($plugin_root_file);




// Helper function to format dates for HTML input
function format_date_for_input($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    return $timestamp ? date('Y-m-d', $timestamp) : '';
}
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

$first_name   = esc_attr($current_user->first_name);
$last_name    = esc_attr($current_user->last_name);
$email        = esc_attr($current_user->user_email);
$dob        = esc_attr($current_user->date_of_birth);

// User meta from previous submission
$dob = esc_attr(get_user_meta($user_id, 'date_of_birth', true));
$address           = esc_attr(get_user_meta($user_id, 'aisha_std_address', true));
$city              = esc_attr(get_user_meta($user_id, 'aisha_std_city', true));
$postal_code       = esc_attr(get_user_meta($user_id, 'aisha_std_postal_code', true));
$country           = esc_attr(get_user_meta($user_id, 'aisha_std_country', true));
$aims_code         = esc_attr(get_user_meta($user_id, 'aisha_std_aims_code', true));
$halqa             = esc_attr(get_user_meta($user_id, 'aisha_std_halqa', true));
$waqifa            = esc_attr(get_user_meta($user_id, 'aisha_std_waqifa', true));
$waqif_number      = esc_attr(get_user_meta($user_id, 'aisha_std_waqif_number', true));
$returning         = esc_attr(get_user_meta($user_id, 'aisha_std_returning', true));
$student_number    = esc_attr(get_user_meta($user_id, 'aisha_std_student_number', true));

// Emergency Contact 1
$em1_name          = esc_attr(get_user_meta($user_id, 'aisha_std_emergency1_name', true));
$em1_phone         = esc_attr(get_user_meta($user_id, 'aisha_std_emergency1_phone', true));
$em1_email         = esc_attr(get_user_meta($user_id, 'aisha_std_emergency1_email', true));
$em1_relation      = esc_attr(get_user_meta($user_id, 'aisha_std_emergency1_relation', true));

// Emergency Contact 2
$em2_name          = esc_attr(get_user_meta($user_id, 'aisha_std_emergency2_name', true));
$em2_phone         = esc_attr(get_user_meta($user_id, 'aisha_std_emergency2_phone', true));
$em2_email         = esc_attr(get_user_meta($user_id, 'aisha_std_emergency2_email', true));
$em2_relation      = esc_attr(get_user_meta($user_id, 'aisha_std_emergency2_relation', true));

// Additional Info
$current_program   = esc_attr(get_user_meta($user_id, 'aisha_std_current_program', true));
$awards            = esc_attr(get_user_meta($user_id, 'aisha_std_awards', true));
$quran             = esc_attr(get_user_meta($user_id, 'aisha_std_quran', true));
$curr_office       = esc_attr(get_user_meta($user_id, 'aisha_std_current_office', true));
$curr_office_det   = esc_attr(get_user_meta($user_id, 'aisha_std_current_office_details', true));
$past_office       = esc_attr(get_user_meta($user_id, 'aisha_std_past_office', true));
$past_office_det   = esc_attr(get_user_meta($user_id, 'aisha_std_past_office_details', true));

// Language Proficiency
$eng_sp            = esc_attr(get_user_meta($user_id, 'aisha_std_eng_sp', true));
$eng_wr            = esc_attr(get_user_meta($user_id, 'aisha_std_eng_wr', true));
$eng_rd            = esc_attr(get_user_meta($user_id, 'aisha_std_eng_rd', true));

$urdu_sp = get_user_meta($user_id, 'aisha_std_urdu_sp', true);
$urdu_wr = get_user_meta($user_id, 'aisha_std_urdu_wr', true);
$urdu_rd           = esc_attr(get_user_meta($user_id, 'aisha_std_urdu_rd', true));

$other_lang        = esc_attr(get_user_meta($user_id, 'aisha_std_other_lang', true));
$other_sp          = esc_attr(get_user_meta($user_id, 'aisha_std_other_sp', true));
$other_wr          = esc_attr(get_user_meta($user_id, 'aisha_std_other_wr', true));
$other_rd          = esc_attr(get_user_meta($user_id, 'aisha_std_other_rd', true));
$other_languages   = esc_attr(get_user_meta($user_id, 'aisha_std_other_languages', true));
$phone = esc_attr(get_user_meta($user_id, 'phone', true));

$phone_code = '';
$phone_number = '';
if (strpos($phone, '|') !== false) {
    list($phone_code, $phone_number) = explode('|', $phone);
} else {
    $phone_number = $phone;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Aisha Academy Admissions Form</title>
<link rel="stylesheet" href="<?php echo plugins_url('assets/css/student-manager.css', $plugin_root_file); ?>">
</head>
<body>

<h2>Aisha Academy Admissions Form</h2>
<div id="formResponse"></div>

<div id="aisha-form-wrapper">
    <div id="form-message" style="display:none; padding:10px; margin-bottom:15px;"></div>
<form id="studentProfileForm" method="POST" enctype="multipart/form-data" novalidate>
  <input type="hidden" name="action" value="handle_aisha_profile_ajax">
  <input type="hidden" id="save_only" name="save_only" value="0">
<input type="hidden" name="is_update" value="1">

  <?php wp_nonce_field('submit_aisha_profile_ajax', 'aisha_profile_nonce'); ?>

<?php if (get_user_meta(get_current_user_id(), 'aisha_form_submitted', true) === 'yes') : ?>
    <input type="hidden" name="is_update" value="1">
<?php endif; ?>


   <div class="progress-container">
            <div class="progress-bar">
                  <div class="progress-step active" data-step="1">Personal Info</div>
                  <div class="progress-step" data-step="2">Emergency Contacts</div>
                  <div class="progress-step" data-step="3">Education</div>
                  <div class="progress-step" data-step="4">Employment</div>
                  <div class="progress-step" data-step="5">Additional Info</div>
                  <div class="progress-step" data-step="6">Documents</div>
                </div>
        </div>

<div class="form-step active" id="step-1">
             
                 <h3>Personal Information</h3>
                     <table>
      <tr>
        <td>First Name:</td>
            <td><input type="text" name="std_first_name" size="15" value="<?php echo $first_name; ?>" required></td>
        <td>Last Name:</td>
            <td><input type="text" name="std_last_name" size="15" value="<?php echo $last_name; ?>"required></td>
      </tr>
      <tr>
        <td>Phone Code:</td>
            <td><input type="text" name="std_phone_code" size="5" value="<?php echo $phone_code; ?>"required></td>
        <td>Phone Number:</td>
            <td><input type="text" name="std_phone_number" size="15" value="<?php echo $phone_number; ?>"required></td>
      </tr>
      
          
                      <tr>
        <td>Email:</td>
            <td><input type="email" name="std_email" size="20" value="<?php echo $email; ?>" required></td>
        <td>Date of Birth:</td>
            <td><input type="date" name="std_dob" size="10" value="<?php echo $dob; ?>" required></td>
      </tr>
      <tr>
        <td>Country:</td>
        <td>

<select name="std_country" id="std_country" required>
    <option value="">Select Country</option>
</select>


        </td>
        <td id="aims-code-label">AIMS Code:</td>
        <td id="aims-code-field"><input type="text" name="std_aims_code" size="10" value="<?php echo $aims_code; ?>"></td>
      </tr>
      <tr>
        <td>Halqa/Majlis:</td>
        <td><input type="text" name="std_halqa" size="15" value="<?php echo $halqa; ?>"></td>
        <td>Address:</td>
        <td><input type="text" name="std_address" size="20" value="<?php echo $address; ?>" required></td>
      </tr>
      <tr>
        <td>Postal Code:</td>
        <td><input type="text" name="std_postal_code" size="7" value="<?php echo $postal_code; ?>" required></td>
        <td>City/Province:</td>
        <td><input type="text" name="std_city" size="15" value="<?php echo $city; ?>" required></td>
      </tr>
      <tr>
        <td>Waqifa-e-Nau?</td>
        <td>
              <input type="radio" name="std_waqifa" value="yes" <?php checked($waqifa, 'yes'); ?> required>Yes
              <input type="radio" name="std_waqifa" value="no" <?php checked($waqifa, 'no'); ?>>No
            </td>

        <td>Waqf Number:</td>
        <td><input type="text" name="std_waqif_number" size="10" value="<?php echo $waqif_number; ?>" <?php echo ($waqifa !== 'yes') ? 'disabled' : ''; ?>></td>
      </tr>
      <tr>
        <td>Returning Student?</td>
        <td>
          <input type="radio" name="std_returning" value="yes" <?php checked($returning, 'yes'); ?> required>Yes
          <input type="radio" name="std_returning" value="no" <?php checked($returning, 'no'); ?>>No
        </td>
        <td>Student Number:</td>
        <td><input type="text" name="std_student_number" size="10" value="<?php echo $student_number; ?>" <?php echo ($returning !== 'yes') ? 'disabled' : ''; ?>></td>
      </tr>
    </table>
</div>


<div class="form-step" id="step-2">
    <h3>Emergency Contact 1</h3>
    <table>
      <tr>
        <td>Name:</td>
        <td><input type="text" name="std_emergency1_name" size="20" value="<?php echo $em1_name; ?>" required></td>
        <td>Phone:</td>
        <td><input type="tel" name="std_emergency1_phone" size="15" value="<?php echo $em1_phone; ?>" required></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type="email" name="std_emergency1_email" size="20" value="<?php echo $em1_email; ?>"></td>
        <td>Relationship:</td>
        <td><input type="text" name="std_emergency1_relation" size="15" value="<?php echo $em1_relation; ?>" required></td>
      </tr>
    </table>
    
    <h3>Emergency Contact 2</h3>
    <table>
      <tr>
        <td>Name:</td>
        <td><input type="text" name="std_emergency2_name" size="20" value="<?php echo $em2_name; ?>"></td>
        <td>Phone:</td>
        <td><input type="tel" name="std_emergency2_phone" size="15" value="<?php echo $em2_phone; ?>"></td>
      </tr>
      <tr>
        <td>Email:</td>
        <td><input type="email" name="std_emergency2_email" size="20" value="<?php echo $em2_email; ?>"></td>
        <td>Relationship:</td>
        <td><input type="text" name="std_emergency2_relation" size="15" value="<?php echo $em2_relation; ?>"></td>
      </tr>
    </table>

</div>

<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

$education_history = maybe_unserialize(get_user_meta($user_id, 'aisha_std_education_history', true));
if (!is_array($education_history) || empty($education_history)) {
    $education_history = [
        ['institution' => '', 'from_date' => '', 'to_date' => '', 'degree' => '']
    ];
}

$employment_history = maybe_unserialize(get_user_meta($user_id, 'aisha_std_employment_history', true));
if (!is_array($employment_history) || empty($employment_history)) {
    $employment_history = [
        ['position' => '', 'from_date' => '', 'to_date' => '', 'organization' => '']
    ];
}
?>

<!-- Step 3: Education -->
<div class="form-step" id="step-3">
    <h3>Education</h3>
    <table id="educationTable">
        <thead>
            <tr>
                <th>Institution</th>
                <th>From</th>
                <th>To</th>
                <th>Degree/Grades</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($education_history as $index => $edu): ?>
            <tr>
                <td><input type="text" name="std_education_institution_<?php echo $index; ?>" value="<?php echo esc_attr($edu['institution']); ?>"></td>
                <td><input type="date" name="std_education_from_<?php echo $index; ?>" value="<?php echo esc_attr($edu['from_date']); ?>"></td>
                <td><input type="date" name="std_education_to_<?php echo $index; ?>" value="<?php echo esc_attr($edu['to_date']); ?>"></td>
                <td><input type="text" name="std_education_degree_<?php echo $index; ?>" value="<?php echo esc_attr($edu['degree']); ?>"></td>
                <td><button type="button" class="remove-row" onclick="this.closest('tr').remove()">&times;</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="button" class="btn" onclick="addEducationRow()">Add Education Row</button><br>
</div>

<!-- Step 4: Employment/Volunteer -->
<div class="form-step" id="step-4">
    <h3>Employment/Volunteer</h3>
    <table id="employmentTable">
        <thead>
            <tr>
                <th>Position</th>
                <th>From</th>
                <th>To</th>
                <th>Organization</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employment_history as $index => $emp): ?>
            <tr>
                <td><input type="text" name="std_employment_position_<?php echo $index; ?>" value="<?php echo esc_attr($emp['position']); ?>"></td>
                <td><input type="date" name="std_employment_from_<?php echo $index; ?>" value="<?php echo esc_attr($emp['from_date']); ?>"></td>
                <td><input type="date" name="std_employment_to_<?php echo $index; ?>" value="<?php echo esc_attr($emp['to_date']); ?>"></td>
                <td><input type="text" name="std_employment_organization_<?php echo $index; ?>" value="<?php echo esc_attr($emp['organization']); ?>"></td>
                <td><button type="button" class="remove-row" onclick="this.closest('tr').remove()">&times;</button></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button type="button" class="btn" onclick="addEmploymentRow()">Add Employment Row</button>
</div>







<div class="form-step" id="step-5">
    <h3>Additional Information</h3>
    <table>
      <tr>
        <td>Currently enrolled program:</td>
        <td><textarea name="std_current_program" rows="2" cols="40"><?php echo $current_program; ?></textarea></td>
      </tr>
      <tr>
        <td>Academic awards:</td>
        <td><textarea name="std_awards" rows="2" cols="40"><?php echo $awards; ?></textarea></td>
      </tr>
      <tr>
        <td>Qur'an recitation:</td>
        <td>
          <input type="radio" name="std_quran" value="Simple Recitation Completed" <?php checked($quran, 'Simple Recitation Completed'); ?> required>Simple Recitation Completed
          <input type="radio" name="std_quran" value="Completed with Translation" <?php checked($quran, 'Completed with Translation'); ?> required>Completed with Translation
          <input type="radio" name="std_quran" value="Not Completed" <?php checked($quran, 'Not Completed'); ?> required>Not Completed
        </td>
      </tr>
      <tr>
        <td>Current office bearer?</td>
        <td>
          <input type="radio" name="std_current_office" value="yes" <?php checked($curr_office, 'yes'); ?> required>Yes
          <input type="radio" name="std_current_office" value="no" <?php checked($curr_office, 'no'); ?>>No
          Details: <input type="text" name="std_current_office_details" size="20" value="<?php echo $curr_office_det; ?>">
        </td>
      </tr>
      <tr>
        <td>Past office bearer?</td>
        <td>
          <input type="radio" name="std_past_office" value="yes" <?php checked($past_office, 'yes'); ?> required>Yes
          <input type="radio" name="std_past_office" value="no" <?php checked($past_office, 'no'); ?>>No
          Details: <input type="text" name="std_past_office_details" size="20" value="<?php echo $past_office_det; ?>">
        </td>
      </tr>
    </table>

    <h3>Language Proficiency (1=minimal, 5=fluent)</h3>
    <table>
      <tr>
        <th>Language</th>
        <th>Spoken</th>
        <th>Written</th>
        <th>Reading</th>
      </tr>
      <tr>
        <td>English</td>
        <td>
          <select name="std_eng_sp" required>
            <option value="">Select</option>
            <option value="1" <?php selected($eng_sp, '1'); ?>>1</option>
            <option value="2" <?php selected($eng_sp, '2'); ?>>2</option>
            <option value="3" <?php selected($eng_sp, '3'); ?>>3</option>
            <option value="4" <?php selected($eng_sp, '4'); ?>>4</option>
            <option value="5" <?php selected($eng_sp, '5'); ?>>5</option>
          </select>
        </td>
        <td>
          <select name="std_eng_wr" required>
            <option value="">Select</option>
            <option value="1" <?php selected($eng_wr, '1'); ?>>1</option>
            <option value="2" <?php selected($eng_wr, '2'); ?>>2</option>
            <option value="3" <?php selected($eng_wr, '3'); ?>>3</option>
            <option value="4" <?php selected($eng_wr, '4'); ?>>4</option>
            <option value="5" <?php selected($eng_wr, '5'); ?>>5</option>
          </select>
        </td>
        <td>
          <select name="std_eng_rd" required>
            <option value="">Select</option>
            <option value="1" <?php selected($eng_rd, '1'); ?>>1</option>
            <option value="2" <?php selected($eng_rd, '2'); ?>>2</option>
            <option value="3" <?php selected($eng_rd, '3'); ?>>3</option>
            <option value="4" <?php selected($eng_rd, '4'); ?>>4</option>
            <option value="5" <?php selected($eng_rd, '5'); ?>>5</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Urdu</td>
        <td>
          <select name="std_urdu_sp" required>
            <option value="">Select</option>
            <option value="1" <?php selected($urdu_sp, '1'); ?>>1</option>
            <option value="2" <?php selected($urdu_sp, '2'); ?>>2</option>
            <option value="3" <?php selected($urdu_sp, '3'); ?>>3</option>
            <option value="4" <?php selected($urdu_sp, '4'); ?>>4</option>
            <option value="5" <?php selected($urdu_sp, '5'); ?>>5</option>
          </select>
        </td>
        <td>
          <select name="std_urdu_wr" required>
            <option value="">Select</option>
            <option value="1" <?php selected($urdu_wr, '1'); ?>>1</option>
            <option value="2" <?php selected($urdu_wr, '2'); ?>>2</option>
            <option value="3" <?php selected($urdu_wr, '3'); ?>>3</option>
            <option value="4" <?php selected($urdu_wr, '4'); ?>>4</option>
            <option value="5" <?php selected($urdu_wr, '5'); ?>>5</option>
          </select>
        </td>
        <td>
          <select name="std_urdu_rd" required>
            <option value="">Select</option>
            <option value="1" <?php selected($urdu_rd, '1'); ?>>1</option>
            <option value="2" <?php selected($urdu_rd, '2'); ?>>2</option>
            <option value="3" <?php selected($urdu_rd, '3'); ?>>3</option>
            <option value="4" <?php selected($urdu_rd, '4'); ?>>4</option>
            <option value="5" <?php selected($urdu_rd, '5'); ?>>5</option>
          </select>
        </td>
      </tr>
      <tr>
        <td><input type="text" name="std_other_lang" placeholder="Other" size="10" value="<?php echo $other_lang; ?>"></td>
        <td>
          <select name="std_other_sp">
            <option value="">Select</option>
            <option value="1" <?php selected($other_sp, '1'); ?>>1</option>
            <option value="2" <?php selected($other_sp, '2'); ?>>2</option>
            <option value="3" <?php selected($other_sp, '3'); ?>>3</option>
            <option value="4" <?php selected($other_sp, '4'); ?>>4</option>
            <option value="5" <?php selected($other_sp, '5'); ?>>5</option>
          </select>
        </td>
        <td>
          <select name="std_other_wr">
            <option value="">Select</option>
            <option value="1" <?php selected($other_wr, '1'); ?>>1</option>
            <option value="2" <?php selected($other_wr, '2'); ?>>2</option>
            <option value="3" <?php selected($other_wr, '3'); ?>>3</option>
            <option value="4" <?php selected($other_wr, '4'); ?>>4</option>
            <option value="5" <?php selected($other_wr, '5'); ?>>5</option>
          </select>
        </td>
        <td>
          <select name="std_other_rd">
            <option value="">Select</option>
            <option value="1" <?php selected($other_rd, '1'); ?>>1</option>
            <option value="2" <?php selected($other_rd, '2'); ?>>2</option>
            <option value="3" <?php selected($other_rd, '3'); ?>>3</option>
            <option value="4" <?php selected($other_rd, '4'); ?>>4</option>
            <option value="5" <?php selected($other_rd, '5'); ?>>5</option>
          </select>
        </td>
      </tr>
    </table>
    <p>Other languages: <input type="text" name="std_other_languages" size="30" value="<?php echo $other_languages; ?>"></p>
</div>


<div class="form-step" id="step-6">
    <h3>Document Upload</h3>

    <?php
    $gov_id_url = get_user_meta($user_id, 'aisha_std_government_id', true);
    $edu_cert_url = get_user_meta($user_id, 'aisha_std_education_certs', true);
    $exp_letter_url = get_user_meta($user_id, 'aisha_std_experience_letter', true);
    $receipt_url = get_user_meta($user_id, 'aisha_std_payment_receipt', true);

    function show_file_preview($label, $name, $file_url) {
        $html = "<tr><td>{$label}</td><td>";
        $html .= "<input type='file' name='{$name}' accept='.jpg,.jpeg,.png,.pdf' data-meta-key='aisha_{$name}' onchange=\"previewUpload(this, '{$name}_preview')\">";
        $html .= "<div id='{$name}_preview'>";

        if ($file_url) {
            $ext = pathinfo($file_url, PATHINFO_EXTENSION);
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                $html .= "<img src='{$file_url}' style='max-height:100px;' />";
            } elseif (strtolower($ext) === 'pdf') {
                $html .= "<a href='{$file_url}' target='_blank'>📄 View PDF</a>";
            }
            $html .= "<br><button type='button' onclick=\"removeUploadedFile('aisha_{$name}')\">❌ Remove</button>";
        }

        $html .= "</div></td></tr>";
        return $html;
    }
    ?>

    <table>
        <?= show_file_preview("Government ID (required):", "std_government_id", $gov_id_url); ?>
        <?= show_file_preview("Education Certificates (required):", "std_education_certs", $edu_cert_url); ?>
        <?= show_file_preview("Experience Letter (optional):", "std_experience_letter", $exp_letter_url); ?>
        <?= show_file_preview("Upload Receipt:", "std_payment_receipt", $receipt_url); ?>
    </table>

    <hr>

    <h3>Payment Instructions</h3>
    <ul>
        <li style="color:red;"><strong>Registeration Fees is $75.00 Cad</strong></li>
        <li>Pay via the <a href="https://amjinc.ca" target="_blank">AMJ Portal at Online Donation (amjinc.ca)</a></li>
        <li>Pay through your local <strong>Halqa Secretary Māl</strong></li>
        <li>Upload your payment receipt above</li>
    </ul>

    <h3>Terms & Conditions</h3>
    <div style="margin-top: 10px;">
        <input class="form-check-input" type="checkbox" name="admission_form_complete" id="termsCheck" required>
        <label class="form-check-label" for="termsCheck">
            I accept all the 
            <a href="https://digitalconsulter.com/privacy-policy/" target="_blank">Terms and Conditions</a>. 
            I confirm all the information provided is accurate and has been carefully reviewed.
        </label>
    </div>
</div>
<hr>
<!-- Step navigation buttons -->
<button type="button" class="btn" id="prev-btn">Previous</button>
<button type="button" id="submit-btn">Submit Application</button>
<button type="button" id="save-btn">Save & Continue Later</button>
<button type="button" class="btn" id="next-btn">Next</button>

<div id="form-message" style="display:none;"></div>








<script>
const ajaxurl_data = {
    ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
    nonce: "<?php echo wp_create_nonce('submit_aisha_profile_ajax'); ?>"
};

jQuery(document).ready(function ($) {
    let currentStep = 1;
    const totalSteps = $('.form-step').length;

    let educationRowCount = <?php echo count($education_history); ?>;
    let employeeRowCount = <?php echo count($employment_history); ?>;


function showStep(step) {
    $('.form-step').removeClass('active');
    $('#step-' + step).addClass('active');

    $('#prev-btn').prop('disabled', step === 1);

 if (step === totalSteps) {
    $('#next-btn').hide();        // Hide "Save & Continue"
    $('#save-btn').hide();        // ✅ Hide "Save & Continue Later"
    $('#submit-btn').show();      // Show "Submit"
} else {
    $('#next-btn').show();
    $('#save-btn').show();        // ✅ Show "Save & Continue Later"
    $('#submit-btn').hide();
}
    $('.progress-step').each(function () {
        const stepNumber = parseInt($(this).data('step'));
        $(this).removeClass('active completed');
        if (stepNumber < step) $(this).addClass('completed');
        else if (stepNumber === step) $(this).addClass('active');
    });

    document.getElementById('aisha-form-wrapper')?.scrollIntoView({ behavior: 'smooth' });
}

    function nextStep() {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function validateAllSteps() {
        let isValid = true;
        $(`#step-${currentStep} [required]`).each(function () {
            const $field = $(this);
            if ($field.is(':disabled') || !$field.is(':visible')) return;

            const type = $field.attr('type');
            if (type === 'radio') {
                const name = $field.attr('name');
                if ($(`input[name="${name}"]:checked`).length === 0) {
                    isValid = false;
                    $field.closest('label, td').addClass('input-error');
                    return false;
                }
            } else if (!$field.val().trim()) {
                isValid = false;
                $field.addClass('input-error');
                return false;
            } else {
                $field.removeClass('input-error');
            }
        });
        return isValid;
    }

    function validateVisibleFields() {
        let isValid = true;
        $('.form-step.active [required]').each(function () {
            const value = $(this).val().trim();
            if (!value) {
                isValid = false;
                $(this).addClass('input-error');
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            } else {
                $(this).removeClass('input-error');
            }
        });
        return isValid;
    }

    $('#next-btn').on('click', function () {
        if (validateAllSteps()) {
            nextStep();
        } else {
            alert('Please fill all required fields in this step.');
        }
    });

    $('#prev-btn').on('click', prevStep);

    $('#submit-btn').on('click', function () {
        $('#save_only').val('0');
        $('#studentProfileForm').submit();
    });

    $('#save-btn').on('click', function () {
        $('#save_only').val('1');
        $('#studentProfileForm').submit();
    });

    $('#studentProfileForm').on('submit', function (e) {
    e.preventDefault();

    if (!validateVisibleFields()) {
        alert('Please fill in all required fields in this step.');
        return;
    }

    // Check if we're on the last step and user is submitting (not just saving)
    if (currentStep === totalSteps && $('#save_only').val() === '0') {
        const requiredFiles = {
            'aisha_std_government_id': <?php echo $gov_id_url ? 'true' : 'false'; ?>,
            'aisha_std_education_certs': <?php echo $edu_cert_url ? 'true' : 'false'; ?>,
            'aisha_std_payment_receipt': <?php echo $receipt_url ? 'true' : 'false'; ?>
        };

        let docStepValid = true;

        Object.keys(requiredFiles).forEach(metaKey => {
            const hasUploaded = requiredFiles[metaKey];
            const input = $(`input[data-meta-key="${metaKey}"]`);

            if (!hasUploaded && input.length && !input.val()) {
                docStepValid = false;
                input.addClass('input-error');
                input[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                input.removeClass('input-error');
            }
        });

        if (!docStepValid) {
            alert('Please upload all required documents before submitting.');
            return;
        }
    }

    let formData = new FormData(this);
    formData.append('action', 'submit_aisha_profile');
    formData.append('aisha_profile_nonce', ajaxurl_data.nonce);
    formData.append('save_only', $('#save_only').val());

    // ✅ Append is_update manually if it exists in the form
    const isUpdateField = $('#studentProfileForm input[name="is_update"]');
    if (isUpdateField.length) {
        formData.append('is_update', isUpdateField.val());
    }

    $('#submit-btn, #save-btn').prop('disabled', true).html('Processing...');

    $.ajax({
        url: ajaxurl_data.ajax_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log('AJAX Response:', response);
            if (response.success) {
                if (response.data.status === 'completed') {
                    $('#form-message').html(`<strong style="color:green;">✅ ${response.data.message}</strong>`).show();

                    // ✅ Redirect based on server-provided URL
                    setTimeout(() => {
                        window.location.href = response.data.redirect;
                    }, 1000);

                } else if (response.data.status === 'incomplete') {
                    $('#form-message').html(`<strong style="color:red;">❌ ${response.data.message}</strong>`).show();
                    document.getElementById('admission_form_complete')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                const errorMsg = typeof response.data === 'string' ? response.data : (response.data?.message || 'Unknown error occurred.');
                $('#form-message').html(`<strong style="color:red;">❌ ${errorMsg}</strong>`).show();
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error, xhr.responseText);
            $('#form-message').html(`<strong style="color:red;">❌ AJAX failed: ${error}</strong>`).show();
        },
        complete: function () {
            $('.form-step input, .form-step select, .form-step textarea').prop('disabled', false);
            $('#submit-btn').prop('disabled', false).html('Submit Application');
            $('#save-btn').prop('disabled', false).html('Save & Continue Later');
            $('#save_only').val('0');
        }
    });
});

    $('#termsCheck').on('change', function () {
        if (this.checked) {
            $.post(ajaxurl_data.ajax_url, {
                action: 'update_is_profile',
                _ajax_nonce: ajaxurl_data.nonce
            }, function (res) {
                if (!res.success) {
                    console.warn('Failed to update profile status');
                }
            }).fail(function (xhr) {
                console.error("Checkbox AJAX failed:", xhr.responseText);
            });
        }
    });

    // Education Rows
    window.addEducationRow = function () {
        $('#educationTable tbody').append(`
            <tr>
                <td><input type="text" name="std_education_institution_${educationRowCount}" required></td>
                <td><input type="date" name="std_education_from_${educationRowCount}" required></td>
                <td><input type="date" name="std_education_to_${educationRowCount}" required></td>
                <td><input type="text" name="std_education_degree_${educationRowCount}" required></td>
                <td><button type="button" class="remove-row">×</button></td>
            </tr>
        `);
        educationRowCount++;
    };
    $('#educationTable').on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });

    // Employment Rows
    window.addEmploymentRow = function () {
        $('#employmentTable tbody').append(`
            <tr>
                <td><input type="text" name="std_employment_position_${employeeRowCount}"></td>
                <td><input type="date" name="std_employment_from_${employeeRowCount}"></td>
                <td><input type="date" name="std_employment_to_${employeeRowCount}"></td>
                <td><input type="text" name="std_employment_organization_${employeeRowCount}"></td>
                <td><button type="button" class="remove-row">×</button></td>
            </tr>
        `);
        employeeRowCount++;
    };
    $('#employmentTable').on('click', '.remove-row', function () {
        $(this).closest('tr').remove();
    });




    // Load Countries
    async function loadCountries() {
        const select = document.getElementById('std_country');
        const savedCountry = "<?php echo esc_js($country); ?>";
        if (!select) return;

        select.classList.add('loading');
        select.disabled = true;

        try {
            const response = await fetch('https://restcountries.com/v3.1/all?fields=name');
            if (!response.ok) throw new Error('Failed to fetch countries');

            const countries = await response.json();
            countries.sort((a, b) => a.name.common.localeCompare(b.name.common));

            while (select.options.length > 1) {
                select.remove(1);
            }

            countries.forEach(country => {
                const name = country.name.common;
                const option = new Option(name, name);
                if (name === savedCountry) option.selected = true;
                select.add(option);
            });

            if (savedCountry && ![...select.options].some(opt => opt.value === savedCountry)) {
                const fallback = new Option(savedCountry, savedCountry);
                fallback.selected = true;
                select.add(fallback);
            }

            select.classList.remove('loading', 'error');
        } catch (error) {
            console.error('Country loading failed:', error);
            select.classList.add('error');
            ['USA', 'Canada', 'UK', 'Australia', 'Pakistan'].forEach(name => {
                const option = new Option(name, name);
                if (name === savedCountry) option.selected = true;
                select.add(option);
            });
        } finally {
            select.disabled = false;
        }
    }

    // Waqifa/Returning Toggle
    function toggleConditionalFields() {
        const waqifaVal = $('input[name="std_waqifa"]:checked').val();
        const returningVal = $('input[name="std_returning"]:checked').val();

        $('input[name="std_waqif_number"]').prop('disabled', waqifaVal !== 'yes');
        $('input[name="std_student_number"]').prop('disabled', returningVal !== 'yes');
    }
    $('input[name="std_waqifa"]').on('change', toggleConditionalFields);
    $('input[name="std_returning"]').on('change', toggleConditionalFields);
    toggleConditionalFields();

    // File Preview
    window.previewUpload = function (input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;
            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = `<img src="${e.target.result}" style="max-height:100px;" />`;
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                preview.innerHTML = `<p>📄 PDF selected: ${file.name}</p>`;
            } else {
                preview.innerHTML = `<p>Selected: ${file.name}</p>`;
            }
        }
    };

    // File Remove
    window.removeUploadedFile = function (meta_key) {
        if (confirm('Are you sure you want to remove this file?')) {
            fetch(ajaxurl_data.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'remove_user_document',
                    meta_key: meta_key,
                    _ajax_nonce: ajaxurl_data.nonce
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) location.reload();
                else alert('Failed to remove file');
            });
        }
    };

    // Initial Setup
    showStep(currentStep);
    loadCountries();
});
</script>



</body>
</html>