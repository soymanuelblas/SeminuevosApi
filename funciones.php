<?php

function guiacss(){
    ?>
   <link rel="stylesheet" href="guia7/css/iGuider.css">
    <link rel="stylesheet" href="guia7/themes/neon/iGuider-theme-neon.css">
    <link rel="stylesheet" href="guia7/doc_files/css/prism.css">

    <style>
         @media screen and (max-width: 1024px) {
            .ocultar_guia {
            display: none !important;
            }
        }
    </style>

    <?php
}

function guiascript(){
    ?>
    <script src="guia7/js/jquery.iGuider.js"></script>
    <script src="guia7/themes/neon/iGuider-theme-neon.js"></script>
    <script src="guia7/doc_files/js/prism.js"></script>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>

    <?php
}

function btn1($name = null){
    ?>
    <i class="material-icons" style="cursor: pointer;color: #010159; position: fixed;right: 10px; top: 10px; font-size: 35px;"
       onclick="<?=$name?>();"
    >help</i>
    <?php
}

function btn2($name = null){
    ?>
    <button id="<?=$name?>" class="btn btn-primary waves-effect" ><i style="font-size: 13px;" class="material-icons">help</i>Ayuda</button>
    <?php
}

function btn3($name = null ){
    ?>
    <i class="material-icons" style="cursor: pointer;color: #010159; position: absolute; right: 10px; font-size: 35px; top: 10px;"
       onclick="<?=$name?>();"
    >help</i>
    <?php
}

function btn4($name = null ){
    ?>
    <i id="<?=$name?>" class="material-icons" style="cursor: pointer;color: #010159; position: absolute; right: 10px; font-size: 35px; top: 10px;"
    >help</i>

    <?php
}

function hideguia(){
    ?>
    <style>
        .ocultar_guia {
            display: none !important;
        }
    </style>
    <?php
}




?>