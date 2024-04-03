<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EntrySearch.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_EntrySearch extends Engine_Form {

  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $this->setAttribs(array('id' => 'entry_filter_form', 'class' => 'global_form_box'))->setMethod('GET');

    $this->addElement('Select', 'sort', array(
        'label' => 'Sort by:',
        'multiOptions' => array(),
        'onchange' => 'searchData()',
    ));
  }

}
