<?php

namespace App\ModuleProcess\Aion;

class JsonPretty
{
	private $filePath;
	function __construct($projectDir)
	{
		$this->filePath = $projectDir.'/var/data';
	}
	function safe_json_encode($data, $pretty = false)
	{
		$options = 0;
		if ($pretty)
		{
			//$options |= JSON_PRETTY_PRINT;
			$options |= JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
		}

		$json = json_encode($data, $options);

		if (json_last_error() !== JSON_ERROR_NONE)
		{
			throw new \Exception('JSON encode error: ' . json_last_error_msg());
		}

		return $json;
	}
	function save_file($folder, $file_pref,  $file_name, $json, $date_suff = true)
	{
		if ($date_suff)
		{
			$date = date('dmy_His');
		}
		else
		{
			$date = '';
		}

		$file = $this->filePath . '/' . $folder . '/' . $file_pref .  $file_name . $date . '.json';
		file_put_contents($file, $json);
	}
	function prepare_and_save($p)
	{
		$folder = $p['file_folder'];
		$file_pref = $p['file_pref'];
		$file_name = $p['file_name'];
		$data = $p['data'];
		$date_suff = $p['date_suff'];
		
		$pretty = true;

		$array_data = json_decode($data, true);

		$json = $this->safe_json_encode($array_data, $pretty);

		$this->save_file($folder, $file_pref, $file_name, $json, $date_suff);
	}
}
