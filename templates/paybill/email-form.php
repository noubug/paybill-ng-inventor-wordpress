<div class="form-group gateway-paybill-email">
	<label for="first-name">
		<?php echo __( 'Email', 'inventor-paybill' ); ?>
	</label>

	<input type="text"
		   class="form-control"
		   name="email"
		   id="email"
		   value="<?php echo ! empty( $_POST['email'] ) ? esc_attr( $_POST['email'] ) : null; ?>" >
</div>