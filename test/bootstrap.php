<?php

error_reporting(E_ALL | E_STRICT);

require_once dirname(__FILE__).'/../vendor/autoload.php';

WP_Mock::setUsePatchwork(true);
WP_Mock::bootstrap();
