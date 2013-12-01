<?php
/*
Plugin Name: Discover Tree Menu Widget
Description: Show pages,categories,archives, and links as tree menu as widget in your sidebar
Author: Abdul Ibad
Version: 1.0
Author URI: http://www.dulabs.com
Plugin URI: http://www.dulabs.com
*/


define('DISCOVER','discover');

define('DISCOVER_VERSION','1.0');

define('DISCOVER_SVP','http://svp.artonesia.org/'.md5(ucfirst(DISCOVER)));

$path = str_replace('\\','/',dirname(__FILE__));
$rel_path = str_replace(str_replace('\\','/',ABSPATH),"",$path); 
$url = str_replace(str_replace('\\','/',ABSPATH),get_option('siteurl').'/',$path);
define('DISCOVER_PATH',$path);
define('DISCOVER_REL_PATH',$rel_path);
define('DISCOVER_URL',$url);



class Discover{

	var $id;

	var $menu = array();

	var $images;
	
	var $config;
	
	function Discover($id='',$config=''){
	
		$this->setID($id);
		$this->setConfig($config);
	
	}
	
	function setID($id){
	
		$this->id = $id;
	
	}
	
	function setConfig($config){
	
		$this->config = $config;
		
	}
	
	function setImages($img){
	
		$this->images = $img;
		
	}
	
	function add($id,$parentid,$name,$url='',$title='',$target='',$icon='',$iconOpen='',$open=''){
	
		$this->menu[] = array('id'=>$id,
							  'parent'=>$parentid,
							  'name'=>$name,
							  'url'=>$url,
							  'title'=>$title,
							  'target'=>$target,
							  'icon'=>$icon,
							  'iconOpen'=>$iconOpen,
							  'open'=>$open);
	
	}
	
	function build(){
	
		$tree = $this->id;
		
		$config = $this->config;
	
		// the config
		$useSelection = $config['selection'];
		$useLines = $config['lines'];
		$useIcons = $config['icons'];
		$useCookies = $config['cookies'];
		$useStatusText = $config['statusText'];
		$closeSameLevel = $config['closeSameLevel'];
		$openAll = $config['openAll'];
		
		// Start the code
		$menu_htmlcode .= "<script type=\"text/javascript\">\n";
		$menu_htmlcode .= "$tree = new dTree('$tree','$this->images');\n";
	
		// pass on the dTree API parameters
		$menu_htmlcode .= "$tree.config.useSelection=".$useSelection.";\n";
		$menu_htmlcode .= "$tree.config.useLines=".$useLines.";\n";
		$menu_htmlcode .= "$tree.config.useIcons=".$useIcons.";\n";
		$menu_htmlcode .= "$tree.config.useCookies=".$useCookies.";\n";
		$menu_htmlcode .= "$tree.config.useStatusText=".$useStatusText.";\n";
		$menu_htmlcode .= "$tree.config.closeSameLevel=".$closeSameLevel.";\n";
	
		$menus = $this->menu;
	
		foreach($menus as $menu){
		
				$menu_htmlcode .= "$tree.add(";
				$menu_htmlcode .= "'".$menu['id']."',";
				$menu_htmlcode .= "'".$menu['parent']."',";
				$menu_htmlcode .= "'".$menu['name']."',";
				$menu_htmlcode .= "'".$menu['url']."',";
				$menu_htmlcode .= "'".$menu['title']."',";
				$menu_htmlcode .= "'".$menu['target']."',";
				$menu_htmlcode .= "'".$menu['icon']."',";
				$menu_htmlcode .= "'".$menu['iconOpen']."',";
				$menu_htmlcode .= "'".$menu['open']."')";
				$menu_htmlcode .= "\n";
		}
		$menu_htmlcode .= "document.write($tree);\n";
		$menu_htmlcode .= $openAll ? "$tree.openAll();\n" : "$tree.closeAll();\n";
		$menu_htmlcode .= "$tree.openTo('0','true');\n";
		$menu_htmlcode .= "</script>\n";
	
		return $menu_htmlcode;
	}
	
	function addToHead(){
		echo '<link rel="stylesheet" type="text/css" href="'.DISCOVER_URL.'/dtree.css">'."\n";
		echo "<script type=\"text/javascript\" src=\"".DISCOVER_URL."/dtree.js\"></script>\n";	
	}

}


function widget_discover_init(){


if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	function widget_discover_pages($args){
		global $wpdb;
		
		extract($args);
		
		$options = get_option('widget_discover_pages');
	
		$discoverConfig['selection'] = $options['selection']=='1'?'true':'false';
		$discoverConfig['lines'] = $options['lines']=='1'?'true':'false';
		$discoverConfig['icons'] = $options['icons']=='1'?'true':'false';
		$discoverConfig['statusText'] = 'false';
		$discoverConfig['cookies'] = 'false';
		$discoverConfig['closeSameLevel'] = 'false';
		$discoverConfig['openAll'] = $options['openall']=='1'?true:false;
		$root = $options['rootname'];
	
		$title = $options['title'];
		$images = $options['images'];
		
		echo $before_widget;
		
		echo $before_title.$title.$after_title;
		
		$discover = new Discover('discover_'.md5('discover_pages'));
	
		$discover->setConfig($discoverConfig);
				
		$discover->setImages(DISCOVER_URL.'/'.$images);
		
		$discover->add('0','-1',$root,get_option('siteurl'));
		
		$pages = get_pages();
		
		foreach($pages as $page){
				$discover->add($page->ID,
							 $page->post_parent,
							 $page->post_title,
							 get_permalink($page->ID)
							 );
		}
		
		$menu = $discover->build();
		
		echo $menu;
		
		echo $after_widget;
		
	}
	
	function widget_discover_pages_control(){
	
		widget_discover_control('pages');
	
	}
	
		
	function widget_discover_categories($args){
	
		global $wpdb;
		
		extract($args);
	
		$options = get_option('widget_discover_categories');
	
		$discoverConfig['selection'] = $options['selection']=='1'?'true':'false';
		$discoverConfig['lines'] = $options['lines']=='1'?'true':'false';
		$discoverConfig['icons'] = $options['icons']=='1'?'true':'false';
		$discoverConfig['statusText'] = 'false';
		$discoverConfig['cookies'] = 'false';
		$discoverConfig['closeSameLevel'] = 'false';
		$discoverConfig['openAll'] = $options['openall']=='1'?true:false;
		$root = $options['rootname'];
	
		$title = $options['title'];
		$images = $options['images'];
		
		echo $before_widget;
		
		echo $before_title.$title.$after_title;
		
		$discover = new Discover('discover_'.md5('discover_categories'));
	
		$discover->setConfig($discoverConfig);
		
		global $wp_version;

		$discover->setImages(DISCOVER_URL.'/'.$images);
		
		$discover->add('0','-1',$root,get_option('siteurl'));
		$categories = get_all_category_ids();
		
		foreach($categories as $cat_id){
			$category = get_category($cat_id);
			$id = $category->cat_ID;
			$parent = $category->category_parent;
			$name = $category->cat_name;
			$url = get_category_link($id);
						
			$discover->add($id,$parent,$name,$url);
		}
		
		$menu = $discover->build();

		echo $menu;
		
		echo $after_widget;
		
	}
	
	function widget_discover_categories_control(){
		widget_discover_control('categories');
	}
	
	
	function widget_discover_links($args){
	
		global $wpdb;
		
		extract($args);
	
		$options = get_option('widget_discover_links');
	
		$discoverConfig['selection'] = $options['selection']=='1'?'true':'false';
		$discoverConfig['lines'] = $options['lines']=='1'?'true':'false';
		$discoverConfig['icons'] = $options['icons']=='1'?'true':'false';
		$discoverConfig['statusText'] = 'false';
		$discoverConfig['cookies'] = 'false';
		$discoverConfig['closeSameLevel'] = 'false';
		$discoverConfig['openAll'] = $options['openall']=='1'?true:false;
		$root = $options['rootname'];
		$images = $options['images'];
	
		$title = $options['title'];
	
		echo $before_widget;
		
		echo $before_title.$title.$after_title;
		
		$discover = new Discover('discover_'.md5('discover_links'));
	
		$discover->setConfig($discoverConfig);
		$discover->setImages(DISCOVER_URL.'/'.$images);
		$discover->add('0','-1',$root,get_option('siteurl'));
		
		$categories = get_categories('type=link');
		
		foreach($categories as $category){
		
				$discover->add($category->cat_ID,$category->category_parent,$category->cat_name);		

			}
									
		$links = get_bookmarks();
		
		foreach($links as $link){
		
				$id = '0'.$link->link_id;
				// parents is an categories
				$parents = array_unique(wp_get_object_terms($id, 'link_category','fields=tt_ids'));
				$name = $link->link_name;
				$url = $link->link_url;
			
			// if the link have more one category
			foreach($parents as $parent){
				$discover->add($id,$parent,$name,$url,'','_blank');
				}
		}
		
		$menu = $discover->build();
		
		echo $menu;
		
		echo $after_widget;
		
	}
	
	function widget_discover_links_control(){
	
		widget_discover_control('links');
	
	}
	
	
	function widget_discover_archives($args){
		global $wpdb,$wp_locale;
		extract($args);
				
		$options = get_option('widget_discover_archives');
	
		$discoverConfig['selection'] = $options['selection']=='1'?'true':'false';
		$discoverConfig['lines'] = $options['lines']=='1'?'true':'false';
		$discoverConfig['icons'] = $options['icons']=='1'?'true':'false';
		$discoverConfig['statusText'] = 'false';
		$discoverConfig['cookies'] = 'false';
		$discoverConfig['closeSameLevel'] = 'false';
		$discoverConfig['openAll'] = $options['openall']=='1'?true:false;
		$root = $options['rootname'];
		$images = $options['images'];
	
		$title = $options['title'];
		$type = $options['type'];
		
		if(empty($type)){
			$type='monthly';
		}
		
		$discover = new Discover('discover_'.md5('discover_archives'));
		
		$discover->setConfig($discoverConfig);
		
		$discover->setImages(DISCOVER_URL.'/'.$images);
		
		// Create first node
		$discover->add('0','-1',$root,'');
		
		echo $before_widget;
		
		echo $before_title.$title.$after_title;
		
		
		// this is what will separate dates on weekly archive links
		$archive_week_separator = '&#8211;';

		// over-ride general date format ? 0 = no: use the date format set in Options, 1 = yes: over-ride
		$archive_date_format_over_ride = 0;

		// options for daily archive (only if you over-ride the general date format)
		$archive_day_date_format = 'Y/m/d';

		// options for weekly archive (only if you over-ride the general date format)
		$archive_week_start_date_format = 'Y/m/d';
	
		$archive_week_end_date_format	= 'Y/m/d';

		if ( !$archive_date_format_over_ride ) {
			$archive_day_date_format = get_option('date_format');
			$archive_week_start_date_format = get_option('date_format');
			$archive_week_end_date_format = get_option('date_format');
		}

		
				
		if ( '' != $limit ) {
			$limit = (int) $limit;
			$limit = ' LIMIT '.$limit;
		}
		
		$r = '';
		
		//filters
		$where = apply_filters('getarchives_where', "WHERE post_type = 'post' AND post_status = 'publish'", $r );
		$join = apply_filters('getarchives_join', "", $r);
		
		if($type == 'monthly'){
			$arcresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date) ORDER BY post_date DESC". $limit);
			if ( $arcresults ) {
			
				foreach ( $arcresults as $arcresult ) {
					$year_url = get_year_link($arcresult->year);
					$url	= get_month_link($arcresult->year,	$arcresult->month);
					$text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($arcresult->month), $arcresult->year);
					
					// Add year
					$discover->add($arcresult->year,'0',$arcresult->year,$year_url);
					
					// Add month
					$discover->add($arcresult->month,$arcresult->year,$text,$url);
				}
			}
		}elseif ('yearly' == $type) {
		
			$arcresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date) ORDER BY post_date DESC" . $limit);
			if ($arcresults) {
		
				foreach ($arcresults as $arcresult) {
					$url = get_year_link($arcresult->year);
					$text = sprintf('%d', $arcresult->year);
					if ($show_post_count){
						$postcount = '&nbsp;('.$arcresult->posts.')';
					}
				
					$discover->add($arcresult->year,'0',$text,$url);
				
				}
			}
		}elseif ( 'daily' == $type ) {
		
			$arcresults = $wpdb->get_results("SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, DAYOFMONTH(post_date) AS `dayofmonth`, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY YEAR(post_date), MONTH(post_date), DAYOFMONTH(post_date) ORDER BY post_date DESC" . $limit);
			if ( $arcresults ) {
			
				foreach ( $arcresults as $arcresult ) {
					
					// Year
					$year_url = get_year_link($arcresult->year);
					
					// Month
					$month_url = get_month_link($arcresult->year,	$arcresult->month);
					$month_text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($arcresult->month), $arcresult->year);
					
					// Day 
					
					$day_url	= get_day_link($arcresult->year, $arcresult->month, $arcresult->dayofmonth);
					
					$date = sprintf('%1$d-%2$02d-%3$02d 00:00:00', $arcresult->year, $arcresult->month, $arcresult->dayofmonth);
					
					$day_text = mysql2date($archive_day_date_format, $date);
					
					if ($show_post_count){
						$postcount = '&nbsp;('.$arcresult->posts.')';
						}
					
					// Add the year
					$discover->add($arcresult->year,'0',$arcresult->year,$year_url);

					// Add the month
					// combine the month with the year.
					$discover->add($arcresult->month.$arcresult->year,$arcresult->year,$month_text,$month_url);
				
					// Add the day
					$discover->add($arcresult->dayofmonth,$arcresult->month.$arcresult->year,$day_text,$day_url);
								
				}
			}
		}elseif ( 'weekly' == $type ) {
		
		$start_of_week = get_option('start_of_week');
		
		$arcresults = $wpdb->get_results("SELECT DISTINCT WEEK(post_date, $start_of_week) AS `week`, YEAR(post_date) AS yr, DATE_FORMAT(post_date, '%Y-%m-%d') AS yyyymmdd, count(ID) as posts FROM $wpdb->posts $join $where GROUP BY WEEK(post_date, $start_of_week), YEAR(post_date) ORDER BY post_date DESC" . $limit);
		
		$arc_w_last = '';
		
			if ( $arcresults ) {
		
				foreach ( $arcresults as $arcresult ) {
					if ( $arcresult->week != $arc_w_last ) {
						$arc_year = $arcresult->yr;
						$arc_w_last = $arcresult->week;
						$arc_week = get_weekstartend($arcresult->yyyymmdd, get_option('start_of_week'));
						$arc_week_start = date_i18n($archive_week_start_date_format, $arc_week['start']);
						$arc_week_end = date_i18n($archive_week_end_date_format, $arc_week['end']);
						$url  = sprintf('%1$s/%2$s%3$sm%4$s%5$s%6$sw%7$s%8$d', get_option('home'), '', '?', '=', $arc_year, '&amp;', '=', $arcresult->week);
						$text = $arc_week_start . $archive_week_separator . $arc_week_end;
						
						if ($show_post_count){
							$text .= '&nbsp;('.$arcresult->posts.')';
						}
						
						// Add the year
						$discover->add($arc_year,'0',$arc_year);
						
						// add the week
						$discover->add($arc_week,$arc_year,$text,$url);
						
						
					}
				}
			}
		}   
		
		$menu = $discover->build();
		
		echo $menu;

		echo $after_widget;
	}
	
	
	function widget_discover_archives_control(){
	
		widget_discover_control('archives');
	
	}
	
	
	function widget_discover_control($name){
	
		$options = get_option('widget_discover_'.$name);
		
		if(isset($_POST['discover-'.$name.'-submit'])){
		
			$newoptions['title'] = $_POST['discover-title-'.$name];
			$newoptions['rootname'] = $_POST['discover-rootname-'.$name];
			$newoptions['selection'] = isset($_POST['discover-selection-'.$name]);
			$newoptions['lines'] = isset($_POST['discover-lines-'.$name]);
			$newoptions['icons'] = isset($_POST['discover-icons-'.$name]);
			$newoptions['openall'] = isset($_POST['discover-openall-'.$name]);
			$newoptions['images']  = $_POST['discover-images-'.$name];
			
			if($name == 'archives'){
				$newoptions['type'] = $_POST['discover-type-archives'];	
			}
			
			if($options != $newoptions){
			
				update_option('widget_discover_'.$name,$newoptions);
				
			}
		}
		
		$options = get_option('widget_discover_'.$name);
		
		$title = $options['title'];
		$rootname = $options['rootname'];
		$selection = $options['selection'];
		$lines = $options['lines'];
		$icons = $options['icons'];
		$openall = $options['openall'];
		$images = $options['images'];

?>
	<div style="text-align: left">	
		<div>
			Title:<br /> 
			<input type="text" name="discover-title-<?php echo $name;?>" value="<?php echo $title;?>" />
		</div>
		<div>
			Root name:<br /> 
			<input type="text" name="discover-rootname-<?php echo $name;?>" value="<?php echo $rootname;?>" />
		</div>
		
		<?php if($name=='archives'):?>
		<?php $types = array('daily','weekly','monthly','yearly'); $curr_type = $options['type'];?>
		<div>
		Archives Type:<br />
		<select name="discover-type-archives">
		<?php foreach($types as $type): ?>
		<?php $select = $curr_type == $type ? ' selected="selected"':'';?>
		<option value="<?php echo $type;?>"<?php echo $select;?>><?php echo ucfirst($type);?></option>		
		<?php endforeach;	?>
		</select>
		</div>
		<?php endif;?>
		
		<p>
		<?php
		$opt[] = array('name'=>'discover-selection-'.$name,'label'=>'Use Selection','value'=>'1','checked'=>$selection);
		$opt[] = array('name'=>'discover-lines-'.$name,'label'=>'Use Lines','value'=>'1','checked'=>$lines);
		$opt[] = array('name'=>'discover-icons-'.$name,'label'=>'Use Icons','value'=>'1','checked'=>$icons);
		$opt[] = array('name'=>'discover-openall-'.$name,'label'=>'Open All','value'=>'1','checked'=>$openall);
		
		foreach($opt as $op){
	   
			echo '<div>';
			widget_discover_checkbox($op['name'],$op['value'],$op['checked']);
			echo '&nbsp;'.$op['label'].'<br />';
			echo '</div>';
			echo "\n";	
			
		
		}	
		?>
		</p>		
		<div>Images Style:<br />
		<select name="discover-images-<?php echo $name;?>">
		<?php
		$dirs = widget_discover_images_style();
		foreach($dirs as $dir):
			$select = ($dir['name'] == $images) ? ' selected="selected"':'';
		?>
		<option value="<?php echo $dir['name'];?>"<?php echo $select;?>><?php echo $dir['name'];?></option>
		<?php 
		endforeach;
		?>
		</select>
		</div>		
		<input type="hidden" name="discover-<?php echo $name;?>-submit" value="1" />
	</div>
<?php
		$upgrade = widget_discover_upgrade();
			
		
	}
	
	function widget_discover_checkbox($name,$value,$checked){
	
		$check = $checked ? 'checked="checked" ':'';
	
		echo '<input type="checkbox" name="'.$name.'" value="'.$value.'" '.$check.'/>';
	
	}
	
	function widget_discover_images_style(){
					
		$odir = opendir(DISCOVER_PATH);

			while($cdir = readdir($odir)){
			
				if($cdir != '.' && $cdir != '..'){
					$path = DISCOVER_PATH.'/'.$cdir;
					
					if(is_dir($path)){
						$dir['name'] = $cdir;
						$dir['path'] = $path;
						$dir['url'] = DISCOVER_URL.'/'.$cdir;
						$return_dir[] = $dir;
					}
			}
		}
		
		closedir($odir);

		return $return_dir;
		
	}
	
	function widget_discover_upgrade(){
	
		/*$svp = new SVP(DISCOVER_SVP);
		$svp->parse_text();
		*/
		return $svp->getpart();
	}
	
	function widget_discover_register(){
	
		$widgets[] = array(	'name'=>'Discover Pages',
							'widget'=>'widget_discover_pages', // Callback function
							'control'=>'widget_discover_pages_control',
							'width'=>300,
							'height'=>320);
						  
		$widgets[] = array(	'name'=>'Discover Categories',
							'widget'=>'widget_discover_categories', // Callback function
							'control'=>'widget_discover_categories_control',
							'width'=>300,
							'height'=>320);
							
		$widgets[] = array(	'name'=>'Discover Links',
							'widget'=>'widget_discover_links', // Callback function
							'control'=>'widget_discover_links_control',
							'width'=>300,
							'height'=>320);
				
		$widgets[] = array(	'name'=>'Discover Archives',
							'widget'=>'widget_discover_archives',
							'control'=>'widget_discover_archives_control',
							'width'=>300,
							'height'=>350);
							
		foreach($widgets as $widget){
		
		register_sidebar_widget(array($widget['name'],'widgets'),$widget['widget']);
		register_widget_control(array($widget['name'],'widgets'),$widget['control'],$widget['width'],$widget['height']);	
		
		}
	}
		
	
widget_discover_register();		
}


add_action('plugins_loaded','widget_discover_init');
add_action('wp_head',array('Discover','addToHead'));
?>