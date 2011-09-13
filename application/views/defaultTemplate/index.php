<?php
$css = TEMPLATE_URL . 'stylesheets' . DS .'style.css';
echo <<<Template
<!docetype html>
    <html>
        <head>
            <title> $title </title>
            <link rel="stylesheet" href="$css" type="text/css" />
        </head>
        <body>
            <div id="maindiv">
                <h2> $title </h2>
                <p> $content </p>
                <p> $modelmessage </p>
            </div>
        </body>
    </html>
Template;
?>