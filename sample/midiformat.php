<?php

require_once 'IO/MIDI.php';

$options = getopt("f:v:");

function usage() {
    echo "Usage: php midiformat.php -f <midi_file> -v <smf version>\n";
    echo "ex) php midiformat.php -f in.mid -v 0\n";
//    echo "ex) php midiformat.php -f in.mid -v 1\n";
}

if ((isset($options['f']) === false) || (is_readable($options['f']) === false) || (isset($options['v']) === false)) {
    usage();
    exit(1);
}

$mididata = file_get_contents($options['f']);
$smf_version = (int)$options['v'];

if ($smf_version !== 0) {
    fprintf(STDERR, "smf_version:$smf_version is not implemented yet.");
    usage();
    exit(1);
}

$midi = new IO_MIDI();
$midi->parse($mididata);

if ($midi->header['header']['Format'] === $smf_version) {
    fprintf(STDERR, "Warning: smf version($smf_version) remain unchanged.\n");
    echo $mididata;
}

// format 0 routine.

$first_track_key = null;
$first_track_value = null;

$allTrack = array();
$numberOfTracks = 0; // format 0;
foreach ($midi->tracks as $key => $value) {
    if (is_null($first_track_key)) {
        $first_track_key = $key;
        $first_track_value = $value;
    }
    $advance = 0;
    foreach ($value["track"] as $key2 => $value2) {
        $advance += $value2['DeltaTime'];
	$channel = isset($value2['MIDIChannel'])?($value2['MIDIChannel']):0;
	$advance_ch = sprintf("%d%02d", $advance, $channel);
	$allTrack[$advance_ch] = $value2;
    }
}

ksort($allTrack);

$midi->header['header']['Format'] =$smf_version;
$midi->header['header']['NumberOfTracks'] = 1; // format 0;
$prev_advance = 0;
foreach ($allTrack as $advance_ch => $value) {
    $advance = (int) ($advance_ch / 100);
    $allTrack[$advance_ch]['DeltaTime'] = $advance - $prev_advance;
    $prev_advance = $advance;
}

$allTrack = array_values($allTrack);

$first_track_value['track'] = $allTrack;
$midi->tracks = array($first_track_key => $first_track_value);

$opts = array();

// $opts['runningstatus'] = true;

echo $midi->build($opts);
