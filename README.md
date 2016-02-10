# PinUrFavorites

<h2>Summary</h2>
WordPress Plugin allows for users who are logged in to pin their favorite posts to a Pinned Favorites template page.
On the Pinned Favorites template page users can keep a list of their favorite posts from your website and gives them the
option to visit the post or delete the post from their Pinned Favorites.

<h2>How to Install</h2>

<b>1.</b> Install PinUrFavorites by downloading the plugin directory from the Github repository and then placing it in yourWordPressSite/wp-content/plugins.</br>
<b>2.</b> Activate the PinUrFavorites plugin from the Plugins page on the WordPress Dashboard left side menu.</br>
<b>3.</b> In order to start letting users pin their favorite posts you need provide a link on your website that is similiar to the following: <br>
&lt;a id="4" class="addfavorite" name="fromPost" href="#"&gt;<br />
&lt;img width="15" height="15" title="Pin to favorites!" src="http://localhost/web_gallery/wp-content/plugins/PinUrFavorites/images//favorite_pin.png"&gt;<br />
&lt;span style="color:#2295de; font-weight: bold;"&gt; Pin as favorite&lt;/span&gt;<br />
&lt;/a&gt;

<p>Note about html link: The link attributes for id, class, and name must stay consistent in order for PinUrFavorites to work correctly. 
</p> 

<b>4.</b>Copy and paste the HTML link above into your single.php file. The file is located in yourwordpresssite/wp-content/themes/yourtheme/single.php <br>

<b>5. </b>View a post on your website and see if the link appears. If it doesn't you may have to do some further investigating in your theme directory and find the file responsible for displaying post content. <br>

<b>6. </b> Next add a new page to your website and select the PinUrFavorites template called: pinned favorites temp. This template displays the users posts that they have Pinned in their Favorites. Provide a link somewhere on your website where users can view and click on it to visit their My Pinned Favorites page. On this page users can manage their pinned favorites and re-visit their favorite posts on your website. 

<h3>Admin Features</h3>
<p>When the PinUrFavorites plugin is activated a menu item is added to the WordPress Dashboard. The menu item is a link for the Pinned Favorites on your website. Admin user can view the Pinned Favorites page to see what posts users have pinned on his or her website. </p> 

