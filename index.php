<?php

include_once 'AvgDestinationCalculator.php';

$stdin = fopen('php://stdin', 'r');
$n = trim(fgets($stdin));

$directions = [];

while ($n--) {
    $directions[] = trim(fgets($stdin));
}

$calulator = new AvgDestinationCalculator($directions);
$calulator->run();
