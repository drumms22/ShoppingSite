<?php

include('commonFile.php');
header("Content-Type: application/json; charset=UTF-8");

$container->get('rest_service')->processApi();