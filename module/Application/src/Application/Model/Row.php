<?php

namespace Application\Model;

use Zend\Db\Sql\Expression;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Row implements HydratorAwareInterface
{
    protected $_data, $_sm;
    protected $_table, $_hydrator, $_inputFilter;
    protected $_force_insert = false;
    protected $_modifiedFields = array();
    
    public function getSm() 
    {
        return $this->_sm;
    }
    
    public function setSm(ServiceManager $serviceManager) 
    {
        $this->_sm = $serviceManager;
        return $this;
    }
    
    public function setHydrator(HydratorInterface $hydrator)
    {
        return $this->_hydrator = $hydrator;
    }
    
    public function getHydrator()
    {
        if($this->_hydrator === null){
            $this->_hydrator = new HydratorObjectProperty();
        }
        return $this->_hydrator;
    }
    
    public function setForceInsert($mode) 
    {
         $this->_force_insert = $mode;
         return $this;
    }
    
    public function __sleep()
    {
        if($this->_data === null){
            throw new \Exception('empty row['.  get_class($this).']');
        }
        $data = array('_data');
        /*echo 'sleep '.get_class($this).':<br>';
        foreach($this->getTable()->getSleeps() AS $col){
            if(preg_match('/^\_(.*)\_rowset$/', $col, $match)){
                $method = 'get'.ucfirst($match[1]).'Rowset';
                if(method_exists($this, $method)){
                    $this->{$method}();
                    echo $col.'=>'.get_class($this->{$method}()).'<br>';
                    $data[] = $col;
                }
            }
            if(preg_match('/^\_(.*)\_row$/', $col, $match)){
                $method = 'get'.ucfirst($match[1]).'Row';
                if(method_exists($this, $method)){
                    $this->{$method}();
                    $data[] = $col;
                }
            }
        }*/
        return $data;
    }
    
    public function getModifiedFields()
    {
        return $this->_modifiedFields;
    }
    public function isModified($col)
    {
        return array_key_exists($col, $this->_modifiedFields);
    }
    
    public function exchangeArray($data)
    {
        $data = array_merge($this->getTable()->getDefaults(), $data);
        if($this->_data!== null){
            return $this->setFromArray($data);
        }
        $this->getTable()->filterData($data);
        $this->_data = array();
        foreach($this->getTable()->getCols() AS $col){
            $this->_data[$col] = null;
        }
        foreach($data AS $col=>$value){
            $this->_data[$col] = $value;
        }
        if(!$this->isNotSave()){
            foreach($this->getTable()->getCacheCols() AS $col){
                $value = $this->getCacheCol($col);
                if($value!== false)
                    $this->$col = $value;
            }
        }
        return $this;
    }
    
    public function setFromArray($data)
    {
        $this->getTable()->filterData($data);
        foreach($data AS $col=>$value){
            if(in_array($col, $this->getTable()->getCols())){
                $this->{$col} = $value;
            }
        }
        return $this;
    }
    
    public function __get($col)
    {
        if(isset($this->_data[$col]))
            return $this->_data[$col];
    }

    public function __set($col, $value)
    {
        $this->getTable()->filterCol($col, $value);
        if(!isset($this->_data[$col])){
            $this->_data[$col] = $value;
        }
        if(isset($this->_data[$col]) && $this->_data[$col] !== $value){
            if(in_array($col, $this->getTable()->getCols()) and !$this->isModified($col)){
                $this->_modifiedFields[$col] = $this->_data[$col];
            }
            if(!$this->isNotSave() and in_array($col, $this->getTable()->getCacheCols())) {
                $this->setCacheCol($col, $value);
            }
            $this->_data[$col] = $value;
        }
    }
    
    public function setCacheCol($col, $value)
    {
        $id = $this->getCacheId();
        $adapter = $this->getTable()->getCacheAdapter();
        $adapter->setItem($id.'-'.$col, $value);
        return $this;
    }
    
    public function getCacheCol($col)
    {
        $id = $this->getCacheId();
        $adapter = $this->getTable()->getCacheAdapter();
        return $adapter->getItem($id.'-'.$col);
    }
    
    public function __isset($columnName)
    {
        return array_key_exists($columnName, $this->_data);
    }
    
    public function isNotSave()
    {
        return ($this->{$this->getTable()->getKey()} === null);
    }
    
    public function toArrayForSave()
    {
        if ($this->{$this->getTable()->getKey()} === null or $this->_force_insert) {
            $data = $this->toArray();
        }else{
            $data = array();
            foreach($this->toArray() AS $col=>$value){
                if($this->isModified($col)){
                    if(in_array($col, $this->getTable()->getCounters())){
                        $diff = $value - $this->_modifiedFields[$col];
                        if($diff){
                            $data[$col] = new Expression($col.(($diff)?'+'.$diff:$diff));
                        }
                    }else{
                        $data[$col] = $value;
                    }
                }
            }
        }
        foreach($data AS $col=>$value){
            if(!in_array($col, $this->getTable()->getCols())){
                unset($data[$col]);
            }
        }
        return $data;
    }
    
    public function toArray()
    {
        return $this->_data;
    }
    
    public function getArrayCopy()
    {
        $data = get_object_vars($this);
        return $data['_data'];
    }
    
    public function setTable($table)
    {
        $this->_table = $table;
        return $this;
    }
    
    public function getTable()
    {
        if($this->_table === null) {
            $path = explode('\\', get_class($this));
            array_pop($path);
            $baseClass = implode('\\',$path);
            $rowClass = preg_replace('/^[^\\\]*\\\Model\\\/', '', $baseClass.'\Table');
            $this->_table = $this->getSm()->get($rowClass);
        }
        return $this->_table;
    }
    
    public function getDefaults()
    {
        return $this->getTable()->getDefaults() + array_fill_keys($this->getTable()->getCols(), null);
    }
    
    public function save()
    {
        $save = false;
        $insert = false;
        if ($this->isNotSave() or $this->_force_insert) {
            $insert = true;
            $this->_preInsert();
            $save = true;
        }else{
            $this->_preUpdate();
            if(count($this->_modifiedFields)){
                $save = true;
            }
        }
        if($save){
            $this->getTable()->saveRow($this, $this->_force_insert);
            $this->_modifiedFields = array();
            $this->_force_insert = false;
        }
        return $this;
    }
    
    public function delete()
    {
        $this->getTable()->deleteRow($this);
        return $this;
    }
    
    protected function _preInsert()
    {
        
    }
    
    protected function _preUpdate()
    {
        
    }
    
    public function getCacheId()
    {
        if(!$this->{$this->getTable()->getKey()}){
            throw new \Exception('Invalid primary key('.$this->getTable()->getName().'/'.$this->getTable()->getKey().')');
        }
        return $this->getTable()->getName().'-'.$this->getTable()->getKey().':'.$this->{$this->getTable()->getKey()};
    }
    
    public function syncCache()
    {
        
    }
}