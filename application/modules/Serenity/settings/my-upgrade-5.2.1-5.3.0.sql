/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Serenity
 * @copyright  Copyright 2006-2022 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: my-upgrade-5.2.1-5.3.0.sql 2022-06-20
 */

UPDATE `engine4_serenity_customthemes` SET `value`='#CCCCCC' WHERE `column_key` = 'serenity_font_color_light' AND `theme_id`=3;