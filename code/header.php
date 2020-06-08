<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link  rel="icon" type="image/x-icon" href="favicon.png" />

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Magento Rest API demo</title>

        <script type="text/javascript" src="libs/jquery/jquery.min.js"></script>

        <link rel="stylesheet" href="libs/jquery/jquery-ui.min.css" />
        <script type="text/javascript" src="libs/jquery/jquery-ui.min.js"></script>

        <link rel="stylesheet" href="libs/bootstrap/bootstrap.min.css" />
        <script type="text/javascript" src="libs/bootstrap/bootstrap.bundle.min.js"></script>

        <?php
        if (!empty($customJSFile) && file_exists($customJSFile)) {
            echo '<script type="text/javascript" src="'.$customJSFile.'"></script>';
        } 
        ?>

        <style type="text/css">
            div.jumbotron.custom {
                padding: 1rem;
                margin: 1em 0 2em 0;
            }
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            input[type=number] {
                -moz-appearance:textfield; /* Firefox */
            }

            .sortable {
                list-style-type: none;
                margin: 0;
                padding: 0;
            }
            .sortable li {
                margin: 0 3px 3px 3px;
                padding: 0.4em;
                padding-left: 1.5em;
                cursor: grabbing;
            }
            .sortable li span:first-child {
                margin-left: -1.3em;
            }
            .sortable li span:last-child {
                margin-top: 0.3rem;
                float: right;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark justify-content-between">

            <ul class="navbar-nav mr-auto">
                <a class="navbar-brand" href="index.php">Rest API Demo</a>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class=" active nav-link dropdown-toggle" href="#" id="navbarDropdown" data-toggle="dropdown">Add Product</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="addsimple.php">Simple Product</a>
                        <a class="dropdown-item" href="addbundle.php">Bundle Product</a>
                    </div>
                </li>
            </ul>
        </nav>