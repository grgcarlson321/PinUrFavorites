<?php
/**
 * Template Name: pinned_favorites_temp.php
 * Created by PhpStorm.
 * User: gregcarlson
 * Date: 2/9/16
 * Time: 11:37 AM
 */

	get_header();
	global $wpdb;
	$current_id = $current_user->ID;
	$base_url = get_bloginfo('wpurl');
	$category_ID = $_GET['cat_ID']; //from the url
	?>

	<section class="content">
	  <div class="template_content">
          <h3><span>My Pinned Items</span></h3>
          <br>
          <?php /*
        <!--Start building favorites list for the user-->
	<article id="post-<?php the_ID(); ?>">
    	<div class="post-inner post-hover">
<?php
        //retrieve saved news items favorites
	//$category_links =tc_favorites_get_news_items($current_id);
	//Build a table if there are any saved news items or display no items
	?>
	<BR><span class="post-title">Pinned News items: </span>
	<article id="post-<?php the_ID(); ?>">
<?php

	if(sizeof($category_links) > 0){	?>
<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width = '30%'>Category</th><th width '10%'>Delete</th></tr>
<?php
		// List favorite saved news item link and display
		foreach ($category_links as $unit){
			$post_category = get_the_category($unit->post_id);
				// Get the categories for favorite
				foreach($post_category as $category){
					$category_list .= $category->cat_name.", ";
				}
			$category_list = substr($category_list, 0, -2);
			$the_link=get_permalink($unit->post_id);
			$link_title=$unit->post_title;
			//Display favorites post link, date saved, categories, and delete favorite option
			echo "<tr id='row-".$unit->favorite_id."'><td width = '50%'><li class='".linkIcon($the_link)."'><a href='".$the_link."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></li></td><td width ='10%'>".favoriteformatdate($unit->entry_date).
			"</td><td width = '30%'>".$category_list."</td><td width = '10%' style='text-align: center; padding-top: 6px;'><a href='?deleteFavorite=".$unit->favorite_id."' class=deleteFavorite id=".$unit->favorite_id." name = fromFavorites>
			<img src='wp-content/uploads/2014/10/deleteicon.png' height=25 width=25 title='Delete from favorites!' alt='click to delete favorite'></a></td></tr>";

			$category_list = "";
		} //end for loop
?>
</table>
<?php
	} //end if
	else{
			echo "<span style=''>No favorites for news items.</span>";
	}
</article><!--/.post-->
 */

 ?>



</div>
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>