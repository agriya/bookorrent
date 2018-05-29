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
class PartitionsController extends AppController
{
    public $name = 'Partitions';
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
			'Seat.id',
			'Seat.class',
			'Seat.order',
			'Seat.name'
        );
		if ((!empty($this->request->params['action']) and ($this->request->params['action'] == 'index'))) {
            $this->Security->validatePost = false;
        }
        parent::beforeFilter();
    }
    public function index()
    {
        $this->pageTitle = __l('Partitions');
        $conditions = array();
		$conditions['Partition.user_id'] = $this->Auth->user('id');
		if (!empty($this->request->params['named']['slug'])) {
			$hall = $this->Partition->Hall->find('first', array('conditions' => array('Hall.slug' => $this->request->params['named']['slug']),'recursive' => -1));	
			if(!empty($hall)){
				$conditions['Partition.hall_id'] = $hall['Hall']['id'];
			}
		}
        $this->set('active', $this->Partition->find('count', array(
            'conditions' => array_merge($conditions, array(
								'Partition.is_active' => 1
								) 
							),
            'recursive' => -1
        )));
        $this->set('inactive', $this->Partition->find('count', array(
            'conditions' => array_merge($conditions, array(
								'Partition.is_active' => 0
								) 
							),
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Partition.is_active'] = 1;
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Partition.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Hall' => array(
					'fields' => array(
						'Hall.id',
						'Hall.name',
						'Hall.slug'
					)
				),
				'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
					)
				)
			),			
            'order' => array(
                'Partition.id' => 'desc'
            ) ,
            'recursive' => -1
        );
        $this->set('partitions', $this->paginate());
        $moreActions = $this->Partition->moreActions;
        $this->set(compact('moreActions'));		
    }	
    public function add()
    {
        $this->pageTitle = __l('Add Partition');
        if (!empty($this->request->data)) {
			if($this->Auth->user('role_id') != ConstUserTypes::Admin){
				$this->request->data['Partition']['user_id'] = $this->Auth->user('id');
			}else{
				$conditions = array('Hall.id' => $this->request->data['Partition']['hall_id']);
				$hall = $this->Partition->Hall->find('first', array('conditions' => $conditions,'recursive' => -1));
				$this->request->data['Partition']['user_id'] = $hall['Hall']['id'];				
			}
			$this->request->data['Partition']['is_active'] = 1;	
			if($this->Partition->validates($this->request->data['Partition']) && !empty($this->request->data['Partition']['result'])){
				$data = json_decode($this->request->data['Partition']['result']);
				$this->request->data['Seat'] = array();
				foreach($data as $key =>  $value){	
					$temp = array(
					  "][" => ":",
					  "[" => "",
					  "]" => ""
					);
					$key = strtr($key, $temp);	
					$sep_data = explode(":", $key);
					if(isset($sep_data[0]) && $sep_data[0] == "dataSeat" && (in_array($sep_data[2], array('id', 'order', 'class', 'name')))){
						$this->request->data['Seat'][$sep_data[1]][$sep_data[2]] = $value;
					}					
				}
				$this->Partition->create();
				if ($this->Partition->save($this->request->data['Partition'])) {
					$partition_id = $this->Partition->getLastInsertId();
					$seats = array('Seat' => array());
					foreach($this->request->data['Seat'] as $key => $value){
						$data = array();
						$row_col = explode("-", $key);
						$data['hall_id'] = $this->request->data['Partition']['hall_id'];
						$data['partition_id'] = $partition_id;
						$data['name'] = $value['name'];
						$data['row'] = $row_col[0];
						$data['column'] = $row_col[1];
						$data['seat_status_id'] = $value['class'];
						$data['position'] = $value['order'];
						$seats['Seat'][] = $data;
					}
					if(!empty($seats['Seat'])){
						$this->Partition->Seat->saveAll($seats['Seat']);
					}
					$this->Session->setFlash(__l('Partition has been added') , 'default', null, 'success');
					$this->redirect(array(
						'action' => 'index'
					));
				} else {
					$this->Session->setFlash(__l('Partition could not be added. Please, try again.') , 'default', null, 'error');
				}
			}else {
				$this->Session->setFlash(__l('Partition could not be added. Please, try again.') , 'default', null, 'error');
			}
        }
		$conditions = array();
		$conditions['Hall.is_active'] = 1;
		if($this->Auth->user('role_id') != ConstUserTypes::Admin){
			$conditions['Hall.user_id'] = $this->Auth->user('id');
		}
		$halls = $this->Partition->Hall->find('list', array('conditions' => $conditions,'recursive' => -1));		
		$seating_name_types = array(ConstSeatNameType::Alphabet => "Alphabet", ConstSeatNameType::RomanNumbers => "RomanNumbers", ConstSeatNameType::Number => "Number");
		$stage_positions = array(ConstStagePosition::Top => "Top", ConstStagePosition::Bottom => "Bottom");
		$seating_directions = array(ConstSeatArrangementDirection::LeftToRight => "LeftToRight", ConstSeatArrangementDirection::RightToLeft => "RightToLeft");
		$seat_status = array(ConstSeatStatus::Available => "Available", ConstSeatStatus::Unavailable => "Unavailable", ConstSeatStatus::NoSeat => "NoSeat");
		$this->set(compact('seating_name_types', 'stage_positions', 'seating_directions', 'halls', 'seat_status'));
    }
    public function edit($id = null)
    {
		$this->pageTitle = __l('Edit Partition');
		if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$partition_count = $this->Partition->CustomPricePerTypesSeat->find('count', array(
				'conditions' => array(
					'CustomPricePerTypesSeat.partition_id' => $id
				),
				'recursive' => -1
			));
		if($partition_count > 0){
			$this->Session->setFlash(__l('Partition already assign to items') , 'default', null, 'error');
			$this->redirect(array(
				'action' => 'index'
			));			
		}
        if (!empty($this->request->data)) {
			if($this->Auth->user('role_id') != ConstUserTypes::Admin){
				$this->request->data['Partition']['user_id'] = $this->Auth->user('id');
			}else{
				$conditions = array('Hall.id' => $this->request->data['Partition']['hall_id']);
				$hall = $this->Partition->Hall->find('first', array('conditions' => $conditions,'recursive' => -1));
				$this->request->data['Partition']['user_id'] = $hall['Hall']['id'];				
			}
			$this->request->data['Partition']['is_active'] = 1;				
			if($this->Partition->validates($this->request->data['Partition']) && !empty($this->request->data['Partition']['result'])){
				$data = json_decode($this->request->data['Partition']['result']);
				$this->request->data['Seat'] = array();
				foreach($data as $key =>  $value){	
					$temp = array(
					  "][" => ":",
					  "[" => "",
					  "]" => ""
					);
					$key = strtr($key, $temp);	
					$sep_data = explode(":", $key);
					if(isset($sep_data[0]) && $sep_data[0] == "dataSeat" && (in_array($sep_data[2], array('id', 'order', 'class', 'name')))){
						$this->request->data['Seat'][$sep_data[1]][$sep_data[2]] = $value;
					}					
				}				
				if ($this->Partition->save($this->request->data['Partition'])) {
					$previous_records = array();
					$partition_id = $this->Partition->getLastInsertId();
					$seats = array('Seat' => array());
					foreach($this->request->data['Seat'] as $key => $value){
						$data = array();
						$row_col = explode("-", $key);
						if(!empty($value['id'])){
							$data['id'] = $value['id'];
							$previous_records[] = $data['id'];
						}
						$data['hall_id'] = $this->request->data['Partition']['hall_id'];
						$data['partition_id'] = $this->request->data['Partition']['id'];
						$data['name'] = $value['name'];
						$data['row'] = $row_col[0];
						$data['column'] = $row_col[1];
						$data['seat_status_id'] = $value['class'];
						$data['position'] = $value['order'];
						$seats['Seat'][] = $data;
					}
					if(count($previous_records) != count($seats['Seat'])){
						$this->Partition->Seat->deleteAll(array(
							'Seat.partition_id' => $this->request->data['Partition']['id']
						));						
					}
					if(!empty($seats['Seat'])){
						$this->Partition->Seat->saveAll($seats['Seat']);
					}
					$this->Session->setFlash(__l('Partition has been update') , 'default', null, 'success');
					$this->redirect(array(
						'action' => 'index'
					));
				} else {
					$this->Session->setFlash(__l('Partition could not be update. Please, try again.') , 'default', null, 'error');
				}
			}else {
				$this->Session->setFlash(__l('Partition could not be update. Please, try again.') , 'default', null, 'error');
			}
        }else{
			$this->request->data = $this->Partition->find('first', array(
                'conditions' => array(
                    'Partition.id' => $id,
                ) ,
                'contain' => array(
					'Hall' => array(
						'fields' => array(
							'Hall.id',
							'Hall.name',
						)
					)
                ),
                'recursive' => 0
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }			
		}
		$conditions = array();
		$conditions['Hall.is_active'] = 1;
		if($this->Auth->user('role_id') != ConstUserTypes::Admin){
			$conditions['Hall.user_id'] = $this->Auth->user('id');
		}
		$halls = $this->Partition->Hall->find('list', array('conditions' => $conditions,'recursive' => -1));		
		$seating_name_types = array(ConstSeatNameType::Alphabet => "Alphabet", ConstSeatNameType::RomanNumbers => "RomanNumbers", ConstSeatNameType::Number => "Number");
		$stage_positions = array(ConstStagePosition::Top => "Top", ConstStagePosition::Bottom => "Bottom");
		$seating_directions = array(ConstSeatArrangementDirection::LeftToRight => "LeftToRight", ConstSeatArrangementDirection::RightToLeft => "RightToLeft");
		$seat_status = array(ConstSeatStatus::Available => "Available", ConstSeatStatus::Unavailable => "Unavailable", ConstSeatStatus::NoSeat => "NoSeat");
		$this->set(compact('seating_name_types', 'stage_positions', 'seating_directions', 'halls', 'seat_status'));
    }
	public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$partition_count = $this->Partition->CustomPricePerTypesSeat->find('count', array(
				'conditions' => array(
					'CustomPricePerTypesSeat.partition_id' => $id
				),
				'recursive' => -1
			));
		if($partition_count > 0){
			$this->Session->setFlash(__l('Partition already assign to items, So unable to delete') , 'default', null, 'error');
			$this->redirect(array(
				'action' => 'index'
			));			
		}		
        if ($this->Partition->delete($id)) {
            $this->Session->setFlash(__l('Partition deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }	
    public function admin_index()
    {
        $this->pageTitle = __l('Partitions');
        $conditions = array();
		if (!empty($this->request->params['named']['slug'])) {
			$hall = $this->Partition->Hall->find('first', array('conditions' => array('Hall.slug' => $this->request->params['named']['slug']),'recursive' => -1));	
			if(!empty($hall)){
				$conditions['Partition.hall_id'] = $hall['Hall']['id'];
			}
		}		
        $this->set('active', $this->Partition->find('count', array(
            'conditions' => array_merge($conditions, array(
								'Partition.is_active' => 1
								) 
							),
            'recursive' => -1
        )));
        $this->set('inactive', $this->Partition->find('count', array(
            'conditions' => array_merge($conditions, array(
								'Partition.is_active' => 0
								) 
							),
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Partition.is_active'] = 1;
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Partition.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Hall' => array(
					'fields' => array(
						'Hall.id',
						'Hall.name',
						'Hall.slug'
					)
				),
				'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
					)
				)
			),			
            'order' => array(
                'Partition.id' => 'desc'
            ) ,
            'recursive' => -1
        );
        $this->set('partitions', $this->paginate());
        $moreActions = $this->Partition->moreActions;
        $this->set(compact('moreActions'));		
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }	
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Partition->delete($id)) {
            $this->Session->setFlash(__l('Partition deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
	public function update() {
		$this->autoRender = false;
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $selectedIds = array();
            foreach($this->request->data[$this->modelClass] as $primary_key_id => $is_checked) {
                if ($is_checked['id']) {
                    $selectedIds[] = $primary_key_id;
                }
            }
            if ($actionid && !empty($selectedIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
					$partition_count = $this->Partition->CustomPricePerTypesSeat->find('count', array(
						'conditions' => array(
							'CustomPricePerTypesSeat.partition_id' => $selectedIds
						),
						'recursive' => -1
					));
					if($partition_count > 0){
						$this->Session->setFlash(__l('Partition already assign to items, So unable to disable') , 'default', null, 'error');
						$this->redirect(array(
							'action' => 'index'
						));			
					}
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 0
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked request has been disabled') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 1
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked request has been enabled') , 'default', null, 'success');
                } elseif ($actionid == ConstMoreAction::Delete) {
					$partition_count = $this->Partition->CustomPricePerTypesSeat->find('count', array(
							'conditions' => array(
								'CustomPricePerTypesSeat.partition_id' => $selectedIds
							),
							'recursive' => -1
						));
					if($partition_count > 0){
						$this->Session->setFlash(__l('Partition already assign to items, So unable to delete') , 'default', null, 'error');								
					}else{					
						$this->{$this->modelClass}->deleteAll(array(
							$this->modelClass . '.id' => $selectedIds
						));
						$this->Session->setFlash(__l('Checked request has been deleted') , 'default', null, 'success');
					}
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
	}	
	public function getpartitions($id){
		if (is_null($id)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$partitions = array();
		if($id != 0) {
			$partitions = $this->Partition->find('list', array(
				'conditions' => array(
					'Partition.hall_id' => $id,
					'Partition.is_active' => 1
				) ,
				'order' => array(
					'Partition.name' => 'ASC',
				) ,
				'recursive' => -1
			));
		}		
		$this->set(compact('partitions'));
	}
	public function preview($id = null) {
		 $this->pageTitle = __l('Partition preview');
		if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$partition = $this->Partition->find('first', array(
			'conditions' => array(
				'Partition.id' => $id
			),
			'recursive' => -1
		));
		$seats = $this->Partition->Seat->find('all', array(
			'conditions' => array(
					'Seat.partition_id' => $id,
			),
			'fields' => array(
				'Seat.id',
				'Seat.name',
				'Seat.row',
				'Seat.column',
				'Seat.seat_status_id',
				'Seat.position'
			),
			'order' => array(
				'Seat.position' => 'ASC'
			),
			'resursive' => -1 
		));
		if (empty($seats)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$seat_map = "";
		$col = 1;
		$temp_row = $available_arr = $unavailable_arr = $noseat_arr = $row_name = $temp_row_name = "";
		if($partition['Partition']['seating_name_type'] == ConstSeatNameType::Number){
			$row_name = implode(",", range(1, $partition['Partition']['no_of_rows']));
		}
		
		foreach($seats as $seat){
			$text = "";
			$row_col = explode('-', $seat['Seat']['name']);
			if($partition['Partition']['seating_name_type'] != ConstSeatNameType::Number){
				if($row_col[0] != $temp_row_name){
					// type other than number seat -> rowname-colname Ex: R-10
					$temp_row_name = $row_col[0];
					$row_name = (empty($row_name)) ? "'".$temp_row_name."'" : $row_name.", '".$temp_row_name."'";
				}
			}else{
				// type  number seat -> Seat Number Ex: 10
				$row_col[1] = $seat['Seat']['name'];
			}
			if($seat['Seat']['seat_status_id'] == ConstSeatStatus::Available){
				$text = "a[".$seat['Seat']['id'].",".$row_col[1]."]";
				$available_arr  = (empty($available_arr)) ? $seat['Seat']['id'] : $available_arr.", ".$seat['Seat']['id']; 
			}else if($seat['Seat']['seat_status_id'] == ConstSeatStatus::Unavailable){
				$text = "u[".$seat['Seat']['id'].",".$row_col[1]."]";
				$unavailable_arr  = (empty($unavailable_arr)) ? $seat['Seat']['id'] : $unavailable_arr.", ".$seat['Seat']['id']; 
			}else if($seat['Seat']['seat_status_id'] == ConstSeatStatus::NoSeat){
				$text = "_";
				$noseat_arr  = (empty($noseat_arr)) ? $seat['Seat']['id'] : $noseat_arr.", ".$seat['Seat']['id']; 
			}
			$temp_row .=$text;
			$col++;
			if($partition['Partition']['no_of_columns'] < $col){
				if(empty($seat_map)){
					$seat_map = "'".$temp_row."'";
				}else{
					$seat_map .= ",'".$temp_row."'";
				}
				$temp_row = "";
				$col = 1;
			}
		}
		$this->set(compact('partition', 'seat_map', 'available_arr', 'unavailable_arr', 'noseat_arr', 'row_name'));
	}
	public function admin_preview($id = null)
    {
        $this->setAction('preview', $id);
    }
}
?>