<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Management</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        
        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 12px 20px;
            border: 1px solid #ddd;
            border-radius: 30px;
            font-size: 16px;
            outline: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            border-color: #3498db;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.2);
        }
        
        .search-input::placeholder {
            color: #95a5a6;
        }
        
        .plugins-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .instructor-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid #3498db;
            position: relative;
        }
        
        .instructor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .instructor-name {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .other-name {
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: auto;
        }
        
        .status-active {
            background-color: #e3f9e5;
            color: #1b7052;
        }
        
        .status-pending {
            background-color: #fff4e5;
            color: #cc5a00;
        }
        
        .status-inactive {
            background-color: #ffecec;
            color: #d33a3a;
        }
        
        .instructor-details {
            margin-top: 15px;
            color: #34495e;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .instructor-details div {
            margin-bottom: 10px;
        }
        
        .instructor-details strong {
            color: #2c3e50;
            min-width: 100px;
            display: inline-block;
        }
        
        .instructor-meta {
            display: flex;
            justify-content: space-between;
            color: #7f8c8d;
            font-size: 13px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #ecf0f1;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
        }
        
        .meta-icon {
            margin-right: 5px;
            font-size: 14px;
        }
        
        .dashboard-access {
            color: #3498db;
        }
        
        .email-verified {
            color: #2ecc71;
        }
        
        .email-pending {
            color: #f39c12;
        }
        
        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 15px;
        }
        
        .btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            margin-left: 8px;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background-color: #3498db;
            color: white;
        }
        
        .btn-view {
            background-color: #2ecc71;
            color: white;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .course-list, .hifz-course-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 5px;
        }
        
        .course-item, .hifz-course-item {
            margin-bottom: 8px;
            padding: 8px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }
        
        .course-meta, .hifz-course-meta {
            display: flex;
            gap: 10px;
            margin-top: 4px;
            font-size: 12px;
            color: #7f8c8d;
        }
        
        .tablenav-pages .pagination-links {
    display: inline-block;
    margin-top: 20px;
}
.tablenav-pages .pagination-links a,
.tablenav-pages .pagination-links span {
    display: inline-block;
    padding: 6px 12px;
    margin: 0 3px;
    background: #f1f1f1;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #0073aa;
    text-decoration: none;
}
.tablenav-pages .pagination-links .current {
    background: #0073aa;
    color: white;
    font-weight: bold;
}

        
        
        @media (max-width: 768px) {
            .plugins-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1>Instructor Management</h1>
    </div>

    <div class="search-container">
        <input type="text" class="search-input" placeholder="Search instructors by name, department, or email...">
    </div>
    
    
    <div class="wrap">

    <div class="plugins-container">
        <?php
        if (!defined('ABSPATH')) exit;

        if (!current_user_can('edit_users')) {
            wp_die(__('You do not have permission to access this page.', 'student-manager'));
        }

        
        $all_instructors = get_users([
    'meta_query' => [
        [
            'key' => '_is_tutor_instructor',
            'value' => '',
            'compare' => '!='
        ]
    ],
    'orderby' => 'registered',
    'order' => 'DESC'
]);

$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$per_page = 10;
$total_instructors = count($all_instructors);
$total_pages = ceil($total_instructors / $per_page);

// Slice instructors for current page
$instructors = array_slice($all_instructors, ($current_page - 1) * $per_page, $per_page);

        if (!empty($instructors)) {
            foreach ($instructors as $instructor) {
                $ID = $instructor->ID;
                $full_name = trim(implode(' ', array_filter([
                    get_user_meta($ID, 'employment_first_name', true),
                    get_user_meta($ID, 'employment_middle_name', true),
                    get_user_meta($ID, 'employment_last_name', true),
                ])));

                $email = get_user_meta($ID, 'employment_email', true) ?: $instructor->user_email;
                $phone = get_user_meta($ID, 'employment_inst_cellPhone', true);
                $address = get_user_meta($ID, 'employment_inst_address', true);
                $majlis = get_user_meta($ID, 'employment_inst_majlis', true);
                $aims_code = get_user_meta($ID, 'employment_inst_aimsCode', true);
                $dashboard_access = get_user_meta($ID, 'dashboard_access', true) ?: 'active';
                $email_status = get_user_meta($ID, 'email_verified', true) ?: 'no';
                $reg_date = date_i18n('Y-m-d H:i', strtotime($instructor->user_registered));
                $ip = get_user_meta($ID, 'registration_ip', true);
                $admin_remarkes = get_user_meta($ID, 'admin_remarkes', true);
                $emp_other_name = get_user_meta($ID, 'employment_other_names', true);
                $emp_ip = get_user_meta($ID, 'registration_ip', true);

                // Determine status badge
                $status_class = 'status-active';
                $status_text = __('Active', 'student-manager');
                if ($dashboard_access === 'pending') {
                    $status_class = 'status-pending';
                    $status_text = __('Pending', 'student-manager');
                } elseif ($dashboard_access === 'inactive') {
                    $status_class = 'status-inactive';
                    $status_text = __('Inactive', 'student-manager');
                }

                // File handling
                $files = get_user_meta($ID, 'std_files', true);
                $file_html = '—';
                
                if (!empty($files)) {
                    if (is_array($files)) {
                        $file_links = [];
                        foreach ($files as $file) {
                            if (is_string($file) && !empty($file)) {
                                $file_links[] = '<a href="' . esc_url($file) . '" target="_blank">' . esc_html(basename($file)) . '</a>';
                            }
                        }
                        $file_html = !empty($file_links) ? implode('<br>', $file_links) : '—';
                    } elseif (is_string($files) && !empty($files)) {
                        $file_html = '<a href="' . esc_url($files) . '" target="_blank">' . esc_html(basename($files)) . '</a>';
                    }
                }

                // PDF Handling
                $pdf_path = get_user_meta($ID, 'employment_pdf_path', true);
                $pdf_html = '<span class="text-danger">' . __('PDF not found', 'student-manager') . '</span>';
                
                if (!empty($pdf_path)) {
                    if (is_array($pdf_path)) {
                        $pdf_path = !empty($pdf_path[0]) ? $pdf_path[0] : '';
                    }
                    
                    if (is_string($pdf_path) && !empty($pdf_path)) {
                        $upload_dir = wp_upload_dir();
                        $pdf_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $pdf_path);
                        $pdf_html = '<a href="' . esc_url($pdf_url) . '" target="_blank" title="' . esc_attr__('View Admission Form', 'student-manager') . '">
                            <i class="fas fa-file-pdf"></i> ' . esc_html__('View Form', 'student-manager') . '
                        </a>';
                    }
                }
                ?>
                <div class="instructor-card">
                    <div class="instructor-name">
                        <?php echo esc_html($full_name ?: __('No name provided', 'student-manager')); ?>
                        <span class="status-badge <?php echo esc_attr($status_class); ?>">
                            <?php echo esc_html($status_text); ?>
                        </span>
                    </div>
                    
                    <?php if ($emp_other_name) : ?>
                        <div class="other-name">
                            <strong><?php esc_html_e('Other name:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($emp_other_name); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="instructor-details">
                        <div>
                            <strong><?php esc_html_e('Email:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($email); ?>
                        </div>
                        <div>
                            <strong><?php esc_html_e('Phone:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($phone ?: '—'); ?>
                        </div>
                        <div>
                            <strong><?php esc_html_e('Aims Code:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($aims_code ?: '—'); ?>
                        </div>
                        <div>
                            <strong><?php esc_html_e('Majlis:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($majlis ?: '—'); ?>
                        </div>
                        <div>
                            <strong><?php esc_html_e('Registered:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($reg_date); ?>
                        </div>
                        <div>
                            <strong><?php esc_html_e('Ip Address:', 'student-manager'); ?></strong> 
                            <?php echo esc_html($emp_ip); ?>
                        </div>
                    </div>
                    
                    <div class="instructor-meta">
                        <div class="meta-item dashboard-access">
                            <i class="fas fa-tachometer-alt meta-icon"></i>
                            <span>
                                <?php echo esc_html(ucfirst($dashboard_access)); ?>
                            </span>
                        </div>
                        <div class="meta-item <?php echo ($email_status === 'yes') ? 'email-verified' : 'email-pending'; ?>">
                            <i class="fas <?php echo ($email_status === 'yes') ? 'fa-check-circle' : 'fa-hourglass-half'; ?> meta-icon"></i>
                            <span>
                                <?php echo esc_html($email_status === 'yes' ? __('Verified', 'student-manager') : __('Pending', 'student-manager')); ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <?php echo $pdf_html; ?>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=instructor-profile&user_id=' . $ID)); ?>" class="btn btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="<?php echo esc_url(admin_url('admin.php?page=edit-instructor-management&user_id=' . $ID)); ?>" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<p>' . __('No instructors found.', 'student-manager') . '</p>';
        }
        ?>
    </div>

<?php if ($total_pages > 1): ?>
    <div class="tablenav bottom" style="margin-top: 30px; text-align:center;">
        <div class="tablenav-pages">
            <span class="pagination-links">
                <?php
                echo paginate_links(array(
                    'base' => add_query_arg('paged', '%#%'),
                    'format' => '',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => '« Prev',
                    'next_text' => 'Next »',
                    'type' => 'plain',
                ));
                ?>
            </span>
        </div>
    </div>
<?php endif; ?>


</div>
    
    
    
    

    <script>
        // Search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.instructor-card');
            
            cards.forEach(card => {
                const name = card.querySelector('.instructor-name').textContent.toLowerCase();
                const details = card.querySelector('.instructor-details').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || details.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>