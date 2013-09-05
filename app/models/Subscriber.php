<?php
	class Subscriber extends Eloquent
	{
		
		public function lists()
		{
			return $this->belongsToMany('Addressbook', 'list_subscriber', 'subscriber_id', 'list_id');
		}	

		public function trackers()
		{
			return $this->hasMany('Tracker');
		}			
	}