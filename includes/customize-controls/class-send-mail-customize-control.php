<?php
class WP_Send_Mail_Customize_Control extends WP_Customize_Control {
	public $type = 'mailtpl_send_mail';
	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$id    = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
		$class = 'customize-control customize-control-' . $this->type;

		?><li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
			<label>
				<button class="button button-primary " id="mailtpl-send_mail" tabindex="0"><?php _e( 'Send', 'mailtpl' ); ?></button>
				<img id="mailtpl-spinner" src="<?php echo admin_url('images/spinner.gif');?>" alt="" style="display:none;"/>
				<span id="mailtpl-success" style="display:none;"><?php _e( 'Email sent!', 'mailtpl');?></span>
				<?php if ( ! empty( $this->description ) ) : ?>
					<p><span class="description customize-control-description"><?php echo $this->description; ?></span></p>
				<?php endif; ?>
			</label>
		</li><?php
	}
}