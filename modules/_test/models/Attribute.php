<?php

class Attribute extends \ls\model\LSModel {

    private $id;
    private $name;
    private $objectID;

    public function __construct() {
        $this->id = new \ls\model\LSAttribute();
        $this->id->setType('float');
        $this->name = new \ls\model\LSAttribute();
        $this->name->setType('string');
        $this->objectID = new \ls\model\LSAttribute();
        $this->objectID->setType('float');
    }
    
    public function setFromArray($data) {
        $this->id->setValue(LSDataConverter::convert($this->id->getType(), $data[0]));
        $this->name->setValue(LSDataConverter::convert($this->name->getType(), $data[1]));
        $this->objectID->setValue(LSDataConverter::convert($this->objectID->getType(), $data[2]));
    }

    public function setFromAssoc($data) {
        $this->id->setValue(LSDataConverter::convert($this->id->getType(), $data['id']));
        $this->name->setValue(LSDataConverter::convert($this->name->getType(), $data['Name']));
        $this->objectID->setValue(LSDataConverter::convert($this->objectID->getType(), $data['ObjectID']));
    }

    public function toArray() {
        $arrayData = array( $this->id->getValue(), $this->name->getValue(), $this->objectID->getValue() );
        
        return $arrayData;
    }

    public function toAssoc() {
        $assocData = array();
        $assocData['id'] = $this->id->getValue();
        $assocData['Name'] = $this->name->getValue();
        $assocData['ObjectID'] = $this->objectID->getValue();
        
        return $assocData;
    }
    
    /**
     * @return AttributeMgr
     */
    public function manager() {
        return AttributeMgr::instance();
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

    public function setObjectID($objectID) {
        $this->objectID->setValue($objectID);
    }

    public function getObjectID() {
        return $this->objectID->getValue();
    }

    protected function attribute($name) {
        if ($name === 'id') {
            return $this->id;
        } else if ($name === 'Name') {
            return $this->name;
        } else if ($name === 'ObjectID') {
            return $this->objectID;
        }
    
        return null;
    }

}
