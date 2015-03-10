<?php
  /*
     System Exclusive
   */

// require_once 'IO/Bit.php';

class IO_MIDI_SysEx {
    function dumpPayload($payload, $opts = array()) {
        static $sysExVendors = null;
        if (is_null($sysExVendors)) {
            $dir = dirname(__FILE__);
            $d = opendir("$dir/SysEx");
            while ($file = readdir($d)) {
                if (substr($file, -4, 4) === ".php") {
                    $vendor = substr($file, 0, -4);
                    $sysExVendors []= $vendor;
                    require_once("IO/MIDI/SysEx/$vendor.php");
                }
            }
        }
        foreach ($sysExVendors as $vendor) {
            $klass = "IO_MIDI_SysEx_$vendor";
            $sysExPrefix = $klass::SYSEX_PREFIX;
            $sysExPrefixLen = strlen($sysExPrefix);
            if (strncmp($payload, $sysExPrefix, $sysExPrefixLen) === 0) {
                $vendersys = new $klass();
                $vendersys->dumpPayload(substr($payload, $sysExPrefixLen));
           }
        }
    }
}
