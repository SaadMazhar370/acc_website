<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    color: white;
    display: inline-block;
    text-transform: uppercase;
}
.status-yes {
    background-color: #333cff; /* Green */
}
.status-no {
    background-color: #990e00; /* Red */
}


.tablenav-pages {
    text-align: right;
    margin-top: 20px;
}
.tablenav-pages .pagination-links a,
.tablenav-pages .pagination-links span {
    padding: 6px 10px;
    margin: 0 2px;
    background: #f1f1f1;
    border: 1px solid #ccd0d4;
    border-radius: 3px;
    text-decoration: none;
    color: #0073aa;
}
.tablenav-pages .pagination-links .current {
    background: #0073aa;
    color: white;
    font-weight: bold;
}

</style>
<div class="wrap">
    <h1 style="font-size: 28px; font-weight: 600; margin-bottom: 20px;">Aisha Academy Management</h1>

    <div class="student-filter-bar" style="margin-bottom: 20px;">
        <div style="display: flex; gap: 10px; align-items: center;">
            <input type="text" id="student-search" placeholder="Search by name, email, phone..." 
                style="flex: 1; padding: 8px 12px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px;"/>

            <select id="student-filter-status" 
                style="padding: 8px 12px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <button id="reset-filters" class="button">Reset Filters</button>
            <button id="export-csv" class="button button-primary">Export CSV</button>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped" style="border: 1px solid #ccd0d4; width: 100%;">
        <thead style="background-color: #f1f1f1;">
            <tr>
                <th>Student Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Register For</th>
                <th>Admin Remarks</th>
                <th>Instructor(s)</th>
                <th>Courses</th>
                <th>Dashboard Access</th>
                <th>Email Status</th>
                <th>Registered Date</th>
                <th>File</th>
                <th>Admission Form</th>
                <th>IP</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $args = array(
            'meta_query' => array(
                'relation' => 'AND',
                array('key' => 'form_type', 'value' => 'student_form'),
                array('key' => 'register_for', 'value' => 'aisha_cademy_canada')
            ),
            'orderby' => 'registered',
            'order' => 'DESC'
        );


$all_students = get_users($args);
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = 10;
$total_students = count($all_students);
$total_pages = ceil($total_students / $per_page);

// Slice the users for current page
$students = array_slice($all_students, ($current_page - 1) * $per_page, $per_page);


        if (!empty($students)) {
            foreach ($students as $student) {
                $dob = get_user_meta($student->ID, 'date_of_birth', true);
                $sec_email = get_user_meta($student->ID, 'secondary_email', true);
                $phone = get_user_meta($student->ID, 'phone', true);
                $status = get_user_meta($student->ID, 'dashboard_access', true) ?: 'active';
                $emailstatus = get_user_meta($student->ID, 'email_verified', true);
                $emailstatus = $emailstatus ? $emailstatus : 'no';
                $reg_date = $student->user_registered;
                $reg_for = get_user_meta($student->ID, 'register_for', true);
                $std_number = get_user_meta($student->ID, 'enrollment_number', true);
                $registration_ip = get_user_meta($student->ID, 'registration_ip', true);
                $tutor_courses = tutor_utils()->get_enrolled_courses_ids_by_user($student->ID);
                $meta_courses = get_user_meta($student->ID, '_aisha_enrolled_courses', true);
                $instructors = get_user_meta($student->ID, 'assigned_instructors', true);
                $admin_remarkes = get_user_meta($student->ID, 'admin_remarkes', true);
                $std_files = get_user_meta($student->ID, 'std_files', true);
                $admission_form = get_user_meta($student->ID, 'is_profile', true);

                $file_display_html = '—';
                if (!empty($std_files) && is_array($std_files)) {
                    $file_display_html = '<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 5px;">';
                    $visible_previews = '';
                    $hidden_previews = '';
                    foreach ($std_files as $index => $file_url) {
                        $ext = pathinfo($file_url, PATHINFO_EXTENSION);
                        $preview = '';
                        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $preview = '<img src="' . esc_url($file_url) . '" style="width:80px;height:80px;object-fit:cover;cursor:pointer;" onclick="window.open(\'' . esc_url($file_url) . '\')" />';
                        } elseif (in_array(strtolower($ext), ['pdf'])) {
                            $preview = '<a href="' . esc_url($file_url) . '" target="_blank"><img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="PDF" width="30" height="30" /></a>';
                        } else {
                            $preview = '<a href="' . esc_url($file_url) . '" target="_blank">Download</a>';
                        }
                        $thumbnail = '<div class="file-thumb">' . $preview . '</div>';
                        if ($index < 4) {
                            $visible_previews .= $thumbnail;
                        } else {
                            $hidden_previews .= $thumbnail;
                        }
                    }
                    $file_display_html .= $visible_previews . '</div>';
                    if (!empty($hidden_previews)) {
                        $file_display_html .= '<span class="more-files-toggle" style="cursor:pointer; display:inline-block; color:#0073aa; text-decoration:underline; font-size:12px; margin-top:5px;" onclick="this.style.display=\'none\'; this.nextElementSibling.style.display=\'grid\';">+ more</span>';
                        $file_display_html .= '<div class="more-files" style="display:none; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 5px;">' . $hidden_previews . '</div>';
                    }
                }

                $instructor_names = [];
                $all_courses = array_unique(array_merge(
                    is_array($tutor_courses) ? $tutor_courses : [],
                    is_array($meta_courses) ? $meta_courses : []
                ));

                $course_badges = [];
                foreach ($all_courses as $course_id) {
                    $title = get_the_title($course_id);
                    if (!empty($title)) {
                        $course_badges[] = '<a href="' . get_permalink($course_id) . '" class="course-badge" target="_blank">' . esc_html($title) . '</a>';
                    }
                }
                $visible = array_slice($course_badges, 0, 4);
                $hidden = array_slice($course_badges, 4);
                $courses_display = implode('', $visible);
                if (!empty($hidden)) {
                    $courses_display .= '<span class="more-courses-toggle" onclick="this.style.display=\'none\'; this.nextElementSibling.style.display=\'inline\';">+' . count($hidden) . ' more</span>';
                    $courses_display .= '<span class="more-courses" style="display:none;">' . implode('', $hidden) . '</span>';
                }

                if (is_serialized($instructors)) {
                    $instructors = maybe_unserialize($instructors);
                }
                if (!is_array($instructors)) {
                    $instructors = [];
                }
                foreach ($instructors as $instructor_id) {
                    $instructor = get_userdata($instructor_id);
                    if ($instructor) {
                        $instructor_names[] = esc_html($instructor->display_name);
                    }
                }

                echo '<tr data-status="' . esc_attr(strtolower($status)) . '">';
                echo '<td>' . esc_html($std_number ? $std_number : '—') . '</td>';
                echo '<td><strong>' . esc_html($student->first_name . ' ' . $student->last_name) . '</strong></td>';
                echo '<td>' . esc_html($student->user_email) .'&nbsp;'.'<br>'. 'Sec Email: ' . esc_html($sec_email) . '</td>';
                echo '<td>' . esc_html($phone ? $phone : '—') . '</td>';
                echo '<td>' . esc_html($dob ? $dob : '—') . '</td>';
                echo '<td>' . esc_html($reg_for ? $reg_for : '—') . '</td>';
                echo '<td>' . esc_html($admin_remarkes ? $admin_remarkes : '—') . '</td>';
                echo '<td>' . (!empty($instructor_names) ? esc_html(implode(', ', $instructor_names)) : '—') . '</td>';
                echo '<td>' . (!empty($course_badges) ? $courses_display : '—') . '</td>';
                $status_clean = strtolower($status);
                $btn_class = ($status_clean === 'active') ? 'btn-info' : 'btn-danger';
                echo '<td><button type="button" class="btn ' . esc_attr($btn_class) . '">' . esc_html(ucfirst($status_clean)) . '</button></td>';
                
                echo '<td><span class="status-badge status-' . esc_attr(strtolower($emailstatus)) . '">' . esc_html(ucfirst($emailstatus)) . '</span></td>';
                echo '<td>' . esc_html(date('Y-m-d', strtotime($reg_date))) . '</td>';
                
                $fields = [
                'aisha_std_government_id'     => 'Government Issued ID',
                'aisha_std_education_certs'   => 'Educational Certificates',
                'aisha_std_experience_letter' => 'Experience Letter',
                'aisha_std_payment_receipt'   => 'Fee Receipt',
];

// Replace this with the correct PDF icon URL or use your own
$pdf_icon_url = plugins_url('assets/icons/pdf-icon.png', __FILE__); // e.g. student-manager/assets/icons/pdf-icon.png

echo '<td>';
foreach ($fields as $meta_key => $label) {
    $file_url = get_user_meta($student->ID, $meta_key, true);
    

    if (!empty($file_url)) {
        $ext = strtolower(pathinfo($file_url, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
            echo '<a href="' . esc_url($file_url) . '" target="_blank">
                    <img src="' . esc_url($file_url) . '" style="width: 30px; height: 30px; object-fit: contain; border:1px solid #ccc;" alt="' . esc_attr($label) . '">
                  </a>';
        } elseif ($ext === 'pdf') {
            echo '<a href="' . esc_url($file_url) . '" target="_blank" style="display: inline-block; margin-top: 5px;">
                    <img src="' . esc_url($pdf_icon_url) . '" alt="PDF" style="width: 32px; height: 32px;"> View PDF
                  </a>';
        } else {
            echo '<a href="' . esc_url($file_url) . '" target="_blank">View File</a>';
        }
    } else {
        echo '<span style="color: #999;">Not uploaded</span>';
    }

    echo '</div>';
}
echo '</td>';

$is_profile = strtolower(trim(get_user_meta($student->ID, 'is_profile', true)));

if ($is_profile === 'yes') {
    $pdf_path = get_user_meta($student->ID, 'aisha_admission_pdf_path', true);

    if (!empty($pdf_path)) {
        $upload_dir = wp_upload_dir();
        $pdf_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $pdf_path);

        echo '<td><a href="' . esc_url($pdf_url) . '" class="btn btn-sm btn-success" target="_blank" title="View Admission Form">
                <i class="dashicons dashicons-visibility"></i> View Admission Form
              </a></td>';
    } else {
        echo '<td><span class="text-danger">PDF not found</span></td>';
    }
} else {
    echo '<td><span class="text-danger">Admission Form Pending</span></td>';
}


                // echo '<td>' . esc_html($admission_form ? $admission_form : '—') . '</td>';

                echo '<td>' . esc_html($registration_ip ? $registration_ip : '—') . '</td>';
                echo '<td><div style="display: flex; flex-direction: column; gap: 5px;">
                
                <a href="admin.php?page=edit-aisha-academy-student&user_id=' . esc_attr($student->ID) . '" class="button button-small">Edit</a>
                
                <a href="#" class="button button-small delete-button" data-user-id="' . esc_attr($student->ID) . '">Delete</a>
                 <a href="admin.php?page=aisha-academy-student-details&user_id=' . esc_attr($student->ID) . '" class="button button-small">Profile</a>
                 <a href="admin.php?page=tutor_report&sub_page=students&student_id=' . esc_attr($student->ID) . '" class="button button-small">Report</a>

                
                </div></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="15" style="text-align:center; padding: 20px;">No students found.</td></tr>';
        }
        ?>
        </tbody>
    </table>
    
    <?php if ($total_pages > 1): ?>
    <div class="tablenav bottom" style="margin-top: 20px;">
        <div class="tablenav-pages">
            <span class="pagination-links">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => '«',
                    'next_text' => '»',
                    'type' => 'plain',
                ));
                ?>
            </span>
        </div>
    </div>
<?php endif; ?>

</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('student-search');
    const statusFilter = document.getElementById('student-filter-status');
    const resetBtn = document.getElementById('reset-filters');
    const exportBtn = document.getElementById('export-csv');
    const tableRows = document.querySelectorAll('tbody tr');
    const table = document.querySelector('table');

    // Search by text (name, email, phone)
    searchInput.addEventListener('keyup', filterRows);
    statusFilter.addEventListener('change', filterRows);

    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        statusFilter.value = '';
        filterRows();
    });

    function filterRows() {
        const keyword = searchInput.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        tableRows.forEach(function (row) {
            const text = row.textContent.toLowerCase();
            const rowStatus = row.getAttribute('data-status') || '';

            const matchesSearch = text.includes(keyword);
            const matchesStatus = !statusValue || rowStatus === statusValue;

            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    exportBtn.addEventListener('click', function () {
        let csv = [];
        const rows = table.querySelectorAll('thead tr, tbody tr');

        rows.forEach(function (row) {
            if (row.style.display === 'none' && row.closest('thead') === null) return; // skip hidden body rows
            let cells = Array.from(row.querySelectorAll('th, td')).map(function (cell) {
                return `"${cell.innerText.trim().replace(/"/g, '""')}"`;
            });
            csv.push(cells.join(','));
        });

        let blob = new Blob([csv.join('\n')], { type: 'text/csv' });
        let url = URL.createObjectURL(blob);
        let a = document.createElement('a');
        a.href = url;
        a.download = 'student_record.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    });
});
    
    
    
</script>







