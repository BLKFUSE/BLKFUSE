<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesuserimport_Api_Core extends Core_Api_Abstract {

    public function defaultLevel() {

        $table = Engine_Api::_()->getDbTable('levels', 'authorization');
        $select = $table->select()
                            ->from($table->info('name'),array('level_id'))
                            ->where('flag like ?','default');
        return $table->fetchRow($select);
    }


    public function saveUser($values, $defaultPhoto = null, $coverPhoto = null, $form = array()) {
        $email = @$values['email'];
        $values = array_merge($values, $_POST);
        $values['email'] = $email;
        $userTable = Engine_Api::_()->getDbTable('users', 'user');

        $settings = Engine_Api::_()->getApi( 'settings', 'core' );
        $userPermissionType = array('everyone', 'member', 'network', 'registered');

        if(!empty($values['password']))
            $values['password'] = $values['password'];
        else
            $values['password'] = '123456';

        if(!empty($values['language'])) {
            $values['locale'] = $values['language'];
            $values['language'] = $values['language'];
        } else {
            $values['locale'] = $_POST['language'];
            $values['language'] = $_POST['language'];
        }

        $values['creation_date'] = date('Y-m-d H:i:s');
        $values['creation_ip'] = $_SERVER['REMOTE_ADDR'];
        $values['modified_date'] = date('Y-m-d H:i:s');

        if(!empty($values['level_id'])) {
            $level_id = $values['level_id'];
            $values['level_id'] = $values['level_id'];
        } else {
            $values['level_id'] = $_POST['level_id'];
            $level_id = $_POST['level_id'];
        }

        if(!empty($values['approved'])) {
            $values['approved'] = $values['approved'];
            $approved = $values['approved'];
        } else if($_POST['approved']) {
            $values['approved'] = $_POST['approved'];
            $approved = $values['approved'];
        } else {
            $values['approved'] = 0;
            $approved = 0;
        }

        if(!empty($values['enabled'])) {
            $values['enabled'] = $values['enabled'];
            $enabled = $values['enabled'];
        } else if($_POST['enabled']) {
            $values['enabled'] = $_POST['enabled'];
            $enabled = $values['enabled'];
        } else {
            $values['enabled'] = 0;
            $enabled = 0;
        }

        $timezone = $values['timezone'];

        if(!empty($values['verified'])) {
            $values['verified'] = $values['verified'];
            $verified = $values['verified'];
        } else if($_POST['verified']) {
            $values['verified'] = $_POST['verified'];
            $verified = $values['verified'];
        } else {
            $values['verified'] = 0;
            $verified = 0;
        }

        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesusercoverphoto')) {
            $values['cover'] = '0';
        }

        $user = $userTable->createRow();
        $user->setFromArray($values);
        $user->save();

        $user_id = $user->getIdentity();
        $dbInsert = Zend_Db_Table_Abstract::getDefaultAdapter();
        foreach($userPermissionType as $type) {
            $dbInsert->query('INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES ("user", "'.$user_id.'", "comment", "'.$type.'", "0", "1", NULL);');
            $dbInsert->query('INSERT IGNORE INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES ("user", "'.$user_id.'", "view", "'.$type.'", "0", "1", NULL);');
        }

        if($form) {
            // Add fields
            $customfieldform = $form->getSubForm('fields');
            if($customfieldform){
                $customfieldform->setItem($user);
                $customfieldform->saveValues();
            }
        }

        if(!empty(@$values['fields'])) {
            $profleType = array_slice(@$values['fields'],0,1);
            $profile_type = array_shift($profleType);
        } else if($values['profile_types']) {
            $profile_type = @$values['profile_types'][0];
        } else if($values['profile_type']) {
            $profile_type = @$values['profile_type'];
        } else {
            $profile_type = 1;
        }

        if(!empty($profile_type)) {
            $dbInsert->query("INSERT IGNORE INTO `engine4_user_fields_values` (`item_id`, `field_id`, `index`, `value`, `privacy`) VALUES ('".$user->getIdentity()."', '1', 0, '".$profile_type."', NULL);");
        } else {
            $dbInsert->query("INSERT IGNORE INTO `engine4_user_fields_values` (`item_id`, `field_id`, `index`, `value`, `privacy`) VALUES ('".$user->getIdentity()."', '1', 0, '4', NULL);");
        }

        // Set new network
        $userNetworks = $values['network_id'];
        unset($values['network_id']);
        if($userNetworks == NULL) { $userNetworks = array(); }
        $joinIds = $userNetworks;
        foreach( $joinIds as $id ) {
            $network = Engine_Api::_()->getItem('network', $id);
            $network->membership()->addMember($user)
                ->setUserApproved($user)
                ->setResourceApproved($user);
        }

        if(!empty($values['sesuserimport_adsusertypes'])) {
            $emailIDs = explode('@', $values['email']);
            $emailIDs = explode('test', $emailIDs[0]);
            $userPhoto = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesuserimport' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "dummy_user_photos" . DIRECTORY_SEPARATOR . 'test' . $emailIDs[1].'.jpg';
            if (file_exists($userPhoto)) {
                $user->setPhoto($userPhoto);
            }
        }


        if(!empty($values['photo'])) {
            $user->setPhoto($defaultPhoto);
        }

        //For SE Cover Photo widget
        if(!empty($values['cover'])) {
            Engine_Api::_()->sesuserimport()->setCoverPhoto($coverPhoto, $user);
        }

        if(empty($values['first_name']) && empty($values['last_name'])) {
            //Display Name
            $firstNameFieldId = $this->getFieldId(array('first_name'), $profile_type);
            $lastNameFieldId = $this->getFieldId(array('last_name'), $profile_type);

            $first_name = $this->getprofileFieldValue(array('user_id' => $user_id, 'field_id' => $firstNameFieldId));
            $last_name = $this->getprofileFieldValue(array('user_id' => $user_id, 'field_id' => $lastNameFieldId));
        } else {

            $firstNameProfileId = $this->getFieldId(array('first_name'), $profile_type);
            $lastNameProfileId = $this->getFieldId(array('last_name'), $profile_type);
            $genderProfileId = $this->getFieldId(array('gender'), $profile_type);
            $birthdayProfileId = $this->getFieldId(array('birthdate'), $profile_type);

            $first_name = $values['first_name'];
            $last_name = $values['last_name'];
            $gender = $values['gender'];
            $birthday = $values['birthdate'];

            if(engine_in_array($gender, array('Male', 'Female'))) {
                $gender = $this->getOptionValue(array('field_id' => $genderProfileId, 'label' => $values['gender']));
            }

            $dbInsert->query("INSERT IGNORE INTO `engine4_user_fields_values` (`item_id`, `field_id`, `index`, `value`, `privacy`) VALUES
            ('".$user_id."', '1', 0, '".$profile_type."', NULL),
            ('".$user_id."', '".$firstNameProfileId."', 0, '".$first_name."', NULL),
            ('".$user_id."', '".$lastNameProfileId."', 0, '".$last_name."', NULL),
            ('".$user_id."', '".$genderProfileId."', 0, '".$gender."', NULL),
            ('".$user_id."', '".$birthdayProfileId."', 0, '".$birthday."', NULL);");

            $display_name = $first_name . ' '. $last_name;
            $userName = str_replace(' ', '', strtolower($display_name));
            $user->username = $userName.rand();
            $user->level_id = $_POST['level_id'];
            $user->save();
        }

        if(!empty($values['displayname'])) {
            $display_name = $values['displayname'];
        } else {
            $display_name = $first_name . ' '. $last_name;
        }

        $user->displayname = $display_name;
        $user->approved = $approved;
        $user->enabled = $enabled;
        $user->verified = $verified;
        $user->level_id = $level_id;
        $user->timezone = $timezone;
        $user->save();

        $dbInsert->query("INSERT IGNORE INTO `engine4_user_fields_search` (`item_id`, `profile_type`, `first_name`, `last_name`) VALUES ('".$user_id."', '".$profile_type."', '".$first_name."', '".$last_name."');");

        $dbInsert->query("INSERT IGNORE INTO `engine4_core_search` (`type`, `id`, `title`) VALUES ('user', '".$user_id."', '".$display_name."');");
    }

    public function getOptionValue($params = array()) {

        $optionsTable = Engine_Api::_()->fields()->getTable('user', 'options');
        $optionsTableName = $optionsTable->info('name');

        return $optionsTable->select()
                ->from($optionsTableName, array('option_id'))
                ->where($optionsTableName . '.field_id = ?', $params['field_id'])
                ->where($optionsTableName . '.label = ?', $params['label'])
                ->query()
                ->fetchColumn();
    }

    public function getFieldId($typeField = array(), $profile_type) {

        $metaTable = Engine_Api::_()->fields()->getTable('user', 'meta');
        $metaTableName = $metaTable->info('name');

        $mapsTable = Engine_Api::_()->fields()->getTable('user', 'maps');
        $mapsTableName = $mapsTable->info('name');

        return $metaTable->select()
                ->setIntegrityCheck(false)
                ->from($metaTableName, array('field_id'))
                ->joinLeft($mapsTableName, "$metaTableName.field_id = $mapsTableName.child_id", null)
                ->where($mapsTableName . '.option_id = ?', $profile_type)
                ->where($metaTableName . '.display = ?', '1')
                ->where($metaTableName . '.type IN (?)', (array) $typeField)
                ->query()
                ->fetchColumn();
    }

    public function isEmailExist($email) {

		  $userTable = Engine_Api::_()->getDbTable('users', 'user');
		  $userTableName = $userTable->info('name');
		  return $userTable->select()
                        ->from($userTableName, array('user_id'))
                        ->where('email = ?', $email)
                        ->query()
                        ->fetchColumn();
    }


    public function isUserExist($username) {
		  $userTable = Engine_Api::_()->getDbTable('users', 'user');
		  $userTableName = $userTable->info('name');
		  return $userTable->select()
                        ->from($userTableName, array('user_id'))
                        ->where('username = ?', $username)
                        ->query()
                        ->fetchColumn();
    }

  	public function getprofileFieldValue($params = array()) {

		  $valuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
		  $valuesTableName = $valuesTable->info('name');
		  return $valuesTable->select()
            ->from($valuesTableName, array('value'))
            ->where($valuesTableName . '.item_id = ?', $params['user_id'])
            ->where($valuesTableName . '.field_id = ?', $params['field_id'])->query()
            ->fetchColumn();
    }

    public function setCoverPhoto($photo, $user, $level_id = null) {

        if ($photo instanceof Zend_Form_Element_File) {
            $file = $photo->getFileName();
            $fileName = $file;
        } else if ($photo instanceof Storage_Model_File) {
            $file = $photo->temporary();
            $fileName = $photo->name;
        } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
            $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
            $file = $tmpRow->temporary();
            $fileName = $tmpRow->name;
        } else if (is_array($photo) && !empty($photo['tmp_name'])) {
            $file = $photo['tmp_name'];
            $fileName = $photo['name'];
        } else if (is_string($photo) && file_exists($photo)) {
            $file = $photo;
            $fileName = $photo;
        } else {
            throw new User_Model_Exception('invalid argument passed to setPhoto');
        }

        if (!$fileName) {
            $fileName = $file;
        }

        $name = basename($file);
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
        $coreSettings = Engine_Api::_()->getApi('settings', 'core');
        $mainHeight = $coreSettings->getSetting('main.photo.height', 1600);
        $mainWidth = $coreSettings->getSetting('main.photo.width', 1600);

        // Resize image (main)
        $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
            ->resize($mainWidth, $mainHeight)
            ->write($mainPath)
            ->destroy();

        $normalHeight = $coreSettings->getSetting('normal.photo.height', 375);
        $normalWidth = $coreSettings->getSetting('normal.photo.width', 375);
        // Resize image (normal)

        $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize($normalWidth, $normalHeight)
                ->write($normalPath)
                ->destroy();

        $coverPath = $path . DIRECTORY_SEPARATOR . $base . '_c.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
                ->resize(1500, 1500)
                ->write($coverPath)
                ->destroy();

        $params = array(
            'parent_type' => $user->getType(),
            'parent_id' => $user->getIdentity(),
            'user_id' => $user->getIdentity(),
            'name' => basename($fileName),
        );

        try {
            $iMain = $filesTable->createFile($mainPath, $params);
            $iIconNormal = $filesTable->createFile($normalPath, $params);
            $iMain->bridge($iIconNormal, 'thumb.normal');
            $iCover = $filesTable->createFile($coverPath, $params);
            $iMain->bridge($iCover, 'thumb.cover');
            if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesusercoverphoto')) {
                $user->cover = $iMain->file_id;
            } else {
                $user->coverphoto = $iMain->file_id;
                $user->coverphotoparams = '{"top":"0","left":"0"}';
            }
            $user->save();
        } catch (Exception $e) {
            @unlink($mainPath);
            @unlink($normalPath);
            @unlink($coverPath);
            if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE
            && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album')) {
                throw new Album_Model_Exception($e->getMessage(), $e->getCode());
            } else {
                throw $e;
            }
        }
        @unlink($mainPath);
        @unlink($normalPath);
        @unlink($coverPath);
        if (!empty($tmpRow)) {
            $tmpRow->delete();
        }
    }
}
