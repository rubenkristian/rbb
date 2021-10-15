<!Doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            @font-face {
                font-family: 'Nexa';
                src: url("file:///android_res/font/nexaregular.otf");
            }
            body{
                font-family: 'Nexa';
                font-size: 18px;
            }
            table, th, td{
                table-layout: fixed ;
                width: 100%;
                border-collapse: collapse;
                max-width: 800px;
                margin-left: auto;
                margin-right: auto;
            }
            th {
                padding: 20px;
            }
            td {
                padding: 10px;
            }
            .button {
                background-color: #202d57; /* Green */
                border: none;
                color: white;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                position: relative;
                left: 50%;
                right: 50%;
                margin: 10px 0 10px 0;
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, 0%);
            }
            .tab {
              padding-left: 2px;
            }
            a {
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <?php foreach($documents as $index => $document): ?>
            <a href="../public/documents/<?=$document['type_extension']?>/<?=$document['filename']?>.<?=$document['type_extension']?>">Download file <?=$document['name']?></a>
        <?php endforeach; ?>
    </body>
</html>