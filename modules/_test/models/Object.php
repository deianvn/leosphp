<?php

class Object extends \ls\model\LSModel {

    private $id;
    private $name;

    public function __construct() {
        $this->id = new \ls\model\LSAttribute();
        $this->id->setType('float');
        $this->name = new \ls\model\LSAttribute();
        $this->name->setType('string');
    }
    
    public function setFromArray($data) {
        $this->id->setValue(LSDataConverter::convert($this->id->getType(), $data[0]));
        $this->name->setValue(LSDataConverter::convert($this->name->getType(), $data[1]));
    }

    public function setFromAssoc($data) {
        $this->id->setValue(LSDataConverter::convert($this->id->getType(), $data['id']));
        $this->name->setValue(LSDataConverter::convert($this->name->getType(), $data['Name']));
    }

    public function toArray() {
        $arrayData = array( $this->id->getValue(), $this->name->getValue() );
        
        return $arrayData;
    }

    public function toAssoc() {
        $assocData = array();
        $assocData['id'] = $this->id->getValue();
        $assocData['Name'] = $this->name->getValue();
        
        return $assocData;
    }
    
    /**
     * @return ObjectMgr
     */
    public function manager() {
        return ObjectMgr::instance();
    }
    
    public function save() {
        return $this->manager()->update()->object($this)->submit();
    }
    
    public function setId($id) {
        $this->id->setValue($id);
    }

    public function getId() {
        return $this->id->getValue();
    }

    public function setName($name) {
        $this->name->setValue($name);
    }

    public function getName() {
        return $this->name->getValue();
    }

    protected function attribute($name) {
        if ($name === 'id') {
            return $this->id;
        } else if ($name === 'Name') {
            return $this->name;
        }
    
        return null;
    }

}
