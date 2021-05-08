<?php
require 'helpers.php';

$result = $suiteResults['fail'] === 0 ? 'pass' : 'fail';

echo
str_repeat('-', 80)."\n",
" $title  [$result]\n";

foreach($cases as $caseTitle => $case) {
  echo
	"\n".str_repeat('-', 80)."\n",
	"[$result]  $caseTitle  {pass {$case['pass']} / fail {$case['fail']}}\n\n";

	foreach ($case['tests'] as $test) {
		echo
		"[{$test['result']}] {$test['type']}()\n",
		str_repeat(' ', 7)."line {$test['line']}, {$test['file']}\n",
		str_repeat(' ', 7)."{$test['source']}\n";
	}
}

echo
str_repeat('=', 80)."\n",
"Tests: [$result], {pass {$suiteResults['pass']} / fail {$suiteResults['fail']}}, ",
percent($suiteResults)."% success\n";
