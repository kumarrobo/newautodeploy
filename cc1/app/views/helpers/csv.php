<?php
class CsvHelper extends AppHelper
{
var $delimiter = ',';
var $enclosure = '"';
var $filename = 'Export.csv';
var $line = array();
var $buffer;

function CsvHelper()
{
    $this->clear();
}
function clear() 
{
    $this->line = array();
    $this->buffer = fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
}

function addField($value) 
{
    $this->line[] = $value;
}

function endRow() 
{
    $this->addRow($this->line);
    $this->line = array();
}

function addRow($row) 
{
    ob_clean();
    fputcsv($this->buffer, $row, $this->delimiter, $this->enclosure);
}

function renderHeaders() 
{
    header('Content-Type: text/csv');
    header("Content-type:application/vnd.ms-excel");
    header("Content-disposition:attachment;filename=".$this->filename);
}

function setFilename($filename) 
{
    $this->filename = $filename;
    if (strtolower(substr($this->filename, -4)) != '.csv') 
    {
        $this->filename .= '.csv';
    }
}

function render($outputHeaders = true, $to_encoding = null, $from_encoding ="auto") 
{
    if ($outputHeaders) 
    {
        if (is_string($outputHeaders)) 
        {
            $this->setFilename($outputHeaders);
        }
        $this->renderHeaders();
    }
    rewind($this->buffer);
    $output = stream_get_contents($this->buffer);

    if ($to_encoding) 
    {
        $output = mb_convert_encoding($output, $to_encoding, $from_encoding);
    }
    return $this->output($output);
}

function array_to_csv($array, $download = "")
{
    if ($download != "")
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachement; filename="' . $download . '"');
    }
    
    ob_start();
    $f = fopen('php://output', 'w') or show_error("Can't open php://output");
    $n = 0;
    foreach ($array as $line)
    {
        $n++;
        if ( ! fputcsv($f, $line))
        {
            show_error("Can't write line $n: $line");
        }
    }
    
    flock($f,LOCK_EX);
    
    fclose($f) or show_error("Can't close php://output");
    $str = ob_get_contents();
    ob_end_clean();
    
    if ($download == "")
    {
        return $str;
    }
    else
    {
        echo $str;
    }
}
}
?>