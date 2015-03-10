<?php

require_once 'IO/MIDI.php';

$options = getopt("f:s:");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false) || (isset($options['s']) === false)) {
    echo "Usage: php midimetadelete.php -f <midi_file> -s <sysex sequence>\n";
    echo "ex) php midimetadelete.php -f in.mid -s 4300010203 \n";
    exit(1);
}

$mididata = file_get_contents($options['f']);

$sysexList = array();
foreach (explode(',', $options['s']) as $sysex) {
    $sysexList []= array(
          'DeltaTime' => 0,
          'EventType' => 15,
          'SystemEx' => hex2bin(trim($sysex))
    );
}

$midi = new IO_MIDI();
$midi->parse($mididata);
$res = [];

foreach ($midi->tracks as $key => &$value) {
	$first_key = $key;
	break;
}

$track = $midi->tracks[$first_key]["track"];
array_splice($track, 0, 0, $sysexList);
$midi->tracks[$first_key]["track"] = $track;

$opts = array();

//$opts['runningstatus'] = true;

echo $midi->build($opts);
