<?php defined('APPLICATION') or exit();
/**
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// An associative array of information about this plugin.
$PluginInfo['YahooVideo'] = array(
   'Name' => 'Yahoo Video Embed',
   'Description' => 'Allows users to embed Yahoo videos in posts.',
   'Version' => '1.0',
   'Author' => "Adrian",
   'License' => 'GNU GPL2',
   'MobileFriendly' => TRUE
);

class YahooVideoPlugin extends Gdn_Plugin {
   /*
    * Include the CSS and JS files.
    * 
    * @param Gdn_Controller $Sender
    */
   private function IncludeAssets($Sender) {
      $Sender->AddCssFile('yahoovideo.css', 'plugins/YahooVideo');
      $Sender->AddJsFile('yahoovideo.js', 'plugins/YahooVideo');
   }

    /**
     * Include assets in the Vanilla app's DiscussionController.
     *
     * @param DiscussionController $Sender
     */
    public function DiscussionController_Render_Before($Sender) {
        $this->IncludeAssets($Sender);
    }

    /**
     * Include assets in the Vanilla app's PostController.
     *
     * @param PostController $Sender
     */
    public function PostController_Render_Before($Sender) {
        $this->IncludeAssets($Sender);
    }

    /**
     * Include assets in the Dashboard app's ActivityController.
     *
     * @param ActivityController $Sender
     */
    public function ActivityController_Render_Before($Sender) {
        $this->IncludeAssets($Sender);
    }

    /**
     * Include assets in the Dashboard app's ProfileController.
     *
     * @param ProfileController $Sender
     */
    public function ProfileController_Render_Before($Sender) {
        $this->IncludeAssets($Sender);
    }
}
