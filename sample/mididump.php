<?php

require_once 'IO/MIDI.php';

$options = getopt("f:hv");

if ((isset($options['f']) === false) || (is_readable($options['f']) === false)) {
    fprintf(STDERR, "Usage: php mididump.php -f <midi_file> [-h]\n");
    fprintf(STDERR, "ex) php mididump.php -f test.mid -h \n");
    exit(1);
}

$mididata = file_get_contents($options['f']);

$midi = new IO_MIDI();
$midi->parse($mididata);

$opts = array();
if (isset($options['h'])) {
    $opts['hexdump'] = true;
}
if (isset($options['v'])) {
    $opts['verbose'] = true;
}

$midi->dump($opts);
