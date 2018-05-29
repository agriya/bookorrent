<?php
$i = 0;
do {
    $user->paginate = array(
        'conditions' => $conditions,
        'offset' => $i,
          'contain' => array(
                       'Ip' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso_alpha2',
                            )
                        ) ,
                        'Timezone' => array(
                            'fields' => array(
                                'Timezone.name',
                            )
                        ) ,
                        'fields' => array(
                            'Ip.ip',
                            'Ip.latitude',
                            'Ip.longitude',
                            'Ip.host',
                        )
                    ) ,
                  ) ,
        'fields' => array(
            'User.username',
            'User.email',
            'User.available_wallet_amount',
            'User.booking_total_booked_count',
            'User.booking_total_site_revenue',
            'User.item_count',
            'User.host_total_site_revenue',
        ),
		'order' => array(
			'User.id' => 'desc'
		) ,
        'recursive' => 0
    );
    if(!empty($q)){
        $user->paginate['search'] = $q;
    }
   $Users = $user->paginate();
      if (!empty($Users)) {
        $data = array();
        foreach($Users as $User) {
	        $data[]['User'] = array(
				__l('Username') => $User['User']['username'],
				__l('Email') => $User['User']['email'],
                __l('Available Wallet Amount') => $User['User']['available_wallet_amount'],
                __l('Total Bookings as')  . ' ' . Configure::read('item.alt_name_for_booker_singular_caps') => $User['User']['booking_total_booked_count'],
                __l('Site Revenue as') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps') .' ('.Configure::read('site.currency').')' => $User['User']['booking_total_site_revenue'],
				__l('Total').Configure::read('item.alt_name_for_item_plural_caps').(' as Host')  => $User['User']['item_count'],
				__l('Site Revenue as Host').' ('.Configure::read('site.currency').')' => $User['User']['host_total_site_revenue'],

          	);
        }
        if (!$i) {
            $this->Csv->addGrid($data);
        } else {
            $this->Csv->addGrid($data, false);
        }
    }
    $i+= 20;
}
while (!empty($Users));
echo $this->Csv->render(true);
?>