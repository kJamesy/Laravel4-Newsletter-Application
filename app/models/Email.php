<?php
	class Email extends Eloquent
	{
		
		public function trackers()
		{
			return $this->hasMany('Tracker');
		}				
	}