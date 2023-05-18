<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: SearchAjax.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_SearchAjax extends Engine_Form {

  public function init() {

    $this->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
    ));
    parent::init();

    $this->addElement('Select', 'type', array(
        'multiOptions' => array(
            'video' => 'Videos',
            'sesvideo_chanel' => 'Channels',
            'sesvideo_artist' => 'Artists',
            'sesvideo_playlist' => 'Playlists',
        ),
        'onchange' => 'typevalue(this.value)',
        'value' => 'video',
    ));

    $this->addElement('Text', 'search', array(
        'placeholder' => 'Search'
    ));
    $this->addElement('Button', 'submit', array(
        'label' => 'Search',
          'type' => 'submit',
        'decorators' => array('ViewHelper')
    ));
  }

}
