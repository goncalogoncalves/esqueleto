[![Semver](http://img.shields.io/SemVer/1.0.0.png)](http://semver.org/spec/v1.0.0.html)
[![Open Source Love](https://badges.frapsoft.com/os/v2/open-source.svg?v=103)](https://github.com/ellerbrock/open-source-badges/)
[![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php)
[![Code Climate](https://lima.codeclimate.com/github/goncalogoncalves/php-performance/badges/gpa.svg)](https://lima.codeclimate.com/github/goncalogoncalves/php-performance)
# php-performance
Class responsible for assisting in performance evaluation of a PHP code

#### Example:
```php
use Devgo\Performance;
$performance = new Performance();
$performance->addStep("1");
usleep(300000); // 0.30s
$performance->addStep("2");
usleep(100000); // 0.10s
$performance->addStep("3");
$steps = $performance->getSteps();
$report = $performance->buildReport();
//$resultSave = $performance->saveReport('performance.txt');

print_r("<pre>");
var_dump($report);
print_r("</pre>");

```
#### Output:
```
___ NEW REPORT ___  2017-02-23 18:40:38

NEW STEP: 1
Memory (usage: 14.8 mb / peak: 14.84 mb)

NEW STEP: 2
Memory (usage: 14.8 mb / peak: 14.84 mb)
Duration from _1_ to _2_:
0.3005 seconds  (Minutes: 0 / Seconds: 0)

NEW STEP: 3
Memory (usage: 14.8 mb / peak: 14.84 mb)
Duration from _2_ to _3_:
0.1006 seconds  (Minutes: 0 / Seconds: 0)

Execution time: 0.401 seconds
```

#### Install with composer:
```
composer require devgo/php-performance
```
