--TEST--
url parser multibyte/utf-8
--SKIPIF--
<?php
include "skipif.inc";
?>
--FILE--
<?php
echo "Test\n";

$urls = array(
	"s\xc3\xa7heme:",
	"s\xc3\xa7heme://h\xc6\x9fst",
	"s\xc3\xa7heme://h\xc6\x9fst:23/päth/öf/fıle"
);

foreach ($urls as $url) {
	printf("\n%s\n", $url);
	var_dump(new http\Url($url, null, http\Url::PARSE_MBUTF8));
}
?>
DONE
--EXPECTF--
Test

sçheme:
object(http\Url)#%d (8) {
  ["scheme"]=>
  string(7) "sçheme"
  ["user"]=>
  NULL
  ["pass"]=>
  NULL
  ["host"]=>
  NULL
  ["port"]=>
  NULL
  ["path"]=>
  NULL
  ["query"]=>
  NULL
  ["fragment"]=>
  NULL
}

sçheme://hƟst
object(http\Url)#%d (8) {
  ["scheme"]=>
  string(7) "sçheme"
  ["user"]=>
  NULL
  ["pass"]=>
  NULL
  ["host"]=>
  string(5) "hƟst"
  ["port"]=>
  NULL
  ["path"]=>
  NULL
  ["query"]=>
  NULL
  ["fragment"]=>
  NULL
}

sçheme://hƟst:23/päth/öf/fıle
object(http\Url)#%d (8) {
  ["scheme"]=>
  string(7) "sçheme"
  ["user"]=>
  NULL
  ["pass"]=>
  NULL
  ["host"]=>
  string(5) "hƟst"
  ["port"]=>
  int(23)
  ["path"]=>
  string(16) "/päth/öf/fıle"
  ["query"]=>
  NULL
  ["fragment"]=>
  NULL
}
DONE
