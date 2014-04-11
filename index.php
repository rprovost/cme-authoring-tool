<?php
session_start();

$action = isset($_REQUEST['do']) ? $_REQUEST['do'] : 'home';

include_once 'source/' . $action . '.php';
