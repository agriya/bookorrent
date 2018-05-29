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
class SeatsController extends AppController
{
    public $name = 'Seats';	
    public function generate()
    {
        $this->pageTitle = __l('Seat Generate');
		$rows = $this->request->params['named']['rows'];
		$cols = $this->request->params['named']['cols'];
		$row_name_type = $this->request->params['named']['naming'];
		$direction = $this->request->params['named']['direction'];
		$rowNames = $this->Seat->generate_row_name($rows, $row_name_type);
		if(!empty($this->request->params['named']['partition_id'])){
			$partition = $this->Seat->Partition->find('first', array(
                'conditions' => array(
                    'Partition.id' => $this->request->params['named']['partition_id'],
                ) ,
				'fields' => array(
					'Partition.stage_position'
				),
                'recursive' => -1
            ));
			$data = $this->Seat->find('all', array(
                'conditions' => array(
                    'Seat.partition_id' => $this->request->params['named']['partition_id'],
                ) ,                
                'recursive' => -1
            ));	
			
			$position = $partition['Partition']['stage_position'];
			$this->request->params['named']['width'] = ($this->request->params['named']['cols'] + 1) * 2.8;			
			foreach($data as $key => $value){
				$this->request->data['Seat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['id'] = $value['Seat']['id'];
				$this->request->data['Seat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['order'] = $value['Seat']['position'];
				$this->request->data['Seat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['class'] = $value['Seat']['seat_status_id'];
				$this->request->data['Seat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['name'] = $value['Seat']['name'];
			}			
		}
		$this->set(compact('rows', 'cols', 'row_name_type', 'direction', 'rowNames', 'position'));
    }
	public function admin_generate()
    {
        $this->setAction('generate');
    }
}
?>