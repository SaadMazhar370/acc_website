<?php
/**
 * @package       TutorLMS/Templates
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_option( 'users_can_register', false ) ) {
	$args = array(
		'image_path'  => tutor()->url . 'assets/images/construction.png',
		'title'       => __( 'Ooh! Access Denied', 'edumall' ),
		'description' => __( 'You do not have access to this area of the application. Please refer to your system  administrator.', 'edumall' ),
		'button'      => array(
			'text'  => __( 'Go to Home', 'edumall' ),
			'url'   => get_home_url(),
			'class' => 'tutor-btn',
		),
	);
	tutor_load_template( 'feature_disabled', $args );

	return;
}
?>
<div class="user-form-box user-register-form">
	<div class="user-form-wrap">
		<div class="form-header">
			<h4 class="form-title"><?php esc_html_e( 'Student registration', 'edumall' ); ?></h4>
			<p class="form-description">
				<?php printf( esc_html__( 'Already have an account? %sLog in%s', 'edumall' ), '<a href="' . esc_url( edumall_login_url() ) . '" class="link-transition-02">', '</a>' ); ?>
			</p>
		</div>

		<?php do_action( 'tutor_before_student_reg_form' ); ?>

		<form method="post" enctype="multipart/form-data" id="tutor-registration-form">
			<input type="hidden" name="tutor_course_enroll_attempt" value="<?php echo isset( $_GET['enrol_course_id'] ) ? (int) $_GET['enrol_course_id'] : ''; ?>">
			<?php do_action( 'tutor_student_reg_form_start' ); ?>

			<?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
			<input type="hidden" value="tutor_register_student" name="tutor_action"/>
<input type="hidden" name="ip_address" value="<?php echo esc_attr( $_SERVER['REMOTE_ADDR'] ); ?>" />
<input type="hidden" name="form_type" id="form_type" value="">


			<?php
			$validation_errors = apply_filters( 'tutor_student_register_validation_errors', array() );
			if ( is_array( $validation_errors ) && count( $validation_errors ) ) :
				?>
				<div class="tutor-alert tutor-warning tutor-mb-12">
					<ul class="tutor-required-fields">
						<?php foreach ( $validation_errors as $validation_error ) : ?>
							<li>
								<?php echo esc_html( $validation_error ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>


<div class="form-row row">
    <div class="col-xs-12">
        <label for="register_for">Register For</label>
        <select name="register_for" id="register_for" class="form-control">
            <option value="">Please Select</option>
            <option value="aisha_cademy_canada">Aisha Academy Canada</option>
            <option value="girls_hifzul_quran_school">Girls’ Hifzul Qur’ān School</option>
        </select>
    </div>
</div>
			<div class="form-row row">
				<div class="col-xs-12 col-sm-6">
					<label><?php esc_html_e( 'First Namess', 'edumall' ); ?></label>
					<input type="text" name="first_name"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'first_name' ) ); ?>"
					       placeholder="<?php esc_attr_e( 'First Name', 'edumall' ); ?>"
					/>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label><?php esc_html_e( 'Last Name', 'edumall' ); ?></label>
					<input type="text" name="last_name"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'last_name' ) ); ?>"
					       placeholder="<?php esc_attr_e( 'Last Name', 'edumall' ); ?>"
					/>
				</div>
			</div>

			<div class="form-row row">
				<div class="col-xs-12 col-sm-6">
					<label><?php esc_html_e( 'Username', 'edumall' ); ?></label>
					<input type="text" name="user_login" class="tutor_user_name"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'user_login' ) ); ?>"
					       placeholder="<?php esc_attr_e( 'Username', 'edumall' ); ?>"
					/>
				</div>
				<div class="col-xs-12 col-sm-6">
					<label><?php esc_html_e( 'Email', 'edumall' ); ?></label>
					<input type="text" name="email"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'email' ) ); ?>"
					       placeholder="<?php esc_attr_e( 'Your Email', 'edumall' ); ?>"
					/>
				</div>
			</div>
			
			<div class="form-row row">
    <div class="col-xs-12 col-sm-6">
    <label for="ip_reg_phone"><?php esc_html_e( 'Phone', 'edumall' ); ?></label>
    <input type="text" name="phone"
           value="<?php echo esc_attr( tutor_utils()->input_old( 'phone' ) ); ?>"
           placeholder="<?php esc_attr_e( 'Phone', 'edumall' ); ?>"
    />
</div>

    <div class="col-xs-12 col-sm-6">
        <label><?php esc_html_e( 'Date of Birth', 'edumall' ); ?></label>
        <input type="date" name="date_of_birth" id="ip_reg_dob"
               value="<?php echo esc_attr( tutor_utils()->input_old( 'date_of_birth' ) ); ?>"
               placeholder="<?php esc_attr_e( 'Date of Birth', 'edumall' ); ?>"
        />
    </div>

			<div class="form-row row">
				<div class="col-xs-12 col-sm-6 form-input-password">
					<label><?php esc_html_e( 'Password', 'edumall' ); ?></label>
					<input type="password" name="password"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'password' ) ); ?>"
					       placeholder="<?php esc_attr_e( 'Password', 'edumall' ); ?>"
					/>
					<button type="button" class="btn-pw-toggle" data-toggle="0"
					        aria-label="<?php esc_attr_e( 'Show password', 'edumall' ); ?>">
					</button>
				</div>

				<div class="col-xs-12 col-sm-6 form-input-password">
					<label><?php esc_html_e( 'Password confirmation', 'edumall' ); ?></label>
					<input type="password" name="password_confirmation"
					       value="<?php echo esc_attr( tutor_utils()->input_old( 'password_confirmation' ) ); ?>"
					       placeholder="<?php esc_attr_e( 'Password confirmation', 'edumall' ); ?>"
					/>
					<button type="button" class="btn-pw-toggle" data-toggle="0"
					        aria-label="<?php esc_attr_e( 'Show password', 'edumall' ); ?>">
					</button>
				</div>
			</div>

			<?php
			// providing register_form hook.
			do_action( 'tutor_student_reg_form_middle' );
			do_action( 'register_form' );
			?>

			<?php do_action( 'tutor_student_reg_form_end' ); ?>

			<?php
			$tutor_toc_page_link = tutor_utils()->get_toc_page_link();
			?>
			
			
			<?php if ( null !== $tutor_toc_page_link ) : ?>
    <div class="tutor-mb-24" style="margin-top: 20px; padding: 12px 16px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
        <label style="display: block; margin-bottom: 8px;">
            <input type="checkbox" name="accept_toc" required style="margin-right: 6px;">
            <?php esc_html_e( 'I agree to the website\'s', 'edumall' ); ?>
            <a target="_blank" href="<?php echo esc_url( $tutor_toc_page_link ); ?>" style="color: #0073aa; text-decoration: underline;">
                <?php esc_html_e( 'Terms and Conditions', 'edumall' ); ?>
            </a>
        </label>
    </div>
<?php endif; ?>

			<div class="form-row">
				<button type="submit" name="tutor_register_student_btn" value="register" class="tutor-button form-submit"><?php esc_html_e( 'Register', 'edumall' ); ?></button>
			</div>
		</form>

		<?php do_action( 'tutor_after_registration_form_wrap' ); ?>
	</div>
	<?php do_action( 'tutor_after_student_reg_form' ); ?>
</div>

<script>
jQuery(document).ready(function ($) {
    // Set form_type dynamically using jQuery
    var registerDropdown = $('select[name="register_for"]');
    var formTypeInput = $('#form_type');

    if (formTypeInput.length) {
        if (registerDropdown.length > 0) {
            formTypeInput.val('student_form');
        } else {
            formTypeInput.val('emp_form');
        }
    }

    // Validate "Register For" selection if present
    $('#tutor-registration-form').on('submit', function (e) {
        if (registerDropdown.length > 0 && registerDropdown.val() === '') {
            e.preventDefault();
            alert('Please select a value for "Register For".');
        }
    });
});
</script>

