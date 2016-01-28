jQuery(document).ready(function($) {
   // Define variables for links and regex matching.
   var YahooLink = 'sports.yahoo.com/video';
   var YahooRegex1 = 'sports.yahoo.com/video/([A-Za-z0-9\-\_]+).html';
 
   // Interval for timer in milliseconds.
   // This is important for the binds.
   // It is used on document load because it gives the page time to load up.
   var EmbedInterval = 500;
   
   // Yahoo Embed
   // Replace link to video with embed.
   function YahooEmbed() {
      setInterval(function() {
         $('.Message, .Excerpt, #PageBody').find('a[href*="' + YahooLink + '"]').each(function() {
            var YahooVideoID = $(this).attr('href').match(YahooRegex1);	
		
            if(YahooVideoID != null)	
               $(this).replaceWith('<div class="YahooVideoWrap"><iframe class="YahooVideoEmbed" src="https://sports.yahoo.com/video/' + YahooVideoID[1]+'.html?format=embed" width="640" height="360" frameborder="0">Your browser does not support iframes.</iframe></div>');        
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
   var YahooAddHeight = 0;
 
   
   // Set interval to give page time to load up the video embed, so that
   // things like the preview and bind events get scaled by aspect ratio
   // for the responsive video frames.
   function setAspectRatio() {
      setInterval(function() {
         $('.YahooVideoEmbed').each(function() {
            $(this).css('height', $(this).width() * AspectRatio + YahooAddHeight);
         });
      }, EmbedInterval);
   }
   
   // Function to run all embed functions.
   function LoadAllEmbeds() {
	   YahooEmbed();

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
