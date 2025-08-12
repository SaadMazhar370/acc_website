<?php
/**
 * Template part for display register form on popup.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Edumall
 * @since   1.0.0
 * @version 2.8.4
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="popup-content-header">
	<h3 class="popup-title"><?php esc_html_e( 'Sign Up', 'edumall' ); ?></h3>
	<p class="popup-description">
		<?php printf( esc_html__( 'Already have an account? %sLog in%s', 'edumall' ), '<a href="#" class="open-popup-login link-transition-02">', '</a>' ); ?>
	</p>
</div>

<div class="popup-content-body">
	<form id="edumall-register-form" class="edumall-register-form" method="post">

		<?php do_action( 'edumall_popup_register_before_form_fields' ); ?>

		<div class="row">
			<div class="col-xs-12">
				<div class="form-group">
					<label for="ip_reg_register_for" class="form-label"><?php esc_html_e( 'Register For', 'edumall' ); ?></label>
				<select name="register_for" id="ip_reg_register_for" class="form-control" required>
	<option value="" disabled selected>Please Select</option>
	<option value="aisha_cademy_canada">Aisha Academy Canada</option>
	<option value="girls_hifzul_quran_school">Girls’ Hifzul Qur’ān School</option>
</select>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_firstname" class="form-label"><?php esc_html_e( 'First Name', 'edumall' ); ?></label>
					<input type="text" id="ip_reg_firstname" class="form-control form-input" name="firstname" placeholder="<?php esc_attr_e( 'First Name', 'edumall' ); ?>">
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_lastname" class="form-label"><?php esc_html_e( 'Last Name', 'edumall' ); ?></label>
					<input type="text" id="ip_reg_lastname" class="form-control form-input" name="lastname" placeholder="<?php esc_attr_e( 'Last Name', 'edumall' ); ?>">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_phone" class="form-label"><?php esc_html_e( 'Phone', 'edumall' ); ?></label>
					<input type="text" id="ip_reg_phone" class="form-control form-input" name="phone" placeholder="<?php esc_attr_e( 'Phone', 'edumall' ); ?>">
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_dob" class="form-label"><?php esc_html_e( 'Date Of Birth', 'edumall' ); ?></label>

<input type="date" id="ip_reg_dob" class="form-control form-input"
       name="date_of_birth" placeholder="<?php esc_attr_e( 'Date Of Birth', 'edumall' ); ?>">

				</div>
			</div>
		</div>

		<?php do_action( 'edumall_popup_register_after_form_field_name' ); ?>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_username" class="form-label"><?php esc_html_e( 'Username', 'edumall' ); ?></label>
					<input type="text" id="ip_reg_username" class="form-control form-input" name="username" placeholder="<?php esc_attr_e( 'Username', 'edumall' ); ?>">
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_email" class="form-label"><?php esc_html_e( 'Email', 'edumall' ); ?></label>
					<input type="email" id="ip_reg_email" class="form-control form-input" name="email" placeholder="<?php esc_attr_e( 'Your Email', 'edumall' ); ?>">
				</div>
			</div>
		</div>

		<?php do_action( 'edumall_popup_register_after_form_field_login' ); ?>

		<div class="row">
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_password" class="form-label"><?php esc_html_e( 'Password', 'edumall' ); ?></label>
					<div class="form-input-group form-input-password">
						<input type="password" id="ip_reg_password" class="form-control form-input" name="password" placeholder="<?php esc_attr_e( 'Password', 'edumall' ); ?>">
						<button type="button" class="btn-pw-toggle" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password', 'edumall' ); ?>"></button>
					</div>
				</div>
			</div>
			<div class="col-xs-6">
				<div class="form-group">
					<label for="ip_reg_password2" class="form-label"><?php esc_html_e( 'Re-Enter Password', 'edumall' ); ?></label>
					<div class="form-input-group form-input-password">
						<input type="password" id="ip_reg_password2" class="form-control form-input" name="password2" placeholder="<?php esc_attr_e( 'Re-Enter Password', 'edumall' ); ?>">
						<button type="button" class="btn-pw-toggle" data-toggle="0" aria-label="<?php esc_attr_e( 'Show password', 'edumall' ); ?>"></button>
					</div>
				</div>
			</div>
		</div>

		<?php do_action( 'edumall_popup_register_after_form_field_password' ); ?>

		<?php
		$privacy_page_id = get_option( 'wp_page_for_privacy_policy', 0 );
		$terms_page_id   = Edumall::setting( 'page_for_terms_and_conditions', 0 );

		$privacy_link = $privacy_page_id ? sprintf(
			'<a href="%s" target="_blank">%s</a>',
			esc_url( get_permalink( $privacy_page_id ) ),
			esc_html__( 'Privacy Policy', 'edumall' )
		) : esc_html__( 'Privacy Policy', 'edumall' );

		$terms_link = $terms_page_id ? sprintf(
			'<a href="%s" target="_blank">%s</a>',
			esc_url( get_permalink( $terms_page_id ) ),
			esc_html__( 'Terms', 'edumall' )
		) : esc_html__( 'Terms', 'edumall' );
		?>

		<div class="form-group accept-account">
			<label class="form-label form-label-checkbox" for="ip_accept_account">
				<input type="checkbox" id="ip_accept_account" class="form-control" name="accept_account" required value="1">
				<?php printf( esc_html__( 'Accept the %1$s and %2$s', 'edumall' ), $terms_link, $privacy_link ); ?>
			</label>
		</div>

		<?php do_action( 'edumall_popup_register_after_form_field_accept' ); ?>
		<?php do_action( 'edumall_popup_register_after_form_fields' ); ?>

		<!-- his is where the response shows -->
		<div class="form-response-messages"></div>

		<!-- Submission and hidden fields -->
		<div class="form-group">
			<?php wp_nonce_field( 'user_register', 'user_register_nonce' ); ?>
			<input type="hidden" name="action" value="edumall_user_register">
			<input type="hidden" name="form_type" value="student_form">

			<!-- ew: Hidden field for IP address -->
<input type="hidden" name="ip_address" value="<?php echo esc_attr( $_SERVER['REMOTE_ADDR'] ); ?>">

			<button type="submit" class="button form-submit"><?php esc_html_e( 'Register', 'edumall' ); ?></button>
		</div>
	</form>
</div>


<script>
jQuery(document).ready(function ($) {
    $('#edumall-register-form').on('submit', function (e) {
        if ($('#ip_reg_register_for').val() === '') {
            e.preventDefault();
            alert('Please select a value for "Register For".');
        }
    });
});
</script>

