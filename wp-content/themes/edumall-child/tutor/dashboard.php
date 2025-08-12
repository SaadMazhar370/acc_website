<?php
/**
 * Template for displaying student Public Profile
 */
defined( 'ABSPATH' ) || exit;

$current_user    = wp_get_current_user();
$access          = get_user_meta( $current_user->ID, 'dashboard_access', true );
$email_verified  = get_user_meta( $current_user->ID, 'email_verified', true );
$form_submitted  = get_user_meta( $current_user->ID, 'aisha_form_submitted', true ); // ✅ Make sure you save this after form submission
$emp_form_submitted  = get_user_meta( $current_user->ID, 'is_emp_form_submited', true ); // ✅ Make sure you save this after form submission

// Redirect if email is not verified
if ( $email_verified !== 'yes' ) {
    wp_redirect( 'https://digitalconsulter.com/waiting-for-approval/' );
    exit;
}

// No redirect if verified – allow handling below
?>

<?php get_header(); ?>

<div class="page-content">
    <?php if ( $access !== 'active' ) : ?>
        <style>
            .tutor-dashboard-sidebar,
            .tutor-dashboard-header-button,
            .tutor-dashboard-header-stats,
            .dashboard-header-toggle-menu {
                display: none !important;
            }
            .page-main-content {
                width: 100% !important;
                margin-left: 0 !important;
            }
            body.tutor-dashboard {
                padding-left: 0 !important;
            }
        </style>
    <?php endif; ?>

    <div class="tutor-dashboard-header-wrap">
        <div class="container small-gutter">
            <div class="row">
                <div class="col-md-12">
                    <div class="tutor-dashboard-header">
                        <!-- Avatar and welcome -->
                        <div class="dashboard-header-toggle-menu dashboard-header-menu-open-btn">
                            <?php echo Edumall_Helper::get_file_contents( EDUMALL_THEME_SVG_DIR . '/icon-toggle-sidebar.svg' ); ?>
                        </div>
                        <div class="tutor-header-user-info">
                            <div class="tutor-dashboard-header-avatar">
                                <?php echo edumall_get_avatar( $current_user->ID, 150 ); ?>
                            </div>
                            <div class="tutor-dashboard-header-info">
                                <h4 class="tutor-dashboard-header-display-name">
                                    <span class="welcome-text"><?php esc_html_e( 'Howdy,', 'edumall' ); ?></span>
                                    <?php echo esc_html( $current_user->display_name ); ?>
                                </h4>
                            </div>
                        </div>

                        <div class="tutor-dashboard-header-button">
                            <?php do_action( 'tutor_dashboard/before_header_button' ); ?>
                            <?php Edumall_Header::instance()->print_open_canvas_menu_button(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container small-gutter">
        <div class="row">
            <div class="page-main-content">
                <div class="tutor-dashboard-content">
                    <?php
                    global $wp_query;
                    $dashboard_page_name = '';
                    if ( isset( $wp_query->query_vars['tutor_dashboard_page'] ) ) {
                        $dashboard_page_name = $wp_query->query_vars['tutor_dashboard_page'];
                    }

if ( !$dashboard_page_name || $dashboard_page_name === 'dashboard' ) {

    // ✅ If access is active, show dashboard normally
    if ( $access === 'active' ) {
        tutor_load_template( 'dashboard.dashboard' ); // <-- load actual dashboard
    }

    // 🚫 If form is submitted and access is still pending, redirect to thank you
    elseif ( $form_submitted === 'yes' && $access !== 'active' ) {
        if ( ! defined( 'DOING_AJAX' ) && ! is_page( 'profile-complete-successfullly' ) ) {
            wp_redirect( site_url( '/profile-complete-successfullly/' ) );
            exit;
        }

        // ✅ Show thank-you message if already on that page
        echo '<div style="padding: 20px; background: #e6ffed; color: #065f46; border: 1px solid #b7eb8f; font-size: 16px; font-weight: 500; border-radius: 5px;">
        🎉 Thank you! Your admission form has been submitted. The academy management is reviewing your application. Once approved, you will gain full access to your dashboard.
        </div>';
    }
    elseif ( $emp_form_submitted === 'yes' && $access !== 'active' ) {
        if ( ! defined( 'DOING_AJAX' ) && ! is_page( 'profile-complete-successfullly' ) ) {
            wp_redirect( site_url( '/profile-complete-successfullly/' ) );
            exit;
        }

        // ✅ Show thank-you message if already on that page
        echo '<div style="padding: 20px; background: #e6ffed; color: #065f46; border: 1px solid #b7eb8f; font-size: 16px; font-weight: 500; border-radius: 5px;">
        🎉 Thank you! Your admission form has been submitted. The academy management is reviewing your Job application. Once approved, you will gain full access to your dashboard.
        </div>';
    }

    // 📝 Show the form if user hasn’t submitted it yet
// 📝 Show the form if user hasn’t submitted it yet

else {
    $user_id                   = get_current_user_id();
    $form_type                 = get_user_meta($user_id, 'form_type', true); // student_form or emp_form
    $is_inst_form              = get_user_meta($user_id, 'is_inst_form', true); // yes for instructor
    $student_form_submitted    = get_user_meta($user_id, 'aisha_form_submitted', true);
    $instructor_form_submitted = get_user_meta($user_id, 'is_emp_form_submitted', true);

    // ✅ If either form was already submitted, show default dashboard
    if (
        ($form_type === 'student_form' && $student_form_submitted === 'yes') ||
        ($is_inst_form === 'yes' && $instructor_form_submitted === 'yes')
    ) {
        tutor_load_template('dashboard.dashboard');
    } else {
        // 📝 Show the correct form
        if ($form_type === 'student_form') {
            echo do_shortcode('[student_profile_form]');
        } elseif ($is_inst_form === 'yes') {
            echo do_shortcode('[instructor_career_form]');
        } else {
            echo '<p style="padding: 20px; color: red;">Unable to determine user type. Please contact support.</p>';
        }
    }
}
}else {
    if ( $access === 'active' ) {
        // ✅ Load actual TutorLMS dashboard content
        tutor_load_template( 'dashboard.' . $dashboard_page_name );
    } else {
        // ❌ Still restricted
        echo '<p style="padding: 20px; color: red; font-weight: bold;">Access to this section is restricted until your admission is approved.</p>';
    }
}

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
