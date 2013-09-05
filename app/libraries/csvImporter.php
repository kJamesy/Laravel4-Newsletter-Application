<?php

class Readcsv
{
	public static function csv_open($file)
	{
		$contactsArray = array();

		if (($handle = fopen($file, "r")) !== FALSE) 
		{
		    
		    fgetcsv($handle, 1000, ","); // Just abandon the first record
	  
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
		    {
		        $contactsArray[] = array('first_name' => $data[0], 'last_name' => $data[1], 'email' => $data[2]);
		    }

		    return $contactsArray;

		    fclose($handle);
		}
	}
}
