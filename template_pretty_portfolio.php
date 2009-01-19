<?php
/*
Template Name: Pretty Portfolio Template
*/
?>
<?php 
  global $_plugin_path;
	include_pretty_portfolio_assets();
 	get_header();

  $_options = get_option('pretty_portfolio_options'); 
  $_category_id = $_options['pretty_portfolio_category'] == '' ? 0 : $_options['pretty_portfolio_category'];
  $_query = sprintf("cat=%s&showposts=%d", $_category_id, -1);
  
  $wp_query = new WP_Query($_query);
  if(have_posts()):  
?>
  <script type="text/javascript" charset="utf-8">
    // for JS images
    var PLUGIN_PATH='<?php echo $_plugin_path ?>';
  </script>
	<div id="slider" class='clearfix'>
		    
	  <ul class="navigation right">
		<?php foreach($wp_query->posts as $post): ?>
      <li><a href="#<?php echo $post->post_name ?>"><?php echo pp_truncate($post->post_title, 30) ?></a></li>
		<?php endforeach; ?>
	  </ul>

	  <div class="scroll">
	    <div class="scrollContainer">
			<?php
	    // load portfolio slides
			foreach($wp_query->posts as $post):
				$images = array(
          'big_image'		=> get_post_meta($post->ID, 'portfolio_big', true),
          'big_preview' => get_post_meta($post->ID, 'portfolio_big_preview', true)
					);
        $data = array(
          'client_name'	=> get_post_meta($post->ID, 'client_name', true),
          'skills'				=> get_post_meta($post->ID, 'skills', true),
          'client_link'	=> get_post_meta($post->ID, 'client_link', true)
          );
	     ?>
	      <div class="panel clearfix" id="<?php echo $post->post_name ?>">
					<h2><a href="<?php echo get_permalink(); ?>"><?php echo $post->post_title ?></a></h2>
					<?php if($images['big_image']): ?>
						<div class='portfolio_image_big'>
						  <?php if($images['big_preview']): ?>
						    <a href='<?php echo $images['big_preview'] ?>' class='lightbox' title='<?php echo $post->post_title ?>'>
              <?php endif; ?>
							  <img src='<?php echo $images['big_image'] ?>' />
							<?php if($images['big_preview']): ?>
  						  </a>
              <?php endif; ?>
						</div>
					<?php endif; ?>
					<div class='clearfix'>
						<div class='portfolio_excerpt'>
							<?php echo pp_truncate($post->post_excerpt, 255) ?>
						</div>
						<div class='portfolio_details'>
							<dl>
							<?php foreach($data as $key => $value): ?>
								<dt><?php echo ucwords(str_replace('_', ' ', $key)) ?>:</dt>
								<dd><?php echo preg_match('/^(http|mailto)/', $value) ? sprintf('<a href="%1$s">%1$s</a>', $value) : $value ?></dd>
							<?php endforeach; ?>
							</dl>
						</div>
					</div>
				</div>
	     <?php endforeach; ?>
	    </div>
	  </div>
	</div>
		
<?php else: ?>
	<div style='margin:40px 0;'>
		Sorry, but there are no portfolio pieces to display currently.
	</div>
	<?php get_sidebar(); ?>
<?php endif; ?>

<?php get_footer(); ?>


