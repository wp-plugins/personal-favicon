<?php
/*
Plugin Name: Personal Favicon
Plugin URI: http://www.subotica.in.rs/2009/09/25/wp-plugin-personal-favicon/
Description: Personal Favicon is plugin that enable to customize favicon for your Blog.
Version: 1.0
Author: Dejan Major - mangup
Author URI: http://www.subotica.in.rs/
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$ver= '1.0';

function personal_setup_menu() {
	if (function_exists('current_user_can')) {
		if (!current_user_can('manage_options')) return;
	} else {
		global $user_level;
		get_currentuserinfo();
		if ($user_level < 8) return;
	}
	if (function_exists('add_options_page')) {
		add_options_page(__('Personal FavIcon'), __('Personal FavIcon'), 1, __FILE__, 'personal_setup_page');
	}
} 

// Install the options page
add_action('admin_menu', 'personal_setup_menu');

function personal_setup_page(){
	global $wpdb;
	if (isset($_POST['update'])) {
		$options['picon_url'] = trim($_POST['picon_url'],'{}');
		$options['picon_action'] = trim($_POST['picon_action'],'{}');
		update_option('picon_url_option', $options);
		// Show a message to say we've done something
		echo '<div class="updated"><p>' . __('Options saved. Refresh Your Blog to see changes.') . '</p></div>';
	} else {
		
		$options = get_option('picon_url_option');
	}
	
	?>
		<div class="wrap">
		<h2><?php echo __('Personal FavIcon Setup Page'); ?></h2>
		<form method="post" action="">
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php _e('Personal FavIcon url URL:') ?></th>
				<td>
				<input name="picon_url" type="text" id="picon_url" value="<?php echo $options['picon_url']; ?>" size="60" /><br />
				

				Please insert direct link to Your favicon.<br />
				Example: <em>http://www.some-site.com/somedir/favicon.ico</em><br />.</td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Where to apply favicon:') ?></th>
				<td>
				<select name="picon_action" id="picon_action">
				<option value="<?php echo $options['picon_action']; ?>"><?php echo $options['picon_action']; ?></option>
				<option value="Nowhere">Nowhere</option>
				<option value="Blog">Blog</option>
				<option value="Admin Page">Admin Page</option>
				<option value="Blog & Admin Page">Blog & Admin Page</option>
				</select>
				<br />
				Chose where to apply Your FavIcon.
				</td>
			</tr>

		
		</table>
	
		<div class="submit">
		<input type="submit" name="update" value="<?php _e('Update') ?>"  style="font-weight:bold;" />
		</div>
		</form> 
		
       		
	</div>
	<?php	
}

function PIcon() {
global $options;
echo"<link rel=\"shortcut icon\" href=\"$options[picon_url]\" />";
}

$options = get_option('picon_url_option');
switch($options[picon_action]){
	case 'Blog':
	add_action('wp_head', 'PIcon');
	break;
	
	case 'Admin Page':
	add_action('admin_head', 'PIcon');
	break;
	
	case 'Blog & Admin Page':
	add_action('wp_head', 'PIcon');
	add_action('admin_head', 'PIcon');
	break;
	
	default:
	break;
}

?>