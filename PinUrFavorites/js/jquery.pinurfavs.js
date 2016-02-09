/**
 * Created by gregcarlson on 2/9/16.
 */
/*
 jquery.custom.js

 License: GNU General Public License v3.0
 License URI: http://www.gnu.org/licenses/gpl-3.0.html
 By: Greg Carlson
 Handles the display effects for favorites for Transition Coalition website.

 */
jQuery(document).ready(function($) {
    var current_page = $(location).attr('href');
//get click and reveal clicks bases on the class name of the link

//display effects for when user deletes from favorites template
    $('a.deleteFavorite').live('click', function(e){

        e.preventDefault();

        var id = $(this).attr('id');
        var base_url = location.host;

        $.ajax({
            type: "POST",
            url: TCFavAjax.ajaxurl,
            dataType: 'json',
            data:{'action':'tc_ajax_delete_favorite', 'favorite_id': id},
            success: function() {
                $('#row-'+id).children('td')
                $('#row-'+id).children().css({backgroundColor:'#f8eb97'}, 300)
                $('#row-'+id).children('td')
                    .slideUp(function() {$('#row-'+id).remove(); });
                return false;
            }
        });
    });
//display effects for when user adds a favorite post
    $('a.addfavorite').click( function(e) {
        e.preventDefault();
        if($(this).hasClass('disabled')) return false;
        var id = $(this).attr('id');
        var base_url = location.host;
        $.post(TCFavAjax.ajaxurl,{'action':'tc_ajax_favorite', 'post_id': id }, function(ret){
            e.preventDefault();
            if(ret > 0){
                if( $('a#'+id).attr('name') == 'fromPost'){
                    $('a#'+id).html("<img src='http://"+base_url+"/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Pinned in your favorites'><span style='color:#2295de; font-weight: bold;'> Pinned in your favorites</a></span>");
                }else{
                    $('a#'+id).html("<img src='http://"+base_url+"/wp-content/uploads/2014/09/favoriteSaved.png' height=15 width=15 title='Pinned in your favorites'>");
                }
                $('a#'+id).addClass('disabled');
            }
        });
    });

    $('a.disabled').one('click', function(e) {
        e.preventDefault();
        return false;
    });
//end document ready
});

