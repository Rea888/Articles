<?php

require 'includes/init.php';

Auth::logout();

Url::redirect('/article/articles_main.php');
