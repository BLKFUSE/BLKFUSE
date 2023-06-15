<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
return array(
    array(
        'title' => 'SNS - Advanced Contests - Jury Members',
        'description' => 'This widget displays the Jury Members added by the contest owners for voting on their contests. This widget should be placed on the "Contest - Contest View Page"',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontestjurymember.jury-members',
        'defaultParams' => array(
            'title' => '',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Enter Title truncation limit.',
                        'value' => 45,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels).',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels).',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of members to show)',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
            )
        ),
    ),
        )
?>