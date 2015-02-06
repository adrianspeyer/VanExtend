<?php if (!defined('APPLICATION')) exit;

// Define the plugin
$PluginInfo['Reddit'] = array(
    'Name'                 => 'Reddit Social Connect',
    'Description'          => 'Users may sign into your site using their Reddit account.',
    'Version'              => '0.1.3',
    'RequiredApplications' => array('Vanilla' => '2.1.7'),
    'RequiredTheme'        => false,
    'RequiredPlugins'      => false,
    'MobileFriendly'       => true,
    'SettingsUrl'          => '/dashboard/social/reddit',
    'SettingsPermission'   => 'Garden.Settings.Manage',
    'HasLocale'            => true,
    'RegisterPermissions'  => false,
    'Hidden'               => true,
    'SocialConnect'        => false,
    'RequiresRegistration' => true,
	'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com',
    'License' => 'GNU GPL2'
);


/**
 * Include Garden's core OAuth library
 *
 * @link https://github.com/reddit/reddit/wiki/OAuth2
 */
require_once PATH_LIBRARY . '/vendors/oauth/OAuth.php';

/**
 * Reddit Social Connect Plugin
 *
 * Created with the help of Kasper, Shadowdare, hgtonight, and
 * the Vanilla Forums Community. Inspired by Todd Burry and his 
 * Facebook Plugin.
 * 
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * 
 * @author Adrian Speyer (http://www.adrianspeyer.com)
 * @author Shadowdare (http://gamebyline.com)
 * @author Zachary Doll <Zachary.Doll@gmail.com>
 * @author Kasper Isager <kasperisager@gmail.com>
 */
class RedditPlugin extends Gdn_Plugin {
    
	/**
Add CSS to work with version 2.2
**/
public function AssetModel_StyleCss_Handler($Sender) {
      $Sender->AddCssFile('reddit.css', 'plugins/Reddit');
   }
	
	
	/// Constants ///

    const ProviderKey = 'Reddit';

    /// Properties

    /**
     * @access protected
     * @var    bool|null|string
     */
    protected $_AccessToken = null;

    /**
     * @access protected
     * @var    null|string
     */
    protected $_RedirectUri = null;

    /// Methods ///

    /**
     * Code to be run upon enabling the plugin
     *
     * @access public
     * @throws Gdn_UserException
     */
    public function Setup() {
        $Error = '';

        if (!function_exists('curl_init')) {
            $Error = ConcatSep("\n", $Error, T('This plugin requires curl.'));

            throw new Gdn_UserException($Error, 400);
        }

        $this->Structure();
    }

    /**
     * Create database structure
     *
     * @access public
     */
    public function Structure() {
        // Save the Reddit provider type.
        Gdn::SQL()->Replace(
            'UserAuthenticationProvider',
            array(
                'AuthenticationSchemeAlias' => 'reddit',
                'URL'                       => '...',
                'AssociationSecret'         => '...',
                'AssociationHashMethod'     => '...'
            ),
            array('AuthenticationKey' => self::ProviderKey),
            true
        );
    }

    /**
     * Code to be run upon disabling the plugin
     *
     * We simply return true in order to indicate that the plugin has been
     * successfully disabled.
     *
     * @access public
     * @return bool
     */
    public function OnDisable() {
        return true;
    }

    /**
     * Has the plugin been correctly configured?
     *
     * @access public
     * @return bool
     */
    public function IsConfigured() {
        $AppID  = C('Plugins.Reddit.ClientID');
        $Secret = C('Plugins.Reddit.Secret');

        if (!$AppID || !$Secret) return false;

        return true;
    }

    /**
     * Check if Social Sign In is enabled and the plugin configured.
     *
     * @access public
     * @return bool
     */
    public function SocialSignIn() {
        return C('Plugins.Reddit.SocialSignIn', true) && $this->IsConfigured();
    }

    /**
     * Check if Social Reactions is enabled and the plugin configured.
     *
     * @access public
     * @return bool
     */
    public function SocialReactions() {
        return C('Plugins.Reddit.SocialReactions', TRUE) && $this->IsConfigured();
    }

    /**
     * @access public
     * @param  string $Path
     * @param  bool   $Post
     * @return mixed
     * @throws Gdn_UserException
     */
    public function API($Path, $Post = false) {
        // Build the url.
        $Url = 'https://ssl.reddit.com/api/v1/authorize' . ltrim($Path, '/');

        $AccessToken = $this->AccessToken();

        if (!$AccessToken) {
            throw new Gdn_UserException(T("You don't have a valid Reddit connection."));
        }

        if (strpos($Url, '?') === false) {
            $Url .= '?';
        } else {
            $Url .= '&';
        }

        $Url .= 'access_token=' . urlencode($AccessToken);

        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_HEADER, false);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Curl, CURLOPT_URL, $Url);

        if ($Post !== false) {
            curl_setopt($Curl, CURLOPT_POST, true);
            curl_setopt($Curl, CURLOPT_POSTFIELDS, $Post);
            Trace("  POST $Url");
        } else {
            Trace("  GET  $Url");
        }

        $Response    = curl_exec($Curl);
        $HttpCode    = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
        $ContentType = curl_getinfo($Curl, CURLINFO_CONTENT_TYPE);
        curl_close($Curl);

        Gdn::Controller()->SetJson('Type', $ContentType);

        if (strpos($ContentType, 'javascript') !== false) {
            $Result = json_decode($Response, true);

            if (isset($Result['error'])) {
                Gdn::Dispatcher()->PassData('RedditResponse', $Result);
                throw new Gdn_UserException($Result['error']['message']);
            }
        } else {
            $Result = $Response;
        }

        return $Result;
    }

    /**
     * @access public
     * @param  bool $Query
     */
    public function Authorize($Query = false) {
        $Uri = $this->AuthorizeUri($Query);
        Redirect($Uri);
    }

    /**
     * @access public
     * @return bool|string
     */
    public function AccessToken() {
        if (!$this->IsConfigured()) {
            return false;
        }

        if ($this->_AccessToken === null) {
            if (Gdn::Session()->IsValid()) {
                $this->_AccessToken = GetValueR(self::ProviderKey . '.AccessToken', Gdn::Session()->User->Attributes);
            } else {
                $this->_AccessToken = false;
            }
        }

        return $this->_AccessToken;
    }

    /**
     * @access public
     * @param  bool $Query
     * @param  bool $RedirectUri
     * @return string
     */
    public function AuthorizeUri($Query = false, $RedirectUri = false) {
        $AppID = C('Plugins.Reddit.ClientID');

        if (!$RedirectUri) {
            $RedirectUri = $this->RedirectUri();
        }
        if ($Query) {
            $RedirectUri .= '&' . $Query;
        }

        $MainGet = array(
            'duration'      => 'temporary', // 'temporary' or 'permanent'
            'response_type' => 'code',
            'scope'         => 'identity',
            'state'         => Gdn::Session()->TransientKey(),
            'client_id'     => $AppID,
            'redirect_uri'  => $RedirectUri
        );

        $SigninHref = 'https://ssl.reddit.com/api/v1/authorize?' . http_build_query($MainGet);

        if ($Query) {
            $SigninHref .= '&' . $Query;
        }

        return $SigninHref;
    }

    /**
     * @access public
     * @param  null $NewValue
     * @return null|string
     */
    public function RedirectUri($NewValue = null) {
        if ($NewValue !== null) {
            $this->_RedirectUri = $NewValue;
        } else if ($this->_RedirectUri === null) {
            $RedirectUri = Url('/entry/connect/reddit', true);

            if (strpos($RedirectUri, '=') !== false) {
                $p           = strrchr($RedirectUri, '=');
                $Uri         = substr($RedirectUri, 0, -strlen($p));
                $p           = urlencode(ltrim($p, '='));
                $RedirectUri = $Uri . '=' . $p;
            }

            $Path = Gdn::Request()->Path();

            $this->_RedirectUri = $RedirectUri;
        }

        return $this->_RedirectUri;
    }

    /**
     * @access public
     * @param  string $AccessToken
     * @return mixed
     */
    public function GetProfile($AccessToken) {
        $Url    = 'https://oauth.reddit.com/api/v1/me/';
        $Header = array('Authorization: Bearer ' . $AccessToken);

        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Curl, CURLOPT_POST, false);
        curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($Curl, CURLOPT_HTTPHEADER, $Header);
        $Contents = curl_exec($Curl);
        // Debug Purposes: $Errors = curl_error($Curl); var_dump($Errors);
        curl_close($Curl);

        $Profile = json_decode($Contents, true);

        return $Profile;
    }

    /**
     * Return the Profile Connect Url
     *
     * @access public
     * @return string
     * @static
     */
    public static function ProfileConnectUrl() {
        return Url(UserUrl(Gdn::Session()->User, false, 'redditconnect'), true);
    }

    /**
     * @access protected
     * @param  int|string $Code
     * @param  string     $RedirectUri
     * @return string
     */
    protected function GetAccessToken($Code, $RedirectUri) {
        $Post = array(
            'client_id'     => C('Plugins.Reddit.ClientID'),
            'client_secret' => C('Plugins.Reddit.Secret'),
            'grant_type'    => 'authorization_code',
            'code'          => $Code,
            'redirect_uri'  => $RedirectUri
        );

        // Get the redirect URI.
        $Url  = 'https://ssl.reddit.com/api/v1/access_token/';
        $Curl = curl_init();
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($Curl, CURLOPT_USERPWD, $Post['client_id'] . ':' . $Post['client_secret']);
        curl_setopt($Curl, CURLOPT_POST, true);
        curl_setopt($Curl, CURLOPT_POSTFIELDS, $Post);
        curl_setopt($Curl, CURLOPT_URL, $Url);
        $Contents = curl_exec($Curl);
        $Info     = curl_getinfo($Curl);
        curl_close($Curl);

        $Tokens   = json_decode($Contents, true);
        $ErrorMsg = GetValue('error', $Tokens);

        if ($ErrorMsg == 'invalid_grant') {
            Redirect('/plugin/reddit/error/invalid_grant');
        } else if ($ErrorMsg != '') {
            Redirect('/plugin/reddit/error/unknown_error/' . urlencode($ErrorMsg));
        }

        $AccessToken = GetValue('access_token', $Tokens);

        return $AccessToken;
    }

    /**
     * Create the Reddit share button
     *
     * @access protected
     * @param  string HTML for the Reddit share button
     */
    protected function AddReactButton($Args) {
        if ($this->AccessToken()) {
            $CssClass = 'ReactButton Hijack';
        } else {
            $CssClass = 'ReactButton PopupWindow';
        }

        $Type = $Args['RecordType'];
        $Url  = false;

        switch ($Type) {
            case 'discussion':
                $Url = $Args['Discussion']->Url;
                break;

            case 'comment':
                $ID  = $Args['Comment']->CommentID;
                $Url = '/discussion/comment/' . $ID . '/#Comment_' . $ID;
                break;
        }

        if (!$Url) return false;

        // URL for manually creating sharing buttons
        $ShareUrl = 'http://www.reddit.com/submit?url=' . Url($Url, true);

        // Simple share button image
        $ShareImg = '<img src="http://i.imgur.com/6f3Zf6I.png" alt="submit to reddit" border="0">';

        // Build React button
        $ReactButton  = ' ';
        $ReactButton .= Anchor(Sprite('ReactReddit') . $ShareImg, $ShareUrl, $CssClass);
        $ReactButton .= ' ';

        return $ReactButton;
    }

    /**
     * Create the Reddit login button
     *
     * @access protected
     * @return string HTML for the Reddit login button
     */
    protected function AddSigninButton() {
        $ImgSrc     = Asset($this->GetPluginFolder(false) . '/design/reddit-icon.png');
        $ImgAlt     = T('Sign In with Reddit');
        $SigninHref = $this->AuthorizeUri();

        return '<a id="RedditAuth" href="'.$SigninHref.'" rel="nofollow"><img src="'.$ImgSrc.'" alt="'.$ImgAlt.'" align="bottom"></a>';
    }

    /**
     * @access protected
     * @param  object $Sender
     * @param  string $Title
     * @param  string $Exception
     */
    protected function RenderBasicError($Sender, $Title = 'Title', $Exception = 'Exception.') {
        $Sender->RemoveCssFile('admin.css');
        $Sender->AddCssFile('style.css');
        $Sender->MasterView = 'default';
        $Sender->CssClass   = 'SplashMessage NoPanel';

        $Sender->SetData('Title', $Title);
        $Sender->SetData('Exception', $Exception);

        $Sender->Render('/home/error', '', 'dashboard');
    }

    /// Event Handlers ///

    /**
    * Add 'Reddit' option to the row.
    *
    * @access public
    * @param  Gdn_Controller $Sender
    * @param  array          $Args
    */
    public function Base_AfterReactions_Handler($Sender, $Args) {
        if (!$this->SocialReactions()) return;

        if ($this->AddReactButton($Args)) {
            echo Gdn_Theme::BulletItem('Share') . $this->AddReactButton($Args);
        }
    }

    /**
     * Echo out the Reddit Sign In button
     *
     * @access public
     */
    public function Base_SignInIcons_Handler() {
        if (!$this->SocialSignIn()) return;

        echo "\n" . $this->AddSigninButton();
    }

    /**
     * Echo out the Reddit Sign In button
     *
     * @access public
     */
    public function Base_BeforeSignInButton_Handler() {
        if (!$this->SocialSignIn()) return;

        echo "\n" . $this->AddSigninButton();
    }

    /**
     * Echo out the Reddit Sign In button
     *
     * @access public
     */
    public function Base_BeforeSignInLink_Handler() {
        if (!$this->SocialSignIn()) return;

        if (!Gdn::Session()->IsValid()) {
            echo "\n" . Wrap($this->AddSigninButton(), 'li', array('class' => 'Connect RedditConnect'));
        }
    }

    /**
     * @access public
     * @param  Gdn_Controller $Sender
     * @param  array          $Args
     */
    public function Base_GetConnections_Handler($Sender, $Args) {
        $Profile = GetValueR('User.Attributes.' . self::ProviderKey . '.Profile', $Args);

        $Sender->Data['Connections'][self::ProviderKey] = array(
            'Icon'        => $this->GetWebResource('icon.png', '/'),
            'Name'        => 'Reddit',
            'ProviderKey' => self::ProviderKey,
            'ConnectUrl'  => $this->AuthorizeUri(false, self::ProfileConnectUrl()),
            'Profile'     => array('Name' => GetValue('name', $Profile))
        );
    }

    /**
     * @access public
     * @param  PluginController $Sender
     * @return null
     */
    public function PluginController_Reddit_Create($Sender) {
        $RequestMethod = strtolower($Sender->RequestArgs[0]);
        $RequestArg1   = strtolower($Sender->RequestArgs[1]);
        $RequestArg2   = urldecode(GetValue(2, $Sender->RequestArgs, 'no error returned'));

        // Handle error requests.
        if (($RequestMethod == 'error') && ($RequestArg1 != '')) {
            // Email not verified error.
            switch($RequestArg1) {
                case 'invalid_grant':
                    $Title     = T('Reddit.Error.InvalidGrant.Title', 'Reddit Authentication Error');
                    $Exception = T('Reddit.Error.InvalidGrant.Exception', 'You must reconnect your Reddit acount and allow Reddit to share basic information about your profile.');
                    break;

                case 'email_not_verified':
                    $Title     = T('Reddit.Error.Authentication.Title', 'Reddit Authentication Error');
                    $Exception = T('Reddit.Error.Authentication.Exception', "You must verify your Reddit account's email address first.");
                    break;

                case 'unknown_error':
                    $Title     = T('Reddit.Error.UnknownError.Title', 'Reddit Unknown Error');
                    $Exception = sprintf(T('Reddit.Error.UnknownError.Exception', 'Unknown error: (%s). Please contact the developers.'), $RequestArg2);
                    break;
            }

            $this->RenderBasicError($Sender, $Title, $Exception);

            return null;
        }

        // We are not using this controller for anything else, so redirect home.
        Redirect('/');

        return null;
    }

    /**
     * @access public
     * @param  Gdn_Controller $Sender
     */
    public function EntryController_SignIn_Handler($Sender) {
        if (!$this->SocialSignIn()) {
            return;
        }

        if (isset($Sender->Data['Methods'])) {
            $ImgSrc     = Asset($this->GetPluginFolder(false) . '/design/reddit-signin.png');
            $ImgAlt     = T('Sign In with Reddit');
            $SigninHref = $this->AuthorizeUri();

            // Add the Reddit method to the controller.
            $RDMethod = array(
                'Name'       => self::ProviderKey,
                'SignInHtml' => '<a id="RedditAuth" href="'.$SigninHref.'" rel="nofollow" ><img src="'.$ImgSrc.'" alt="'.$ImgAlt.'"></a>'
            );

            $Sender->Data['Methods'][] = $RDMethod;
        }
    }

    /**
     * @access public
     * @param  Gdn_Controller $Sender
     * @param  array          $Args
     * @throws Gdn_UserException
     */
    public function EntryController_ConnectData_Handler($Sender, $Args) {
        // Don't bother continuing is this isn't related to Reddit
        if (GetValue(0, $Args) != 'reddit') return;

        $TransientKey = GetIncomingValue('state');

        // Validate the transient key we sent along when initialising the request
        // Since this isn't a postback, we simply pair the two trasient keys and
        // check that they match.
        if ($TransientKey !== Gdn::Session()->TransientKey()) {
            throw new Gdn_UserException(T('The transient key did not validate.'));
        }

        if ($Error = GetIncomingValue('error')) {
            // If the user denied permission access at Reddit, then redirect to front.
            if ($Error == 'access_denied') Redirect('/');

            throw new Gdn_UserException(GetIncomingValue('error_description', T('There was an error connecting to Reddit.')));
        }

        $AppID  = C('Plugins.Reddit.ClientID');
        $Secret = C('Plugins.Reddit.Secret');
        $Code   = GetIncomingValue('code');

        $AccessToken = $Sender->Form->GetFormValue('AccessToken');

        // Get the access token.
        if (!$AccessToken && $Code) {
            // Exchange the token for an access token.
            $Code = urlencode($Code);

            $RedirectUri = $this->RedirectUri();
            $AccessToken = $this->GetAccessToken($Code, $RedirectUri);
            $NewToken    = true;
        }

        // Get the profile.
        try {
            $Profile = $this->GetProfile($AccessToken);
        } catch(Exception $Ex) {
            if (!isset($NewToken)) {
                // There was an error getting the profile, which probably means the saved access token is no longer valid. Try and reauthorize.
                if ($Sender->DeliveryType() == DELIVERY_TYPE_ALL) {
                    Redirect($this->AuthorizeUri());
                } else {
                    $Sender->SetHeader('Content-type', 'application/json');
                    $Sender->DeliveryMethod(DELIVERY_METHOD_JSON);
                    $Sender->RedirectUrl = $this->AuthorizeUri();
                }
            } else {
                $Sender->Form->AddError(T('There was an error with the Reddit connection.'));
            }
        }

        // If user has not verified their email at Reddit, then redirect to error controller.
        if (!GetValue('has_verified_email', $Profile)) {
            Redirect('/plugin/reddit/error/email_not_verified');
        }

        $Form = $Sender->Form; //new Gdn_Form();
        $ID   = GetValue('id', $Profile);
        $Form->SetFormValue('UniqueID', $ID);
        $Form->SetFormValue('Provider', self::ProviderKey);
        $Form->SetFormValue('ProviderName', 'Reddit');
        $Form->SetFormValue('FullName', GetValue('name', $Profile));
        // Email is not returned by Reddit API: $Form->SetFormValue('Email', GetValue('email', $Profile));
        $Form->AddHidden('AccessToken', $AccessToken);

        if (C('Plugins.Reddit.UseRedditNames')) {
            $Form->SetFormValue('Name', GetValue('name', $Profile));
            SaveToConfig(array(
                'Garden.User.ValidationRegex'    => UserModel::USERNAME_REGEX_MIN,
                'Garden.User.ValidationLength'   => '{3,50}',
                'Garden.Registration.NameUnique' => false
            ), '', false);
        }

        // Save some original data in the attributes of the connection for later API calls.
        $Attributes = array();
        $Attributes[self::ProviderKey] = array(
            'AccessToken' => $AccessToken,
            'Profile'     => $Profile
        );
        $Form->SetFormValue('Attributes', $Attributes);

        $Sender->SetData('Verified', true);
    }

    /**
     * @access public
     * @param  ProfileController $Sender
     * @param  string            $UserReference
     * @param  string            $Username
     * @param  bool              $Code
     */
    public function ProfileController_RedditConnect_Create($Sender, $UserReference, $Username, $Code = false) {
        $Sender->Permission('Garden.SignIn.Allow');

        $Sender->GetUserInfo($UserReference, $Username, '', true);
        $Sender->_SetBreadcrumbs(T('Connections'), '/profile/connections');

        // Get the access token.
        $AccessToken = $this->GetAccessToken($Code, self::ProfileConnectUrl());

        // Get the profile.
        $Profile = $this->GetProfile($AccessToken);

        // Save the authentication.
        Gdn::UserModel()->SaveAuthentication(array(
            'UserID'   => $Sender->User->UserID,
            'Provider' => self::ProviderKey,
            'UniqueID' => $Profile['id']));

        // Save the information as attributes.
        $Attributes = array(
            'AccessToken' => $AccessToken,
            'Profile'     => $Profile
        );

        Gdn::UserModel()->SaveAttribute($Sender->User->UserID, self::ProviderKey, $Attributes);

        $this->EventArguments['Provider'] = self::ProviderKey;
        $this->EventArguments['User']     = $Sender->User;
        $this->FireEvent('AfterConnection');

        Redirect(UserUrl($Sender->User, '', 'connections'));
    }

    /**
     * @access public
     * @param  SocialController $Sender
     */
    public function SocialController_Reddit_Create($Sender) {
        $Sender->Permission('Garden.Settings.Manage');

        $Form = $Sender->Form;

        if ($Form->IsPostBack()) {
            $Settings = array(
                'Plugins.Reddit.ClientID'              => $Form->GetFormValue('ClientID'),
                'Plugins.Reddit.Secret'                => $Form->GetFormValue('Secret'),
                'Plugins.Reddit.UseRedditNames'        => $Form->GetFormValue('UseRedditNames'),
                'Plugins.Reddit.SocialSignIn'          => $Form->GetFormValue('SocialSignIn'),
                'Plugins.Reddit.SocialReactions'       => $Form->GetFormValue('SocialReactions'),
                'Garden.Registration.SendConnectEmail' => $Form->GetFormValue('SendConnectEmail')
            );

            SaveToConfig($Settings);

            $Sender->InformMessage(T('Your settings have been saved.'));
        } else {
            $Form->SetValue('ClientID', C('Plugins.Reddit.ClientID'));
            $Form->SetValue('Secret', C('Plugins.Reddit.Secret'));
            $Form->SetValue('UseRedditNames', C('Plugins.Reddit.UseRedditNames'));
            $Form->SetValue('SendConnectEmail', C('Garden.Registration.SendConnectEmail', false));
            $Form->SetValue('SocialSignIn', C('Plugins.Reddit.SocialSignIn', true));
            $Form->SetValue('SocialReactions', $this->SocialReactions());
        }

        $Sender->AddSideMenu('dashboard/social');
        $Sender->SetData('Title', T('Reddit Settings'));
        $Sender->Render('Settings', '', 'plugins/Reddit');
    }
}
