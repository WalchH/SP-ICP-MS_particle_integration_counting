<?php

$min_nr_of_dwell_for_peak = 3;
$background_cutoff = 3;



function extract_peaks($filename,$min_nr_of_dwell_for_peak,$background_cutoff)
{
	$str = str_replace("\r\n","\n",file_get_contents($filename));

	$lines = explode("\n",$str);

	$timestamps = array();
	$values = array();
	$max_val = 0;
	$max_index = 0;

	$good_values = 0;

	$index = 0;

	for ($i=4;$i<count($lines);$i++)
	{
		if (strlen($lines[$i])<2) 
			break;

		$fields = explode(",",$lines[$i]);

		$timestamp = $fields[0];
		$value = $fields[1];


		array_push($timestamps, $timestamp);
		array_push($values, $value);

		if ($index==0)
		{
			$max_value = $value;
			$max_index = $index;
		} else
		if ($value > $max_value)
		{
			$max_value = $value;
			$max_index = $index;
		}

		$index++;
	}

	$state = "no_peak";

	$peak_timestamps = array();
	$peak_values = array();
	$peak_start = array();
	$peak_sum = array();
	$peak_dwell = array();

	$peak_counter = 0;
	$dwell = 0;
	$sum = 0;

	for ($i=0;$i<count($values);$i++)
	{

		if ($state=="no_peak")
		{	
			$good_values = 0;

			for ($n=0;$n<$min_nr_of_dwell_for_peak;$n++)
			{
				$index = $n+$i;
				if ($index>(count($values)-1))
					break;

				if ($values[$i+$n]>=$background_cutoff)
					$good_values++;
			}

			if ($good_values >= $min_nr_of_dwell_for_peak)
			{
				$state = "peak";
				$dwell=0;
				$sum=0;
				$peak_counter++;
				$start=$timestamps[$i];
			}
		} 

		if ($state == "peak")
		{
			if ($values[$i] >= $background_cutoff)
			{
				$sum+=$values[$i];
				$dwell++;
				array_push($peak_timestamps, $timestamps[$i]);
				array_push($peak_values, $values[$i]);
			} else
			{
				array_push($peak_start,$start);
				array_push($peak_dwell,$dwell);
				array_push($peak_sum,$sum);
				$dwell=0;
				$sum=0;
				$state = "no_peak";
			}
		}
	}


	//echo "Nr. of peaks: " . $peak_counter . ", Peak-Values: " . count($peak_values) . "\r\n\r\n";
	echo "Nr. of peaks: " . $peak_counter . "\r\n\r\n";

	$str = "dwell-times,peak-sum\r\n";
	for ($i=0;$i<count($peak_dwell);$i++)
	{
		//$str .= 	$peak_start[$i] . "," . $peak_dwell[$i] . "," . $peak_sum[$i] . "\r\n";
		$str .= $peak_dwell[$i] . "," . $peak_sum[$i] . "\r\n";
	}

	file_put_contents(__DIR__ . "/results/".basename($filename,".csv"). "_result.csv",$str);
}


if (!file_exists(__DIR__ . '/results')) {
    mkdir(__DIR__ . '/results', 0777, true);
}

$files = glob(__DIR__ . "/csv_files/*.csv");

for ($i=0;$i<count($files);$i++)
{
	echo $files[$i] . "\r\n";
	extract_peaks($files[$i],$min_nr_of_dwell_for_peak,$background_cutoff);
}


