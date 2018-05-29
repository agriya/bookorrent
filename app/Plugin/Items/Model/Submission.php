<?php
/**
 * Book or Rent
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    BookorRent
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class Submission extends AppModel
{
    public $name = 'Submission';
    public $validate = array(
        //'form_id' => array('numeric'),
        //'ip' => array('ip')
        
    );
    public $belongsTo = array(
        'Category' => array(
            'className' => 'Items.Category',
            'foreignKey' => 'category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array(
        'SubmissionField' => array(
            'className' => 'Items.SubmissionField',
            'foreignKey' => 'submission_id',
            'dependent' => true,
        )
    );
    public function submit($data) 
    {
        $data['Submission']['category_id'] = $data['Item']['category_id'];
        App::import('Model', 'Items.FormField');
        $this->FormField = new FormField();
        $dynamicFormFields = $this->FormField->find('list', array(
            'conditions' => array(
                'FormField.category_id' => $data['Item']['category_id']
            ) ,
            'fields' => array(
                'name',
                'type',
                'id'
            )
        ));
        App::uses('Items.Item', 'Model');
        $this->Item = new Item();
        $this->create($data);
        $this->save();
        if (!empty($data['Submission']['id'])) {
            $id = $data['Submission']['id'];
        } else {
            $id = $this->id;
        }
        $item_id = $data['Submission']['item_id'];
        $submissionFields = $this->SubmissionField->find('list', array(
            'conditions' => array(
                'SubmissionField.submission_id' => $id
            ) ,
            'fields' => array(
                'SubmissionField.form_field',
                'SubmissionField.id',
            ) ,
            'recursive' => -1
        ));
        $formFields = array(
            'Submission' => array(
                'id' => $id
            )
        );
        $field_name = array();
        if (!empty($data['Form'])) {
            foreach($data['Form'] as $formField => $response) {
                if (is_array($response)) {
                    $response = implode("\n", $response);
                }
                foreach($dynamicFormFields as $field_id => $field_data) {
                    foreach($field_data as $filedName => $type) {
                        if ($filedName == $formField) {
                            $fieldtype = $type;
                            $formFieldId = $field_id;
                        }
                    }
                }
                if ($fieldtype == 'file') {
                    if (!empty($data['Form'][$formField]['name'])) {
                        $field_name[] = $formField;
                    } else {
                        $response = '';
                    }
                } else if ($fieldtype == 'thumbnail') {
                    $field_name[] = $formField;
                }
                if (!empty($response)) {
                    if ($fieldtype == 'file') {
                        $response = $data['Form'][$formField]['name'];
                    }
                    $formFields['SubmissionField'][] = array(
                        'form_field_id' => $formFieldId,
                        'form_field' => $formField,
                        'response' => $response,
                        'type' => $fieldtype,
                        'id' => !empty($submissionFields[$formField]) ? $submissionFields[$formField] : ''
                    );
                }
            }
        }
        if ($this->saveAll($formFields, array(
            'validate' => false
        ))) {
            $submissionFormFields = $this->SubmissionField->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        array(
                            'SubmissionField.submission_id' => $formFields['Submission']['id']
                        ) ,
                        array(
                            'SubmissionField.form_field' => $field_name
                        )
                    )
                ) ,
                'recursive' => -1
            ));
            if (!empty($submissionFormFields)) {
                foreach($submissionFormFields as $submissionFormField) {
                    $formFieldId = $submissionFormField['SubmissionField']['id'];
                    $formFieldName = $submissionFormField['SubmissionField']['form_field'];
                    $formFieldType = $submissionFormField['SubmissionField']['type'];
                    if ($formFieldType == 'file') {
                        App::uses('Attachment', 'Model');
                        $this->Attachment = new Attachment();
                        $Attachment = $this->Attachment->find('first', array(
                            'conditions' => array(
                                'Attachment.foreign_id' => $formFieldId,
                                'Attachment.class' => 'SubmissionThumb'
                            ) ,
                            'recursive' => -1
                        ));
                        $data['SubmissionThumb'] = array();
                        $data['SubmissionThumb']['filename'] = $data['Form'][$formFieldName];
                        $this->Attachment->Behaviors->attach('ImageUpload');
                        $this->Attachment->set($data['SubmissionThumb']['filename']);
                        if (!empty($data['SubmissionThumb']['filename']['name'])) {
                            if (empty($Attachment)) {
                                $this->Attachment->create();
                            } else {
                                $data['SubmissionThumb']['id'] = $Attachment['Attachment']['id'];
                            }
                            $data['SubmissionThumb']['class'] = 'SubmissionThumb';
                            $data['SubmissionThumb']['foreign_id'] = $formFieldId;
                            $this->Attachment->save($data['SubmissionThumb']);
                        }
                        $this->Attachment->Behaviors->detach('ImageUpload');
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }
    public function getSubmissions($formId) 
    {
        $fields = $this->fields($formId);
        $skel = Set::combine($fields, '{n}');
        $submissions = $this->findAllByCategoryId($formId);
        foreach($submissions as &$submission) {
            $submission['SubmissionField'] = Set::combine($submission['SubmissionField'], '{n}.form_field', '{n}.response');
            $submission = Set::merge($skel, $submission['Submission'], $submission['SubmissionField']);
        }
        return $submissions;
    }
    public function getSubmission($formId) 
    {
        $submission = $this->findByCategoryId($formId);
        $submission['SubmissionField'] = Set::combine($submission['SubmissionField'], '{n}.form_field', '{n}.response');
        $submission = Set::merge($submission['Submission'], $submission['SubmissionField']);
        return $submission;
    }
    public function fields($formId) 
    {
        $submissions = $this->find('list', array(
            'conditions' => array(
                'category_id' => $formId
            ) ,
            'fields' => array(
                'id'
            )
        ));
        $data = $this->SubmissionField->find('all', array(
            'conditions' => array(
                'submission_id' => $submissions
            ) ,
            'group' => 'SubmissionField.form_field',
            'contain' => array() ,
            'fields' => array(
                'form_field'
            )
        ));
        $submissionFields = $this->find('first', array(
            'conditions' => array(
                'category_id' => $formId
            )
        ));
        $fields = Set::extract('{n}.SubmissionField.form_field', $data);
        $fields2 = array_keys($submissionFields['Submission']);
        $fields = Set::merge($fields2, $fields);
        return $fields;
    }
}
?>