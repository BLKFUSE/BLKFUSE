<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sessociallogin
 * @package    Sessociallogin
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Photo.php 2017-07-04 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sessociallogin_Plugin_Signup_Photo extends Core_Plugin_FormSequence_Abstract {

    protected $_name = 'account';
    protected $_formClass = 'User_Form_Signup_Photo';
    protected $_script = array('signup/form/photo.tpl', 'user');
    protected $_adminFormClass = 'User_Form_Admin_Signup_Photo';
    protected $_adminScript = array('admin-signup/photo.tpl', 'user');
    protected $_skip;
    protected $_coordinates;

    public function isActive() {
        return parent::isActive();
    }

    public function onView() {

        if (!empty($_SESSION['facebook_signup'])) {
            try {
                $facebookTable = Engine_Api::_()->getDbtable('facebook', 'sessociallogin');
                $facebook = $facebookTable->getApi();
                $settings = Engine_Api::_()->getDbtable('settings', 'core');
                if ($facebook) {
                    // Fetch image from Facebook
                    $apiInfo = $facebook->api('/me'); // @TODO: Temporarily store FB user data session
                    $user_id = $apiInfo['id'];
                    $photo_url = "https://graph.facebook.com/"
                            . $user_id
                            . "/picture?type=large"
                    ;

                    $this->_fetchImage($photo_url);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }

        if (!empty($_SESSION['linkedin_signup'])) {
            try {
                $linkedinTable = Engine_Api::_()->getDbtable('linkedin', 'sessociallogin');
                $linkedin = $linkedinTable->getApi();
                $settings = Engine_Api::_()->getDbtable('settings', 'core');
                if ($linkedin && $linkedinTable->isConnected()) {
                    // Fetch image from linkedin
                    //$apiInfo = $linkedin->api('/me'); // @TODO: Temporarily store FB user data session
                    $user_id = $_SESSION['signup_fields']['id'];
                    $this->_fetchImage($_SESSION['signup_fields']['photo']);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }
        if (!empty($_SESSION['telegram_signup'])) {
            try {
                $linkedinTable = Engine_Api::_()->getDbtable('telegram', 'sessociallogin');
                $settings = Engine_Api::_()->getDbtable('settings', 'core');
                // Fetch image from linkedin
                $this->_fetchImage($_SESSION['signup_fields']['photo']);
                
            } catch (Exception $e) {
                // Silence?
            }
        }
        if (!empty($_SESSION['flickr_signup'])) {
            try {
                $flickrTable = Engine_Api::_()->getDbtable('flickr', 'sessociallogin');
                $settings = Engine_Api::_()->getDbtable('settings', 'core');
                if ($flickrTable->isConnected()) {
                    // Fetch image from linkedin
                    //$apiInfo = $linkedin->api('/me'); // @TODO: Temporarily store FB user data session
                    $user_id = $_SESSION['signup_fields']['id'];
                    $this->_fetchImage($_SESSION['signup_fields']['photo']);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }
         if (!empty($_SESSION['vk_signup'])) {
            try {
                $vkTable = Engine_Api::_()->getDbtable('vk', 'sessociallogin');
                $settings = Engine_Api::_()->getDbtable('settings', 'core');
                if ($vkTable->isConnected()) {
                    // Fetch image from linkedin
                    //$apiInfo = $linkedin->api('/me'); // @TODO: Temporarily store FB user data session
                    $user_id = $_SESSION['signup_fields']['id'];
                    $this->_fetchImage($_SESSION['signup_fields']['photo']);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }
        if (!empty($_SESSION['instagram_signup'])) {
            try {
                $instagramTable = Engine_Api::_()->getDbtable('instagram', 'sessociallogin');
                if ($instagramTable->getApi() && $instagramTable->isConnected()) {
                    $this->_fetchImage($_SESSION['sessociallogin_instagram']['inphoto_url']);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }

        if (!empty($_SESSION['yahoo_signup'])) {
            try {
                if (1) {
                    $this->_fetchImage($_SESSION['signup_fields']['photo']);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }

        if (!empty($_SESSION['pinterest_signup'])) {
            try {
                if (1) {
                    $this->_fetchImage($_SESSION['signup_fields']['photo']);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }

        if (!empty($_SESSION['google_signup'])) {
            try {
                $googleTable = Engine_Api::_()->getDbtable('google', 'sessociallogin');
                if ($googleTable->isConnected()) {
                    $photo_url = $_SESSION['signup_fields']['photo'];
                    $this->_fetchImage($photo_url);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }

        // Attempt to preload information
        if (!empty($_SESSION['twitter_signup'])) {
            try {
                $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
                //$twitter = $twitterTable->getApi();
                $settings = Engine_Api::_()->getDbtable('settings', 'core');
                if ($settings->core_twitter_enable) {
                    //$accountInfo = $twitter->account->verify_credentials();
                    $photo_url = $_SESSION['signup_fields']['photo'];
                    $this->_fetchImage($photo_url);
                }
            } catch (Exception $e) {
                // Silence?
            }
        }

        // Attempt to preload information
        if (!empty($_SESSION['janrain_signup']) &&
                !empty($_SESSION['janrain_signup_info']) &&
                !empty($_SESSION['janrain_signup_info']['photo'])) {
            try {
                $this->_fetchImage($_SESSION['janrain_signup_info']['photo']);
            } catch (Exception $e) {
                // Silence?
            }
        }
    }

    public function onSubmit(Zend_Controller_Request_Abstract $request) {
        // Form was valid
        $skip = $request->getParam("skip");
        $photoIsRequired = Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.photo');
        $uploadPhoto = $request->getParam("uploadPhoto");
        $finishForm = $request->getParam("nextStep");
        $this->getSession()->coordinates = $request->getParam("coordinates");
        // do this if the form value for "skip" was not set
        // if it is set, $this->setActive(false); $this->onsubmisvalue and return true.

        if ($this->getForm()->isValid($request->getPost()) &&
                $skip != "skipForm" &&
                $uploadPhoto == true &&
                $finishForm != "finish") {
            $this->getSession()->data = $this->getForm()->getValues();
            $this->getSession()->Filedata = $this->getForm()->Filedata->getFileInfo();

            $this->_resizeImages($this->getForm()->Filedata->getFileName());

            $this->getSession()->active = true;
            $this->onSubmitNotIsValid();
            return false;
        } else if ($skip != "skipForm" &&
                $finishForm == "finish" &&
                isset($_SESSION['TemporaryProfileImg'])) {
            $this->setActive(false);
            $this->onSubmitIsValid();
            return true;
        } else if ($photoIsRequired && $skip == "skipForm") {
            $this->getSession()->active = true;
            $this->onSubmitNotIsValid();
            return false;
        } else if ($skip == "skipForm" ||
                (!isset($_SESSION['TemporaryProfileImg']) && $finishForm == "finish")) {
            $this->setActive(false);
            $this->onSubmitIsValid();
            $this->getSession()->skip = true;
            $this->_skip = true;
            return true;
        }

        // Form was not valid
        else {
            $this->getSession()->active = true;
            $this->onSubmitNotIsValid();
            return false;
        }
    }

    public function onProcess() {
        // In this case, the step was placed before the account step.
        // Register a hook to this method for onUserCreateAfter
        if (!$this->_registry->user) {
            // Register temporary hook
            Engine_Hooks_Dispatcher::getInstance()->addEvent('onUserCreateAfter', array(
                'callback' => array($this, 'onProcess'),
            ));
            return;
        }
        $user = $this->_registry->user;

        // Remove old key
        unset($_SESSION['TemporaryProfileImg']);
        unset($_SESSION['TemporaryProfileImgProfile']);
        unset($_SESSION['TemporaryProfileImgSquare']);

        // Process
        $data = $this->getSession()->data;

        $params = array(
            'parent_type' => 'user',
            'parent_id' => $user->user_id
        );

        if (!$this->_skip &&
                !$this->getSession()->skip &&
                !empty($this->getSession()->tmp_file_id)) {
            // Save
            $storage = Engine_Api::_()->getItemTable('storage_file');

            // Update info
            $iMain = $storage->getFile($this->getSession()->tmp_file_id);
            $iMain->setFromArray($params);
            $iMain->save();
            $iMain->updatePath();

            $iProfile = $storage->getFile($this->getSession()->tmp_file_id, 'thumb.profile');
            $iProfile->setFromArray($params);
            $iProfile->save();
            $iProfile->updatePath();

            $iNormal = $storage->getFile($this->getSession()->tmp_file_id, 'thumb.normal');
            $iNormal->setFromArray($params);
            $iNormal->save();
            $iNormal->updatePath();

            $iSquare = $storage->getFile($this->getSession()->tmp_file_id, 'thumb.icon');
            $iSquare->setFromArray($params);
            $iSquare->save();
            $iSquare->updatePath();

            // Update row
            $user->photo_id = $iMain->file_id;
            $user->save();

            $this->_resizeThumbnail($user);
        }
    }

    protected function _resizeImages($file) {
        $name = basename($file);
        $path = dirname($file);

        // Resize image (main)
        $iMainPath = $path . '/m_' . $name;
        $image = Engine_Image::factory();
        $image->open($file)
                ->autoRotate()
                ->resize(720, 720)
                ->write($iMainPath)
                ->destroy();

        // Resize image (profile)
        $iProfilePath = $path . '/p_' . $name;
        $image = Engine_Image::factory();
        $image->open($file)
                ->autoRotate()
                ->resize(320, 640)
                ->write($iProfilePath)
                ->destroy();

        // Resize image (icon.normal)
        $iNormalPath = $path . '/n_' . $name;
        $image = Engine_Image::factory();
        $image->open($file)
                ->autoRotate()
                ->resize(140, 160)
                ->write($iNormalPath)
                ->destroy();

        // Resize image (icon.square)
        $iSquarePath = $path . '/s_' . $name;
        $image = Engine_Image::factory();
        $image->open($file)
                ->autoRotate();
        $size = min($image->height, $image->width);
        $x = ($image->width - $size) / 2;
        $y = ($image->height - $size) / 2;
        $image->resample($x, $y, $size, $size, 48, 48)
                ->write($iSquarePath)
                ->destroy();

        // Cloud compatibility, put into storage system as temporary files
        $storage = Engine_Api::_()->getItemTable('storage_file');

        // Save/load from session
        if (empty($this->getSession()->tmp_file_id)) {
            // Save
            $iMain = $storage->createTemporaryFile($iMainPath);
            $iProfile = $storage->createTemporaryFile($iProfilePath);
            $iNormal = $storage->createTemporaryFile($iNormalPath);
            $iSquare = $storage->createTemporaryFile($iSquarePath);

            $iMain->bridge($iProfile, 'thumb.profile');
            $iMain->bridge($iNormal, 'thumb.normal');
            $iMain->bridge($iSquare, 'thumb.icon');

            $this->getSession()->tmp_file_id = $iMain->file_id;
        } else {
            // Overwrite
            $iMain = $storage->getFile($this->getSession()->tmp_file_id);
            $iMain->store($iMainPath);

            $iProfile = $storage->getFile($this->getSession()->tmp_file_id, 'thumb.profile');
            $iProfile->store($iProfilePath);

            $iNormal = $storage->getFile($this->getSession()->tmp_file_id, 'thumb.normal');
            $iNormal->store($iNormalPath);

            $iSquare = $storage->getFile($this->getSession()->tmp_file_id, 'thumb.icon');
            $iSquare->store($iSquarePath);
        }

        // Save path to session?
        $_SESSION['TemporaryProfileImg'] = $iMain->map();
        $_SESSION['TemporaryProfileImgProfile'] = $iProfile->map();
        $_SESSION['TemporaryProfileImgSquare'] = $iSquare->map();

        // Remove temp files
        @unlink($path . '/p_' . $name);
        @unlink($path . '/m_' . $name);
        @unlink($path . '/n_' . $name);
        @unlink($path . '/s_' . $name);
    }

    protected function _resizeThumbnail($user) {
        $storage = Engine_Api::_()->storage();

        $iProfile = $storage->get($user->photo_id, 'thumb.profile');
        $iSquare = $storage->get($user->photo_id, 'thumb.icon');

        // Read into tmp file
        $pName = $iProfile->getStorageService()->temporary($iProfile);
        $iName = dirname($pName) . '/nis_' . basename($pName);

        if (!empty($this->getSession()->coordinates)) {
            list($x, $y, $w, $h) = explode(':', $this->getSession()->coordinates);

            $image = Engine_Image::factory();
            $image->open($pName)
                    ->autoRotate()
                    ->resample((int) $x, (int) $y, (int) $w, (int) $h, 48, 48)
                    ->write($iName)
                    ->destroy();

            $iSquare->store($iName);
            @unlink($iName);
        } else {
            $image = Engine_Image::factory();
            $image->open($pName);
            $size = min($image->height, $image->width);
            $x = ($image->width - $size) / 2;
            $y = ($image->height - $size) / 2;
            $w = $h = $size;
        }
        $image = Engine_Image::factory();
        $image->open($pName)
                ->autoRotate()
                ->resample((int) $x, (int) $y, (int) $w, (int) $h, 400, 400)
                ->write($pName)
                ->destroy();

        $iProfile->store($pName);
        @unlink($pName);
    }

    protected function _fetchImage($photo_url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $photo_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        $tmpfile = APPLICATION_PATH_TMP . DS . md5($photo_url) . '.jpg';
        @file_put_contents($tmpfile, $data);
        $this->_resizeImages($tmpfile);
    }

    public function onAdminProcess($form) {
        /*$step_table = Engine_Api::_()->getDbtable('signup', 'user');
        $step_row = $step_table->fetchRow($step_table->select()->where('class = ?', 'User_Plugin_Signup_Photo'));
        $step_row->enable = $form->getValue('enable');
        $step_row->save();*/

        $step_table = Engine_Api::_()->getDbtable('signup', 'user');
        $step_row = $step_table->fetchRow($step_table->select()->where('class = ?', 'Sessociallogin_Plugin_Signup_Photo'));
        $step_row->enable = $form->getValue('enable');
        $step_row->save();

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $values = $form->getValues();
        $settings->user_signup_photo = $values['require_photo'];

        $form->addNotice('Your changes have been saved.');
    }

}
