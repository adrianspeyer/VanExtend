$(document).ready(function() {
$(this).find("pre").not('.prettyprint.linenums').each(function(){
   $(this).addClass("prettyprint linenums");
   prettyPrint();
}); 
});