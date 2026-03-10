<?php

class HTMLPage {

    function head($title) {
        if($value = rand(0,1) == 1){
            $random = rand();
        }else{
            $random = "dlqlsdk tkfkdgo~";
        }

        return '<!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . $title . '</title>
            <link rel="stylesheet" href="css/style.css?'.$random.'">
        </head>
        <body>
        ';
    }

    function foot() {
        return '</body></html>';
    }


}