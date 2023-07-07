<?php
/**
* @package   controles_techniques
* @subpackage controles_techniques
* @author    your name
* @copyright 2011 your name
* @link      http://www.yourwebsite.undefined
* @license    All rights reserved
*/

class menuZone extends jZone {
    protected $_tplname='menu';

    protected function _prepareTpl(){
        $this->_tpl->assign('menu','menu');
    }
}
