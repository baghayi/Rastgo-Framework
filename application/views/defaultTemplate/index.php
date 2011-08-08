<!docetype html>
    <html>
        <head>
            <title><?=$title?></title>
            <link rel="stylesheet" href="<?php echo SITE_TEMPLATE_PATH . 'stylesheets' . DS .'style.css'; ?>" type="text/css" /> 
        </head>
        <body>
            <div id="maindiv">
                <h2><?=isset($title)?$title:'';?></h2>
                <p><?=isset($content)?$content:'';?></p>
                <p><?=isset($modelmessage)?$modelmessage:'';?></p>
            </div>
        </body>
    </html>