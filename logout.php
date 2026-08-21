<?php


require_once('config.php');

// Destrói a sessão atual
session_destroy();

// Redireciona para a página de login
header("Refresh: 1; url=client/shop.php");