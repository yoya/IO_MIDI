<?php

require_once 'IO/MIDI.php';

$options = getopt("f:ctF");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    echo "Usage: php midililyc.php -f <midi_file> [-ctF]\n";
    echo "ex) php midililyc.php -f test.mid -c -F\n";
    exit(1);
}

$filename = $options['f'];

$countFlag = false;
$textFlag = false;
$filenameFlag = false;
if (isset($options['c'])) {
    $countFlag = true;
}
if (isset($options['t'])) {
    $textFlag = true;
}
if (isset($options['F'])) {
    $filenameFlag = true;
}


$mididata = file_get_contents($filename);

$midi = new IO_MIDI();
$midi->parse($mididata);

$tracks = $midi->tracks;

$count = 0;

if ($filenameFlag) {
   echo $filename;
}

if ($countFlag) {
   echo " ";
}
if ($textFlag) {
   echo "\n";
}

foreach ($tracks as $idx => $track) {
    foreach ($track['track'] as $key => $chunk) {
        if(isset($chunk['MetaEventType']) && $chunk['MetaEventType'] == 0x5){
	    if ($textFlag) {
	        echo $chunk["MetaEventData"]."\n";
            }
	    $count ++;
        }
    }
}

if ($countFlag) {
    echo $count."\n";
}
