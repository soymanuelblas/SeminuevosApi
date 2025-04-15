<?php
include "variables.php";
include "funciones.php";

$id = $_GET['id'];

$sql = "SELECT vehiculo.id,vehiculo.version_id AS idversion, version.descripcion as version, vehiculo.noexpediente, vehiculo.numeroserie,
        vehiculo.tipostatus_id AS statusvehiculoid, statusvehiculo.descripcion AS statusvehiculo , venta.id AS ventaid , venta.descripcion AS venta, vehiculo.precio,vehiculo.kilometraje, vehiculo.sitio_id AS sitioid, sitio.domicilio1 AS domicilio, vehiculo.tipo_vehiculo AS tipoid, tipo.descripcion AS tipovehiculo, vehiculo.color_id AS colorid, color.descripcion AS color, vehiculo.nomotor AS motor, vehiculo.fecha AS fecha, vehiculo.duenio AS duenioid, duenio.descripcion AS duenio, vehiculo.garantia AS garantiaid, garantia.descripcion AS garantia, vehiculo.precio_contado AS contado, vehiculo.numero_placa AS placa, vehiculo.duplicado AS duplicadoid, duplicado.descripcion AS duplicado,  vehiculo.observaciones, tipomarca.descripcion AS marca, tipoannio.descripcion AS annio ,tipomodelo.descripcion AS modelo, sitio.nombre AS nomsitio, tipomodelo.id AS modeloid, sitio.id AS miSiteMB
        FROM vehiculo 
        LEFT JOIN tipostatus AS venta ON vehiculo.status_venta = venta.id
        LEFT JOIN version AS version ON vehiculo.version_id = version.id
        LEFT JOIN sitio AS sitio ON vehiculo.sitio_id = sitio.id
        LEFT JOIN tipostatus AS tipo ON vehiculo.tipo_vehiculo = tipo.id
        LEFT JOIN tipostatus AS color ON vehiculo.color_id = color.id
        LEFT JOIN tipostatus AS statusvehiculo ON vehiculo.tipostatus_id = statusvehiculo.id
        LEFT JOIN tipostatus AS duenio ON vehiculo.duenio = duenio.id 
        LEFT JOIN tipostatus AS garantia ON vehiculo.garantia = garantia.id
        LEFT JOIN tipostatus AS duplicado ON vehiculo.duplicado = duplicado.id
        LEFT JOIN tipomodelo ON version.tipomodelo_id = tipomodelo.id
        LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
        LEFT JOIN tipoannio ON version.tipoannio_id = tipoannio.id WHERE vehiculo.id = '$id'";

$resultado = $mysqli->query($sql);
$row = $resultado->fetch_array(MYSQLI_ASSOC);

$modeloid = $row['modeloid'];
$versionid = $row['idversion'];
$miSiteMB = $row['miSiteMB'];


?>
<script src="js/select2.js"></script>


<div class="header" role="tab" role="button">
    <ul class="header-dropdown m-r--5" style="margin:-8px 0px;" id="ve">
        <li>
            <span class="ocultar_guia" style="color: black;">
                <i class="material-icons" style="cursor: pointer;color: #010159;font-size: 35px;"
                    onclick="guia();">help</i>
            </span>
        </li>
    </ul>
</div>


<form id="formularioContacto" method="post" action="javascript:void(0)">
    <input type="hidden" id="id" name="id" value="<?php echo $row['id']; ?>" />
    <input type="hidden" id="site" name="site" value="<?php echo $miSiteMB; ?>" />
    <input type="hidden" id="annioMB" name="annioMB" value="<?php echo $row['annio']; ?>" />

    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b># de Serie<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line serie_error">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a1" placeholder="" id="serie" name="serie"
                        onkeyup="mayus(this); this.value=NumText1(this.value)" onchange="validar();"
                        value="<?php echo $row['numeroserie']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Tipo<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a2" id="tipo" name="tipo"
                        value="<?php echo $row['tipoid']; ?>">
                        <?php
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=2 AND id != ".$row[tipoid]." ");
                            echo '<option value="'.$row[tipoid].'">'.$row[tipovehiculo].'</option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.$valores[descripcion].'</option>';
                            }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Version<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a3" id="version" name="version"
                        value="<?php echo $row['idversion']; ?>">
                        <?php
                        $query = $mysqli -> query ("SELECT version.id AS id,version.descripcion AS version, tipomarca.descripcion AS marca, tipoannio.descripcion AS annio FROM version
                            LEFT JOIN tipomodelo ON tipomodelo.id = version.tipomodelo_id
                            LEFT JOIN tipomarca ON tipomarca.id = tipomodelo.tipomarca_id
                            LEFT JOIN tipoannio ON tipoannio.id = version.tipoannio_id
                            WHERE tipomodelo_id = '$modeloid' AND version.id != '$versionid'  ");
                            echo '<option value="'.$row[idversion].'">'.$row[marca].' '.$row[version].' '.$row[annio].'</option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.$valores[marca].' '.$valores[version].' '.$valores[annio].'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Color<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a4" id="color" name="color"
                        value="<?php echo $row['colorid']; ?>">
                        <?php
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=16 AND id != ".$row[colorid]."");
                            echo '<option value="'.$row[colorid].'">'.$row[color].'</option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.$valores[descripcion].'</option>';
                            }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll2" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b># de Expediente<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a5" placeholder="Número de Expediente"
                        id="expediente" name="expediente" value="<?php echo $row['noexpediente']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll4" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b># de Motor<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a6" placeholder="Número de Motor" id="motor"
                        name="motor" value="<?php echo $row['motor']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll5" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>KM<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a7" placeholder="Kilometraje" id="km" name="km"
                        value="<?php echo number_format($row['kilometraje']); ?>"
                        onkeyup="this.value=NumText(this.value)" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll6" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Precio Venta<span style="color:red;">*</span> $</b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a8" placeholder="Precio Venta" id="precio"
                        name="precio" value="<?php echo number_format($row['precio'],2); ?>"
                        onchange="MASK(this,this.value,'-$##,###,##0.00',1)" onkeyup="this.value=NumText(this.value)" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll7" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Precio Contado<span style="color:red;">*</span> $</b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a9" placeholder="Precio Contado" id="contado"
                        name="contado" value="<?php echo number_format($row['contado'],2); ?>"
                        onchange="MASK(this,this.value,'-$##,###,##0.00',1)" onkeyup="this.value=NumText(this.value)" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll8" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Fecha de Registro</b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="date" style="border:none; height:30px; border-radius:0px;" class="form-control require a10" disabled placeholder="Fecha" id="fecha"
                        name="fecha" value="<?php echo $row['fecha']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll9" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Dueños<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a11" id="duenio" name="duenio"
                        value="<?php echo $row['duenioid']; ?>">
                        <?php
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=38 AND id != ".$row[duenioid]."");
                            echo '<option value="'.$row[duenioid].'">'.($row[duenio]).'</option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.($valores[descripcion]).'</option>';
                            }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll10" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Garantia<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a12" id="garantia" name="garantia"
                        value="<?php echo $row['garantiaid']; ?>">
                        <?php
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=39  AND id != ".$row[garantiaid]." ");
                            echo '<option value="'.$row[garantiaid].'">'.($row[garantia]).'</option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                echo '<option value="'.$valores[id].'">'.($valores[descripcion]).'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll11" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Status Venta<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a13" id="venta" name="venta"
                        value="<?php echo $row['ventaid']; ?>">
                        <?php
                        require 'conexion.php';
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=37 AND id != ".$row[ventaid]."");
                            echo '<option value="'.$row[ventaid].'">'.$row[venta].'</option>';
                                while ($valores = mysqli_fetch_array($query)) {
                                    echo '<option value="'.$valores[id].'">'.$valores[descripcion].'</option>';
                                }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll12" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Duplicado<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="form-control show-tick require a14" id="duplicado" name="duplicado"
                        value="<?php echo $row['duplicadoid']; ?>">
                        <?php
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo= 53 AND id != ".$row[duplicadoid]."");
                            echo '<option value="'.$row[duplicadoid].'">'.$row[duplicado].'</option>';
                                while ($valores = mysqli_fetch_array($query)) {
                                    echo '<option value="'.$valores[id].'">'.$valores[descripcion].'</option>';
                                }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll113" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Placa<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control require a15" placeholder="Número de placa" id="placa"
                        name="placa" value="<?php echo $row['placa']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll14" class="col-lg-3 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Observaciones</b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:33px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" style="border:none; height:30px; border-radius:0px;" class="form-control a16" placeholder="Observaciones" id="obs" name="obs"
                        value="<?php echo $row['observaciones']; ?>" />
                </div>
            </div>
        </div>
    </div>
    <?php if ($sitiocambio == $sitioSession) { ?>

    <button type="submit" class="btn btn-primary m-t-15 col-md-offset-10 waves-effect a17"
        onclick="envioAjax('updatevehiculo.php','formularioContacto','post','.resultado')" id="enviarvehiculo"><i
            class="material-icons">save</i>Guardar</button>

    <?php } ?>
</form>
<button type="button" class="validar_serie" style="display:none;" onclick="validar();"></button>
<script type="text/javascript">
function validarForm(idForm) {
    var exprTel = /^([0-9]+){10}$/;
    var exprEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    //Validamos cualquier input con la clase 'require'
    if ($(idForm + " input.require").val() == "") {
        alert("Algunos Datos Son Obligatorios");
        return false;
    } else if ($(idForm + " select[name='tipo'].require").length && !$(idForm + " select[name='tipo'].require").val()) {
        alert("Te falta llenar el Tipo de Vehiculo");
        return false;
    } else if ($(idForm + " select[name='version'].require").length && !$(idForm + " select[name='version'].require")
        .val()) {
        alert("Te falta llenar la Version");
        return false;
    } else if ($(idForm + " select[name='color'].require").length && !$(idForm + " select[name='color'].require")
        .val()) {
        alert("Te falta llenar el Color del vehiculo");
        return false;
    } else if ($(idForm + " input[name='expediente'].require").length && !$(idForm +
            " input[name='expediente'].require").val()) {
        alert("Te falta llenar el Número de Expediente");
        return false;
    } else if ($(idForm + " input[name='serie'].require").length && !$(idForm + " input[name='serie'].require").val()) {
        alert("Te falta llenar el Número de Serie");
        return false;
    } else if ($(idForm + " input[name='motor'].require").length && !$(idForm + " input[name='motor'].require").val()) {
        alert("Te falta llenar el Número de Motor");
        return false;
    } else if ($(idForm + " input[name='km'].require").length && !$(idForm + " input[name='km'].require").val()) {
        alert("Te falta llenar el Kilometraje");
        return false;
    } else if ($(idForm + " input[name='venta'].require").length && !$(idForm + " input[name='venta'].require").val()) {
        alert("Te falta llenar el Precio de Venta");
        return false;
    } else if ($(idForm + " select[name='duenio'].require").length && !$(idForm + " select[name='duenio'].require")
        .val()) {
        alert("Te falta llenar los Dueños");
        return false;
    } else if ($(idForm + " select[name='garantia'].require").length && !$(idForm + " select[name='garantia'].require")
        .val()) {
        alert("Te falta llenar la Garantia");
        return false;
    }
    //Devuelve true si todo está correcto
    else {
        return true;
    }
}

function envioAjax(url, idForm, method, capa) {
    event.preventDefault();
    var selectorjQform = "#" + idForm;
    var validado = validarForm(selectorjQform);
    if (validado) {
        $.ajax({
            url: url,
            method: method,
            data: $(selectorjQform).serialize(),
            dataType: 'html',
            error: function(jqXHR, textStatus, strError) {
                alert("Error de conexión. Por favor, vuelva a intentarlo");
            },
            success: function(data) {
                if(data == 2){
                    Swal.fire({
                        icon: "info",
                        title: "Oops...",
                        text: "El expediente ya existe, favor de elige otro.",
                    });
                }
                else if (data) {
                    if ($(capa).length) {
                        $(capa).show();
                        document.getElementById(idForm).reset();
                    } else {
                        colorName = 'alert-success';
                        placementAlign = 'right';
                        animateEnter = '';
                        placementFrom = 'top';
                        animateExit = '';
                        text = 'Registro Actualizado Correctamente';
                        showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                            animateExit);
                        document.getElementById(idForm).reset();
                        setTimeout('document.location.reload()', 1200);
                    }
                } else {
                    alert("Error inesperado. Por favor, vuelva a intentarlo");
                }
            }
        });
    }
}

function mayus(e) {
    e.value = e.value.toUpperCase();
}

$(document).ready(function() {
    $('#sitio').select2();
    $('#tipo').select2();
    $('#version').select2();
    $('#color').select2();
    $('#status').select2();
    $('#duenio').select2();
    $('#garantia').select2();
    $('#venta').select2();
    $('#duplicado').select2();
});

function validar() {
    var str = isNaN($("#serie").val());
    var annio = document.getElementById("annioMB").value;

    if(parseInt(annio) > 1980){
        if (str == true) {
            if ($("#serie").val().length < 17) {
                colorName = 'alert-warning';
                placementAlign = 'right';
                animateEnter = '';
                placementFrom = 'top';
                animateExit = '';
                text = 'El numero de serie tiene que tener 17 digitos';
                showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                $('#enviarvehiculo').attr('disabled', true);
                $(".serie_error").removeClass(" focused success");
                $(".serie_error").addClass(" focused error");

            } else if ($("#serie").val().length > 17) {
                colorName = 'alert-warning';
                placementAlign = 'right';
                animateEnter = '';
                placementFrom = 'top';
                animateExit = '';
                text = 'El numero de serie tiene que tener 17 digitos';
                showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
                $('#enviarvehiculo').attr('disabled', true);
                $(".serie_error").removeClass(" focused success");
                $(".serie_error").addClass(" focused error");
            } else {
                validarserie();
                $('#enviarvehiculo').attr('disabled', false);
            }
        }
        else {
            colorName = 'alert-warning';
            placementAlign = 'right';
            animateEnter = '';
            placementFrom = 'top';
            animateExit = '';
            text = 'El numero de serie no se encuentra validado y es incorrecto favor de revisar';
            showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
            $('#enviarvehiculo').attr('disabled', true);
            $(".serie_error").removeClass(" focused success");
            $(".serie_error").addClass(" focused error");
        }
    }
    else{
        $('#enviarvehiculo').attr('disabled', false);
        $(".serie_error").removeClass(" focused error");
        $(".serie_error").addClass(" focused success");
        colorName = 'alert-success';
        placementAlign = 'right';
        animateEnter = '';
        placementFrom = 'top';
        animateExit = '';
        text = 'El número de serie es correcto';
        showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
    }
}



function validarserie() {
    var serie = $('#serie').val();
    var form_data = new FormData();
    form_data.append('serie', serie);
    $.ajax({
        url: 'validar_serie.php',
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        error: function(jqXHR, textStatus, strError) {
            alert("Error de conexión. Por favor, vuelva a intentarlo");
        },
        success: function(response) {
            //alert(response);
            if (response) {
                if (response == 1) {
                    //alert(response);
                    $('#enviarvehiculo').attr('disabled', false);
                    $(".serie_error").removeClass(" focused error");
                    $(".serie_error").addClass(" focused success");
                    colorName = 'alert-success';
                    placementAlign = 'right';
                    animateEnter = '';
                    placementFrom = 'top';
                    animateExit = '';
                    text = 'El número de serie es correcto';
                    showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                        animateExit);
                } else if (response == 2) {
                    //alert(response);
                    $('#enviarvehiculo').attr('disabled', true);
                    $(".serie_error").addClass(" focused error");
                    $(".serie_error").removeClass(" focused success");
                    colorName = 'alert-warning';
                    placementAlign = 'right';
                    animateEnter = '';
                    placementFrom = 'top';
                    animateExit = '';
                    text = 'El número de serie no coincide favor de revisar';
                    showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                        animateExit);
                    $(".serie_error").removeClass(" focused success");
                    $(".serie_error").addClass(" focused error");
                }
            } else {
                alert("Error inesperado. Por favor, vuelva a intentarlo");
            }
        }
    });
}

function validarserie1() {
    var serie = $('#serie').val();
    var form_data = new FormData();
    form_data.append('serie', serie);
    $.ajax({
        url: 'validar_serie.php',
        dataType: 'text', // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        error: function(jqXHR, textStatus, strError) {
            alert("Error de conexión. Por favor, vuelva a intentarlo");
        },
        success: function(response) {
            //alert(response);
            if (response) {
                if (response == 1) {
                    //alert(response);
                    $('#enviarvehiculo').attr('disabled', false);
                    $(".g-modal-next").css("display", "inline");
                } else if (response == 2) {
                    //alert(response);
                    $('#enviarvehiculo').attr('disabled', true);
                    $(".g-modal-next").css("display", "none");
                }
            } else {
                alert("Error inesperado. Por favor, vuelva a intentarlo");
            }
        }
    });
}

function MASK(form, n, mask, format) {
    if (format == "undefined") format = false;
    if (format || NUM(n)) {
        dec = 0, point = 0;
        x = mask.indexOf(".") + 1;
        if (x) {
            dec = mask.length - x;
        }
        if (dec) {
            n = NUM(n, dec) + "";
            x = n.indexOf(".") + 1;
            if (x) {
                point = n.length - x;
            } else {
                n += ".";
            }
        } else {
            n = NUM(n, 0) + "";
        }
        for (var x = point; x < dec; x++) {
            n += "0";
        }
        x = n.length, y = mask.length, XMASK = "";
        while (x || y) {
            if (x) {
                while (y && "#0.".indexOf(mask.charAt(y - 1)) == -1) {
                    if (n.charAt(x - 1) != "-")
                        XMASK = mask.charAt(y - 1) + XMASK;
                    y--;
                }
                XMASK = n.charAt(x - 1) + XMASK, x--;
            } else if (y && "0".indexOf(mask.charAt(y - 1)) + 1) {
                XMASK = mask.charAt(y - 1) + XMASK;
            }
            if (y) {
                y--
            }
        }
    } else {
        XMASK = "";
    }
    if (form) {
        form.value = XMASK;
        if (NUM(n) < 0) {
            form.style.color = "#FF0000";
        } else {
            form.style.color = "#000000";
        }
    }
    return XMASK;
}

// Convierte una cadena alfanumÃ©rica a numÃ©rica (incluyendo formulas aritmÃ©ticas)
//
// s   = cadena a ser convertida a numÃ©rica
// dec = numero de decimales a redondear
//
// La funciÃ³n devuelve el numero redondeado
function NUM(s, dec) {
    for (var s = s + "", num = "", x = 0; x < s.length; x++) {
        c = s.charAt(x);
        if (".-+/*".indexOf(c) + 1 || c != " " && !isNaN(c)) {
            num += c;
        }
    }
    if (isNaN(num)) {
        num = eval(num);
    }
    if (num == "") {
        num = 0;
    } else {
        num = parseFloat(num);
    }
    if (dec != undefined) {
        r = .5;
        if (num < 0) r = -r;
        e = Math.pow(10, (dec > 0) ? dec : 0);
        return parseInt(num * e + r) / e;
    } else {
        return num;
    }
}

$(document).ready(function(e) {
    // Simular click
    $('.validar_serie').click();
});

function NumText(string) { //solo letras y numeros
    var out = '';
    //Se añaden las letras validas
    var filtro = '0123456789,.'; //Caracteres validos
    for (var i = 0; i < string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) out += string.charAt(i);
    return out;
}

function NumText1(string) { //solo letras y numeros
    var out = '';
    //Se añaden las letras validas
    var filtro = 'abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890'; //Caracteres validos
    for (var i = 0; i < string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) out += string.charAt(i);
    return out;
}
</script>


<script>
function guia() {

    document.getElementById("scroll").scrollIntoView();

    $(document).on('click', '.g-modal-close', function() {
        $('#tipo').select2();
        $('#version').select2();
        $('#color').select2();
        $('#status').select2();
        $('#duenio').select2();
        $('#garantia').select2();
        $('#venta').select2();
        $('#duplicado').select2();
        tab();
    });

    $('#tipo').select2();
    $('#version').select2();
    $('#color').select2();
    $('#status').select2();
    $('#duenio').select2();
    $('#garantia').select2();
    $('#venta').select2();
    $('#duplicado').select2();

    $('#scroll').removeAttr('hidden');

    document.onkeydown = function(e) {
        console.log(e.which);
        if (e.which == 9) {
            return false;
        }
    };

    myTour();
    return false;
}

var preseOpt = {

    keyboard: false,
    tourMap: {
        open: true
    },
    intro: {
        title: 'EDITAR VEHICULO PASO A PASO',
        width: 500,
    },
    create: function() {

        $.checkId = function() {};
        $.checkValue = function(target, val) {
            if (jQuery.trim(target.val()) != val) {
                return false;
            } else {
                return true;
            }
        }
    },
    steps: [{
        before: function() {
            document.getElementById("scroll").scrollIntoView();

            if ($("#serie").val().length < 17) {

                var errorMessage = $('<div>').addClass('gErrorMessage').text(
                    'El número de serie no coincide favor de revisar');
                $('.gErrorMessage').remove();
                $('.gFooter').after(errorMessage);
                $(".g-modal-next").css("display", "none");

            } else if ($("#serie").val().length > 17) {
                var errorMessage = $('<div>').addClass('gErrorMessage').text(
                    'El número de serie no coincide favor de revisar');
                $('.gErrorMessage').remove();
                $('.gFooter').after(errorMessage);
                $(".g-modal-next").css("display", "none");

            } else {
                validarserie1();
                $(".g-modal-next").css("display", "inline");
            }

        },
        title: 'NUMERO DE SERIE',
        content: 'Ingresa número de serie del vehículo <br> ' +
            'El VIN puede haber sido situado en el tablero del lado del conductor, en la orilla de la puerta o en el poste B del ' +
            'chasis. <br> ',
        target: '.a1',
        position: 'rcc',
        autofocus: true,
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'El número de serie no coincide favor de revisar'
        },
    }, {
        before: function() {
            document.getElementById("scroll").scrollIntoView();
            $(".g-modal-next").css("display", "inline");
            $('#tipo').select2('open');
        },
        title: 'TIPO',
        content: 'Selecciona el tipo de carrocería del vehículo <br> <span id="menu2" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu2()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a2'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {

        before: function() {
            document.getElementById("scroll").scrollIntoView();
            $(".g-modal-next").css("display", "inline");
            $('#version').select2('open');
        },
        title: 'VERSION',
        content: 'Selecciona la versión del vehículo <br> <span id="menu3" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu3()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a3'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {

        before: function() {
            document.getElementById("scroll").scrollIntoView();
            $(".g-modal-next").css("display", "inline");
            $('#color').select2('open');
        },
        title: 'COLOR',
        content: 'Selecciona el color del vehículo <br> <span id="menu4" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu4()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a4'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            document.getElementById("scroll").scrollIntoView();
        },
        title: 'NUMERO DE EXPEDIENTE',
        content: 'Ingresa el número del expediente que se encuentra en el contrato ',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        target: '.a5',
        position: 'rcc',
        autofocus: true,
    }, {
        before: function() {
            document.getElementById("scroll2").scrollIntoView();
        },
        title: 'NUMERO DE MOTOR',
        content: 'Ingresa el número de motor del vehículo ',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        target: '.a6',
        position: 'rcc',
        autofocus: true,
    }, {
        before: function() {
            $(".g-modal-next").css("display", "inline");
            document.getElementById("scroll4").scrollIntoView();
        },
        title: 'KILOMETRAJE',
        content: 'Ingresa el kilometraje del vehículo ',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        target: '.a7',
        position: 'rcc',
        autofocus: true,
    }, {
        before: function() {
            document.getElementById("scroll5").scrollIntoView();

            if ($("#precio").val() == '0.00') {

                var errorMessage = $('<div>').addClass('gErrorMessage').text('Campo Obligatorio!');
                $('.gErrorMessage').remove();
                $('.gFooter').after(errorMessage);
                $(".g-modal-next").css("display", "none");

            } else {
                $(".g-modal-next").css("display", "inline");
            }

        },
        title: 'PRECIO DE VENTA',
        content: 'Ingresa el precio de venta del vehículo ',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        target: '.a8',
        position: 'rcc',
        autofocus: true,
    }, {
        before: function() {
            document.getElementById("scroll5").scrollIntoView();

            if ($("#contado").val() == '0.00') {

                var errorMessage = $('<div>').addClass('gErrorMessage').text('Campo Obligatorio!');
                $('.gErrorMessage').remove();
                $('.gFooter').after(errorMessage);
                $(".g-modal-next").css("display", "none");

            } else {
                $(".g-modal-next").css("display", "inline");
            }
        },
        title: 'PRECIO DE CONTADO',
        content: 'Ingresa el precio de contado del vehículo ',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        target: '.a9',
        position: 'rcc',
        autofocus: true,
    }, {
        before: function() {
            document.getElementById("scroll8").scrollIntoView();
        },
        title: 'FECHA DE REGISTRO',
        content: 'Ingresa la fecha de registro del vehículo ',
        target: '.a10',
        position: 'rcc',
        autofocus: true,
    }, {

        before: function() {
            document.getElementById("scroll9").scrollIntoView();

            $(".g-modal-next").css("display", "inline");
            $('#duenio').select2('open');
        },
        title: 'DUEÑOS',
        content: 'Selecciona la cantidad de dueños del vehículo <br> <span id="menu5" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu5()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a11'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {

        before: function() {
            document.getElementById("scroll10").scrollIntoView();

            $(".g-modal-next").css("display", "inline");
            $('#garantia').select2('open');
        },
        title: 'GARANTIA ',
        content: 'Selecciona el tipo de garantia del vehículo <br> <span id="menu6" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu6()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a12'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {

        before: function() {
            document.getElementById("scroll14").scrollIntoView();
            $(".g-modal-next").css("display", "inline");
            $('#venta').select2('open');
        },
        title: 'STATUS VENTA',
        content: ' Ingresa el status del vehiculo <br> <span id="menu7" style="display: none"><button class="btn ' +
            'btn-primary" onclick="menu7()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a13'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {

        before: function() {
            document.getElementById("scroll14").scrollIntoView();
            $(".g-modal-next").css("display", "inline");
            $('#duplicado').select2('open');
        },
        title: 'DUPLICADO DE LLAVES ',
        content: 'Selecciona si cuenta con duplicado de llaves el vehículo <br> <span id="menu8" style="display: none"><button ' +
            'class="btn ' +
            'btn-primary" onclick="menu8()"> Abrir Menu </button></span>',
        target: '.select2-dropdown',
        event: ['change', '.a14'],
        waitElementTime: 3000,
        position: 'rcc',
        autofocus: true
    }, {
        before: function() {
            document.getElementById("scroll14").scrollIntoView();
        },
        title: 'NUMERO DE PLACA O BAJA',
        content: 'Ingresa el número de placa o baja si el vehículo no cuenta con placas ',
        checkNext: {
            func: function(target) {
                return !$.checkValue(target, '');
            },
            messageError: 'Campo Obligatorio!'
        },
        target: '.a15',
        position: 'rcc',
        autofocus: true,
    }, {
        before: function() {
            document.getElementById("scroll14").scrollIntoView();
        },
        title: 'OBSERVACIONES',
        content: 'Ingresa las observaciones del vehículo ',
        target: '.a16',
        position: 'rcc',
        autofocus: true,
    }, {

        title: 'GUARDAR',
        content: '',
        target: '.a17',
    }, ],
}

$('#tipo').on('select2:closing', function(evt) {

    if ($("#tipo").val() == null || $("#tipo").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu2").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu2").css("display", "none");
    }
});

$('.a3').on('select2:closing', function(evt) {

    if ($("#version").val() == null || $("#version").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu3").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu3").css("display", "none");
    }
});


$('.a4').on('select2:closing', function(evt) {

    if ($("#color").val() == null || $("#color").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu4").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu4").css("display", "none");
    }
});

$('.a5').on('select2:closing', function(evt) {

    if ($("#duenio").val() == null || $("#duenio").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu5").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu5").css("display", "none");
    }
});

$('.a6').on('select2:closing', function(evt) {

    if ($("#garantia").val() == null || $("#garantia").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu6").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu6").css("display", "none");
    }
});

$('.a7').on('select2:closing', function(evt) {

    if ($("#status").val() == null || $("#status").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu7").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu7").css("display", "none");
    }
});

$('.a8').on('select2:closing', function(evt) {

    if ($("#duplicado").val() == null || $("#duplicado").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu8").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu8").css("display", "none");
    }
});

function myTour() {
    iGuider(preseOpt);
}

function menu2() {
    $('#tipo').select2('open');
}

function menu3() {
    $('#version').select2('open');
}

function menu4() {
    $('#color').select2('open');
}

function menu5() {
    $('#duenio').select2('open');
}

function menu6() {
    $('#status').select2('open');
}

function menu7() {
    $('#garantia').select2('open');
}

function menu8() {
    $('#duplicado').select2('open');
}

function tab() {
    document.onkeydown = function(e) {
        var ev = document.all;
        if (ev.keyCode == 9) {

        }
    }
}

$("input[name=precio]").change(function() {
    //alert($("#venta").val());
    if ($("#precio").val() == '0.00') {

        var errorMessage = $('<div>').addClass('gErrorMessage').text('Campo Obligatorio!');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").css("display", "none");

    } else {
        $(".g-modal-next").css("display", "inline");
    }
});

$("input[name=contado]").change(function() {
    //alert($("#venta").val());
    if ($("#contado").val() == '0.00') {

        var errorMessage = $('<div>').addClass('gErrorMessage').text('Campo Obligatorio!');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").css("display", "none");

    } else {
        $(".g-modal-next").css("display", "inline");
    }
});

$("input[name=serie]").change(function() {
    if ($("#serie").val().length < 17) {

        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'El número de serie no coincide favor de revisar');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").css("display", "none");

    } else if ($("#serie").val().length > 17) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'El número de serie no coincide favor de revisar');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").css("display", "none");

    } else {
        validarserie1();
        $(".g-modal-next").css("display", "inline");
    }
});
</script>
