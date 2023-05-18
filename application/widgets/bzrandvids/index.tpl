<?php
/**
 * SocialEngine
 *
 * @category   Application_Widget
 * @package    BryZar Random Videos
 * @copyright  Copyright 2018 - 2019 BryZar
 * @license    https://www.bryzar.com/terms
 * @author     data66, BryZar/ScriptTechs
 * 
 */
 ?>
<style type="text/css">
div.generic_layout_container.layout_bzrandvids {
    padding-bottom: 15px;
}
.bzvideoh {
    width: 100%;
}    
.bzvideoh ul {
    text-align: center;
    }
    .bzvideoh ul li{
        display: inline-block;
        width: 32%;
        font-size: 1.2em;   
        font-family: inherit;
        border: 1px;
        border-style: outset;
        padding: 4px;
        overflow: hidden;
        float: left;
        height: 145px;
        vertical-align: text-top;
    }
    
    .bzvideoh ::before {
        padding-right: 2px;
    }
    .bzvideoh .title {
        text-overflow: ellipsis;
        font-size: .8em;
        padding-right: 2px;
        font-weight: bold;
    }
    .bzvideoh .stats, .owner, .description {
        font-size: .7em;
    }
    .bzvideoh .description {
        float: left;
        text-overflow: ellipsis;
        overflow: hidden;
        clear: both;
        
        padding: 1px 0px 4px 0px
    }
    .bzvideoh .bzphoto{
    float: left;
    padding-right: 2px;
    }
</style>
<div class="generic_list_wrapper">
    <?php if( $this->bzAlign == '1'): ?>
        <ul class="generic_list_widget generic_list_widget_large_photo">
            <?php foreach( $this->paginator as $item ): ?>
                <li>             
                    <div class="photo">
                        <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile'), array('class' => 'thumb')) ?>
                    </div> 
                    <div class="info">    
                        <div class="title">
                            <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>                   
                        </div>
                        <div class="stats">
                            <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>  
                            <?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
                        </div>                  
                    <div class="owner">
                        <?php
                        $owner = $item->getOwner();
                        echo $this->translate('By: %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));                               
                        ?>             
                    </div>   
                    <?php                
                    $desc = trim($this->string()->truncate($this->string()->stripTags($item->description), $this->bzDesVlength));
                    if( !empty($desc) ): ?>                   
                    <div class="description">                                               
                        <?php echo $desc ?>                      
                    </div>
                    <?php endif; ?>
                    <div class="owner">
                        <?php               
                        echo $this->translate('Posted %1$s', $this->timestamp($item->creation_date));                        
                        ?>             
                    </div>      
                   </div>
                </li>
            <?php endforeach; ?>
        </ul>    
    <?php elseif( $this->bzAlign == '0'): ?>
    <div class="bzvideoh">
    <ul>
            <?php foreach( $this->paginator as $item ): ?>
                <li>             
                    <div class="bzphoto">
                        <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
                    </div> 
                    <div class="title">
                            <?php echo $this->htmlLink($item->getHref(), $this->string()->truncate($item->getTitle(), 30)) ?>                  
                        </div>
                    <div class="info">    
                        
                        <div class="stats">
                            <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>  
                            <?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)) ?>
                        </div>                  
                        <div class="owner">
                            <?php
                            $owner = $item->getOwner();
                            echo $this->translate('By: %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));                               
                            ?>             
                        </div>   
  
                   </div>
                    <div> 
                        <?php                
                        $desc = trim($this->string()->truncate($this->string()->stripTags($item->description), $this->bzDesVlength));
                        if( !empty($desc) ): ?>                   
                        <div class="description">                                               
                            <?php echo $desc ?>                      
                        </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>   
    </div>
    <?php endif; ?>
    <?php if( $this->paginator->getPages()->pageCount > 1 ): ?>
    <a class="viewlink" href="<?php echo $this->url(array('action' => 'browse'), 'video_general', true); ?>"><?php echo $this->translate('View Videos') ?><i class="fa-angle-double-right fa"></i></a>
    <?php endif; ?>
</div>
