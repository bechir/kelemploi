<?php

namespace App\Utils;

trait ClassDetectorTrait
{
    public function initName()
    {
        return strtolower((substr(__CLASS__, strrpos(__CLASS__, '\\') + 1)));
    }
}

/*

$time = microtime(true);
for($i=0; $i < 1000000; ++$i){
//$a=explode('\', CLASS); arraypop($a);
// 0.29 sec
substr(strrchr(CLASS, "\"), 1);
// 0.15 sec
//(new \ReflectionClass($this))->getShortName();
// 0.35 sec
//classbasename(CLASS);
// 3.6 sec
}
var_dump([
$time,
microtime(true),
(microtime(true)-$time),
]);

*/
