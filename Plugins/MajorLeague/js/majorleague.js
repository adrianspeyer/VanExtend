jQuery(document).ready(function($) {
   // Define variables for links and regex matching.
   var MLBLink = 'm.mlb.com/video';
   var MLBRegex1 = '/v+([0-9]*$)';
   var MLBRegex2 = '/v+([0-9]*)+/+[-\sa-zA-Z]';
   var MLBRegex3 = '/v+([0-9]*)+/';
   
   // Interval for timer in milliseconds.
   // This is important for the binds.
   // It is used on document load because it gives the page time to load up.
   var EmbedInterval = 500;
   
   // MLB Embed
   // Replace link to video with embed.
   function MLBEmbed() {
      setInterval(function() {
         $('.Message, .Excerpt, #PageBody').find('a[href*="' + MLBLink + '"]').each(function() {
            var MLBVideoID = $(this).attr('href').match(MLBRegex1);		
            if(MLBVideoID == null)	
			var MLBVideoID = $(this).attr('href').match(MLBRegex2);
			if(MLBVideoID == null)	
		    var MLBVideoID = $(this).attr('href').match(MLBRegex3);
		
            if(MLBVideoID != null)	
               $(this).replaceWith('<div class="MLBVideoWrap"><iframe class="MLBVideoEmbed" src="http://m.mlb.com/shared/video/embed/embed.html?content_id=' + MLBVideoID[1] +'"&width=400&height=224&property=mlb" width="400" height="224" frameborder="0">Your browser does not support iframes.</iframe></div>');        
		});
      }, EmbedInterval);
   }
   
   
   // Responsive video frames.
   // Adjusts height to aspect ratio accordingly.
   // Aspect ratio scales:
   // 16:9 => 9 / 16
   // 4:3 => 3 / 4
   var AspectRatio = (9/16);
   // Additional height variables to adjust for button bars in the video embed.
   var MLBAddHeight = 0;
 
   
   // Set interval to give page time to load up the video embed, so that
   // things like the preview and bind events get scaled by aspect ratio
   // for the responsive video frames.
   function setAspectRatio() {
      setInterval(function() {
         $('.MLBVideoEmbed').each(function() {
            $(this).css('height', $(this).width() * AspectRatio + MLBAddHeight);
         });
      }, EmbedInterval);
   }
   
   // Function to run all embed functions.
   function LoadAllEmbeds() {
	   MLBEmbed();

	   setAspectRatio();
   }
   
   // Run functions on document load.
   LoadAllEmbeds();
   
   // Bind the setAspectRatio function to the resize event of the window.
   $(window).resize(setAspectRatio);
   
   // Bind functions to AJAX form submits for comments, activties, and previews.
   $(document).livequery('CommentEditingComplete CommentAdded PreviewLoaded', function() {
      LoadAllEmbeds();
   });
   
   // Bind to click event of these buttons.
   $('body.Vanilla.Post #Form_Preview, input#Form_Share').click(function() {
      LoadAllEmbeds();
   });
});
