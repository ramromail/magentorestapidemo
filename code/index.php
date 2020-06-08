<?php

require 'configs.php';

include('header.php');
?>

<div class="container">
    <div class="row"></div>

    <div class="jumbotron">
        <h1 class="display-4">Welcome</h1>
        <p class="lead">Welcome to Magento REST API demo.</p>
        <hr class="my-4">
        <p>It has following features.</p>
        <ul>
            <li>Support adding of Magento Simple product</li>
            <li>Support adding of some custom attributes, such as tax, description</li>
            <li>Support adding of Magento Bundle product</li>
            <li>All actions are performed via Magento Rest API</li>
        </ul>

        <hr class="my-4">
        <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
        
        <p>Try it here</p>
        <a class="btn btn-primary btn-lg" href="addsimple.php" role="button">Simple Product</a>
        <a class="btn btn-primary btn-lg" href="addbundle.php" role="button">Bundle Product</a>
        <hr class="my-4">
        <p>If you are getting authentication error, make sure api user name, password and magento host address are correct</p>
        <p>You can set the user name, password, hostname</p>
        <ul>
            <li>either via docker-compose.yml file</li>
            <li>Or change the file code/configs.php</li>
        </ul>    


    </div>
</div>