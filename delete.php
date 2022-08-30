<?php
require_once('./initialize.php');

$operation = new Operation();

$operation->delete($_POST);
