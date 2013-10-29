<?php

class CsvManipulation
{
	public static function csvToArray($file)
	{
		$csvFile = new Keboola\Csv\CsvFile($file);
		$allRows = 0;
		$rowsWithThreeFields = 0;
		$subscribers = [];

		foreach($csvFile as $num=>$row) 
		{
			if($num >0) //Skip first row assumed to be the header row
			{
				if(count($row) == 3)
				{
					$subscribers[] = ['first_name' => ucfirst(strtolower(trim($row[0]))), 'last_name' => ucfirst(strtolower(trim($row[1]))), 'email' => strtolower(trim($row[2]))];
					$rowsWithThreeFields++;
				}

				$allRows++;
			}
		}

		$unique_subscribers = [];
		$emails_already_in_array = [];

		foreach ($subscribers as $subscriber) 
		{
			if(!in_array($subscriber['email'], $emails_already_in_array))
			{
				$unique_subscribers[] = $subscriber;
				$emails_already_in_array[] = $subscriber['email'];
			}
		}


		return ['allRows' => $allRows, 'rowsWithThreeFields' => $rowsWithThreeFields, 'noOfUniqueEmails' => count($unique_subscribers), 'uniqueSubscribers' => $unique_subscribers];

	}

}
