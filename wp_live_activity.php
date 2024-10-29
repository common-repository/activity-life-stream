<?php
/*
Plugin Name: WP Live Activity Streaming
Plugin URI: http://www.wickedbrilliant.com/2009/12/live-activity-streaming/ 
Description: Display live activity streaming from different RSS urls to your wordpress sidebar using widgets.
Author: Scott Phillips
Version: 0.9.0
Author URI: http://www.wickedbrilliant.com
*/


/******** Compatibility ****************************/
	if ( ! defined( 'WP_CONTENT_URL' ) )
		define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
		define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
		define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
		define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	
	define('WPLAS_PLUGIN_PATH',WP_PLUGIN_DIR . '/wp_live_activity/');
	define('WPLAS_PLUGIN_URL',WP_PLUGIN_URL . '/wp_live_activity');
	
	if(!function_exists('esc_attr')) :
		function esc_attr($text){
			$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES);
			return apply_filters( 'attribute_escape', $safe_text, $text);
			return $text;
		}
	endif;
	
	if(!function_exists('esc_html')) :
		function esc_html($html){
			$safe_text = _wp_specialchars( $safe_text, ENT_QUOTES );
			return $text;
		}
	endif;
	
	/************* END Compatibility*********************/
	
	add_action('wp_head', 'addWPActivityStyles');


function addWPActivityStyles() {
	echo '<link rel="stylesheet" type="text/css" href="' . WPLAS_PLUGIN_URL . '/wp_live_activity_styles.css" />';
}

if(is_admin()) : 
function WP_Live_Activity_head(){ wp_enqueue_script('jquery'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo WPLAS_PLUGIN_URL; ?>/msdropdown/dd.css" />
<script language="javascript" type="text/javascript" src="<?php echo WPLAS_PLUGIN_URL; ?>/msdropdown/js/jquery.dd.js"></script>
<script type="text/javascript">
	jQuery(function($){
		$('#activity_icon').msDropDown();
	});
</script>
<?php }

add_action('admin_head','WP_Live_Activity_head');
add_action('admin_menu','WP_Live_Activity_menu');
add_action('admin_init','WP_activity_check');



function WP_activity_check(){
	$page	=	isset($_REQUEST['page']) ? $_REQUEST['page'] : '';
	
	if('' == $page) return;
	
	if($page != 'wp_live_activity') return;
	
	if(isset($_POST['sbt_submit'])){
		$activity_name	=	isset($_POST['activity_name']) ? esc_attr($_POST['activity_name']) : '';
		$feed_url		=	isset($_POST['feed_url']) ? esc_url($_POST['feed_url']) : '';
		$feed_cacheTime		=	isset($_POST['feed_cacheTime']) ? esc_attr($_POST['feed_cacheTime']) : '';
		$icon		=	isset($_POST['activity_icon']) ? esc_attr($_POST['activity_icon']) : 'default.png';
		$template		=	isset($_POST['activity_template']) ? esc_attr($_POST['activity_template']) : '{!TEXT_LINK}';
		$wplas_feeds	=	get_option('wplas_feeds');
		$exists	=	false;
		if(!is_array($wplas_feeds))
			$wplas_feeds	=	array();
		foreach($wplas_feeds as $feed){
			if($feed['name']	==	$activity_name || $feed['url']	==	$feed_url)
				$exists	=	true;
		}
		if(!$exists)
			$wplas_feeds[]	=	array('name' => $activity_name,'url' => $feed_url, 'icon' => $icon, 'template' => $template, 'cacheTime' => $feed_cacheTime);
		update_option('wplas_feeds',$wplas_feeds);
		//wp_redirect( admin_url('admin.php?page=wp_live_activity'));
	}
	
	if(isset($_POST['sbt_update'])){
		$activity_name	=	isset($_POST['activity_name']) ? esc_attr($_POST['activity_name']) : '';
		$edit_name		=	isset($_POST['edit_name']) ? esc_attr($_POST['edit_name']) : '';
        $feed_cacheTime		=	isset($_POST['feed_cacheTime']) ? esc_attr($_POST['feed_cacheTime']) : '';
		$feed_url		=	isset($_POST['feed_url']) ? esc_url($_POST['feed_url']) : '';
		$icon		=	isset($_POST['activity_icon']) ? esc_attr($_POST['activity_icon']) : 'default.png';
		$template		=	isset($_POST['activity_template']) ? esc_attr($_POST['activity_template']) : '{!TEXT_LINK}';
		$wplas_feeds	=	get_option('wplas_feeds');
		$exists	=	false;
		$keY = 0;
		if(!is_array($wplas_feeds))
			$wplas_feeds	=	array();
		$wplas_feeds[$edit_name]	=	array('name' => $activity_name,'url' => $feed_url, 'icon' => $icon, 'template' => $template, 'cacheTime' => $feed_cacheTime);
		update_option('wplas_feeds',$wplas_feeds);
		wp_redirect( admin_url('admin.php?page=wp_live_activity'));
	}
	if(isset($_REQUEST['do']) && $_REQUEST['do']	==	'delete' && isset($_REQUEST['name'])){
		$name	=	$_REQUEST['name'];
		$wplas_feeds	=	get_option('wplas_feeds');
		if(!is_array($wplas_feeds))
			$wplas_feeds	=	array();
		foreach($wplas_feeds as $key=>$feed){
			if($feed['name']	==	$name)
				unset($wplas_feeds[$key]);
		}
		update_option('wplas_feeds',$wplas_feeds);
		wp_redirect( admin_url('admin.php?page=wp_live_activity'));
		
	}
	
}


function WP_Live_Activity_menu(){
	add_options_page(__('WP Activity Streaming'),__('Activity Streaming'),8,'wp_live_activity','wp_live_activity');
}
function wp_live_activity(){
	if(isset($_REQUEST['do']) && $_REQUEST['do']	==	'edit' && isset($_REQUEST['name'])){
		$name	=	$_REQUEST['name'];
		$wplas_feeds	=	get_option('wplas_feeds');
		$feed_array	=	array('activity_name' => '','feed_url' => '','activity_icon' => '','activity_template' => '','update' => TRUE);
		if(!is_array($wplas_feeds))
			$wplas_feeds	=	array();
		foreach($wplas_feeds as $key=>$feed){
			if($feed['name']	==	$name){
				$feed_array['activity_name']	=	$feed['name'];
				$feed_array['feed_url']	=	$feed['url'];
				$feed_array['activity_icon']	=	$feed['icon'];
				$feed_array['cacheTime']	=	$feed['cacheTime'];
				$feed_array['activity_template']	=	$feed['template'];
				$edit_name	=	$key;
				break;
			}
		}
			extract($feed_array);
	}
		if(!$activity_template) $activity_template	=	'{!LINK}';
	?>
		<div class="wrap">
			<h2>WP Live Activity Streaming</h2>
			<p>Please define your live activity streaming source urls and their icons. Then you can select these sources when inserting widgets to your sidebar</p>
			<?php $wplas_feeds	=	get_option('wplas_feeds'); ?>
			<table border="0" class="widefat" style="width:600px;">
					<thead>
						<tr>
							<th style="width:50px;"></th>
							<th style="width:100px">Feed Name</th>
							<th>Feed URL</th>
							<th>Cache Time</th>
							<th style="width:80px;"></th>
						</tr>
					</thead>
					<tbody>
						<?php if(is_array($wplas_feeds) && !empty($wplas_feeds))
							foreach($wplas_feeds as $feed) : ?>
						<tr>
							<th><img src="<?php echo WPLAS_PLUGIN_URL ; ?>/icons/<?php echo $feed['icon']; ?>" alt="" /></th>
							<td nowrap><a href="<?php echo admin_url('admin.php?page=wp_live_activity&do=edit&name=' . $feed['name']); ?>"><?php echo $feed['name']; ?></a></td>
							<td width="80%"><?php echo $feed['url']; ?>...</td>
							<td><?php echo $feed['cacheTime']; ?></td>
							<td><a href="<?php echo admin_url('admin.php?page=wp_live_activity&do=delete&name=' . $feed['name']); ?>">Delete</a></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
			</table><br />
			
			
			<div class="add_new_activity">
				<form method="post" action="">
				<table border="0" class="widefat" style="width:600px;">
					<thead>
						<tr>
							<th colspan="2" style="text-align:center">Add New Activity Streaming</th>
						</tr>						
					</thead>
					<tbody>
						<tr>
							<th style="width:100px;">Activity Name</th>
							<td><input type="text" class="text_input" value="<?php echo $activity_name; ?>" name="activity_name" style="width:198px;" /></td>
						</tr>
						<tr>
							<th style="width:100px;">Feed/RSS URL</th>
							<td><input type="text" class="text_input" value="<?php echo $feed_url; ?>" name="feed_url" style="width:198px" /></td>
						</tr>
							<tr>
							<th style="width:100px;">Cache Time</th>
							<td><input type="text" class="text_input" value="<?php echo $cacheTime; ?>" name="feed_cacheTime" style="width:198px" /> minutes</td>
						</tr>
						<tr>
							<th style="width:100px;">Icon</th>
							<td>
							<select id="activity_icon" name="activity_icon" style="width:200px;">
							<?php $dir = WPLAS_PLUGIN_PATH . '/icons';
								$d = dir($dir);
								while (false !== ($entry = $d->read())) {
								if($entry!='.' && $entry!='..') {
									if(!is_dir($entry)){
										$parts	=	pathinfo($entry);
										echo '<option value="'. $parts['basename'] . '" title="' . WPLAS_PLUGIN_URL . '/icons/'. $parts['basename'] . '"' . (($activity_icon == $parts['basename']) ? ' selected="selected"' : '') . '> ' . $parts['filename'] . '</option>';
									}
								}
								}
							?>
							</select>
							</td>
						</tr>
						<tr>
							<th>List Template
							</th>
							<td><textarea rows="5" cols="50" style="width:198px;" name="activity_template"><?php echo $activity_template; ?></textarea></td>
						</tr>
							
						<tr>
							<th><?php if($update == TRUE) : ?><input type="hidden" name="edit_name" value="<?php echo $edit_name; ?>" /><?php endif; ?></th>
							<td style="text-align:right"><input type="submit" name="<?php if($update == TRUE) echo "sbt_update"; else echo "sbt_submit"; ?>" value=" Save Activity Item " class="button button-primary" style="padding:5px;" /></td>
							</tr>
					</tbody>
				</table><br />
				<div style="padding:10px; background:#FFFFCC; font-weight:normal; border:1px solid #990000; width:600px;">
				<strong>Valid ShortCodes:</strong><br />
							<strong>{!LINK}</strong> = Creates an anchor link with title of item<br />
							<strong>{!ITEM_URL}</strong> = Url of the feed item<br />
							<strong>{!ITEM_TITLE}</strong> = Title of the feed item
				</div>
				</form>
			</div>
		</div>
<?php	
}
endif;


class WP_Widget_WPLAS extends WP_Widget {

	function WP_Widget_WPLAS() {
		$widget_ops = array('description' => __( "Display Live activity in your sidebar. Please configure Feeds from Activity Streaming page under settings." ) );
		$this->WP_Widget('wplas', __('Live Activity Streaming'), $widget_ops);
	}

	function widget( $args, $instance ) {
	    
		extract($args, EXTR_SKIP);
		$activities = isset($instance['activities']) ? $instance['activities'] : array();
		$num_posts = isset($instance['num_posts']) ? $instance['num_posts'] : 5;
		$box_title = isset($instance['box_title']) ? $instance['box_title'] : 5;
		$before_widget = preg_replace('/id="[^"]*"/','id="%id"', $before_widget);
		$wplas_feeds	=	get_option('wplas_feeds');
		$active_feeds	=	array();
			foreach($wplas_feeds as $feed)
				if(in_array($feed['name'],$activities))
					$active_feeds[]	=	$feed;
		$feeds	=	array();		
		
		$out = 0;		
		
		$currentFeeds = array();
		
		if(function_exists('fetch_feed')){
			foreach($active_feeds as $feedr){			
				$out = 0;
				$template	=	$feedr['template'];
				if(!$template) $template = '{!LINK}';
				
				$cacheTime = 0 + $feedr['cacheTime'] * 60;
				
				// add_filter adds a cache level per feed.
				add_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', "return $cacheTime;" ));
				$feed	=	fetch_feed($feedr['url']);
				remove_filter( 'wp_feed_cache_transient_lifetime', create_function( '$a', "return $cacheTime;" ));
				
				if ( is_wp_error( $feed ) ) {
					echo $feed->get_error_message(); // a method of WP_Error
				} else {
	
				
					$parsed = parse_url($feedr['url']);
					$hostname = $parsed['host'];
					
					//itunes time/date feed is wrong. This trys to fix it.
					$malformedTimeDateFeeds = array('ax.itunes.apple.com');
					if(!$feed->get_item_quantity()) return '';
					foreach ($feed->get_items(0,$num_posts) as $item) {
						
						if(in_array($hostname,$malformedTimeDateFeeds ))  {
							$dateTimeID =  strtotime('+ '.rand(0,55).' second', strtotime($item->get_date('Y-m-d H:i:s')));
						} else {
							$dateTimeID =  strtotime($item->get_date('Y-m-d H:i:s'));
						}
			
						$text = str_replace(array('{!LINK}','{!ITEM_URL}','{!ITEM_TITLE}','{!TITLE}'),array('<a href="' . $item->get_link() . '">'. $item->get_title() . '</a>',$item->get_link(),$item->get_title(),$item->get_title()),$template);		
						$currentFeeds[$dateTimeID] =  '<div class="wplive_activity" style="background-image: url(' .WPLAS_PLUGIN_URL . '/icons/' . $feedr['icon'] . ');">'  . $text. '</div>';	
				
					} // end for each feed item.
					unset($feed);
				} // end is_wp_error.
			} // end for each.
			
			// sort feeds by date, and then reverse to get newest at top.
			ksort($currentFeeds);
			$currentFeeds = array_reverse($currentFeeds);
			
			// render the widget.
			echo $before_widget;
			echo '<div id="wplive_activity" class="sidebar-box">';
				echo $before_title . $box_title . $after_title;
				foreach($currentFeeds as $feedItem) {
					echo $feedItem;
				}
			echo '</div>';
			echo $after_widget;
			
			}// end if function exists.
	
	} // end function.
	
	
	function getFFTemplate($link = ''){
		$wplas_feeds	=	get_option('wplas_feeds');
		if(is_array($wplas_feeds))
			foreach($wplas_feeds as $feed)
				if($feed['url']	==	$url)
					return $feed['template'];
		return '';
	}

	
	function update( $new_instance, $old_instance ) {
		$new_instance = (array) $new_instance;
		$instance = array( 'num_posts' => 5, 'box_title' => 'My Life Stream','activities' => array());
		foreach ( $instance as $field => $val ) {
			if ( isset($new_instance[$field]) )
				$instance[$field] = $new_instance[$field];
		}
		return $instance;
	}




	function form( $instance ) {
		$wplas_feeds	=	get_option('wplas_feeds');
		// set the defaults.
		$instance = wp_parse_args( (array) $instance, array( 'num_posts' => 5, 'box_title' => 'My Life Stream', 'activities' => array()) );
?>
	<p><strong>Number of Posts for each feed</strong><br />
		<input type="text" style="width:80%" name="<?php echo $this->get_field_name('box_title'); ?>" id="<?php echo $this->get_field_id('box_title'); ?>" value="<?php echo $instance['box_title']; ?>" /></p>
		
		<p><strong>Number of Posts for each feed</strong><br />
		<input type="text" style="width:80%" name="<?php echo $this->get_field_name('num_posts'); ?>" id="<?php echo $this->get_field_id('num_posts'); ?>" value="<?php echo $instance['num_posts']; ?>" /></p>
		<p style="line-height:1.7em"><strong>Display Following Activities:</strong><br />
		<?php   $current_val	=	$instance['activities'];
				if(!is_array($current_val)) $current_val	=	array();
				if(is_array($wplas_feeds) && !empty($wplas_feeds))
				foreach($wplas_feeds as $feed) { ?>
					<label><input class="checkbox" value="<?php echo $feed['name']; ?>" type="checkbox" <?php if(in_array($feed['name'],$current_val)) echo ' checked="checked"'; ?> name="<?php echo $this->get_field_name('activities'); ?>[]" /> <?php echo $feed['name']; ?></label><br />
				<?php }
		echo '</p>';
	}
}


    

function wplas_widgets_init(){
	register_widget('WP_Widget_WPLAS');
}

add_action('init', 'wplas_widgets_init',1);

?>