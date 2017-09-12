<?php
/**
 * Created by PhpStorm.
 * User: Ryan Potsander
 * Date: 9/12/17
 * Time: 3:02 PM
 */

class MyControllerCarousel extends modRestController {
    public $classKey = 'modResource';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';



    public function read($id) {
        if (empty($id)) {
            return $this->failure($this->modx->lexicon('rest.err_field_ns',array(
                'field' => $this->primaryKeyField,
            )));
        }
        /** @var xPDOObject $object */
        $c = $this->getPrimaryKeyCriteria($id);
        $this->object = $this->modx->getObject($this->classKey,$c);

        if (empty($this->object)) {
            return $this->failure($this->modx->lexicon('rest.err_obj_nf',array(
                'class_key' => $this->classKey,
            )));
        }

        $slides = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 25, 'contentid' => $id));

        $slidesValue = json_decode($slides->get('value'));


        $objectArray = Array();
        if ($slides) $objectArray['slides'] = $slidesValue;


        $afterRead = $this->afterRead($objectArray);
        if ($afterRead !== true && $afterRead !== null) {
            return $this->failure($afterRead === false ? $this->errorMessage : $afterRead);
        }

        return $this->success('',$objectArray);
    }



}