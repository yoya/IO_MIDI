<?php
  /*
   http://otonanokagaku.net/nsx39/data/nsx39midiguide.pdf
   */

// require_once 'IO/Bit.php';

class IO_MIDI_SysEx_NSX39 {
    const SYSEX_PREFIX = "\x43\x79\x09\x11";
    var $Type_String_Table = null;
    var $Voice_Chara_Table = null;
    var $Command_Range_Table = null;
    function __construct() {
        static $Type_String_Table = array(
            0x01 => "VERSION_REQUEST",
            0x02 => "VERSION_RESPONSE",
            0x0A => "LYRIC_INPUT",
            0x0B => "COM_SLOT_CONTENT_REQ",
            0x1B => "COM_SLOT_CONTENT_RES",
            0x0C => "COM_SLOT_INPUT",
            0x0D => "COM_DIRECT_INPUT",
            0x0E => "LYRIC_NUM_POS_REQ",
            0x1E => "LYRIC_NUM_POS_RES",
            0x0F => "LYRIC_CONTENT_REQ",
            0x1F => "LYRIC_CONTENT_RES",
            0x20 => "SWITCH_STATUS_REPORT",
            0x21 => "STATUS_REPORT",
        );
        static $Voice_Chara_Table = array(
            // 0x00-
            'あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く',
            'け', 'こ', 'が', 'ぎ', 'ぐ', 'げ', 'ご', 'きゃ',
            // 0x10-
             'きゅ', 'きょ', 'ぎゃ', 'ぎゅ', 'ぎょ', 'さ', 'すぃ', 'す',
            'せ', 'そ', 'ざ', 'ずぃ', 'ず', 'ぜ', 'ぞ', 'しゃ', 
            // 0x20-
            'し', 'しゅ', 'しぇ', 'しょ', 'じゃ', 'じ', 'じゅ', 'じぇ',
            'じょ', 'た', 'てぃ', 'とぅ', 'て', 'と', 'だ', 'でぃ',
            // 0x30-
            'どぅ', 'で', 'ど', 'てゅ', 'でゅ', 'ちゃ', 'ち', 'ちゅ',
            'ちぇ', 'ちょ', 'つぁ', 'つぃ', 'つ', 'つぇ', 'つぉ', 'な',
            // 0x40-
            'に', 'ぬ', 'ね', 'の', 'にゃ', 'にゅ', 'にょ', 'は',
            'ひ', 'ふ', 'へ', 'ほ', 'ば', 'び', 'ぶ', 'べ',
            // 0x50-
            'ぼ', 'ぱ', 'ぴ', 'ぷ', 'ぺ', 'ぽ', 'ひゃ', 'ひゅ',
            'ひょ', 'びゃ', 'びゅ', 'びょ', 'ぴゃ', 'ぴゅ', 'ぴょ', 'ふぁ',
            // 0x60-
            'ふぃ', 'ふゅ', 'ふぇ', 'ふぉ', 'ま', 'み', 'む', 'め',
            'も', 'みゃ', 'みゅ', 'みょ', 'や', 'ゆ', 'よ', 'ら',
            // 0x70-
            'り', 'る', 'れ', 'ろ', 'りゃ', 'りゅ','りょ', 'わ',
            'うぃ', 'うぇ', 'うぉ', 'ん', 'ん', 'ん', 'ん', 'ん',
        );
        static $Command_Range_Table = array (
         //
         'DIRECT_MIDI_3' => array("\x00\x00\x00\x00", "\x07\x7f\x7f\x7f"),
         'DIRECT_MIDI_2' => array("\x0f\x00\x00\x00", "\x0f\x03\x7f\x7f"),
         'DIRECT_MIDI_1' => array("\x0f\x10\x00\x00", "\x0f\x10\x7f\x7f"),
         //
         'NOTEON'  => array("\x08\x09\x00\x00"),
         'NOTEOFF' => array("\x08\x08\x00\x00"),
         'ADV_PB'  => array("\x08\x0e\x00\x00", "\x08\x0e\x7f\x7f"),
         'REVOICE' => array("\x08\x01\x00\x00"),
         //
         'KASHI_POS_INC'  => array("\x09\x01\x00\x00"),
         'KASHI_POS_SEL'  => array("\x09\x02\x00\x00", "\x09\x02\x00\x3f"),
         'KASHI_SLOT_SEL' => array("\x09\x03\x00\x00", "\x09\x03\x00\x0f"),
         'MOJI_SEL'       => array("\x09\x04\x00\x00", "\x09\x04\x00\x7f"),
         'MOJI_SET_KASHI' => array("\x09\x05\x00\x00"),
         //
         'VOL_SET'            => array("\x0a\x01\x00\x00", "\x0a\x01\x00\x08"),
         'VOL_UP'             => array("\x0a\x02\x00\x00"),
         'VOL_DOWN'           => array("\x0a\x03\x00\x00"),
         'KEY_UP'             => array("\x0a\x04\x01\x00"),
         'KEY_DOWN'           => array("\x0a\x04\x00\x00"),
         'TUNING_STOP'        => array("\x0a\x05\x00\x00"),
         'TUNING_START'       => array("\x0a\x05\x01\x00"),
         'TUNING_TOGGLE'      => array("\x0a\x05\x02\x00"),
         'NSX1_GOKAN_OFF'     => array("\x0a\x06\x00\x00"),
         'NSX1_GOKAN_ON'      => array("\x0a\x06\x01\x00"),
         'NSX1_GOKAN_TOGGLE'  => array("\x0a\x06\x02\x00"),
         'MODE_NORMAL'        => array("\x0a\x08\x00\x00"),
         'MODE_DOREMI'        => array("\x0a\x08\x01\x00"),
         'MIDI_RESET'         => array("\x0a\x07\x00\x00"),
         'ROM_RESET'          => array("\x0a\x07\x01\x01"),
		 //
         'END_SLOT'       => array("\x0c\x01\x00\x00"),
         'CACCO_START'    => array("\x0c\x02\x00\x00"),
         'CACCO_END'      => array("\x0c\x03\x00\x00", "\x0c\x03\x00\x0f"),
         'CACCO_END_TIME' => array("\x0c\x03\x01\x00", "\x0c\x03\x01\x0f"),
         'TIME_TAIL'      => array("\x0c\x04\x00\x00"),
         'TIME_CANCEL'    => array("\x0c\x05\x00\x00"),
         'TIME_WAIT'      => array("\x0d\x00\x00\x00", "\x0d\x7f\x7f\x7f"),
		 //
         'DIRECT_MIDI_OUT_3' => array("\x10\x00\x00\x00", "\x17\x7f\x7f\x7f"),
         'DIRECT_MIDI_OUT_2' => array("\x1A\x00\x00\x00", "\x1A\x7f\x7f\x7f"),
         'DIRECT_MIDI_OUT_1' => array("\x19\x00\x00\x00", "\x19\x7f\x7f\x7f"),
		 //
         'SLOT_EXE' => array("\x0a\x48\x00\x00", "\x0a\x47\00\x7f"),
        );
        $this->Voice_Chara_Table = $Voice_Chara_Table;
        $this->Type_String_Table = $Type_String_Table;
		$this->Command_Range_Table = $Command_Range_Table;
    }
    function dumpPayload($payload, $opts = array()) {
        $data = unpack("C*", $payload);
        $dataLen = strlen($payload);
        $type = $data[1]; // 1 origin
        $typeStr = $this->Type_String_Table[$type];
        echo "    $typeStr($type)";
        switch ($type) {
          case 0x01: // VERSION_REQUEST
        break;
          case 0x11: // VERSION_RESPONSE
            $ver = $data[2];
            echo ", VERSION:$ver";
        break;
          case 0x0A: // LYRIC_INPUT
            $slot = $data[2];
            echo ", SLOT:$slot, LYRIC:";
            for ($i = 3 ; $i <= $dataLen - 1 ; $i++) {
                $c = $data[$i];
                printf(" %02X(%s)", $c, $this->Voice_Chara_Table[$c]);
            }
        break;
/*
          case 0x0B: // COM_SLOT_CONTENT_REQ
        break;
          case 0x1B: // COM_SLOT_CONTENT_RES
        break;
*/
          case 0x0C: // COM_SLOT_INPUT
            $slot = $data[2];
            echo ", SLOT:$slot, COMMAND:";
            $no = 0;
            for ($i = 3 ; $i <= $dataLen - 1 ; $i += 4) {
				// minus 1 becaues unpack to 1 origin  
                $command_data = substr($payload, $i-1, 4);
                printf("\n      [%d] %s:", $no++,
					   $this->getCommandName($command_data));

                for ($j = 0; $j < 4 ; $j++) {
                    printf(" %02X", $c = $data[$i+$j]);
                }
            }
        break;
          case 0x0D: // COM_DIRECT_INPUT
            echo ", COMMAND:";
            $no = 0;
            for ($i = 2 ; $i <= $dataLen - 1 ; $i += 4) {
				// minus 1 becaues unpack to 1 origin  
                $command_data = substr($payload, $i-1, 4);
                printf("\n      [%d] %s:", $no++,
					   $this->getCommandName($command_data));
                for ($j = 0; $j < 4 ; $j++) {
                    printf(" %02X", $c = $data[$i+$j]);
                }
            }
        break;
/*
          case 0x0E: // LYRIC_NUM_POS_REQ
        break;
          case 0x1E: // LYRIC_NUM_POS_RES
        break;
          case 0x0F: // LYRIC_CONTENT_REQ
        break;
          case 0x1F: // LYRIC_CONTENT_RES
        break;
          case 0x20: // SWITCH_STATUS_REPORT
        break;
          case 0x21: // STATUS_REPORT
        break;
*/
      default:
        echo ", Not implemented, yet!";
        }
    }
	function getCommandName($command_data) {
		//echo "\nXX  " . bin2hex($command_data)."\n";
		foreach ($this->Command_Range_Table as $command_name => $range) {
			$start = $range[0];
			$end = (count($range) < 2)?$range[0]:$range[1];
			//echo "YY  ".bin2hex($start)." - ". bin2hex($end)."\n";
			if (($start <= $command_data) && ($command_data <= $end)) {
					return $command_name;
			}
		}
		return "Unknown";
	}
}
