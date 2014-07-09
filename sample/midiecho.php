<?php

require_once 'IO/MIDI.php';

$options = getopt("f:r");


if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    echo "Usage: php midiecho.php -f <midi_file> [-r]\n";
    echo "ex) php midiecho.php -f in.mid -r\n";
    exit(1);
}

$mididata = file_get_contents($options['f']);

$midi = new IO_MIDI();
$midi->parse($mididata);

$opts = array();

if (isset($options['r'])) {
    $opts['runningstatus'] = true;
}

echo $midi->build($opts);
