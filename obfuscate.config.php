<?php

use Naneau\Obfuscator\Settings;

require_once __DIR__ . '/vendor/autoload.php';

return (new Settings())
    ->setObfuscateFunctions(true)
    ->setObfuscateVariables(true)
    ->setObfuscateClasses(true)
    ->setStripComments(true)
    ->setTargetDirectory(__DIR__ . '/build/obfuscated')
    ->setSourceDirectory(__DIR__ . '/src');
