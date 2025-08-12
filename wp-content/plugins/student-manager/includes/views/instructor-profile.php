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
$first_name        = $user->first_name ?: get_user_meta($user_id, 'employment_first_name', true);
$middel_name         = $user->middle_name  ?: get_user_meta($user_id, 'employment_middle_name', true);
$last_name         = $user->last_name  ?: get_user_meta($user_id, 'employment_last_name', true);
$email         = $user->email  ?: get_user_meta($user_id, 'employment_email', true);
$email             = $user->user_email;
$phone             = get_user_meta($user_id, 'employment_inst_cellPhone', true);

$home_address             = get_user_meta($user_id, 'employment_inst_address', true);
$home_number             = get_user_meta($user_id, 'employment_inst_homePhone', true);
$aims_code         = get_user_meta($user_id, 'employment_inst_aimsCode', true);
$majlis           = get_user_meta($user_id, 'employment_inst_majlis', true);




$emp_ins_waqif            = get_user_meta($user_id, 'employment_inst_waqifa', true);
$emp_ins_waqif_number             = get_user_meta($user_id, 'employment_inst_waqifaNumber', true);
$emp_ins_currently_serving             = get_user_meta($user_id, 'employment_inst_jammatserving_status', true);
$emp_ins_serving_detail             = get_user_meta($user_id, 'employment_inst_servingDetails', true);

$emp_ins_legally_eligible            = get_user_meta($user_id, 'employment_eligible', true);
$emp_ins_have_worked             = get_user_meta($user_id, 'employment_inst_worked', true);
$emp_if_yes_date             = get_user_meta($user_id, 'employment_inst_workDates', true);
$emp_convicted_act = get_user_meta($user_id, 'employment_inst_convicted', true);
$emp_postion_apply_for= get_user_meta($user_id, 'employment_position', true);
$emp_shift_insterseted        = get_user_meta($user_id, 'employment_interested_hour', true);

$emp_teaching_pref1            = get_user_meta($user_id, 'employment_inst_preference1', true);
$emp_teaching_pref2             = get_user_meta($user_id, 'employment_inst_preference2', true);
$emp_teaching_pref3             = get_user_meta($user_id, 'employment_inst_preference3', true);
$emp_teaching_pref4             = get_user_meta($user_id, 'employment_inst_preference4', true);
$emp_teaching_pref5             = get_user_meta($user_id, 'employment_inst_preference5', true);

$emp_inst_qualification             = get_user_meta($user_id, 'employment_inst_qualifications', true);
$allow_bg_check             = get_user_meta($user_id, 'employment_bg_check', true);

$emp_ins_print_name             = get_user_meta($user_id, 'employment_print_name', true);
$emp_ins_sign             = get_user_meta($user_id, 'employment_signature', true);
$emp_ins_date             = get_user_meta($user_id, 'employment_date', true);






$emp_jammat_majlis            = get_user_meta($user_id, 'employment_inst_ref_jamat_majlis', true);
$emp_jammat_president_name            = get_user_meta($user_id, 'employment_inst_ref_jamat_president', true);
$emp_jammat_phone            = get_user_meta($user_id, 'employment_inst_ref_jamat_phone', true);
$emp_jammat_email            = get_user_meta($user_id, 'employment_inst_ref_jamat_email', true);

$emp_prof_refer1_fname            = get_user_meta($user_id, 'employment_inst_ref_prof1_name', true);
$emp_prof_refer1_title            = get_user_meta($user_id, 'employment_inst_ref_prof1_title', true);
$emp_prof_refer1_org            = get_user_meta($user_id, 'employment_inst_ref_prof1_org', true);
$emp_prof_refer1_phone            = get_user_meta($user_id, 'employment_inst_ref_prof1_phone', true);
$emp_prof_refer1_email            = get_user_meta($user_id, 'employment_inst_ref_prof1_email', true);

$emp_prof_refer2_fname            = get_user_meta($user_id, 'employment_inst_ref_prof2_name', true);
$emp_prof_refer2_title            = get_user_meta($user_id, 'employment_inst_ref_prof2_title', true);
$emp_prof_refer2_org            = get_user_meta($user_id, 'employment_inst_ref_prof2_org', true);
$emp_prof_refer2_phone            = get_user_meta($user_id, 'employment_inst_ref_prof2_phone', true);
$emp_prof_refer2_email            = get_user_meta($user_id, 'employment_inst_ref_prof2_email', true);

$emp_per_refer_fname            = get_user_meta($user_id, 'employment_inst_ref_pers_name', true);
$emp_per_refer_relationship            = get_user_meta($user_id, 'employment_inst_ref_pers_relation', true);
$emp_per_refer_phone            = get_user_meta($user_id, 'employment_inst_ref_pers_phone', true);
$emp_per_refer_email            = get_user_meta($user_id, 'employment_inst_ref_pers_email', true);


$aisha_std_eng_rd             = get_user_meta($user_id, 'employment_inst_rd_en', true);
$aisha_std_eng_wr             = get_user_meta($user_id, 'employment_inst_wr_en', true);
$aisha_std_eng_sp             = get_user_meta($user_id, 'employment_inst_sp_en', true);

$aisha_std_urdu_rd            = get_user_meta($user_id, 'employment_inst_rd_ur', true);
$aisha_std_urdu_wr             = get_user_meta($user_id, 'employment_inst_wr_ur', true);
$aisha_std_urdu_sp             = get_user_meta($user_id, 'employment_inst_sp_ur', true);

$aisha_std_other_rd            = get_user_meta($user_id, 'employment_inst_sp_other', true);
$aisha_std_other_wr             = get_user_meta($user_id, 'employment_inst_wr_other', true);
$aisha_std_other_sp             = get_user_meta($user_id, 'employment_inst_rd_other', true);


$aisha_std_other_languages             = get_user_meta($user_id, 'aisha_std_other_languages', true);



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
                        <th><strong>First Name</strong></th>
                        <th><strong>Middle Name</strong></th>
                        <th><strong>Last Name</strong></th>
                        <th><strong>Email</strong></th>
                        <th><strong>Phone</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($first_name); ?></td>
                        <td><?php echo esc_html($middel_name); ?></td>
                        <td><?php echo esc_html($last_name); ?></td>
                        <td><?php echo esc_html($email); ?></td>
                         <td><?php echo esc_html($phone); ?></td>
                         </tr>
                     </tbody>
                    
                    
                </table>
                
                <div class="section-title">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>additional Information</span>
                </div>
                
                
                 <table class="compact-table">
                    <thead>
                        <th><strong>Address</strong></th>
                        <th><strong>Majlis</strong></th>
                        <th><strong>Aims Code</strong></th>
                        <th><strong>Home Number</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($home_address); ?></td>
                        <td><?php echo esc_html($majlis); ?></td>
                        <td><?php echo esc_html($aims_code); ?></td>
                        <td><?php echo esc_html($home_number); ?></td>




                         </tr>
                     </tbody>
                    
                    
                </table>
                
                     <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>additional Information</span>
                </div>
                
                <table class="compact-table">
                    <thead>
                        <th><strong>Are you a Waqifa?</strong></th>
                        <th><strong>If yes,Waqf-e-Nau number</strong></th>
                        <th><strong>Are you currently serving in Jama’at</strong></th>
                        <th><strong>If yes,please give details</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($emp_ins_waqif); ?></td>
                        <td><?php echo esc_html($emp_ins_waqif_number); ?></td>
                        <td><?php echo esc_html($emp_ins_currently_serving); ?></td>
                         <td><?php echo esc_html($emp_ins_serving_detail); ?></td>

                         </tr>
                     </tbody>
                    
                    
                </table>
                     <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Employment Eligibility</span>
                </div>
                
                
                     <table class="compact-table">
                    <thead>
                        <th><strong>Are you legally eligible to work in Canada for any employer?</strong></th>
                        <th><strong>Have you ever worked for ‘Aisha Academy Canada?</strong></th>
                        <th><strong>Have you ever been convicted of an act for which pardon has not been granted?</strong></th>
                        <th><strong>Position you are applying for</strong></th>
                        <th><strong>Hours you are interested in:</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($emp_ins_legally_eligible); ?></td>
                        <td><?php echo esc_html($emp_ins_have_worked); ?>&nbsp;-Date-&nbsp; <b><?php echo esc_html($emp_if_yes_date); ?></b>  </td>
                        <td><?php echo esc_html($emp_convicted_act); ?></td>
                         <td><?php echo esc_html($emp_postion_apply_for); ?></td>
                         <td><?php echo esc_html($emp_shift_insterseted); ?></td>

                         </tr>
                     </tbody>
                    
                    </table>
                    
                        <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Course Offerings& Qualifications</span>
                </div>
                <?php
// Get and unserialize the saved courses
$course_data = get_user_meta($user_id, 'employment_aisha_courses', true);
$course_data = maybe_unserialize($course_data);

if (!empty($course_data) && is_array($course_data)) :
?>
                
                     <table class="compact-table">
                    <thead>
                        <th><strong>Courses</strong></th>
                        <th><strong>Availability</strong></th>
                        <th><strong>Qualifications</strong></th>

                    </thead>
                    
                           <tbody>
            <?php foreach ($course_data as $course) : ?>
                <tr>
                    <td><?php echo esc_html($course['name']); ?></td>
                    <td>
                        <?php
                        $availability = [];
                        if (!empty($course['am'])) $availability[] = 'AM';
                        if (!empty($course['pm'])) $availability[] = 'PM';
                        echo esc_html(implode(', ', $availability));
                        ?>
                    </td>
                    <td><?php echo esc_html($course['qualifications']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
                    
                    
                </table>
                
                <?php else : ?>
    <p>No course data available.</p>
<?php endif; ?>

<h2>Girls’Hifz ul Qur’an School</h2>

                        <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Course Offerings& Qualifications</span>
                </div>
                <?php
// Get and unserialize the saved courses
$hfzq_course_data = get_user_meta($user_id, 'employment_hifz_classes', true);
$hfzq_course_data = maybe_unserialize($hfzq_course_data);

if (!empty($hfzq_course_data) && is_array($hfzq_course_data)) :
?>
                
                     <table class="compact-table">
                    <thead>
                        <th><strong>Class</strong></th>
                        <th><strong>Qualifications</strong></th>

                    </thead>
                    
                           <tbody>
            <?php foreach ($hfzq_course_data as $hfzq_course) : ?>
                <tr>
                    <td><?php echo esc_html($hfzq_course['name']); ?></td>
                
                    <td><?php echo esc_html($hfzq_course['qualifications']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
                    
                    
                </table>
                
                <?php else : ?>
    <p>No course data available.</p>
<?php endif; ?>

      <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Teaching Preferences</span>
                </div>
                     <table class="compact-table">
                    <thead>
                        <th><strong>1</strong></th>
                        <th><strong>2</strong></th>
                        <th><strong>3</strong></th>
                        <th><strong>4</strong></th>
                        <th><strong>5</strong></th>

                    </thead>
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($emp_teaching_pref1); ?></td>
                        <td><?php echo esc_html($emp_teaching_pref2); ?></td>
                        <td><?php echo esc_html($emp_teaching_pref3); ?></td>
                        <td><?php echo esc_html($emp_teaching_pref4); ?></td>
                        <td><?php echo esc_html($emp_teaching_pref5); ?></td>

                         </tr>
                     </tbody>
                    
                    
                </table>
                
      <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    <span>Skills& Qualifications</span>
                </div>
                     <table class="compact-table">
          
                     <tbody>
                         <tr>
                        <td><?php echo esc_html($emp_inst_qualification); ?></td>

                         </tr>
                     </tbody>
                    
                    
                </table>
                
                
                   <div class="section-title">
                    <i class="fa-solid fa-school"></i>                   
                    <span>Skills& Qualifications</span>
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
                   <div class="section-title">
                    <span>Preferences</span>
                </div>
                
                                    <span>Jammat references</span>

        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Majlis</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Local President Name</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Phone Number</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Jama’at Email Address</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_jammat_majlis); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_jammat_president_name); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_jammat_phone); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_jammat_email); ?></td>
      </tr>
    </tbody>
  </table>
                                    <span> <b>Professional Reference#1</b></span>

        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Full Name:</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Title</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Organization</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Phone Number:</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Email Address:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer1_fname); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer1_title); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer1_org); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer1_phone); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer1_email); ?></td>
      </tr>
    </tbody>
  </table>
                                    <span> <b>Professional Reference#2</b> </span>

        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Full Name:</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Title</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Organization</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Phone Number:</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Email Address:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer2_fname); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer2_title); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer2_org); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer2_phone); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_prof_refer2_email); ?></td>
      </tr>
    </tbody>
  </table>
                                    <span> <b>Personal Reference</b> </span>

        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Full Name:</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Relationship</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Phone Number:</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Email Address:</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_per_refer_fname); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_per_refer_relationship); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_per_refer_phone); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_per_refer_email); ?></td>
      </tr>
    </tbody>
  </table>
                                    <span> <b>Background Check Consent</b> </span>

        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">If asked,do you consent to a back ground check?</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;"><?php echo esc_html($allow_bg_check); ?></th>
      </tr>
    </thead>
  
  </table>
  
           <span> <b>Signature</b> </span>

        
        <table class="compact-table" style="border: 2px solid black; border-collapse: collapse; width: 100%;">
    <thead>
      <tr>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Print Name</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Signature</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">Date</th>
        <th style="border: 1px solid #000; padding: 6pt; width: 25%; text-align: left;">employment Form File</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_ins_print_name); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_ins_sign); ?></td>
        <td style="border: 1px solid #000; padding: 6pt;"><?php echo esc_html($emp_ins_date); ?></td>

<td style="border: 1px solid #000; padding: 6pt;">
<?php
    $user_id = isset($user->ID) ? $user->ID : 0;

    if ($user_id) {
        $pdf_path = get_user_meta($user_id, 'employment_pdf_path', true);
        $pdf_path = maybe_unserialize($pdf_path);

        if (!empty($pdf_path)) {
            if (is_array($pdf_path)) {
                $pdf_path = !empty($pdf_path[0]) ? $pdf_path[0] : '';
            }

            $upload_dir = wp_upload_dir();

            if (strpos($pdf_path, $upload_dir['basedir']) !== false) {
                $relative_path = str_replace($upload_dir['basedir'], '', $pdf_path);
                $pdf_url = $upload_dir['baseurl'] . $relative_path;
            } else {
                $pdf_url = $upload_dir['baseurl'] . '/' . ltrim($pdf_path, '/');
            }

            echo '<a href="' . esc_url($pdf_url) . '" target="_blank" title="' . esc_attr__('View Employment Form', 'student-manager') . '">
                <i class="fas fa-file-pdf"></i> ' . esc_html__('View Form', 'student-manager') . '
            </a>';
        } else {
            echo '<span class="text-danger">' . __('PDF not found', 'student-manager') . '</span>';
        }
    } else {
        echo '<span class="text-danger">User ID missing</span>';
    }
?>
</td>





      </tr>
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