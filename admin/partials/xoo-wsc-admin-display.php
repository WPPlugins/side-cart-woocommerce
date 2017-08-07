<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    xoo-wsc
 * @subpackage xoo-wsc/admin/partials
 */
?>
<?php
?>
<div class="wrap">
	<?php
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$this->render_tabs(); 
	?>

	<div class="xoo-container">
		<div class="xoo-main">
			<?php 
			switch ($tab) {
				case 'style': ?>
					<form method="post" action="options.php">
						<?php
							settings_fields( 'xoo-wsc-sy-options' ); // Display Settings

							do_settings_sections( 'xoo-wsc-sy' ); // Display Sections

							submit_button( 'Save Settings' );	// Display Save Button
						?>			
					</form>
				<?php 
				break;
				case 'advanced':
				?>
					<form method="post" action="options.php">
						
						<?php
							settings_fields( 'xoo-wsc-av-options' );

							do_settings_sections( 'xoo-wsc-av' );

							submit_button( 'Save Settings' );
						?>		
					</form>
				<?php 
				break;
				default:
				?>
					<form method="post" action="options.php">				
						<div class="inside">
							<?php 
								settings_fields( 'xoo-wsc-gl-options' );

								do_settings_sections( 'xoo-wsc-gl' );

								submit_button( 'Save Settings' );
							?>
							<div class="clear"></div>
					</form>
			<?php	
			} 
			?>
		</div>
	</div>

	<div class="xoo-sidebar">
		<div class="xoo-chat">
			<span class="xoo-chhead">Need Help?</span>
			<span class="dashicons dashicons-format-chat xoo-chicon"></span>
			<span class="xoo-chtxt">Use <a href="http://xootix.com">Live Chat</a></span>
		</div>
		<a href="http://xootix.com" class="xoo-more-plugins">Try other awesome plugins.</a>
	</div>
</div>



