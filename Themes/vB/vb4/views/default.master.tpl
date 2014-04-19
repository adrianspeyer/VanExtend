<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
  {asset name='Head'}
</head>
<body id="{$BodyID}" class="{$BodyClass}">
<div id="Frame">
 <div id="Head">
  <div id="navhead">
<div id="loginbox">
{if !$User.SignedIn}
<a class="registerbox" href="{link path="/entry/register"}">Register</a> 
{/if}    
{if !$User.SignedIn}
    <div class="Loginform">
    <form id="Form_User_SignIn" method="post" action="{link path="/entry/signin"}">
    <fieldset id="logindetails" class="logindetails">
    <input id="Form_TransientKey" type="hidden" value="{$TransientKey}" name="TransientKey">
    <input id="Form_hpt" type="hidden" style="display: none;" value="" name="hpt">
    <input id="Form_Target" type="hidden" value="/" name="Target">    
    <label class="UsernameLabel">
    <input type="text" id="Form_Email" name="Email" value="Username" onfocus="this.value='';" class="InputBox">
    </label>
    <label class="PasswordLabel">
    <input type="text" id="Form_Password" name="Password" value="Password"  onfocus="(this.value = '');setAttribute('type', 'password')" class="InputBox Password">
  </label>
  <input type="submit" id="Form_SignIn" name="Sign_In" value="Log In" class="Button">
    </fieldset>
	
    <fieldset class="RememberMe">
    <label for="SignInRememberMe" class="CheckBoxLabel chechkbox">
    <input type="checkbox" id="SignInRememberMe" name="RememberMe" value="1" checked="checked">
    <input type="hidden" name="Checkboxes[]" value="RememberMe">
    <span>Remember me</span>
    </label>
    </fieldset>
    </form>
    </div>
	 {/if}
	   {if $User.SignedIn}
	   <div class="usermebox">
	     {module name="MeModule"}
		 </div>
		{/if}
		</div> 
 <div class="Banner Menu">
      <h1><a class="Title" href="{link path="/"}"><span>{logo}</span></a></h1>
      <ul id="Menu">
         {dashboard_link}
         {discussions_link}
         {inbox_link}
         {custom_menu}
         {profile_link}
         {signinout_link}
      </ul>
      <div id="Search">{searchbox}</div>
    </div>
  </div>
  </div>
  <div id="Body">
    <div id="Content">	{if $BodyID =='vanilla_categories_index' || $BodyID =='vanilla_discussions_index'}	<a class="PostThread" href="{link path="post/discussion"}">+ Post New Thread</a>	{/if}
      {asset name="Content"}	</div>
	{*
	need to work on tnis panel
	 <div id="PrePanel">
	<h2>What's Going On?</h2>
	</div>
   <div id="Panel">
   </div>
	  *}
	  </div>
     <div id="Foot">
    <div class="Row">
     <a href="{vanillaurl}" class="PoweredByVanilla">Powered by Vanilla</a>
      {asset name="Foot"}
    </div>
  </div>
</div>
{event name="AfterBody"}
</body>
</html>