<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div id="akismet-plugin-container">
	<div class="akismet-masthead">
		<div class="akismet-masthead__inside-container">
			<?php Akismet::view( 'logo' ); ?>
		</div>
	</div>
	<div class="akismet-lower">
		<?php Akismet_Admin::display_status(); ?>
		<div class="akismet-boxes">
			<?php

			if ( Akismet::predefined_api_key() ) {
				Akismet::view( 'predefined' );
			} elseif ( $akismet_user && in_array( $akismet_user->status, array( 'active', 'active-dunning', 'no-sub', 'missing', 'cancelled', 'suspended' ) ) ) {
				Akismet::view( 'connect-jp', compact( 'akismet_user' ) );
			} else {
				Akismet::view( 'activate' );
			}

			?>
		</div>
	</div>
</div>