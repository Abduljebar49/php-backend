<?php
require_once('./initialize.php');

$operation = new Operation();

$operation->create($_POST);
