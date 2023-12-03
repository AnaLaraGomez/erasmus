<?php
    class JsonEncodeHelper {
        public static function encodeArray($array) {
            // array es algo como [obj1, obj2..]
            $jsonRespObjects=array();
            foreach ($array as $elemento) {
                $jsonRespObjects[] =  $elemento->to_json();
            }
            // $jsonRespObjects => ['{...}', '{...}']
            // implode(',', $jsonRespObjects) => '{...},{...}'
            $jsonRespString = '[' . implode(',', $jsonRespObjects) . ']';
            return $jsonRespString;
        }
    }
?>