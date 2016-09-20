<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'socketEmmiter\\' => array($baseDir . '/modules'),
    'phpDocumentor\\Reflection\\' => array($vendorDir . '/phpdocumentor/reflection-common/src', $vendorDir . '/phpdocumentor/type-resolver/src', $vendorDir . '/phpdocumentor/reflection-docblock/src'),
    'messagesModel\\' => array($baseDir . '/models'),
    'messagesModelInterface\\' => array($baseDir . '/models'),
    'messagesAPI\\' => array($baseDir . '/controllers'),
    'messageController\\' => array($baseDir . '/controllers'),
    'dbModule\\' => array($baseDir . '/modules'),
    'Webmozart\\Assert\\' => array($vendorDir . '/webmozart/assert/src'),
    'Symfony\\Component\\Yaml\\' => array($vendorDir . '/symfony/yaml'),
    'ResponseModule\\' => array($baseDir . '/modules'),
    'ResponseModuleJSON\\' => array($baseDir . '/modules'),
    'RateLimiter\\' => array($baseDir . '/modules'),
    'Psr\\Log\\' => array($vendorDir . '/psr/log/Psr/Log'),
    'MessagesRateLimiter\\' => array($baseDir . '/modules'),
    'ElephantIO\\' => array($vendorDir . '/wisembly/elephant.io/src', $vendorDir . '/wisembly/elephant.io/test'),
    'Doctrine\\Instantiator\\' => array($vendorDir . '/doctrine/instantiator/src/Doctrine/Instantiator'),
    'DeepCopy\\' => array($vendorDir . '/myclabs/deep-copy/src/DeepCopy'),
    'API\\' => array($baseDir . '/controllers'),
);
