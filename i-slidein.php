<?php
/*
Plugin Name: i-SlideIn
Plugin URI: http://islidein.com
Description: Display a newsletter, banners etc. as a i-SlideIn on your WordPress.
Author: Tho Move Marketing
Author URI: http://move-marketing.dk/
Version: 1.0.0.3
*/

if (!defined('ABSPATH'))
	die();

define('_SLI_NAME_', 'i-SlideIn');
define('_SLI_AUTHOR_', 'Tho Move Marketing');
define('_SLI_VERSION_', '1.0.0.3');
define('_SLI_DOMAIN_', 'http://islidein.com');

// Add plugin setting to Settings tab
add_action ('admin_menu', 'sli_admin_menu');
function sli_admin_menu()
{
	add_options_page('i-SlideIn', 'i-SlideIn', 'manage_options', __FILE__, 'sli_admin_form');
}

// Add plugin link
add_filter('plugin_action_links', 'sli_action_links', 10, 2);
function sli_action_links($links, $file)
{
	if ($file == plugin_basename(__FILE__))
	{
		$sli_links = '<a href="'. get_admin_url() .'options-general.php?page=i-slidein/i-slidein.php">Settings</a>';
		array_unshift($links, $sli_links);
	}
	return $links;
}

// Register plugin option
register_activation_hook (__FILE__, 'sli_default_options');
function sli_default_options()
{
	$sli_options = get_option('sli_options');

	if( !$sli_options ) {
		$arr = array(
			'account' => '',
			'key' => ''
		);
		update_option('sli_options', $arr);
	}
}

// delete plugin settings
register_uninstall_hook (__FILE__, 'sli_delete_options');
function sli_delete_options()
{
	delete_option('sli_options');
}

// submit form
add_action ('admin_init', 'sli_init');
function sli_init( $input )
{
	if( isset($_POST['save-setting']) )
	{
		$sli_options = get_option('sli_options');
		$arr = array(
			'account' => stripslashes( htmlspecialchars_decode ( $_POST['sli_options']['account'] ) ),
			'key' => stripslashes( htmlspecialchars_decode ( $_POST['sli_options']['key'] ) )
		);
		update_option('sli_options', $arr);
	}
}

// create the options page
function sli_admin_form()
{
?>
	<div id="chc-admin" class="wrap">
		<h2><?php echo _SLI_NAME_; ?></h2>
		<div class="chc-video">
			<iframe width="600" height="450" src="https://www.youtube.com/embed/OU_A8F9SesI" frameborder="0" allowfullscreen></iframe>
		</div>
		<form method="post" action="<?php echo get_admin_url(); ?>options-general.php?page=i-slidein/i-slidein.php">
			<?php $options = get_option('sli_options'); ?>
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="sli_options[account]">Account</label></th>
								<td><input type="text" class="regular-text" value="<?php echo $options['account']; ?>" name="sli_options[account]"></td>
							</tr>
							<tr>
								<th scope="row"><label for="sli_options[key]">Key</label></th>
								<td><input type="text" class="regular-text" value="<?php echo $options['key']; ?>" name="sli_options[key]"></td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" name="save-setting" class="button-primary" value="Save setting" />
					</p>
				</div>
			</div>
		</form>
	</div>
<?php
}


// Add action to footer
add_action('wp_footer', 'sli_hook_footer');
function sli_hook_footer()
{
	$options = get_option('sli_options');
?>
	<!-- Start Slide-In -->
	<script type="text/javascript">
	// <![CDATA[
	var _sua = { _account: "<?php echo $options['account']; ?>", _key: "<?php echo $options['key']; ?>"};
	(function() {
	    var suh  = document.getElementsByTagName("head")[0];
	    var su = document.createElement("script"); su.type = "text/javascript";
	    su.src = "<?php echo _SLI_DOMAIN_; ?>/js/su.js";
	    suh.appendChild(su);
	})();
	// ]]>
	</script>
	<!-- //End Slide-In -->
<?php
}