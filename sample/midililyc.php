<?php

require_once 'IO/MIDI.php';

$options = getopt("f:");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    echo "Usage: php midililyc.php -f <midi_file>\n";
    echo "ex) php midililyc.php -f test.mid\n";
    exit(1);
}

$mididata = file_get_contents($options['f']);

$midi = new IO_MIDI();
$midi->parse($mididata);

$tracks = $midi->tracks;

$lilycTable = array();

foreach ($tracks as $idx => $track) {
    foreach ($track['track'] as $key => $chunk) {
        if(isset($chunk['MetaEventType']) && $chunk['MetaEventType'] == 0x5){
            $lilycTable[] = $chunk["MetaEventData"];
        }
    }
    foreach ($lilycTable as $lilyc) {
            echo $lilyc."\n";
    }
}

