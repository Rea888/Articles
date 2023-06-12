<?php

require 'includes/init.php';

Auth::logout();

Url::redirect('/articles_main.php');
