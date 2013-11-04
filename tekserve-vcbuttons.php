<?php

/**
 * Plugin Name: Tekserve VCButtons
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Custom shortcodes for interface elements and Visual Composer button mappings
 * Version: 1.0
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2013 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**  Shortcodes for VC Buttons  **/

//e.g.[repairstatus] 
//shortcode for tekserve repair status checker

$tharurl = plugin_dir_path( __FILE__ ).'extend-vc/icons/sro.jpg';
function repair_status_checker($atts){
return "<div id='status-title'><h2>Check Your Repair Status</h2></div><div id='status-wrapper'><div id='status-content'><p>Please use the form below to check the status of your repair at Tekserve. Just enter your SRO number (found in the upper right corner of your receipt) and billing zip code below.</p><img id='statusimg' src='' /><div id='fail-msg' style='display:none'><p style='padding:5px 0px'>The information you provided does not match what we have on record.<br />Please double check your information and try again. If it still isn't working for you, call us at: 212.929.3645</p><input onclick='javascript:document.location.reload()' class='button' type='button' value='Try Again'></input></div><div style='display:none' class='customer-info'><ul><li class='customer-info'><h3>Customer Info</h3><p></p></li><li id='product-info'><p></p></li></ul><li class='repair-details'><h3>Details</h3><ul class='repair-details'><li style='display: none'><p>During the first 1-3 business days, your repair will be processed and assigned to a technician.</p></li><li style='display: none'><p>A technician will work on your repair during this time. This will include confirming your issue, ordering replacement parts (if needed), and replacing the affected parts.</p></li><li style='display: none'><p>We are confirming that we resolved the issue.</p></li><li style='display: none'><p>The repair is done. It is ready to be picked up, if you have not made other arrangements.</p></li><li style='display: none'><p>Call Customer Support at 212.929.3645 for more information regarding this repair.</p></li></ul></li></div><form class='status-front' id='status-front' method='get'><p><span class='label'>SRO#</span> <a href='javascript:showExampleSRO();'>What's this?</a></p>
<div id='whats-sro' style='display: none; text-align: left; font-size: 24px; font-weight: normal;' ><div style='background-image: url(https://www.tekserve.com/media/wysiwyg/images/sroexample.jpg); background-position: left; background-size: 100%; float: right; min-height: 235px; width: 48%; min-width: 300px; max-width: 100%; margin-left: 1em; background-repeat: no-repeat;' class='sro-example'>&nbsp;</div>Your SRO # (Service Request Order) is the largest number on any repair receipt or invoice from Tekserve. The number is seven digits long and located in the upper right corner of your receipt as shown.</div>
<hr style='clear: both; visibility: hidden;'>
<p class='statusField'><input class='limit' name='sro1' id='sro1' type='text' value='' maxlength='1' size='1' tabindex='1' onkeyup='checkLen(this,this.value)'></input> - <input class='limit' name='sro2' id='sro2' type='text' value='' maxlength='3' size='3' tabindex='2' onkeyup='checkLen(this,this.value)'></input> - <input class='limit' name='sro3' id='sro3' type='text' value='' maxlength='3' size='3' tabindex='3' onkeyup='checkLen(this,this.value)'></input></p><p><span class='label'>Billing ZIP Code</span></p><p><input class='limited' name='zip' id='zip' type='text'  value='' maxlength='5' size='5' tabindex='4' onkeyup='checkLen(this,this.value)' /></p><div class='buttons'><button type='button' class='positive'>Submit</button></div></form></div></div></div><div></div>
<script type='text/javascript'>

var \$j = jQuery;
\$j('button.positive').click(function () {
    var img_base_path = 'http://www.tekserve.com/skin/frontend/tekserve/tekstore/';
    var repair_status = new Array('Created', 'In Progress', 'Testing', 'Complete', 'On Hold', 'Done', 'Service Complete');
    var sro1 = \$j('#sro1').val();
    var sro2 = \$j('#sro2').val();
    var sro3 = \$j('#sro3').val();
    var zip = \$j('#zip').val();
    var sro_zip = 'SRO#: ' + sro1 + '-' + sro2 + '-' + sro3 + '<br />' + 'Billing Zip Code: ' + zip;
    \$j.ajax({
        type: 'GET',
        dataType: 'jsonp',
        url: 'http://www.tekserve.com/status/?sro1=' + sro1 + '&sro2=' + sro2 + '&sro3=' + sro3 + '&zip=' + zip,
        success: function (msg) {
            \$j('#status-content').children('p').add('form.status-front').hide();
            if (msg == false) {
                \$j('#status-title').find('strong').html('Login Failed');
                \$j('#fail-msg').show();
                return;
            }
            var product_name = 'Product: ' + msg.product;
            \$j('#status-title').find('strong').html('Repair Status');
            \$j('form.status-front').hide();
            \$j('.customer-info').show();
            \$j('li.customer-info').children('p').html(sro_zip);
            \$j('#product-info').children('p').html(product_name);
            var result = msg.status;
            var result_index = \$j.inArray(result, repair_status);
            \$j('#status-content').children('img').attr('src', img_base_path + 'images/repair_stage_' + result_index + '.png');
            \$j('#status-content').children('img').show();
            var detail_cmt = \$j('ul.repair-details').find('li');
            detail_cmt[result_index].show();
        }
    });
});

function checkLen(x, y) {
    if (y.length == x.maxLength) {
        var next = x.tabIndex;
        if (next < document.getElementById('status-front').length) {
            document.getElementById('status-front').elements[next].focus();
        }
    }
}

function showExampleSRO() {
	\$j('#whats-sro').toggle();
}
</script>";
}
add_shortcode( 'repairstatus', 'repair_status_checker' );


//e.g.[drawer title='title' swaptitle='alternate title for open drawer' alt='hover tag' notitle='"true" if no hover element desired' id='unique-id' tag='<div>' trigclass='trigger-class' targtag='<span>' targclass='target-content-class' rel='group-for-elements-only-one-open-at-a-time' expanded='"true" to expand on page load' startwrap='<div class="extra-class-wrapper"><h1>' endwrap='</h1></div>' color='orange' alignment='left']
//custom shortcode for collapsomatic elements, requires collapseomatic plugin. This modifies shortcode to pass correct elements and content to Visual Composer
function drawer( $atts, $content = null ) { // New function parameter $content is added!
   extract( shortcode_atts( array(
		'title' => 'Click Here',
		'swaptitle' => 'Click Here to Hide',
		'alt' => '',
		'notitle' => '',
		'id' => 'id'.$ran,
		'tag' => $options['tag'],
		'trigclass' => '',
		'trigpos' => $options['trigpos'],
		'targtag' => $options['targtag'],
		'targclass' => '',
		'targpos' => $options['targpos'],
		'rel' => '',
		'expanded' => '',
		'excerpt' => '',
		'excerptpos' => 'below-trigger',
		'excerpttag' => 'div',
		'excerptclass' => '',
		'swapexcerpt' => false,
		'findme' => '',
		'offset' => $options['offset'],
		'scrollonclose' => '',
		'startwrap' => '',
		'endwrap' => '',
		'elwraptag' => $options['wraptag'],
		'elwrapclass' => $options['wrapclass'],
		'cookiename' => '',
		'color' => 'none',
		'alignment' => 'left'
   ), $atts ) );
 
   $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content
 
   return "<div class='section ${color} dsection'><div class='drawer'><div id='${id}' class='collapseomatic colomat-hover ${alignment}' title='${title}'>${title}</div><div id='swap-${id}' style='display:none;'>${swaptitle}</div><div id='target-${id}' class='collapseomatic_content' style='display:none;'>${content}</div></div></div>";
// 
}
add_shortcode( 'drawer', 'drawer' );


/*** Custom Visual Composer Mappings ***/

vc_map( array(
   "name" => __("Repair Status Checker"),
   "base" => "repairstatus",
   "class" => "",
   "icon" => "icon-wpb-repairstatus",
   "category" => __('Content'),
   'admin_enqueue_css' => array('vc_extend/icons.css')
) );

vc_map( array(
   "name" => __("Sortable Table"),
   "base" => "sorttablepost",
   "class" => "",
   "icon" => "icon-wpb-sorttablepost",
   "category" => __('Content'),
   "admin_enqueue_css" => array('vc_extend/icons.css'),
   "params" => array(
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Unique ID"),
         "param_name" => "id",
         "value" => __(""),
         "description" => __("Enter a unique id (no spaces) for this table."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Custom Post Type"),
         "param_name" => "type",
         "value" => __(""),
         "description" => __("Enter the slug for the post type you would like to display"),
         "admin_label" => true
      ),
     array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Custom Hierarchical Taxononomy"),
         "param_name" => "cat",
         "value" => __(""),
         "description" => __("Enter the slug for the custom taxonomy you would like to display"),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Custom Non-Hierarchical Taxononomy"),
         "param_name" => "cat",
         "value" => __(""),
         "description" => __("Enter the slug for the custom taxonomy you would like to display"),
         "admin_label" => true
      ),
      array( // need to troubleshoot this feature for vc
         "type" => "exploded_textarea",
         "holder" => "div",
         "class" => "",
         "heading" => __("Additional Columns"),
         "param_name" => "meta",
         "value" => __(""),
         "description" => __("Enter the slugs for any additional fields you'd like to display as a column. Separate slugs with line breaks by pressing return key."),
         "admin_label" => true
      ),
	 array(
         "type" => "checkbox",
         "holder" => "div",
         "class" => "",
         "heading" => __("Hide Featured Image"),
         "param_name" => "nothumb",
         "value" => array("true"),
         "description" => __("Check to hide the featured image column"),
         "admin_label" => false
      ),
	 array(
         "type" => "checkbox",
         "holder" => "div",
         "class" => "",
         "heading" => __("Hide Title"),
         "param_name" => "notitle",
         "value" => array("true"),
         "description" => __("Check to hide the title column"),
         "admin_label" => false
      ),
	 array(
         "type" => "checkbox",
         "holder" => "div",
         "class" => "",
         "heading" => __("Hide Post Date"),
         "param_name" => "nodate",
         "value" => array("true"),
         "description" => __("Check to hide the column for the post's date"),
         "admin_label" => false
      ),
     array(
         "type" => "checkbox",
         "holder" => "div",
         "class" => "",
         "heading" => __("Hide Categories"),
         "param_name" => "nocats",
         "value" => array("true"),
         "description" => __("Check to hide the categories column"),
         "admin_label" => false
      ),
	 array(
         "type" => "checkbox",
         "holder" => "div",
         "class" => "",
         "heading" => __("Hide Tags"),
         "param_name" => "notags",
         "value" => array("true"),
         "description" => __("Check to hide the tags column"),
         "admin_label" => false
      )
    )
)	);


vc_map( array(
   "name" => __("Drawer"),
   "base" => "drawer",
   "class" => "",
   "icon" => "icon-wpb-drawer",
   "category" => __('Content'),
   "admin_enqueue_css" => array('vc_extend/icons.css'),
   "params" => array(
	 array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Unique ID"),
         "param_name" => "id",
         "value" => __("click-here"),
         "description" => __("Required; Unique ID to identify drawer on this page. Use all lowercase, no special characters or spaces."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Title"),
         "param_name" => "title",
         "value" => __("Click Here"),
         "description" => __("Required; Text that user clicks on to expand drawer"),
         "admin_label" => true
      ),
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Alignment"),
         "param_name" => "alignment",
         "value" => array("left", "leftcenter", "rightcenter", "right"),
         "description" => __("Required; Choose where the title text will appear on the page."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Alternate Title"),
         "param_name" => "swaptitle",
         "value" => __("Click Here to Hide"),
         "description" => __("Optional; Title that is displayed when drawer is open."),
         "admin_label" => true
      ),
      array(
         "type" => "textfield",
         "holder" => "div",
         "class" => "",
         "heading" => __("Unique ID"),
         "param_name" => "id",
         "value" => __("click-here"),
         "description" => __("Required; Unique ID to identify drawer on this page. Use all lowercase, no special characters or spaces."),
         "admin_label" => true
      ),
      array(
         "type" => "dropdown",
         "holder" => "div",
         "class" => "",
         "heading" => __("Background Color"),
         "param_name" => "color",
         "value" => array("white", "orange", "darkblue", "lightblue"),
         "description" => __("Choose the background color for this drawer."),
         "admin_label" => true
      ),
      array(
         "type" => "textarea_html",
         "holder" => "div",
         "class" => "",
         "heading" => __("Content"),
         "param_name" => "content",
         "value" => __("<p>I am test text block. Click edit button to change this text.</p>"),
         "description" => __("Required; Enter the drop-down content of the drawer.")
   )
) 
	)
);