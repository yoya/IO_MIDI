<?php

require_once 'IO/MIDI.php';

$options = getopt("f:s:o:");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false) || (isset($options['s']) === false)) {
    echo "Usage: php midisysexadd.php -f <midi_file> -s <sysex sequence> [-o offset]\n";
    echo "ex) php midisysexadd.php -f in.mid -s 4300010203 \n";
    echo "ex) php midisysexadd.php -f in.mid -s 4300010203 -o 10\n";
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

$offset = isset($options['o'])?((int)$options['o']):0;

$midi = new IO_MIDI();
$midi->parse($mididata);
$res = [];

foreach ($midi->tracks as $key => &$value) {
	$first_key = $key;
	break;
}

$track = $midi->tracks[$first_key]["track"];
array_splice($track, $offset, 0, $sysexList);
$midi->tracks[$first_key]["track"] = $track;

$opts = array();

//$opts['runningstatus'] = true;

echo $midi->build($opts);
