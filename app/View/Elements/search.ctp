<?php 
$type=isset($type)?$type:'home';
$num_array=array();
for($i=1;$i<=16;$i++) {
  if($i == 16) {
	$num_array[$i]=$i . '+';
  } else {
	$num_array[$i]=$i;
  }
}
if (isset($this->request->params['named']['from'])) {
  $this->request->data['Item']['from'] = $this->request->params['named']['from'];
}
if (isset($this->request->params['named']['to'])) {
  $this->request->data['Item']['to'] = $this->request->params['named']['to'];
}
if (isset($this->request->params['named']['is_flexible']) && $this->request->params['named']['is_flexible']) {
  $this->request->data['Item']['is_flexible'] = $this->request->params['named']['is_flexible'];
}?>
<?php if($type=='home'): ?>
    <div class="banner sep-bot sep-big sep-primary">
	  <div id="banner-trans-bg" class="clearfix">
		  <div class="container">
			<div class="banner-content dc">
			  <div class="banner-content-bg ver-mspace clearfix">
				<h2 class="whitec textb"><span class="show"><?php echo __l('Agriya');?><sup>&reg;</sup><?php echo __l("'s multipurpose booking software.");?></span><span class="show"><?php echo __l('Book or rent anything'); ?></span></h2>
			  </div>
			  <div class="clearfix ver-space dc"> <?php echo $this->Html->link(__l('Get Started'), array('controller' => 'items', 'action' => 'add', 'admin' => false), array('title' => __l('Get Started'), 'id' => 'js-getstarted', 'class' => 'btn btn-primary textb text-18'));?> </div>
			</div>
		  </div>
	  </div>
	</div>
	<section class="row no-mar">
	<div class="container block-space">
	  <div class="clearfix dc">
		  <div class="span16 no-mar inline sep-top">
			<h3 class="ver-space ver-mspace dc"> <span class="small-icon show"><img src="img/smile.png" alt="[<?php echo __l('Image: Smile');?>]" title="<?php echo __l('Smile');?>" /></span><span class="show"><?php echo __l('Big Opportunities');?></span></h3>
			<p class="text-16"><?php echo __l('Collaborative consumption and sharing economy have big markets. Ready to tap these opportunities?'); ?></p>
		  </div>
	  </div>
	  <section class="clearfix">
		<h3 class="dc ver-space ver-mspace clearfix"><span class="span8 bot-space no-mar inline sep-bot"><?php echo __l('Facts');?></span></h3>
		<div class="clearfix">
		  <div class="span8 span8-sm no-mar mob-no-pad">
			<h4 class="dc top-space top-mspace text-18"><?php echo __l('Collaborative activities');?></h4>
			<p class="dc">(<?php echo __l('Education, Sports, Food, etc');?>)</p>
			<p class="text-14 clearfix ver-space ver-mspace mob-dc"> <span class="span right-mspace mob-dc mob-bot-mspace"><img class="top-smspace sep sep-graylighterc" src="img/fact-activities.png" alt="[<?php echo __l('Image: Collaborative activities');?>]" title="<?php echo __l('Collaborative activities');?>"/></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">65%</span> <?php echo __l('of people learn better in a group or team');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">75%</span> <?php echo __l('of people prefer to share our meals');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">90%</span> <?php echo __l('of people prefer sports as a group');?></span> </p>
		  </div>
		  <div class="span8 span8-sm mob-no-pad">
			<h4 class="dc top-space top-mspace text-18 mob-dc"><?php echo __l('Space and item sharing');?></h4>
			<p class="dc">(<?php echo __l('Room, Workspace, Parking, Books, Toys, etc');?>)</p>
			<p class="text-14 clearfix ver-space ver-mspace mob-dc"> <span class="span right-mspace mob-dc mob-bot-mspace"><img class="top-smspace sep sep-graylighterc" src="img/fact-spacesharing.png" alt="[<?php echo __l('Image: Space and item sharing');?>]" title="<?php echo __l('Space and item sharing');?>"/></span> <span class="bot-mspace bot-space show"><?php echo __l('There are');?> <span class="text-18 textb linkc">460 <?php echo __l('million');?></span> <?php echo __l('homes in the developed world');?></span> <span class="bot-mspace bot-space show"><?php echo __l('Each one has about');?> <span class="text-18 textb linkc">$3,000 </span> <?php echo __l('of unused items, taking up space');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">69%</span> <?php echo __l('of people would share these items if they could make money from it');?></span> <span class="bot-mspace bot-mspace bot-space show"><?php echo __l("That's");?> <span class="text-18 textb linkc">$1.4 <?php echo __l('billion');?> </span><?php echo __l('worth of items that could be shared');?></span> </p>
		  </div>
		  <div class="span8 span8-sm mob-no-pad">
			<h4 class="dc top-space top-mspace text-18"><?php echo __l('Vehicle Sharing');?></h4>
			<p class="dc">(<?php echo __l('Cars, Bike, Boat, etc');?>)</p>
			<p class="text-14 clearfix ver-space ver-mspace mob-dc"> <span class="span right-mspace mob-dc mob-bot-mspace"><img class="top-smspace sep sep-graylighterc" src="img/fact-vehiclesharing.png" alt="[<?php echo __l('Image: Vehicle Sharing');?>]" title="<?php echo __l('Vehicle Sharing');?>"/></span> <span class="bot-mspace bot-space show"><?php echo __l('There are');?> <span class="text-18 textb linkc">1 <?php echo __l('billion');?></span> <?php echo __l("cars on the world's roads");?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">740 <?php echo __l('million');?> </span><?php echo __l('of these vehicles are occupied only by one person');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">470 <?php echo __l('million');?></span> <?php echo __l('of these drivers would carpool, if it were possible');?></span> </p>
		  </div>
		</div>
	  </section>
	  <section class="clearfix">
		<h3 class="dc ver-space ver-mspace clearfix"><span class="span8 bot-space no-mar inline sep-bot"><?php echo __l('Needs');?></span></h3>
		<div class="clearfix">
		  <div class="span10 hor-lmspace mob-no-mar mob-no-pad">
			<h4 class="dc ver-space ver-mspace text-18"><?php echo __l('Workforce dissatisfaction');?></h4>
			<p class="text-14 clearfix ver-space ver-mspace mob-dc"> <span class="span right-mspace mob-dc mob-bot-mspace"><img class="top-smspace sep sep-graylighterc" src="img/needs-dissatisfaction.png" alt="[<?php echo __l('Image: Workforce dissatisfaction');?>]" title="<?php echo __l('Workforce dissatisfaction');?>"/></span> <span class="bot-mspace bot-space show"><?php echo __l('The average person works on average');?> <span class="text-18 textb linkc">90,000 <?php echo __l('hours');?></span> <?php echo __l('in their lifetime');?></span> <span class="bot-mspace bot-space show"><?php echo __l('Yet');?> <span class="text-18 textb linkc">80%</span> <?php echo __l('of people are unhappy with their job');?></span> <span class="bot-mspace bot-space show"><?php echo __l("That's");?> <span class="text-18 textb linkc">9,000</span> <?php echo __l('unhappy days - not what we dreamed of as children');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">4</span> <?php echo __l('in');?> <span class="text-18 textb linkc">10</span> <?php echo __l('young people want to start their own business');?></span> </p>
		  </div>
		  <div class="span10 hor-lmspace mob-no-mar mob-no-pad">
			<h4 class="dc ver-space ver-mspace text-18"><?php echo __l('Pervasive loneliness');?></h4>
			<p class="text-14 clearfix ver-space ver-mspace mob-dc"> <span class="span right-mspace mob-dc mob-bot-mspace"><img class="top-smspace sep sep-graylighterc" src="img/needs-loneliness.png" alt="[<?php echo __l('Image: Pervasive loneliness');?>]" title="<?php echo __l('Pervasive loneliness');?>"/></span> <span class="bot-mspace bot-space show"><?php echo __l('There are');?> <span class="text-18 textb linkc">1.2 <?php echo __l('billion');?></span> <?php echo __l('people in the developed world');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">26%</span> <?php echo __l('of these people spend more than');?> <span class="text-18 textb linkc">20 <?php echo __l('hours');?></span> <?php echo __l('alone each week');?></span> <span class="bot-mspace bot-space show"><?php echo __l("That's");?> <span class="text-18 textb linkc">300 <?php echo __l('million');?></span> <?php echo __l('people spending more than');?> <span class="text-18 textb linkc">20%</span> <?php echo __l('of their waking hours alone, despite so many ways to interact together');?></span> </p>
		  </div>
		</div>
	  </section>
	  <section class="clearfix">
		<h3 class="dc ver-space ver-mspace clearfix"><span class="span8 bot-space no-mar inline sep-bot"><?php echo __l('Possibilities');?></span></h3>
		<div class="clearfix dc">
		  <div class="span16 no-mar inline">
			<p class="text-14 clearfix ver-space ver-mspace mob-dc"> <span class="span right-mspace mob-dc mob-bot-mspace"><img class="top-smspace sep sep-graylighterc" src="img/possibilities.png" alt="[<?php echo __l('Image: Possibilities');?>]" title="<?php echo __l('Possibilities');?>"/></span> <span class="dl clearfix mob-dc"><span class="bot-mspace bot-space show"><?php echo __l('There are');?> <span class="text-18 textb linkc">2 <?php echo __l('billion');?></span> <?php echo __l('people in the world with access to the Internet');?></span> <span class="bot-mspace bot-space show"><span class="text-18 textb linkc">78%</span> <?php echo __l('of people feel that the online world has made them more open to sharing in the real world');?></span> <span class="bot-mspace bot-space show"><?php echo __l('There are now over');?> <span class="text-18 textb linkc">7 <?php echo __l('billion');?></span> <?php echo __l('people on the planet, and');?> <span class="text-18 textb linkc">8</span> <?php echo __l('out of');?> <span class="text-18 textb linkc">10</span> <?php echo __l('people say sharing makes them "more happy"');?></span> <span class="bot-mspace bot-space show"><?php echo __l("That's");?> <span class="text-18 textb linkc">5.7</span> <?php echo __l('people ready to create a new sharing economy');?></span></span> </p>
		  </div>
		</div>
	  </section>
	</div>
  </section>
  <section class="row no-mar">
	<?php echo $this->element('categories', array('type' => 'home', 'cache' => array('config' => 'sec')), array('plugin' => 'Items')); ?>
  </section>
  <section class="row no-mar">
	  <div class="container ver-space">
		<h3 class="dc space bot-mspace"><span class="small-icon show"><img src="img/rentify-icon.png" alt="[<?php echo __l('Image: BookorRent');?>]" title="<?php echo __l('BookorRent');?>"></span> <span class="show"><?php echo __l('Agriya');?><sup>&reg;</sup><?php echo __l('BookorRent');?> <?php echo __l('Advantages');?></span> </h3>
		<div class="clearfix dc">
        		<span class="hor-space"><img title="<?php echo __l('Certified'); ?>" alt="[Image: <?php echo __l('Certified'); ?>]" src="img/certified-logo.png"></span>
                <span class="hor-space"><img title="<?php echo __l('Nasscom');?>" alt="[Image: <?php echo __l('Nasscom');?>]" src="img/nasscom.png"></span>
        </div>
		<div class="clearfix ver-space">
		  <ol class="unstyled span8 graydarkc">
		  <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">1. <?php echo __l('Made in ISO 9001-2008 certified and NASSCOM');?><sup>&reg;</sup><?php echo __l('listed company');?></span></li>
			<li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">2. <?php echo __l('First and complete multipurpose booking software');?></span></li>
			<li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">3. <?php echo __l('Has many revenue options (Signup fee, Property listing fee, Property verification fee, Commission on booking, Affiliate, Ads)');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">4. <?php echo __l('Multilingual support');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">5. <?php echo __l('gzip and file based caching for high performance and high traffic sites');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">6. <?php echo __l('With MVC and plugin based architecture');?></span></li>
		  </ol>
		  <ol class="unstyled span8 graydarkc">
			<li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">7. <?php echo __l('Growth hacking plugin for improving user growth');?></span></li>
			<li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">8. <?php echo __l('Integrated dispute module along with activities tracking page.');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">9. <?php echo __l('Countless payment gateways support; powered by ZazPay');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">10. <?php echo __l('High performance and cloud ready');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">11. <?php echo __l('Suitable for many domains: Real estate marketplace (Realty marketplace), Rental booking, Room sharing, Hotel booking, Office/Parking Space sharing, Car sharing, Bike sharing, Boat sharing, etc');?></span></li>
		  </ol>
		    <ol class="unstyled span8 graydarkc">
			<li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">12. <?php echo __l('Mobile friendly');?></span></li>
			<li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate">13.<?php echo __l('Streamlined workflow and hence no maintenance headaches');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">14. <?php echo __l('Native iPhone app available');?></span></li>
			<li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate">15. <?php echo __l('Actively under development with customer suggestions and requests.');?></span></li>
			<li class="bot-space dc sep-secondary-left span8 no-mar"><a href="http://www.agriya.com/contact" class="btn btn-large btn-primary textb top-mspace" title="<?php echo __l('Contact Agriya');?>" target="_blank"><?php echo __l('Contact Agriya');?></a> </li>
		  </ol>
		</div>
	  </div>
  </section>
  <section class="row no-mar">
          <div class="well no-pad no-round no-mar">
            <div class="container ver-space">
              <h3 class="dc space bot-mspace"><span class="small-icon show"><img title="Agriya" alt="[Image: Agriya]" src="img/agriya-icon.png"></span><span class="show"><?php echo __l('Agriya');?><sup>&reg;</sup> <?php echo __l('Solutions');?></span></h3>
              <p class="text-16 dc bot-space bot-mspace"><?php echo __l("Incase if you don't know... for years, Agriya");?><sup>&reg;</sup><?php echo __l("doesn't just sell products, but offers multiple solutions and services.");?></p>
              <h3 class="dc bot-space bot-mspace"><?php echo __l('Micro entrepreneur Solutions');?></h3>
			  <p class="text-16 dc bot-space bot-mspace"><?php echo __l('Passionate micro entrepreneurs prefer mentors and startup accelerators. Sadly, an accelerator like Y Combinator has mere 3% acceptance rate. Agriya<sup>&reg;</sup> provides all related solutions and consultations.');?></p>
              <div class="clearfix ver-space">
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Expert consultation');?></span></span></li>
                    <li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Ideation');?></span></span></li>
                    <li class="bot-space sep-secondary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Pivoting consultation');?></span></span></li>
                  </ol>
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('MVP');?></span></span></li>
                    <li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Marketing strategy');?></span></span></li>
                    <li class="bot-space sep-secondary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Hosting consultation');?></span></span></li>
                  </ol>
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Server management');?></span></span></li>
                    <li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Leads to likeminded startupers');?></span></span></li>
                    <li class="bot-space sep-secondary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('PR (Public Relation)');?></span></span></li>
                  </ol>
             </div>
            <h3 class="dc bot-space bot-mspace"><?php echo __l('SME Solutions');?></h3>
            <div class="clearfix ver-space">
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Search Engine Optimization (SEO)');?></span></span></li>
                  </ol>
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Social media marketing');?></span></span></li>
                  </ol>
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Server management');?></span></span></li>
                  </ol>
             </div>
             <h3 class="dc bot-space bot-mspace"><?php echo __l('Enterprise Solutions');?></h3>
            <div class="clearfix ver-space">
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Big data');?></span></span></li>
                    <li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Machine Learning (ML)');?></span></span></li>
                    <li class="bot-space sep-secondary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Artificial Intelligence (AI)');?></span></span></li>
                  </ol>
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Recommendation Engine');?></span></span></li>
                    <li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Data Analytics');?></span></span></li>
                    <li class="bot-space sep-secondary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Hadoop, Mahout');?></span></span></li>
                  </ol>
                  <ol class="unstyled span8 graydarkc">
                    <li class="bot-space sep-default-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Business Process Outsourcing (BPO)');?></span></span></li>
                    <li class="bot-space sep-primary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Payment processing');?></span></span></li>
                    <li class="bot-space sep-secondary-left span8 no-mar"><span class="span7 htruncate"><span class="left-space"><?php echo __l('Financial solutions');?></span></span></li>
                  </ol>
             </div>
            </div>
          </div>
        </section>
  <section class="row no-mar">
	<div class="well bot-shad no-pad no-round no-mar">
	<div class="container block-space mob-no-pad">
	  <div class="top-space hor-mspace clearfix tab-no-mar">
		<h5 class="ver-mspace textb span right-space"><?php echo __l('Clone of');?></h5>
		<span class="clone clearfix mob-bot-mspace "><?php echo $this->Html->image('cloneof.png', array('alt'=>__l("[Image: uniiverse.com, vayable.com, getyourguide.com, airbnb.com, 9flats.com, thestorefront.com, wimdu.com, evenues.com, opendesks.com, parkcirca.com, relayrides.com, velolet.com, sleepafloat.com, eventbrite.com, tutorspree.com, bookmyshow.com, buzzintown.com, eventjini.com, indianstage.in, madrasevents.in, meraevents.com]"), 'width' => '850', 'height' => '99', 'title'=>__l('uniiverse.com, vayable.com, getyourguide.com, airbnb.com, 9flats.com, thestorefront.com, wimdu.com, evenues.com, opendesks.com, parkcirca.com, relayrides.com, velolet.com, sleepafloat.com, eventbrite.com, tutorspree.com, bookmyshow.com, buzzintown.com, eventjini.com, indianstage.in, madrasevents.in, meraevents.com'))); ?></span>
	  </div>
	</div>
	</div>
  </section>	
<?php else: ?>
	<?php echo $this->Form->create('Item', array('class' => 'normal place-search js-search clearfix', 'action'=>'index', 'enctype' => 'multipart/form-data'));?>
  <section class="row ver-space no-mar">
	<div class="span10 no-mar ver-space">
	  <div class="clearfix ver-space dc">
		<span class="top-space top-smspace show pull-left mob-clr"><i class="icon-map-marker text-24 top-space hor-mspace pull-left mob-clr"></i></span>
		<div class="form-search bot-mspace ">
		  <div class="result-block">
			<?php echo $this->Form->input('Item.cityName', array('id' => 'ItemCityNameSearch','class'=>'span9 ver-mspace','placeholder'=>__l('Where?'),'label' =>false));
			echo $this->Form->input('Item.latitude', array('id' => 'latitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.longitude', array('id' => 'longitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.ne_latitude', array('id' => 'ne_latitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.ne_longitude', array('id' => 'ne_longitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.sw_latitude', array('id' => 'sw_latitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.sw_longitude', array('id' => 'sw_longitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.address', array('id' => 'js-street_id', 'type' => 'hidden'));
			echo $this->Form->input('Item.city_name', array('id' => 'CityName', 'type' => 'hidden'));
			echo $this->Form->input('Item.state_name', array('id' => 'StateName', 'type' => 'hidden'));
			echo $this->Form->input('Item.country_iso2', array('id' => 'js-country_id', 'type' => 'hidden'));
			echo $this->Form->input('Item.type', array( 'value' =>'search', 'type' => 'hidden'));?> 
			<div id="mapblock" class="pa">
			  <div id="mapframe">
			    <div id="mapwindow"></div>
			  </div>
		    </div>
		  </div>
		  <span class="span space show top-mspace mob-no-mar"> 
			<span class="ver-space show checkbox ver-mspace mob-no-mar">
			  <?php echo $this->Form->input('Item.is_flexible', array('label' => false, 'div' => false,'type' => 'checkbox', 'checked' => !empty($this->request->data['Item']['is_flexible']) ? 'checked' : '')); ?>
			  <label class="checker-img dl graydarkerc text-11" for="ItemIsFlexible">
			  <?php echo __l('Include non-'); ?><span class="label"><?php echo __l('exact'); ?></span> <span class=""><?php echo __l('matches(recommended)'); ?></span></label>
			</span> 
		  </span>
		  <div class="submit pull-right right-space ver-space ver-mspace mob-no-pad mob-no-mar top-space mob-clr">
			<?php echo $this->Form->submit(__l('Search'), array('id' => 'js-sub', 'class' => 'btn btn-large hor-mspace btn-primary textb top-mspace text-16 top-space'  ,'disabled' => 'disabled'));?>
		  </div>
		</div>
	  </div>
	</div>
	<div class="span14">
	  <div class="nav-tabs no-bor ver-smspace clearfix">
		<ul id="myTab" class="row unstyled no-mar text-11 pull-right">
		  <li class="pull-left no-mar active">
			<a title="<?php echo __l('Calendar'); ?>" data-toggle="tab" href="#" class="no-under js-show-search-calendar"><?php echo __l('Calendar'); ?></a>
		  </li>
		  <li class="pull-left hor-smspace">/</li>
		  <li class="pull-left ">
			<a title="<?php echo __l('Dropdown'); ?>" data-toggle="tab" href="#" class="no-under js-show-search-dropdown"><?php echo __l('Dropdown'); ?></a>
		  </li>
		</ul>
	  </div>
	  <div class="tab-content" id="myTabContent"> 
		<div class="clearfix pull-right mob-clr dc">
		  <div id="js-inlineDatepicker-calender" class="<?php echo (Configure::read('item.set_default_calendar_type')  == 'calendar') ? 'hide' : null; ?>">
			<div class="input select clearfix">
			  <?php echo $this->Form->input('Item.from',array('div'=>false,'class'=>'span3','label' => '<b>'.__l('From').'</b>', 'type' => 'date' ,'minYear'=>date('Y'), 'maxYear'=>date('Y')+1, 'orderYear' => 'asc')); ?>
			</div>
			<div class="clearfix top-mspace">
			  <?php echo $this->Form->input('Item.to',array('div'=>false,'class'=>'span3','label' => '<b>'.__l('To').'</b>', 'type' => 'date','minYear'=>date('Y'), 'maxYear'=>date('Y')+1, 'orderYear' => 'asc'));		?>
			</div>
		  </div>
		  <div id="js-inlineDatepicker" class="<?php echo (Configure::read('item.set_default_calendar_type')  == 'dropdown') ? 'hide' : null; ?> span14 no-mar"></div>
		  <div class="span14 no-mar dr grayc text-11">
		    <span class="js-date-picker-info <?php echo (Configure::read('item.set_default_calendar_type')  == 'dropdown') ? 'hide' : null; ?>"></span>
		  </div>
		</div>
	  </div>
	</div>
  </section>
  <?php echo $this->Form->end();?>
<?php endif; ?>
