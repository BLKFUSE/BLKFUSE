<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Usergateways.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjoinfees_Model_DbTable_Usergateways extends Engine_Db_Table
{
  protected $_rowClass = 'Sescontestjoinfees_Model_Usergateway';
 //protected $_name = 'payment_gateways';
  protected $_serializedColumns = array('config');

  protected $_cryptedColumns = array('config');

  static private $_cryptKey;

  public function getEnabledGatewayCount()
  {
    return $this->select()
      ->from($this, new Zend_Db_Expr('COUNT(*)'))
      ->where('enabled = ?', 1)
      ->query()
      ->fetchColumn()
      ;
  }

	public function getUserGateway($params = array()){
			$select = $this->select()->from($this->info('name'))->where('contest_id =?',$params['contest_id']);
			if(!isset($params['enabled']))
				$select->where('enabled =?','1');
			if(isset($params['user_id']))
				$select->where('user_id =?',$params['user_id']);
      if(isset($params['gateway_type']))
				$select->where('gateway_type =?',$params['gateway_type']);
      if(isset($params['fetchAll']))
          return $this->fetchAll($select);
			return $this->fetchRow($select);
	}

  // Inline encryption/decryption

    public function insert(array $data)
    {
        // Serialize
        $data = $this->_serializeColumns($data);

        // Encrypt each column
        foreach ($this->_cryptedColumns as $col) {
            if (!empty($data[$col])) {
                $data[$col] = self::_encrypt($data[$col]);
            }
        }

        return parent::insert($data);
    }

 public function update(array $data, $where)
    {
        // Serialize
        $data = $this->_serializeColumns($data);

        // Encrypt each column
        foreach ($this->_cryptedColumns as $col) {
            if (!empty($data[$col])) {
                $data[$col] = self::_encrypt($data[$col]);
            }
        }

        return parent::update($data, $where);
    }

    protected function _fetch(Zend_Db_Table_Select $select)
    {
        $rows = parent::_fetch($select);
        foreach ($rows as $index => $data) {
            // Decrypt each column
            foreach ($this->_cryptedColumns as $col) {
                if (!empty($rows[$index][$col])) {
                    $rows[$index][$col] = self::_decrypt($rows[$index][$col]);
                }
            }
            // Unserialize
            $rows[$index] = $this->_unserializeColumns($rows[$index]);
        }

        return $rows;
    }



    // Crypt Utility

    private static function _encrypt($data)
    {
        if (!extension_loaded('mcrypt')) {
            return $data;
        }

        $key = self::_getCryptKey();

        if (version_compare(phpversion(), '7.1', '>=')) {
            return $data;
        }

        $cryptData = mcrypt_encrypt(MCRYPT_DES, $key, $data, MCRYPT_MODE_ECB);

        return $cryptData;
    }

    private static function _decrypt($data)
    {
        if (!extension_loaded('mcrypt')) {
            return $data;
        }

        $key = self::_getCryptKey();

        if (version_compare(phpversion(), '7.1', '>=') && is_string($data) && substr($data, -1) != '=') {
            return $data;
        }

        $cryptData = mcrypt_decrypt(MCRYPT_DES, $key, $data, MCRYPT_MODE_ECB);
        $cryptData = rtrim($cryptData, "\0");

        return $cryptData;
    }

    private static function _getCryptKey()
    {
        if (null === self::$_cryptKey) {
            $key = Engine_Api::_()->getApi('settings', 'core')->core_secret
                . '^'
                . Engine_Api::_()->getApi('settings', 'core')->payment_secret;
            self::$_cryptKey  = substr(md5($key, true), 0, 8);
        }

        return self::$_cryptKey;
    }
}
