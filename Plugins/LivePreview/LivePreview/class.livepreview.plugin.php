<?php if (!defined('APPLICATION')) exit();
/*
Using LivePreview jQuery Plugin v1.0 made by 2009 Phil Haack
*/

// Define the plugin:
$PluginInfo['LivePreview'] = array(
   'Name' => 'Live Preview',
   'Description' => 'This plugin creates a live preview, using the LivePreview jQuery Plugin by Phil Haack. ',
   'Version' => '1.0',
   'Author' => "Adrian",
   'AuthorUrl' => 'http://vanillaforums.com',
   'MobileFriendly' => TRUE
);

class LivePreviewPlugin extends Gdn_Plugin {

	public function Base_Render_Before($Sender)
    {
        $Sender->AddCssFile('livepreview.css', 'plugins/LivePreview'); 
        $Sender->AddJsFile('livepreview.js', 'plugins/LivePreview');
    }

	public function DiscussionController_AfterBodyField_Handler($Sender) {
	
	//find a better way to get this list
	$allowedhtml = "'p', 'strong', 'br', 'em', 'strike', 'b','i','u','del','code','img', 'src', 'a', 'href','blockquote'";
	
echo"<script>
	
				$(document).ready(function(){
					$('.livepreview').hide();
					$('.preview_hide').show();
			 
					$('.preview_hide').click(function(){
					$('.livepreview').slideToggle();
					}); 
				});

		  $(function() {
			 $('textarea.TextBox').livePreview({
				previewElement: $('div.livepreview'),
				allowedTags: [$allowedhtml],
				interval: 20
				});
			 });
			 </script>
			<label><a href='#' class='preview_hide'>Live Preview</a></label>
			<div class=\"livepreview\"></div>";
	}
	
	public function PostController_DiscussionFormOptions_Handler ($Sender) {
	
	//find a better way to get this list
	$allowedhtml = "'p', 'strong', 'br', 'em', 'strike', 'b','i','u','del','code','img', 'src', 'a', 'href','blockquote'";
	
	echo"<script>
	
				$(document).ready(function(){
					$('.livepreview').hide();
					$('.preview_hide').show();
			 
					$('.preview_hide').click(function(){
					$('.livepreview').slideToggle();
					}); 
				});

		  $(function() {
			 $('textarea.TextBox').livePreview({
				previewElement: $('div.livepreview'),
				allowedTags: [$allowedhtml],
				interval: 20
				});
			 });
			 </script>
			<label><a href='#' class='preview_hide'>Live Preview</a></label>
			<div class=\"livepreview\"></div>";
	}
   
   public function Setup() {
   }


   
}