<?php
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : get_current_user_id();
$user = get_userdata($user_id);

// Optional: if later code uses $student, define it
$student = $user;

// STEP 2: Handle invalid user
if (!$user) {
    echo '<div class="error-message">Invalid user ID</div>';
    exit;
}


// Fetch meta values
$first_name        = $user->first_name ?: get_user_meta($user_id, 'aisha_std_first_name', true);
$last_name         = $user->last_name  ?: get_user_meta($user_id, 'aisha_std_last_name', true);
$email             = $user->user_email;
$phone             = get_user_meta($user_id, 'phone', true);
$enroll_number             = get_user_meta($user_id, 'enrollment_number', true);
$dob               = get_user_meta($user_id, 'date_of_birth', true);
$country           = get_user_meta($user_id, 'aisha_std_country', true);
$aims_code         = get_user_meta($user_id, 'aisha_std_aims_code', true);
$halqa             = get_user_meta($user_id, 'aisha_std_halqa', true);
$address           = get_user_meta($user_id, 'aisha_std_address', true);
$postal_code       = get_user_meta($user_id, 'aisha_std_postal_code', true);
$city              = get_user_meta($user_id, 'aisha_std_city', true);
$province          = get_user_meta($user_id, 'aisha_std_province', true);
$city_province     = $city . ', ' . $province;

$aisha_std_waqif            = get_user_meta($user_id, 'aisha_std_waqifa', true);
$aisha_std_waqif_number             = get_user_meta($user_id, 'aisha_std_waqif_number', true);
$aisha_std_returning             = get_user_meta($user_id, 'aisha_std_returning', true);
$aisha_std_student_number             = get_user_meta($user_id, 'aisha_std_student_number', true);

$aisha_std_emergency1_name            = get_user_meta($user_id, 'aisha_std_emergency1_name', true);
$aisha_std_emergency1_phone             = get_user_meta($user_id, 'aisha_std_emergency1_phone', true);
$aisha_std_emergency1_email             = get_user_meta($user_id, 'aisha_std_emergency1_email', true);
$aisha_std_emergency1_relation             = get_user_meta($user_id, 'aisha_std_emergency1_relation', true);

$aisha_std_emergency2_name            = get_user_meta($user_id, 'aisha_std_emergency2_name', true);
$aisha_std_emergency2_phone             = get_user_meta($user_id, 'aisha_std_emergency2_phone', true);
$aisha_std_emergency2_email             = get_user_meta($user_id, 'aisha_std_emergency2_email', true);
$aisha_std_emergency2_relation             = get_user_meta($user_id, 'aisha_std_emergency2_relation', true);

$aisha_std_current_program             = get_user_meta($user_id, 'aisha_std_current_program', true);
$aisha_std_awards             = get_user_meta($user_id, 'aisha_std_awards', true);
$aisha_std_current_office_details             = get_user_meta($user_id, 'aisha_std_current_office_details', true);

	
$aisha_std_past_office             = get_user_meta($user_id, 'aisha_std_past_office', true);
$aisha_std_past_office_details             = get_user_meta($user_id, 'aisha_std_past_office_details', true);
$aisha_std_current_office             = get_user_meta($user_id, 'aisha_std_current_office', true);


$aisha_std_eng_rd             = get_user_meta($user_id, 'aisha_std_eng_rd', true);
$aisha_std_eng_wr             = get_user_meta($user_id, 'aisha_std_eng_wr', true);
$aisha_std_eng_sp             = get_user_meta($user_id, 'aisha_std_eng_sp', true);

$aisha_std_urdu_rd            = get_user_meta($user_id, 'aisha_std_urdu_rd', true);
$aisha_std_urdu_wr             = get_user_meta($user_id, 'aisha_std_urdu_wr', true);
$aisha_std_urdu_sp             = get_user_meta($user_id, 'aisha_std_urdu_sp', true);

$aisha_std_other_rd            = get_user_meta($user_id, 'aisha_std_other_rd', true);
$aisha_std_other_wr             = get_user_meta($user_id, 'aisha_std_other_wr', true);
$aisha_std_other_sp             = get_user_meta($user_id, 'aisha_std_other_sp', true);


$aisha_std_other_languages             = get_user_meta($user_id, 'aisha_std_other_languages', true);


$tutor_courses = tutor_utils()->get_enrolled_courses_ids_by_user($student->ID);



$aisha_std_quran= get_user_meta($user_id, 'aisha_std_quran', true);

$education_data_serialized = get_user_meta($user_id, 'aisha_std_education_history', true);

$education_data = maybe_unserialize($education_data_serialized);

$employee_data_serialized = get_user_meta($user_id, 'aisha_std_employment_history', true);

$employee_data = maybe_unserialize($employee_data_serialized);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | <?php echo esc_html($first_name . ' ' . $last_name); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #031f42;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-gray: #f5f7fa;
            --medium-gray: #e0e0e0;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
            --shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        .btn {
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      margin-bottom: 15px;
    }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-gray);
            color: var(--secondary-color);
            line-height: 1.5;
            font-size: 14px;
            padding: 0;
            margin: 0;

        }
        
        
        
        @media print {

    #printable-area {
        padding-left: 50px;
        padding-right: 50px;
    }
}
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 15px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .header h1 {
            color: var(--secondary-color);
            font-weight: 500;
            font-size: 20px;
        }
        
        .student-id {
            background-color: var(--primary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .profile-card {
            background-color: var(--white);
            border-radius: 6px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .profile-header {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            display: flex;
            align-items: center;
        }
        
        .profile-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
            font-size: 24px;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .profile-info h2 {
            margin-bottom: 3px;
            font-weight: 500;
            font-size: 18px;
        }
        
        .profile-info p {
            opacity: 0.9;
            font-size: 13px;
        }
        
        .profile-content {
            padding: 20px;
        }
        
        .section-title {
            font-size: 15px;
            color: var(--secondary-color);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 8px;
            color: var(--primary-color);
            font-size: 14px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-size: 12px;
            color: var(--dark-gray);
            text-transform: uppercase;
            margin-bottom: 3px;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 500;
            padding: 6px 0;
            border-bottom: 1px solid var(--medium-gray);
            min-height: 24px;
        }
        
        .address-section {
            background-color: var(--light-gray);
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }
        
        .error-message {
            background-color: var(--accent-color);
            color: white;
            padding: 12px;
            border-radius: 4px;
            text-align: center;
            margin: 15px;
            font-weight: 500;
            font-size: 13px;
        }
        
        .compact-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 13px;
        }
        
        .compact-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 500;
        }
        
        .compact-table td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--medium-gray);
            vertical-align: top;
        }
        
        .compact-table tr:nth-child(even) {
            background-color: var(--light-gray);
        }
        
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 10px;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .compact-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    
    
    
    
    <div class="container">
</div>        <div class="header">
            <h1>Student Profile</h1>
            <span class="student-id">ID: <?php echo esc_html($user_id); ?></span>
        </div>
        
        <button type="button" class="btn" onclick="printDiv()">Print</button>

        
        <div class="profile-card" style="padding:20px" id="printable-area">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo esc_html(substr($first_name, 0, 1) . substr($last_name, 0, 1)); ?>
                </div>
                <div class="profile-info">
                    <h2 style="color:white;"><?php echo esc_html($first_name . ' ' . $last_name); ?></h2>
                    <p><?php echo esc_html($halqa); ?> <b>Jammat</b></p>
                    <p><?php echo esc_html($enroll_number); ?> <b>Enrollment Number</b></p>
                </div>
            </div>
            
            <div class="profile-content">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Personal Information</span>
                </div>
                
                <table class="compact-table">
                    <thead>
                        <th><strong>Email Address</strong></th>
                        <th><strong>Phone Number</strong></th>
                        <th><strong>Date of Birth</strong></th>
                        <th><strong> AIMS Code</strong></th>
                        <th><strong>Halqa/Majlis</strong></th>
                        <th><strong>Country</strong> </th>
                     
                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($email); ?></td>
                        <td><?php echo esc_html($phone); ?></td>
                        <td><?php echo esc_html($dob); ?></td>
                         <td><?php echo esc_html($aims_code); ?></td>
                        <td><?php echo esc_html($halqa); ?></td>

                        <td><?php echo esc_html($country); ?></td>
                         </tr>
                     </tbody>
                    
                    
                </table>
                
                <div class="section-title">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>additional Information</span>
                </div>
                
                
                 <table class="compact-table">
                    <thead>
                        <th><strong>Home Address</strong></th>
                        <th><strong>Postal Code</strong></th>
                        <th><strong>City & Province</strong></th>
                        <th><strong>Are you a Waqifa</strong></th>
                        <th><strong>Are you a returning student?</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($address); ?></td>
                        <td><?php echo esc_html($city_province); ?></td>
                        <td><?php echo esc_html($postal_code); ?></td>
       <td>
    <?php echo esc_html($aisha_std_waqif); ?>
    <?php
    if ($aisha_std_waqif_number) {
        echo ' Number is <b>' . esc_html($aisha_std_waqif_number) . '</b>';
    } else {
        echo " Waqifa number is not existing";
    }
    ?>
</td>
<td>
    <?php echo esc_html($aisha_std_returning); ?>
    <?php
    if ($aisha_std_student_number) {
        echo ' Number is <b>' . esc_html($aisha_std_student_number) . '</b>';
    } else {
        echo " Student number is not existing";
    }
    ?>
</td>



                         </tr>
                     </tbody>
                    
                    
                </table>
                
                     <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Emergency Contact Information </span>
                </div>
                
                <table class="compact-table">
                    <thead>
                        <th><strong>Emergency Contact’s Name</strong></th>
                        <th><strong>Phone Number</strong></th>
                        <th><strong>Email Address</strong></th>
                        <th><strong>Relationship to the applicant</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($aisha_std_emergency1_name); ?></td>
                        <td><?php echo esc_html($aisha_std_emergency1_phone); ?></td>
                        <td><?php echo esc_html($aisha_std_emergency1_email); ?></td>
                         <td><?php echo esc_html($aisha_std_emergency1_relation); ?></td>

                         </tr>
                     </tbody>
                    
                    
                </table>
                <h5>Emergency Contact 2</h5>
                     <table class="compact-table">
                    <thead>
                        <th><strong>Emergency Contact’s Name</strong></th>
                        <th><strong>Phone Number</strong></th>
                        <th><strong>Email Address</strong></th>
                        <th><strong>Relationship to the applicant</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($aisha_std_emergency2_name); ?></td>
                        <td><?php echo esc_html($aisha_std_emergency2_phone); ?></td>
                        <td><?php echo esc_html($aisha_std_emergency2_email); ?></td>
                         <td><?php echo esc_html($aisha_std_emergency2_relation); ?></td>

                         </tr>
                     </tbody>
                    
                    
                </table>
                
                
                   <div class="section-title">
                    <i class="fa-solid fa-school"></i>                   
                    <span>Education</span>
                </div>
        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 6px;"><strong>High School/College/University</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Dates From</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Dates To</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Degree/Experience/Achieved Grades</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($education_data) && is_array($education_data)): ?>
            <?php foreach ($education_data as $edu): ?>
                <tr>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($edu['institution'] ?? ''); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($edu['from_date'] ?? ''); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($edu['to_date'] ?? ''); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($edu['degree'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="border: 1px solid black; padding: 6px;">No education history available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>



  <div class="section-title">
                    <i class="fa-solid fa-users"></i>
                    <span>Employment/Volunteer Experience</span>
                </div>
        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 6px;"><strong>Employment/Volunteer Position</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Dates From</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Dates To</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Name of the Organization</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($employee_data) && is_array($employee_data)): ?>
            <?php foreach ($employee_data as $emp): ?>
                <tr>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($emp['position'] ?? ''); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($emp['from_date'] ?? ''); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($emp['to_date'] ?? ''); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($emp['organization'] ?? ''); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="border: 1px solid black; padding: 6px;">No Employment history available.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


  <div class="section-title">
                    <span>additional Information</span>
                </div>
        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 6px;"><strong>Are you currently enrolled in any educational program? If yes, which program, institution, and year?
</strong></th>
        </tr>
    </thead>
    <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($aisha_std_current_program); ?></td>

                </tr>
          
    </tbody>
</table>
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 6px;"><strong> What academic awards/scholarships have you received, if applicable?</strong></th>
        </tr>
    </thead>
    <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($aisha_std_awards); ?></td>

                </tr>
          
    </tbody>
</table>
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 6px;"><strong> Have you completed the recitation of the Holy Qur’an?</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Are you currently an office bearer in the Jama’at?</strong></th>
            <th style="border: 1px solid black; padding: 6px;"><strong>Have you served as an office bearer in the past?</strong></th>
        </tr>
    </thead>
    <tbody>
                <tr>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($aisha_std_quran); ?></td>
                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($aisha_std_current_office); ?> <br><b>Details</b>&nbsp;<?php echo esc_html($aisha_std_current_office_details); ?> </td>

                    <td style="border: 1px solid black; padding: 6px;"><?php echo esc_html($aisha_std_past_office); ?> <br><b>Details</b>&nbsp;<?php echo esc_html($aisha_std_past_office_details); ?> </td>

                </tr>
          
    </tbody>
</table>

                
                   <div class="section-title">
                    <span>Language Proficiency</span>
                </div>
                
                
        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Language</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Spoken</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Written</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Reading</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border: 1px solid #000; padding: 6pt;">English</td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_eng_sp); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_eng_wr); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_eng_rd); ?></td>
      </tr>
     <tr>
        <td style="border: 1px solid #000; padding: 6pt;">Urdu</td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_urdu_sp); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_urdu_wr); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_urdu_rd); ?></td>
      </tr>
     <tr>
        <td style="border: 1px solid #000; padding: 6pt;">Other</td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_other_sp); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_other_wr); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($aisha_std_other_rd); ?></td>
      </tr>
    </tbody>
  </table>

  <p style="margin-top: 12pt; font-weight: bold;">Please list any other languages you are proficient in, if applicable:</p>
  <div style="border: 1px solid #ccc; padding: 8pt; min-height: 40pt;">
   <?php echo esc_html($aisha_std_other_languages); ?>
  </div>



    <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Instructors</span>
                </div>
                
                <table class="compact-table">
                    <thead>
                        <th><strong>Instructor Name</strong></th>
                        <th><strong>Email</strong></th>
                        <th><strong>Phone Number</strong></th>

                    </thead>
  <tbody>
    <?php
    $instructor_ids = get_user_meta($user_id, 'assigned_instructors', true);

    // Force unserialize if needed
    $instructor_ids = maybe_unserialize($instructor_ids);

    if (!empty($instructor_ids) && is_array($instructor_ids)) {
        foreach ($instructor_ids as $instructor_id) {
            $instructor = get_userdata($instructor_id);
            if ($instructor) {
                $phone = get_user_meta($instructor_id, 'phone_number', true); // Replace with your actual meta key
                $dob = get_user_meta($instructor_id, 'dob', true);     // Replace with your actual meta key

                echo '<tr>';
                echo '<td>' . esc_html($instructor->display_name) . '</td>';
                echo '<td>' . esc_html($instructor->user_email) . '</td>';
                echo '<td>' . esc_html($phone ?: '—') . '</td>';
                echo '</tr>';
            }
        }
    } else {
        echo '<tr><td colspan="4">No instructor assigned</td></tr>';
    }
    ?>
</tbody>

                    
</table>


    <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>assigned Courses</span>
                </div>
                


<table class="compact-table">
    <thead>
        <tr>
            <th><strong>Course Name</strong></th>
            <th><strong>Total Lessons</strong></th>
            <th><strong>Course Status</strong></th>
            <th><strong>Assignments</strong></th>
            <th><strong>Certificate</strong></th>
        </tr>
    </thead>


<tbody>
    <?php
    global $wpdb;

    $tutor_courses = tutor_utils()->get_enrolled_courses_ids_by_user($user_id);
    $meta_courses  = get_user_meta($user_id, '_aisha_enrolled_courses', true);

    $all_courses = array_unique(array_merge(
        is_array($tutor_courses) ? $tutor_courses : [],
        is_array($meta_courses) ? $meta_courses : []
    ));

    if (!empty($all_courses)) {
        foreach ($all_courses as $course_id) {
            $course_title   = get_the_title($course_id);
            $is_completed   = tutor_utils()->is_completed_course($course_id, $user_id);
            $status_label   = $is_completed ? 'Completed' : 'In Progress';
            $total_lessons  = tutor_utils()->get_lesson_count_by_course($course_id);

            // ✅ Enroll Date (check meta first)
            $enroll_meta_key = '_tutor_enrolled_date_' . $course_id;
            $enroll_date = get_user_meta($user_id, $enroll_meta_key, true);

            // ✅ If not in meta, get from Tutor LMS database
            if (empty($enroll_date)) {
                $enroll_date = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT enrolled_at FROM {$wpdb->prefix}tutor_enrollments WHERE user_id = %d AND course_id = %d",
                        $user_id,
                        $course_id
                    )
                );
            }

            // ✅ Format date or show fallback
            $enroll_date_display = $enroll_date ? date('M d, Y', strtotime($enroll_date)) : '—';

            // ✅ Assignments, Questions, Quizzes
            $assignment_count = (array) tutor_utils()->get_assignments_by_course($course_id);
            $question_count   = tutor_utils()->get_total_questions_count_by_course($course_id);
            $quiz_attempts    = tutor_utils()->get_attempted_quiz_count_by_course($course_id, $user_id);

            echo '<tr>';
            echo '<td>' . esc_html($course_title) . '</td>';
            echo '<td>' . esc_html($total_lessons) . '</td>';
            echo '<td>' . esc_html($status_label) . '</td>';
            echo '<td>' . esc_html(count($assignment_count)) . '</td>';

            // ✅ Certificate column
            if ($is_completed) {
                $certificate_url = tutor_utils()->get_certificate_download_url($course_id, $user_id);
                if ($certificate_url) {
                    echo '<td><a href="' . esc_url($certificate_url) . '" target="_blank" class="button button-small">View Certificate</a></td>';
                } else {
                    echo '<td><span style="color:gray;">No certificate</span></td>';
                }
            } else {
                echo '<td><span style="color:gray;">Not Available</span></td>';
            }

            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="8">No courses assigned or enrolled.</td></tr>';
    }
    ?>
</tbody>


</table>


                
                
                
             
            </div>
        </div>
    </div>
<script>
function printDiv() {
  var printContents = document.getElementById('printable-area').innerHTML;
  var originalContents = document.body.innerHTML;

  document.body.innerHTML = printContents;
  window.print();
  document.body.innerHTML = originalContents;
  location.reload(); // reload page to restore event listeners, etc.
}
</script>

</body>

</html>