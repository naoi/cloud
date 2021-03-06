// $Id$

/**
 * @file
 * Used for this module
 * 
 */

var init_refresh_in_sec = 10; 
var refresh_in_sec = init_refresh_in_sec * init_refresh_in_sec ;

 
(function ($) {
  $(document).ready(function() {
    setTimeout(autoupdate_instances_list, ( refresh_in_sec / init_refresh_in_sec ) * 1000);
 });

  

function autoupdate_instances_list() {
  
  refresh_in_sec++ ;
  setTimeout(autoupdate_instances_list, ( refresh_in_sec / init_refresh_in_sec ) * 1000);
  
  var img_tag = $("img[alt$='sort descending']");
  if ( !img_tag.length) {
      img_tag = $("img[alt$='sort ascending']");
  }
  
  var sort_col_val = img_tag.parent().text() ;
  var sort_img_src = img_tag.attr('src') ;
  var order        = sort_col_val.substr( 0 , sort_col_val.length / 2 ) ;
  var sort         = 'desc' ;
  if ( sort_img_src != ''
  &&   sort_img_src.indexOf('desc') != -1 ) {
  
      sort = 'asc' ;
  }


  var tgt_url   = img_tag.parent().attr('href') ;
  var page = 0 ;
  var page_start_index = tgt_url.indexOf('page=')  ; 
  if ( page_start_index != - 1) {
      
    var page_end_index = tgt_url.indexOf('&' , page_start_index )  ; 
    if ( page_end_index == -1 )  {
    
      page_end_index = tgt_url.length  ;
    }
    
    page = tgt_url.substring( page_start_index + 'page='.length , page_end_index ) ;
  }
  
  
  // In case we need to use the current values    
  // var operation   = $('select[name=operation]').val() ;
  // var filter      = $('input[name=filter]').val() ;
  var operation   = $('input[name=operation_hdn]').val() ;
  var filter      = $('input[name=filter_hdn]').val() ;
  
  $url = $('#instances_list_table').attr('autoupdate_url') ;
  $.get($url , { 'order'          : order, 
                 'sort'           : sort,
                 'operation'      : operation,
                 'filter'         : filter, 
                 'page'           : page,      
               }, 
               update_instances_list );
        
 }
 
 
function update_instances_list(response) {

  var result = jQuery.parseJSON(response);    
  
  var head_present = $('#instances_list_table').children('thead').length ;
  var body_present = $('#instances_list_table').children('tbody').length ;
  
  if (result.html != 'NULL' ) { // Returned empty string. skip the output
  
    if (head_present) { // Head already present
        
      if (body_present) {
          
        $('#instances_list_table tbody').replaceWith(result.html);    
      }
      else { // Body not present
      
        $('#instances_list_table').append(result.html);    
      }
    } 
    else {
    
      var head_str = $('#instances_list_table').html() ;
      head_str = head_str.replace( '<tbody>'  , '<thead>'  ) ; 
      head_str = head_str.replace( '</tbody>' , '</thead>' ) ;
      
      $('#instances_list_table tbody').replaceWith(head_str); 
      $('#instances_list_table tbody').append(result.html); 
    }    
    
    Drupal.behaviors.showActionButtons.attach() ;
  }
  else {
  
    if (head_present && body_present) {
    
        $('#instances_list_table tbody').remove() ;    
    }
  }
}

})(jQuery);

