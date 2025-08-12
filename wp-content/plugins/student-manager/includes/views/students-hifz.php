<div class="wrap">
    <h1 style="font-size: 28px; font-weight: 600; margin-bottom: 20px;">Girls’ Hifzul Qur’ān School</h1>


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
                <th>Status</th>
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
        array(
            'key' => 'form_type',
            'value' => 'student_form',
            'compare' => '='
        ),
        array(
            'key' => 'register_for',
            'value' => 'girls_hifzul_quran_school',
            'compare' => '='
        )
    ),
    'orderby' => 'user_registered',
    'order' => 'DESC',
    'number' => -1
);
$students = get_users($args);

        if (!empty($students)) {
            foreach ($students as $student) {
                $dob = get_user_meta($student->ID, 'date_of_birth', true);
                $phone = get_user_meta($student->ID, 'phone', true);
                $status = get_user_meta($student->ID, 'dashboard_access', true) ?: 'active'; // Default to active if empty
                $reg_date = $student->user_registered;
                $reg_for = get_user_meta($student->ID, 'register_for', true);
                $std_number = get_user_meta($student->ID, 'enrollment_number', true);
                $registration_ip = get_user_meta($student->ID, 'registration_ip', true);
                $tutor_courses = tutor_utils()->get_enrolled_courses_ids_by_user($student->ID);
                $meta_courses = get_user_meta($student->ID, '_aisha_enrolled_courses', true);
                $instructors = get_user_meta($student->ID, 'assigned_instructors', true);
                $admin_remarkes = get_user_meta($student->ID, 'admin_remarkes', true);
                $std_files = get_user_meta($student->ID, 'std_files', true);
                $admission_form = get_user_meta($student->ID, 'admission_form', true);
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

                $instructor_names = array();

                $all_courses = array_unique(array_merge(
                    is_array($tutor_courses) ? $tutor_courses : [],
                    is_array($meta_courses) ? $meta_courses : []
                ));

                // Generate course badges
                $course_badges = [];
                foreach ($all_courses as $course_id) {
                    $title = get_the_title($course_id);
                    if (!empty($title)) {
                        $course_badges[] = '<span class="course-badge">' . esc_html($title) . '</span>';
                    }
                }

                $visible = array_slice($course_badges, 0, 4);
                $hidden = array_slice($course_badges, 4);

                $courses_display = implode('', $visible);

                if (!empty($hidden)) {
                    $courses_display .= '<span class="more-courses-toggle" onclick="this.style.display=\'none\'; this.nextElementSibling.style.display=\'inline\';">+' . count($hidden) . ' more</span>';
                    $courses_display .= '<span class="more-courses" style="display:none;">' . implode('', $hidden) . '</span>';
                }

                // Handle instructors data
                if (is_serialized($instructors)) {
                    $instructors = maybe_unserialize($instructors);
                }
                if (!is_array($instructors)) {
                    $instructors = array();
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
                echo '<td>' . esc_html($student->user_email) . '</td>';
                echo '<td>' . esc_html($phone ? $phone : '—') . '</td>';
                echo '<td>' . esc_html($dob ? $dob : '—') . '</td>';
                echo '<td>' . esc_html($reg_for ? $reg_for : '—') . '</td>';
                echo '<td>' . esc_html($admin_remarkes ? $admin_remarkes : '—') . '</td>';
                echo '<td>' . (!empty($instructor_names) ? esc_html(implode(', ', $instructor_names)) : '—') . '</td>';
                echo '<td>' . (!empty($course_badges) ? $courses_display : '—') . '</td>';
                echo '<td><span class="status-badge status-' . esc_attr(strtolower($status)) . '">' . esc_html(ucfirst($status)) . '</span></td>';
                echo '<td>' . esc_html(date('Y-m-d', strtotime($reg_date))) . '</td>';
                echo '<td>' . $file_display_html . '</td>';
                echo '<td>' . esc_html($admission_form ? $admission_form : 'Need TO fill Form') . '</td>';
                echo '<td>' . esc_html($registration_ip ? $registration_ip : '—') . '</td>';
                echo '<td>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            <a href="admin.php?page=hifzul-quran-academy-edit&user_id=' . esc_attr($student->ID) . '" class="button button-small">Edit</a>
                            <a href="#" class="button button-small delete-button" data-user-id="' . esc_attr($student->ID) . '">Delete</a>
                            <a href="user-edit.php?user_id=' . esc_attr($student->ID) . '" class="button button-small">Profile</a>
                        </div>
                      </td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="15" style="text-align:center; padding: 20px;">No students found.</td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Delete button functionality
    document.querySelectorAll('.delete-button').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const userId = this.getAttribute('data-user-id');
            if (confirm('Are you sure you want to delete this student?')) {
                fetch(ajaxurl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'delete_student_user',
                        user_id: userId,
                        security: '<?php echo wp_create_nonce("delete_student_nonce"); ?>'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Student deleted successfully!');
                        this.closest('tr').remove();
                    } else {
                        alert(data.data || 'Something went wrong.');
                    }
                })
                .catch(err => {
                    alert('Request failed.');
                });
            }
        });
    });

    // Filter functionality
    const statusFilter = document.getElementById('student-filter-status');
    const searchInput = document.getElementById('student-search');
    const resetButton = document.getElementById('reset-filters');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const status = statusFilter.value.toLowerCase();
        const searchTerm = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const rowStatus = row.getAttribute('data-status');
            const rowText = row.textContent.toLowerCase();

            const statusMatch = !status || rowStatus === status;
            const searchMatch = !searchTerm || rowText.includes(searchTerm);

            row.style.display = statusMatch && searchMatch ? '' : 'none';
        });
    }

    // Event listeners
    statusFilter.addEventListener('change', filterTable);
    searchInput.addEventListener('input', filterTable);
    resetButton.addEventListener('click', function() {
        statusFilter.value = '';
        searchInput.value = '';
        filterTable();
    });

    // Initial filter in case of URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('status')) {
        statusFilter.value = urlParams.get('status');
    }
    if (urlParams.has('search')) {
        searchInput.value = urlParams.get('search');
    }
    filterTable();
});
</script>