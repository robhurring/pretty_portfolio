<?php
/*
Plugin Name: Pretty Portfolio
Plugin URI: http://github.com/robhurring/pretty_portfolio
Description: Creates a nice CODA like slider effect for your portfolio using page templates.
Version: 1.0
Author: Rob Hurring <rob@zerobased.com>
Author URI: http://blog.ubrio.us
*/

// .h

add_action('edit_form_advanced', 'pretty_portfolio_form', 1);
add_action('save_post', 'pretty_portfolio_save');
add_action('admin_menu', 'pretty_portfolio_admin_menu');

// #define

$_portfolio_fields = array(
  'portfolio_big'	        => 'Big Portfolio Image (660x190px)',
  'portfolio_big_preview' => 'Big Image Preview (lightbox)',
  'client_name'		        => 'Client\'s Name',
  'skills'				        => 'What skills were used?',
  'client_link'		        => 'Link to client\'s website'
  );
$_plugin_path = get_bloginfo('wpurl') . '/wp-content/plugins/pretty_portfolio';

// fn()

function include_pretty_portfolio_assets()
{
  global $_plugin_path;
	$_js_assets = array(
		'jquery' => array('lib' => '/js/jquery-1.2.6.min.js', 'deps' => null, 'version' => '1.2.6'),
		'easing_compatibility'  => array('lib' => '/js/jquery.easing.compatibility.js', 'deps' => array('jquery'), 'version' => '1.0'),
		'easing' => array('lib' => '/js/jquery.easing.1.3.js', 'deps' => array('jquery'), 'version' => '1.3'),
		'scroll_to' => array('lib' => '/js/jquery.scrollTo-min.js', 'deps' => array('jquery'), 'version' => '1.4'),
		'local_scroll' => array('lib' => '/js/jquery.localscroll-min.js', 'deps' => array('jquery'), 'version' => '1.2.6'),
		'serial_scroll' => array('lib' => '/js/jquery.serialScroll-min.js', 'deps' => array('jquery'), 'version' => '1.2.1'),
		'lightbox' => array('lib' => '/js/jquery.lightbox-0.5.min.js', 'deps' => array('jquery'), 'version' => '0.5'),
		'pretty_portfolio' => array('lib' => '/js/jquery.pretty_portfolio.js', 'deps' => array('jquery', 'easing', 'scroll_to', 'local_scroll', 'serial_scroll'), 'version' => '1.0')
		);
		
	wp_enqueue_style('pretty_portfolio', $_plugin_path.'/css/pretty_portfolio.css');
	wp_enqueue_style('lightbox', $_plugin_path.'/css/lightbox.css');

	// comment the next line if you want to use your own jQuery file
	wp_deregister_script('jquery');
	foreach($_js_assets as $name => $data)
		wp_enqueue_script($name, $_plugin_path.$data['lib'], $data['deps'], $data['version']);
}

function pretty_portfolio_admin_menu()
{
  add_options_page('Pretty Portfolio Settings', 'Pretty Portfolio', 7, __FILE__, 'pretty_portfolio_admin_form');
}

/**
 * Settings menu admin form
 */
function pretty_portfolio_admin_form()
{  
  $_options = get_option('pretty_portfolio_options'); 
  if($_POST)
  {
    $_posted_options = array(
      'pretty_portfolio_category' => strip_tags(stripslashes($_POST['pretty_portfolio_category']))
      );
    if($_posted_options != $_options)
    {
      $_options = $_posted_options;
      update_option('pretty_portfolio_options', $_options);
    }
  }
	$pretty_portfolio_category = empty($_options['pretty_portfolio_category']) ? '' : $_options['pretty_portfolio_category'];
  ?>
  <div class="wrap">
  	<div id="icon-options-general" class="icon32"><br /></div>
      <h2>Pretty Portfolio Settings</h2>
    </div>
    <form method="post" action=''>
      <input id="pretty_portfolio_options" type="hidden" value="1" name="pretty_portfolio_options"/>
      <table class="form-table">
				<tr valign="top">
				  <th scope="row">
						<label for="com_cat"><em>Portfolio Category ID</em></label>
					</th>
				  <td>
						<input name="pretty_portfolio_category" type="text" id="pretty_portfolio_category" value="<?php echo $pretty_portfolio_category ?>" size="2" maxlength="2" /><br/>
				    Enter the Category number here, this category's posts will be used as portfolio items on the starting page and for the portfolio page template<br/>
					</td>
				</tr>
      </table>
			<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
    </form>
  </div>
  <?php
}

/**
 * Build form for post admin
 */
function pretty_portfolio_form()
{
  global $_portfolio_fields;
  $id = isset($_GET['post']) ? $_GET['post'] : null;	
  ?>
  <div class="postbox closed" id="projektsdiv">
    <h3>Pretty Portfolio</h3>
    <div class="inside">
      <p>Image Sizes may vary, recommended size displayed in braces. <br/>Put Preview Text into excerpt field.</p>
      <?php
        foreach($_portfolio_fields as $key => $description)
        {
          $existing = $id ? get_post_meta($id, $key, true) : '';
          echo "<p><label for='".$key."'>".$description.": </label><input id='".$key."' name='".$key."' type='text' value='".$existing."'></p>";
        }
      ?>
    </div>
  </div>
  <?php
}

/**
 * Saves the post meta data
 */
function pretty_portfolio_save($id)
{
  global $_portfolio_fields;
  foreach($_portfolio_fields as $key => $description)
  {
    $existing = get_post_meta($id, $key, true);
    $new = $_POST[$key];
    if($new == '')
    {
      if($existing != '')
        delete_post_meta($id, $key, $value);
    }else{
      if($existing == '')
        add_post_meta($id, $key, $new, true);
      else
        update_post_meta($id, $key, $new, $existing);
    }
  }
}

// #include

function pp_truncate($text, $max_length = 30, $end = '...')
{
	return (strlen($text) >= $max_length) ? substr($text, 0, $max_length - strlen($end)) . $end : $text;
}

?>
