<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Create.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Form_Create extends Engine_Form
{
    public $_error = array();

    
    public function init()
    {
        $this->setTitle('Create New Game')
            ->setDescription('')
            ->setAttrib('name', 'game_create');
        $user = Engine_Api::_()->user()->getViewer();
        $userLevel = Engine_Api::_()->user()->getViewer()->level_id;

        $this->addElement('Text', 'title', array(
            'label' => 'Title',
            'allowEmpty' => false,
            'required' => true,
            'maxlength' => '255',
            'filters' => array(
                new Engine_Filter_Censor(),
                'StripTags',
                new Engine_Filter_StringLength(array('max' => '255'))
            ),
            'autofocus' => 'autofocus',
        ));
        
    //UPLOAD PHOTO URL
    $editorOptions = array(
        'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
    );

    $this->addElement('TinyMce', 'description', array(
        'label' => 'Description',
        'allowEmpty' => true,
        'required' => false,
        'editorOptions' => $editorOptions,
    ));

    $this->addElement('Textarea', 'url', array(
        'label' => 'Game URL',
        'allowEmpty' => false,
        'required' => true
    ));
        
        $categories = Engine_Api::_()->getDbtable('categories', 'egames')->getCategoriesAssoc(array('member_levels' => 1));
      $game_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('game_id', 0);
      if($game_id){
        $game = Engine_Api::_()->getItem("egames_game",$game_id);
      }
      if(!empty($categories)) {
        $required = false;
        $allowEmpty = true;
        $categories = array(''=>'')+$categories;
        $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $categories,
          'allowEmpty' => $allowEmpty,
          'required' => $required,
          'onchange' => "showSubCategory(this.value);",
        ));

        //getModuleSubcategory
        $subCategories = array('0'=>'');
        if($game && $game->category_id){
          $subCategories = Engine_Api::_()->getDbtable('categories', 'egames')->getModuleSubcategory(array('member_levels' => 1,'category_id'=>$game->category_id,'return'=>1));
          if($subCategories)
            $subCategories = array(''=>'')+$subCategories;
          else
            $subCategories = array('0'=>'');
        }

        //Add Element: 2nd-level Category
        $this->addElement('Select', 'subcat_id', array(
            'label' => "2nd-level Category",
            'allowEmpty' => true,
            'required' => false,
            'multiOptions' => $subCategories,
            'registerInArrayValidator' => false,
            'onchange' => "showSubSubCategory(this.value);"
        ));


        //getModuleSubsubcategory
        $subsubCategories = array('0'=>'');
        if($game && $game->subcat_id){
          $subsubCategories = Engine_Api::_()->getDbtable('categories', 'egames')->getModuleSubsubcategory(array('member_levels' => 1,'category_id'=>$game->subcat_id,'return'=>1));
          if($subsubCategories)
            $subsubCategories = array(''=>'')+$subsubCategories;
          else
            $subsubCategories = array('0'=>'');
        }
        //Add Element: Sub Sub Category
        $this->addElement('Select', 'subsubcat_id', array(
            'label' => "3rd-level Category",
            'allowEmpty' => true,
            'registerInArrayValidator' => false,
            'required' => false,
            'multiOptions' => $subsubCategories,
            'onchange' => ''
        ));
      }

      $this->addElement('File', 'photo', array(
        'label' => 'Main Photo',
        'allowEmpty' => empty($game_id) ? false : true,
        'required' => empty($game_id) ?  true : false,
        'description' => '',
      ));
      $this->photo->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

        $this->addElement('Checkbox', 'search', array(
            'label' => 'Show this game entry in search results',
            'value' => 1,
            'disableTranslator' => true
        ));

        // Element: auth_view
        $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('egames_game', $user, 'auth_view');
        // Element: auth_comment
        $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('egames_game', $user, 'auth_comment');

            $availableLabels = array(
                'everyone'            => 'Everyone',
                'registered'          => 'All Registered Members',
                'owner_network'       => 'Friends and Networks',
                'owner_member_member' => 'Friends of Friends',
                'owner_member'        => 'Friends Only',
                'owner'               => 'Just Me'
            );
            $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
            $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

       
        if( !empty($viewOptions) && engine_count($viewOptions) >= 1 ) {
            // Make a hidden field
            if( engine_count($viewOptions) == 1 ) {
                $this->addElement('hidden', 'auth_view', array( 'order' => 101, 'value' => key($viewOptions)));
                // Make select box
            } else {
                $this->addElement('Select', 'auth_view', array(
                    'label' => 'Privacy',
                    'description' => 'Who may see this game entry?',
                    'multiOptions' => $viewOptions,
                    'value' => key($viewOptions),
                ));
                $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
            }
        }

        if( !empty($commentOptions) && engine_count($commentOptions) >= 1 ) {
            // Make a hidden field
            if( engine_count($commentOptions) == 1 ) {
                $this->addElement('hidden', 'auth_comment', array('order' => 102, 'value' => key($commentOptions)));
                // Make select box
            } else {
                $this->addElement('Select', 'auth_comment', array(
                    'label' => 'Comment Privacy',
                    'description' => 'Who may post comments on this game entry?',
                    'multiOptions' => $commentOptions,
                    'value' => key($commentOptions),
                ));
                $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
            }
        }

        // Element: submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Create Game',
            'type' => 'submit',
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        $this->addElement('Cancel', 'cancel', array(
            'label' => 'cancel',
            'link' => true,
            'href' =>  Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'browse'), 'egames_general', true),
            'prependText' => ' or ',
            'decorators' => array(
                    'ViewHelper',
            ),
        ));
        $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    }
}
