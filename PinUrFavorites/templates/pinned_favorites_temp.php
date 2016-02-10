<?php
/**
Template Name: pinned favorites temp    
 * Created by PhpStorm.
 * User: gregcarlson
 * Date: 2/9/16
 * Time: 11:37 AM
 * Description: Plugin template to display a user's Pinned Favorites (posts) based on wp_favorites table that keeps track of
 * a user's user_id, post_id, and entry_date for Pinned Favorite.
 */

	get_header();

	//declare variables
	global $wpdb;
	$current_id = $current_user->ID;
	$base_url = get_bloginfo('wpurl');
?>
<section class="content">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php get_template_part( 'content', 'page' ); ?>
			<h3>Pinned Items</h3>
			<article id="post-<?php the_ID(); ?>">
				<?php
				//Retrieve saved TC items favorites
				//$pin_ur_favs = new pin_ur_favs();
				$tc_items = pin_ur_favs::pinned_get_favorites($current_id);

				//Build a table if there are any saved TC items or display no items
				if(sizeof($tc_items) > 0){
					?>
					<table class ="basic_table"><tr><th width = '50%'>Link</th><th width ='10%'>Date</th><th width '10%'>Delete</th></tr>
						<?php
						// List favorite saved TC item link and display
						foreach ($tc_items as $unit){

                        $the_link=get_permalink($unit->post_id);
                        $link_title=$unit->post_title;
                        //Display favorites post link, date saved, categories, and delete favorite option
                        echo "<tr id='row-".$unit->favorite_id."'><td width = '50%'><a href='".$the_link."' alt='link to ".$link_title."' title='". $link_title."' target=_blank >".$link_title."</a></td><td width ='10%'>".pin_ur_favs::favorite_format_date($unit->entry_date).
                            "</td><td width = '10%' style='text-align: center; padding-top: 6px;'><a href class=deleteFavorite id=".$unit->favorite_id." name = fromFavorites>
        					<img src='".$base_url."/wp-content/uploads/2016/02/deleteicon.png' height=25 width=25 title='Delete from favorites!'></a></td></tr>";
                        $category_list = "";
                    } //end for loop
                	?>
					</table>
					<?php
				} //end if
				else{
					echo "<span style=''>No pinned favorites.</span>";
				}

				?>
			</article><!--/.post-->
		</main><!-- #main -->
	</div><!-- #primary -->
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>