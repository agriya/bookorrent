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
class SubmissionField extends AppModel
{
    public $name = 'SubmissionField';
    public $validate = array(
        //'submission_id' => array('numeric'),
        //'form_field' => array('notempty')
        
    );
    public $belongsTo = array(
        'Submission' => array(
            'className' => 'Items.Submission',
            'foreignKey' => 'submission_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'FormField' => array(
            'className' => 'Items.FormField',
            'foreignKey' => 'form_field_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasOne = array(
        'ItemCloneThumb' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'dependent' => false,
            'conditions' => array(
                'ItemCloneThumb.class' => 'ItemCloneThumb',
            ) ,
            'fields' => '',
            'order' => ''
        ) ,
        'SubmissionThumb' => array(
            'className' => 'Items.SubmissionThumb',
            'foreignKey' => 'foreign_id',
            'dependent' => false,
            'conditions' => array(
                'SubmissionThumb.class' => 'SubmissionThumb',
            ) ,
            'fields' => '',
            'order' => ''
        )
    );
}
?>