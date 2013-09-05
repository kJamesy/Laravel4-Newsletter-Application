<?php
	class Addressbook extends Eloquent
	{
		protected $table = 'lists';
		
		public function subscribers()
		{
			return $this->belongsToMany('Subscriber', 'list_subscriber', 'list_id', 'subscriber_id');
		}				
	}