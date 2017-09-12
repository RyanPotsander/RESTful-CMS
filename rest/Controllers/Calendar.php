<?php
/**
 * Created by PhpStorm.
 * User: Ryan Potsander
 * Date: 9/12/17
 * Time: 3:00 PM
 */

class MyControllerCalendar extends modRestController {
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

        /*
        $houseMartinis = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 26, 'contentid' => $id));
        $barBites = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 27, 'contentid' => $id));
        $bottles = $this->modx->getObject('modTemplateVarResource', array('tmplvarid' => 28, 'contentid' => $id));

        $houseMartinisValue = json_decode($houseMartinis->get('value'));
        $barBitesValue = json_decode($barBites->get('value'));
        $bottlesValue = json_decode($bottles->get('value'));

        $objectArray = Array();
        if ($houseMartinis) $objectArray['houseMartinis'] = $houseMartinisValue;
        if ($barBites) $objectArray['barBites'] = $barBitesValue;
        if ($bottles) $objectArray['bottles'] = $bottlesValue;
        */

        $events = $this->modx->runSnippet('calendar_provider');
        $eventsValue = json_decode($events->get('value'));
        $objectArray = Array();
        if ($events) $objectArray['results'] = $eventsValue;


        $afterRead = $this->afterRead($objectArray);
        if ($afterRead !== true && $afterRead !== null) {
            return $this->failure($afterRead === false ? $this->errorMessage : $afterRead);
        }

        return $this->success('',$objectArray);
    }



}