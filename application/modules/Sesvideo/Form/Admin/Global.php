<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_Admin_Global extends Engine_Form {

  public function init() {

    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesvideo_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesvideo.licensekey'),
    ));
    $this->getElement('sesvideo_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('sesvideo.pluginactivated')) {
       
      $this->addElement('Radio', 'sesvideo_enable_welcome_logged_in', array(
          'label' => 'Video Menu Redirection (For Logged In Users)',
          'description' => 'Choose from below where do you want to redirect users when Videos Menu item is clicked in the Main Navigation Bar.',
          'multiOptions' => array(
              1 => 'Video Welcome Page',
              0 => 'Video Home Page',
							2 => 'Video Browse Page',
              3 => 'Browse Channels',
              4 => 'Browse Playlists',
              5 => 'Artists',
          ),
          'value' => $settings->getSetting('sesvideo.enable.welcome.logged.in', 1),
      ));
       $this->addElement('Radio', 'sesvideo_enable_welcome_nonlogged_in', array(
          'label' => 'Video Menu Redirection (For Non-Logged In Users)',
          'description' => 'Choose from below where do you want to redirect users when Videos Menu item is clicked in the Main Navigation Bar.',
          'multiOptions' => array(
              1 => 'Video Welcome Page',
              0 => 'Video Home Page',
              2 => 'Video Browse Page',
              3 => 'Browse Channels',
              4 => 'Browse Playlists',
              5 => 'Artists',
          ),
          'value' => $settings->getSetting('sesvideo.enable.welcome.nonlogged.in', 1),
      ));
      $this->addElement('Text', 'video_videos_manifest', array(
          'label' => 'Plural "videos" Text in URL',
          'description' => 'Enter the text which you want to show in place of "videos" in the URLs of this plugin.',
          'value' => $settings->getSetting('video.videos.manifest', 'videos'),
      ));
      $this->addElement('Text', 'video_video_manifest', array(
          'label' => 'Singular "video" Text in URL',
          'description' => 'Enter the text which you want to show in place of "video" in the URLs of this plugin.',
          'value' => $settings->getSetting('video.video.manifest', 'video'),
      ));
      $this->addElement('Select', 'video_enable_chanel', array(
          'label' => 'Enable Channels',
          'description' => 'Do you want to enable channels for videos on your website? [If you choose Yes, then members would be able to create their channels and add videos into them. Below, you can choose which all videos can be added to channels.]',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No'
          ),
          'onchange' => 'checkChange(this.value)',
          'value' => $settings->getSetting('video.enable.chanel', 0),
      ));
      $this->addElement('Text', 'video_chanels_manifest', array(
          'label' => 'Plural "channels" Text in URL',
          'description' => 'Enter the text which you want to show in place of "channeld" in the URLs of this plugin.',
          'value' => $settings->getSetting('video.chanels.manifest', 'channels'),
      ));
      $this->addElement('Text', 'video_chanel_manifest', array(
          'label' => 'Singular "channel" Text in URL',
          'description' => 'Enter the text which you want to show in place of "channel" in the URLs of this plugin.',
          'value' => $settings->getSetting('video.chanel.manifest', 'channel'),
      ));

      $this->addElement('Radio', "sesvideo_other_modulevideos", array(
        'label' => 'Videos Created in Content Visibility',
        'description' => "Choose the visibility of the videos created in a content to only that content (module) or show in Home page, Browse page and other places of this plugin as well? (To enable users to create videos in a content or module, place the widget \"Content Profile Videos\" on the profile page of the desired content.)",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sesvideo.other.modulevideos', 1),
      ));

      $this->addElement('Select', 'video_tinymce', array(
          'label' => 'Enable WYSIWYG Editor',
          'description' => 'Do you want to enable WYSIWYG Editor for custom terms and conditions  while creating and editing videos?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('video.tinymce', 1),
      ));

      $this->addElement('MultiCheckbox', 'video_enable_chaneloption', array(
          'label' => 'Allow Videos for Channels',
          'description' => 'Choose from below the videos which users can add to their channels?',
          'value' => $settings->getSetting('video.enable.chaneloption', false),
          'multiOptions' => array(
              'my_created' => 'My Created (videos Created by the user)',
              'liked_videos' => 'Liked Videos (videos Liked by the user)',
              'rated_videos' => 'Rated Videos (videos Rated by the user)',
              'watch_later' => 'Watch Later (videos added to watch later by the user)'
          )
      ));
      /* chanel rating */
      $this->addElement('Select', 'video_chanel_rating', array(
          'label' => 'Allow Rating on Channels',
          'description' => 'Do you want to allow users to give rating on channels on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'onchange' => 'rating_chanel(this.value)',
          'value' => $settings->getSetting('video.chanel.rating', 1),
      ));
      $this->addElement('Select', 'video_ratechanel_own', array(
          'label' => 'Allow Rating on Own Channels',
          'description' => 'Do you want to allow users to give rating on own channels on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.ratechanel.own', 1),
      ));
      $this->addElement('Select', 'video_ratechanel_again', array(
          'label' => 'Allow to Edit Rating on Channels',
          'description' => 'Do you want to allow users to edit their rating on channels on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.ratechanel.again', 1),
      ));
      $this->addElement('Select', 'video_ratechanel_show', array(
          'label' => 'Show Earlier Rating on Channels',
          'description' => 'Do you want to show earlier rating on channels on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.ratechanel.show', 1),
      ));
      $this->addElement('Select', 'videochanel_category_enable', array(
          'label' => 'Make Channel Categories Mandatory',
          'description' => 'Do you want to make category field mandatory when users create or edit their chanels?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('videochanel.category.enable', 1),
      ));
      $this->addElement('Select', 'video_enable_subscription', array(
          'label' => 'Allow "Follow" for Channels',
          'description' => 'Do you want to enable follow for channles on your website. If you choose "Yes" then your site members follow channels.',
          'value' => $settings->getSetting('video.enable.subscription', 1),
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
      ));
			$this->addElement('Radio', 'sesvideo_enable_location', array(
        'label' => 'Enable Location in Video',
        'description' => 'Choose from below where do you want to enable location in Video.',
        'multiOptions' => array(
            '1' => 'Yes,Enable Location',
            '0' => 'No,Don\'t Enable Location',
        ),
        'value' => $settings->getSetting('sesvideo.enable.location', 1),
    ));
      /* Rating code */
      $this->addElement('Select', 'video_video_rating', array(
          'label' => 'Allow Rating on Videos',
          'description' => 'Do you want to allow users to give rating on videos on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'onchange' => 'rating_video(this.value)',
          'value' => $settings->getSetting('video.video.rating', 1),
      ));
      $this->addElement('Select', 'video_ratevideo_own', array(
          'label' => 'Allow Rating on Own Videos',
          'description' => 'Do you want to allow users to give rating on own videos on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.ratevideo.own', 1),
      ));
      $this->addElement('Select', 'video_ratevideo_again', array(
          'label' => 'Allow to Edit Rating on Videos',
          'description' => 'Do you want to allow users to edit their rating on videos on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.ratevideo.again', 1),
      ));
      $this->addElement('Select', 'video_ratevideo_show', array(
          'label' => 'Show Earlier Rating on Videos',
          'description' => 'Do you want to show earlier rating on videos on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.ratevideo.show', 1),
      ));
      /* End rating code */
      $this->addElement('Select', 'sesvideo_artist_rating', array(
          'label' => 'Allow Rating on Artists',
          'description' => 'Do you want to allow users to give rating on artists on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'onclick' => 'rating_artist(this.value)',
          'value' => $settings->getSetting('sesvideo.artist.rating', 1),
      ));
      $this->addElement('Select', 'sesvideo_rateartist_again', array(
          'label' => 'Allow to Edit Rating on Artists',
          'description' => 'Do you want to allow users to edit their rating on artists on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesvideo.rateartist.again', 1),
      ));
      $this->addElement('Select', 'sesvideo_rateartist_show', array(
          'label' => 'Show Earlier Rating on Artists',
          'description' => 'Do you want to show earlier rating on artists on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesvideo.rateartist.show', 1),
      ));
      $this->addElement('MultiCheckbox', 'sesvideo_artistlink', array(
          'label' => 'Allow "Add to Favorite" for Artists',
          'description' => 'Do you want to allow members of your website to add artists to their favorites?',
          'multiOptions' => array('favourite' => 'Add to Favourite'),
          'value' => unserialize($settings->getSetting('sesvideo.artistlink', 'a:1:{i:0;s:9:"favourite";}')),
      ));
      $this->addElement('Select', 'video_category_enable', array(
          'label' => 'Make Video Categories Mandatory',
          'description' => 'Do you want to make category field mandatory when users create or edit their videos?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('video.category.enable', 1),
      ));
      $this->addElement('Select', 'video_enable_watchlater', array(
          'label' => 'Enable "Watch Later" for Videos',
          'description' => 'Do you want to enable watch later for videos on your website. If you choose "Yes" then your site members will be able to save videos to their watch later list.',
          'value' => $settings->getSetting('video.enable.watchlater', 1),
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
      ));
      $this->addElement('Select', 'video_enable_report', array(
          'label' => 'Allow Report for Videos',
          'description' => 'Do you want to allow users to report videos on your website?'
          . 'report.',
          'value' => $settings->getSetting('video.enable.report', 1),
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
      ));
      $this->addElement('Select', 'sesvideo_search_type', array(
          'label' => 'Proximity Search Unit',
          'description' => 'Choose the unit for proximity search of location of videos on your website.',
          'multiOptions' => array(
              1 => 'Miles',
              0 => 'Kilometres'
          ),
          'value' => $settings->getSetting('sesvideo.search.type', 1),
      ));
      // $this->addElement('Select', 'video_embeds', array(
      //     'label' => 'Allow Embedding of Videos',
      //     'description' => 'Do you want to allow users to embed videos on your website? If you choose "Yes" then your site members embed videos on this site in other pages using an iframe (like YouTube).',
      //     'value' => $settings->getSetting('video.embeds', 1),
      //     'multiOptions' => array(
      //         '1' => 'Yes',
      //         '0' => 'No',
      //     ),
      // ));
      $this->addElement('Select', 'video_uploadphoto', array(
          'label' => 'Allow to Choose Main Photo',
          'description' => 'Do you want to allow users to choose main photo for the videos while creating / editing their videos ?',
          'value' => $settings->getSetting('video.uploadphoto'),
          'multiOptions' => array(
              '0' => 'No',
              '1' => 'Yes'
          )
      ));
			$this->addElement('Select', 'sesvideo_direct_video', array(
          'label' => 'Allow to Upload Videos Without FFMPEG',
          'description' => 'Do you want to allow .MP4 videos to be uploaded directly without converting them from one extension to another? (Note: This setting will only work for mp4 video extension types for "My Computer" option.)',
          'value' => $settings->getSetting('sesvideo.direct.video'),
          'multiOptions' => array(
              '0' => 'No',
              '1' => 'Yes'
          )
      ));
      $this->addElement('Text', 'sesvideo_youtube_playlist', array(
          'label' => 'Youtube Playlist Video Limit',
          'description' => 'Enter the number of songs to be imported from Youtube Playlists. [We suggest you to choose less than 25 videos to be imported for a playlist as importing more videos may break the connection from Youtube and abort the process.]',
          'value' => $settings->getSetting('sesvideo.youtube.playlist', '25'),
      ));
       $description = 'While creating videos on your website, users can choose Youtube as a source. For this, create an Application Key through the <a href="https://console.developers.google.com" target="_blank">Google Developers Console</a> page. <br>For more information, see: <a href="https://developers.google.com/youtube/v3/getting-started" target="_blank">YouTube Data API</a>.';

       $this->addElement('Text', 'video_youtube_apikey', array(
           'label' => 'Youtube API Key',
           'description' => $description,
           'filters' => array(
               'StringTrim',
           ),
           'value' => $settings->getSetting('video.youtube.apikey'),
       ));
       $this->video_youtube_apikey->getDecorator('Description')->setOption('escape', false);
      /* $this->addElement('Text', 'sesvideo_google_key', array(
        'label' => 'Google Api Key for Youtube Video Playlist',
        'description' => '',
        'value' => $settings->getSetting('sesvideo.google.key', ''),
        )); */

        //New File System Code
        $default_photos_main = array();
        $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
        foreach( $files as $file ) {
          $default_photos_main[$file->storage_path] = $file->name;
        }
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';
		//video adult main photo

    if (engine_count($default_photos_main) > 0) {
			$default_photos = array_merge(array('application/modules/Sesvideo/externals/images/video.png'=>''),$default_photos_main);
      $this->addElement('Select', 'sesvideo_video_default_image', array(
          'label' => 'Main Default Photo for Videos',
          'description' => 'Choose Main default photo for the videos on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change adult default photo.]',
          'multiOptions' => $default_photos,
          'value' => $settings->getSetting('sesvideo.video.default.image'),
      ));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for  videos on your website. Photo to be chosen for videos on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the  videos on your website. Please upload the Photo to be chosen for videos on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'sesvideo_video_default_image', array(
          'label' => 'Main Default Photo for Videos',
          'description' => $description,
      ));
    }
    $this->sesvideo_video_default_image->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    if (engine_count($default_photos_main) > 0) {
			$default_photos = array_merge(array('application/modules/Sesvideo/externals/images/video.png'=>''),$default_photos_main);
      $this->addElement('Select', 'sesvideo_chanel_default_image', array(
          'label' => 'Main Default Photo for Channels',
          'description' => 'Choose Main default photo for the channels on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change adult default photo.]',
          'multiOptions' => $default_photos,
          'value' => $settings->getSetting('sesvideo.chanel.default.image'),
      ));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for  channels on your website. Photo to be chosen for channels on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the  channels on your website. Please upload the Photo to be chosen for channels on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'sesvideo_chanel_default_image', array(
          'label' => 'Main Default Photo for Channels',
          'description' => $description,
      ));
    }
    $this->sesvideo_chanel_default_image->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));


    if (engine_count($default_photos_main) > 0) {
			$default_photos = array_merge(array('application/modules/Sesvideo/externals/images/nophoto_playlist_thumb_profile.png'=>''),$default_photos_main);
      $this->addElement('Select', 'sesvideo_playlist_default_image', array(
          'label' => 'Main Default Photo for Playlists',
          'description' => 'Choose Main default photo for the playlists on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change adult default photo.]',
          'multiOptions' => $default_photos,
          'value' => $settings->getSetting('sesvideo.playlist.default.image'),
      ));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for  playlists on your website. Photo to be chosen for playlists on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the  playlists on your website. Please upload the Photo to be chosen for playlists on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'sesvideo_playlist_default_image', array(
          'label' => 'Main Default Photo for Playlists',
          'description' => $description,
      ));
    }
    $this->sesvideo_playlist_default_image->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));


    if (engine_count($default_photos_main) > 0) {
			$default_photos = array_merge(array('application/modules/Sesvideo/externals/images/sesvideo_adult.png'=>''),$default_photos_main);
      $this->addElement('Select', 'sesvideo_video_default_adult', array(
          'label' => 'Default Adult Photo for Videos / Channels',
          'description' => 'Choose default photo for adult videos / channels on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change adult default photo.]',
          'multiOptions' => $default_photos,
          'value' => $settings->getSetting('sesvideo.video.default.adult'),
      ));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for adult videos / channels on your website. Photo to be chosen for adult videos / channels on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the adult videos/channel on your website. Please upload the Photo to be chosen for adult videos/channel on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'sesvideo_video_default_adult', array(
          'label' => 'Default Adult Photo for Videos/Channels',
          'description' => $description,
      ));
    }
    $this->sesvideo_video_default_adult->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Text', 'video_ffmpeg_path', array(
          'label' => 'Path to FFMPEG',
          'description' => 'Please enter the full path to your FFMPEG installation. (Environment variables are not present)',
          'value' => $settings->getSetting('video.ffmpeg.path', ''),
      ));

      $this->addElement('Select', 'sesvideo_taboptions', array(
          'label' => 'Menu Items Count',
          'description' => 'How many menu items do you want to show in the main navigation menu of this plugin?',
          'multiOptions' => array(
              0 => 0,
              1 => 1,
              2 => 2,
              3 => 3,
              4 => 4,
              5 => 5,
              6 => 6,
              7 => 7,
              8 => 8,
              9 => 9,
          ),
          'value' => $settings->getSetting('sesvideo.taboptions', 6),
      ));
      $this->addElement('Text', 'video_jobs', array(
          'label' => 'Encoding Jobs',
          'description' => 'How many jobs do you want to allow to run at the same time?',
          'value' => $settings->getSetting('video.jobs', 2),
      ));

    $this->addElement('Radio', "sesvideo_allowfavv", array(
        'label' => 'Allow to Favorite Videos',
        'description' => "Do you want to allow members to add Videos on your website to Favorites?",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sesvideo.allowfavv', 1),
    ));

    $this->addElement('Radio', "sesvideo_allowfavc", array(
        'label' => 'Allow to Favorite Channels',
        'description' => "Do you want to allow members to add Channels on your website to Favorites?",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sesvideo.allowfavc', 1),
    ));

    $this->addElement('Textarea', 'video_iframely_disallow', array(
      'label' => 'Disallow these Sources',
      'description' => 'Enter the domains of the sites (separated by commas) that you do not want to allow for Video'
        . ' Source. Example: example1.com, example2.com. Note: We\'ve integrated Iframely API with this module. By '
        . 'default URLs that return a \'player\' are allowed, such as music based websites like Soundcloud. You can '
        . 'use this setting to control which sites should not be allowed in this section.',
      'filters' => array(
        'StringTrim',
      ),
      'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('video.iframely.disallow', ''),
    ));
    $this->video_iframely_disallow->getDecorator('Description')->setOption('escape', false);
    
      // Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Save Changes',
          'type' => 'submit',
          'ignore' => true
      ));
    } else {
      //Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Activate This Plugin',
          'type' => 'submit',
          'ignore' => true
      ));
    }
  }

}
