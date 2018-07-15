<?php
    
require_once 'functions.php';
require_once 'auth_reg.php';

if (!isRegister()) {
    redirect('index');
}

if (isRegister()) {
    session_destroy();
    redirect('index');
}