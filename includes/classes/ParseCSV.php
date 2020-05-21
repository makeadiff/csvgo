<?php
// namespace Parse\ParseCSV;

class ParseCSV implements Iterator
{
	protected $csv_location = '';
	protected $contents = '';
	protected $lines = [];
	protected $data = [];
	private $line_index = 1;

	/**
	 * :TODO:
	 * loadFile()
	 * Autoload support
	 * Composer install
	 * Seperate class for row, column, cell?
	 * phpDoc documentation.
	 */

	public function __construct($url = '')
	{
		if($url) {
			$this->csv_location = $url;
			$this->loadURL();
			$this->parse();
		}
	}

	public function loadFile($file = '')
	{
		if($file) $this->csv_location =  $file;
		if(!$this->csv_location) die("Please specify a CSV file as the argument to this function");

		$this->contents = file_get_contents($this->csv_location);
		$this->lines = explode("\n", $this->contents);
	}

	public function loadURL($url = '')
	{
		if($url) $this->csv_location =  $url;
		if(!$this->csv_location) die("Please specify a GoogleSpreadsheet CSV URL as the argument to this function");
		$cache = true;
		if(!empty($_GET['no_cache']) and $_GET['no_cache']) $cache = false;
		$this->contents = load($this->csv_location, ['cache' => $cache]); // Calls to external library(iFrame function.)
		if(!$this->contents) die("Can't load the CSV URL '{$this->csv_location}'");
		// dump($this->contents);
		$this->lines = explode("\n", $this->contents);
	}

	public function parse($contents = '')
	{
		if($contents) {
			$this->contents = $contents;
			$this->lines = explode("\n", $this->contents);
			$this->line_index = 1;
		}
		if(!$this->lines) die("Can't find any content to parse. Use the loadURL('csv_location') to load the spreadsheet contents before calling the parse()");

		$row_index = 1;
		foreach($this->lines as $l) {
			$row = str_getcsv($l);

			foreach ($row as $column_index => $value) {
				$column_name = $this->numberToColumnLetterIndex($column_index);

				$this->data[$row_index][$column_name] = $value;
			}

			$row_index++;
		}
	}

	public function getCell($cell_name)
	{
		$cell_name = strtoupper($cell_name);

		if(preg_match('/(\D+)(\d+)/', $cell_name, $matches)) {
			$column_name = $matches[1];
			$row_index = $matches[2];
		} else return false;

		return $this->data[$row_index][$column_name];
	}

	public function getRow($row_index)
	{
		return $this->data[$row_index];
	}

	public function getColumn($column_name)
	{
		$return = [];

		foreach ($this->data as $row_index => $row) {
			$return[$row_index] = $row[$column_name];
		}

		return $return;
	}


	/// Iterator functions
	public function rewind()
	{
		$this->line_index = 1;
	}

	public function current() {
        return $this->data[$this->line_index];
    }

    public function key() {
        return $this->line_index;
    }

    public function next() {
        ++$this->line_index;
    }

    public function valid() {
        return isset($this->data[$this->line_index]);
    }


	public function dump($print = false) {
		if($print) var_dump($this->data);
		return $this->data;
	}

	/// Taken from https://stackoverflow.com/a/3302991/5610806
	private function numberToColumnLetterIndex($num) {
	    $numeric = $num % 26;
	    $letter = chr(65 + $numeric);
	    $num2 = intval($num / 26);
	    if ($num2 > 0) {
	        return $this->numberToColumnLetterIndex($num2 - 1) . $letter;
	    } else {
	        return $letter;
	    }
	}
}
