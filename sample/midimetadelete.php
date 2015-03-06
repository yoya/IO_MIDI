<?php

require_once 'IO/MIDI.php';

$options = getopt("f:t:");

$metasubtype = 0;
if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    echo "Usage: php midimetadelete.php -f <midi_file> -t <meta subtype>\n";
    echo "ex) php midimetadelete.php -f in.mid -t 3\n";
    echo "ex) php midimetadelete.php -f in.mid -t 4\n";
    exit(1);
}
$metasubtype = isset( $options['t'] )  ? (int)$options['t'] : 0 ;

$mididata = file_get_contents($options['f']);

$midi = new IO_MIDI();
$midi->parse($mididata);

$res = [];

foreach ($midi->tracks as $key => &$value) {
    foreach ($value["track"] as $key2 => &$value2) {
        if(isset($value2["MetaEventType"])){
        if (($metasubtype === 0) || 
            ($metasubtype == $value2["MetaEventType"])) {
                unset($midi->tracks[$key]["track"][$key2]);
            }
        }
    }
}

$opts = array();

//$opts['runningstatus'] = true;

echo $midi->build($opts);
