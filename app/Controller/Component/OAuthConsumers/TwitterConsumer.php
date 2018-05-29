<?php

class TwitterConsumer extends AbstractConsumer
{
    public function __construct()
    {
        parent::__construct(Configure::read('twitter.consumer_key') , Configure::read('twitter.consumer_secret'));
    }
}
?>