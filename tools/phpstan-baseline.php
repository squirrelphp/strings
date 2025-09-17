<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Call to function is_string\\(\\) with string will always evaluate to true\\.$#',
	'identifier' => 'function.alreadyNarrowedType',
	'count' => 1,
	'path' => __DIR__ . '/../src/Attribute/StringFilter.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$objectOrClass of class ReflectionClass constructor expects class\\-string\\<T of object\\>\\|T of object, string given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Attribute/StringFilterExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$name of method Squirrel\\\\Strings\\\\StringFilterSelectInterface\\:\\:getFilter\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Attribute/StringFilterProcessor.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$array of function array_flip expects array\\<int\\|string\\>, array given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Condense/ConverterUnicode.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly invalid array key type mixed\\.$#',
	'identifier' => 'offsetAccess.invalidOffset',
	'count' => 1,
	'path' => __DIR__ . '/../src/Condense/ConverterUnicode.php',
];
$ignoreErrors[] = [
	'message' => '#^Property Squirrel\\\\Strings\\\\Condense\\\\ConverterUnicode\\:\\:\\$numberToString \\(array\\<int, string\\>\\) does not accept array\\.$#',
	'identifier' => 'assign.propertyType',
	'count' => 1,
	'path' => __DIR__ . '/../src/Condense/ConverterUnicode.php',
];
$ignoreErrors[] = [
	'message' => '#^Property Squirrel\\\\Strings\\\\Condense\\\\ConverterUnicode\\:\\:\\$stringToNumber \\(array\\<string, int\\>\\) does not accept array\\<\\(int\\|string\\)\\>\\.$#',
	'identifier' => 'assign.propertyType',
	'count' => 1,
	'path' => __DIR__ . '/../src/Condense/ConverterUnicode.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\Strings\\\\Filter\\\\TrimFilter\\:\\:filter\\(\\) should return string but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Filter/TrimFilter.php',
];
$ignoreErrors[] = [
	'message' => '#^Argument of an invalid type mixed supplied for foreach, only iterables are supported\\.$#',
	'identifier' => 'foreach.nonIterable',
	'count' => 1,
	'path' => __DIR__ . '/../src/Filter/WrapLongWordsWithHTMLFilter.php',
];
$ignoreErrors[] = [
	'message' => '#^Cannot access offset mixed on mixed\\.$#',
	'identifier' => 'offsetAccess.nonOffsetAccessible',
	'count' => 1,
	'path' => __DIR__ . '/../src/Filter/WrapLongWordsWithHTMLFilter.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$replacement of function preg_replace expects array\\<float\\|int\\|string\\>\\|string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Filter/WrapLongWordsWithHTMLFilter.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\.\\=" between string and mixed results in an error\\.$#',
	'identifier' => 'assignOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/Random/GeneratorAscii.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$pattern of function preg_replace expects array\\<string\\>\\|string, array\\|string given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$pattern of function preg_replace_callback expects array\\<string\\>\\|string, array\\|string given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$replacement of function preg_replace expects array\\<float\\|int\\|string\\>\\|string, array\\|string given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$subject of static method Squirrel\\\\Strings\\\\Regex\\:\\:replace\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$subject of static method Squirrel\\\\Strings\\\\Regex\\:\\:replaceWithCallback\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly impure call to function preg_match\\(\\) in pure method Squirrel\\\\Strings\\\\Regex\\:\\:isMatch\\(\\)\\.$#',
	'identifier' => 'possiblyImpure.functionCall',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly impure call to function preg_match_all\\(\\) in pure method Squirrel\\\\Strings\\\\Regex\\:\\:getMatches\\(\\)\\.$#',
	'identifier' => 'possiblyImpure.functionCall',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly impure call to function preg_replace\\(\\) in pure method Squirrel\\\\Strings\\\\Regex\\:\\:replace\\(\\)\\.$#',
	'identifier' => 'possiblyImpure.functionCall',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly impure call to method Squirrel\\\\Strings\\\\Regex\\:\\:generateRegexException\\(\\) in pure method Squirrel\\\\Strings\\\\Regex\\:\\:getMatches\\(\\)\\.$#',
	'identifier' => 'possiblyImpure.methodCall',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly impure call to method Squirrel\\\\Strings\\\\Regex\\:\\:generateRegexException\\(\\) in pure method Squirrel\\\\Strings\\\\Regex\\:\\:isMatch\\(\\)\\.$#',
	'identifier' => 'possiblyImpure.methodCall',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Possibly impure call to method Squirrel\\\\Strings\\\\Regex\\:\\:generateRegexException\\(\\) in pure method Squirrel\\\\Strings\\\\Regex\\:\\:replace\\(\\)\\.$#',
	'identifier' => 'possiblyImpure.methodCall',
	'count' => 1,
	'path' => __DIR__ . '/../src/Regex.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$name of method Squirrel\\\\Strings\\\\StringFilterSelectInterface\\:\\:getFilter\\(\\) expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Twig/StringExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between \'\\?\' and mixed results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 2,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between mixed and \'\\://\' results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between mixed and string results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 1,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Binary operation "\\." between string and mixed results in an error\\.$#',
	'identifier' => 'binaryOp.invalid',
	'count' => 2,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\Strings\\\\Url\\:\\:getHost\\(\\) should return string but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\Strings\\\\Url\\:\\:getPath\\(\\) should return string but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\Strings\\\\Url\\:\\:getQueryString\\(\\) should return string but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Squirrel\\\\Strings\\\\Url\\:\\:getScheme\\(\\) should return string but returns mixed\\.$#',
	'identifier' => 'return.type',
	'count' => 1,
	'path' => __DIR__ . '/../src/Url.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$string of function strlen expects string, mixed given\\.$#',
	'identifier' => 'argument.type',
	'count' => 7,
	'path' => __DIR__ . '/../src/Url.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
