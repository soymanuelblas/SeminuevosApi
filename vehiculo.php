<?php
include "variables.php";
date_default_timezone_set("America/Mexico_City");
$time = time();
?>

<div class="col-sm-4" style="display: none;">
    <input type="hidden" class="form-control require" value="<?php echo $sitioSession ?>" id="sitio" name="sitio" />
</div>
<div id="scroll" hidden>
    <br>
</div>

<div class="row clearfix" style="width: 90%; margin: 0 auto; box-shadow:none; ">
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label"
            style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Marca<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a1" title="Marca" id="marca" name="marca" style="width: 95%; border:none;">
                        <?php
                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipomarca ORDER BY descripcion ASC ");
                            echo'<option value="0" disabled="" selected=""></option>';
                            while ($valores = mysqli_fetch_array($query)) {
                                    echo '<option  value="'.$valores[id].'">'.$valores[descripcion].'</option>';
                            }
                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br style="height:5px;">
    <div id="scroll1"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label"
            style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Modelo<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a2" title="Modelo" id="modelo" name="modelo" style="width: 95%;">
                        <option value="0" disabled="" selected=""></option>';
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll2"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>version<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a3" id="version" name="version" title="Version" style="width: 95%;">
                        <option value="0" disabled="" selected=""></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll3"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Tipo<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a4" title="Tipo de Vehiculo" id="tipo" name="tipo" style="width: 95%;">
                        <?php
                                                        $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=2 ORDER BY descripcion ASC ");
                                                        echo'<option value="0" disabled="" selected=""></option>';                           
                                                        while ($valores = mysqli_fetch_array($query)) {
                                                            echo '<option  value="'.$valores[id].'">'.$valores[descripcion].'</option>';
                                                        }
                                                    ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll4"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Color<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a5" data-live-search="true" id="color" name="color" style="width: 95%;">
                        <?php
                                                    $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo=16 ORDER BY descripcion ASC ");
                                                    echo'<option value="0" disabled="" selected=""></option>';
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
    <div id="scroll6"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b># de Expediente<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control require a6" value="A" placeholder="" id="expediente"
                        name="expediente" onkeyup="mayus(this);" style="border:none; height:30px; border-radius:0px;" />
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll7"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b># de Serie<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control require a8" placeholder="" id="serie" name="serie"
                    onkeyup="mayus(this); this.value=NumText1(this.value)" onchange="validar();" style="border:none; height:30px; border-radius:0px;"/>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll8"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b># de Motor<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control require a9" placeholder="" id="motor" name="motor"
                        onkeyup="mayus(this);" style="border:none; height:30px; border-radius:0px;"/>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll9"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>KM<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="number" class="form-control require a10" id="km" name="km" style="border:none; height:30px; border-radius:0px;"/>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll10"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Precio Venta<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control require a11" placeholder="" id="venta" name="venta"
                        onchange="MASK(this,this.value,'-$##,###,##0.00',1)" onkeyup="this.value=NumText(this.value)" style="border:none; height:30px; border-radius:0px;"/>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll11"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Precio Contado<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control require a12" id="contado" name="contado"
                        onchange="MASK(this,this.value,'-$##,###,##0.00',1)" onkeyup="this.value=NumText(this.value)" style="border:none; height:30px; border-radius:0px;"/>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll12"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Dueños<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a13" id="duenio" name="duenio" style="width: 95%;">
                        <?php
                            $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo= 38 ORDER BY descripcion ASC ");
                                echo'<option value="0" disabled="" selected=""></option>';
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
    <div id="scroll13"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Garantia<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a14" id="garantia" name="garantia" style="width: 95%;">
                        <?php
                            $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo= 39 ORDER BY descripcion ASC ");
                                echo'<option value="0" disabled="" selected=""></option>';
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
    <div id="scroll14"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Placa<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control require a15" placeholder="" id="placa" name="placa" style="border:none; height:30px; border-radius:0px;"/>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div id="scroll15"></div>
    <div style="border:1px solid #FF7800; height:33px; border-radius: 30px; ">
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Duplicado<span style="color:red;">*</span></b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <select class="require a16" id="duplicado" name="duplicado" style="width: 95%;">
                        <?php
                                                    $query = $mysqli -> query ("SELECT id,descripcion FROM tipostatus WHERE tipo= 53 ");
                                                        echo'<option value="0" disabled="" selected=""></option>';
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
        <div id="scroll16" class="col-lg-4 col-md-2 col-sm-4 col-xs-6 form-control-label" style="height:30px; border-right: 1px solid #FF7800; border-radius: 0px;">
            <label for="email_address_2">
                <p><b>Observaciones</b></p>
            </label>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6" style="height:40px;">
            <div class="form-group">
                <div class="form-line" style="border:none;">
                    <input type="text" class="form-control a17" placeholder="" id="obs" name="obs" style="border:none; height:30px; border-radius:0px;" />
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
function validarForm(idForm) {
    var exprTel = /^([0-9]+){10}$/;
    var exprEmail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    //Validamos cualquier input con la clase 'require'

    if ($(idForm + " select[name='marca'].require").length && !$(idForm + " select[name='marca'].require").val()) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Falta seleccionar la marca...",
        });
        return false;
    }
    else if(($(idForm + " select[name='modelo'].require").length && !$(idForm + " select[name='modelo'].require").val()) || $(idForm + " select[name='modelo'].require").val() == "0" ) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Falta seleccionar el modelo...",
        });
        return false;
    }

    else if(($(idForm + " select[name='version'].require").length && !$(idForm + " select[name='version'].require").val()) || $(idForm + " select[name='version'].require").val() == "0" ) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Falta seleccionar la version...",
        });
        return false;
    }



    else if ($(idForm + " select[name='tipo'].require").length && !$(idForm + " select[name='tipo'].require")
        .val()) {
        alert("Te falta llenar el Tipo de Vehiculo");
        return false;
    } else if ($(idForm + " select[name='version'].require").length && !$(idForm +
            " select[name='version'].require")
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
    } else if ($(idForm + " input[name='serie'].require").length && !$(idForm + " input[name='serie'].require")
        .val()) {
        alert("Te falta llenar el Número de Serie");
        return false;
    } else if ($(idForm + " input[name='motor'].require").length && !$(idForm + " input[name='motor'].require")
        .val()) {
        alert("Te falta llenar el Número de Motor");
        return false;
    } else if ($(idForm + " input[name='km'].require").length && !$(idForm + " input[name='km'].require").val()) {
        alert("Te falta llenar el Kilometraje");
        return false;
    } else if ($(idForm + " input[name='venta'].require").length && !$(idForm + " input[name='venta'].require")
        .val()) {
        alert("Te falta llenar el Precio de Venta");
        return false;
    } else if ($(idForm + " input[type='date'].require").length && !$(idForm + " input[type='date'].require")
        .val()) {
        alert("Te falta llenar la Fecha");
        return false;
    } else if ($(idForm + " select[name='duenio'].require").length && !$(idForm + " select[name='duenio'].require")
        .val()) {
        alert("Te falta llenar los Dueños");
        return false;
    } else if ($(idForm + " select[name='garantia'].require").length && !$(idForm +
            " select[name='garantia'].require")
        .val()) {
        alert("Te falta llenar la Garantia");
        return false;
    } else if ($(idForm + " select[name='duplicado'].require").length && !$(idForm +
            " select[name='duplicado'].require").val()) {
        alert("Te falta llenar el campo Duplicado");
        return false;
    } else if ($(idForm + " input[name='placa'].require").length && !$(idForm + " input[name='placa'].require")
        .val()) {
        alert("Te falta llenar el Número de Placa");
        return false;
    }
    //Devuelve true si todo está correcto
    else {
        return true;
    }
}

function NumText(string) { //solo letras y numeros
    var out = '';
    //Se añaden las letras validas
    var filtro = '0123456789,.'; //Caracteres validos
    for (var i = 0; i < string.length; i++)
        if (filtro.indexOf(string.charAt(i)) != -1) out += string.charAt(i);
    return out;
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
                //alert(data);
                if (data) {
                    if (data == 2) {
                        colorName = 'alert-warning';
                        placementAlign = 'right';
                        animateEnter = '';
                        placementFrom = 'top';
                        animateExit = '';
                        text = 'Ya existe un vehiculo con ese número de expediente';
                        showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                            animateExit);
                    }
                    else if(data == 3){
                        colorName = 'alert-warning';
                        placementAlign = 'right';
                        animateEnter = '';
                        placementFrom = 'top';
                        animateExit = '';
                        text = 'No se guardo el vehiculo, contactar a soporte';
                        showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                            animateExit);
                    }
                    else {
                        colorName = 'alert-success';
                        placementAlign = 'right';
                        animateEnter = '';
                        placementFrom = 'top';
                        animateExit = '';
                        text = 'Registro Guardado Correctamente';
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
</script>

<script type="text/javascript">
$(document).ready(function() {
    $("#marca").change(function() {

        $("#marca option:selected").each(function() {
            id = $(this).val();
            //alert(id);
            $.post("precio.php", {
                id: id
            }, function(data) {
                $("#modelo").html(data);
            });
        });
    })
});

$(document).ready(function() {
    $("#modelo").change(function() {

        $("#modelo option:selected").each(function() {
            id = $(this).val();
            //alert(id);
            $.post("annio.php", {
                id: id
            }, function(data) {
                $("#version").html(data);
            });
        });
    })
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#marca').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#modelo').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#version').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#color').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#status').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#tipo').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#duenio').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#garantia').select2({
        dropdownParent: $('#conte-modal1')
    });

    $('#duplicado').select2({
        dropdownParent: $('#conte-modal1')
    });
});


function mayus(e) {
    e.value = e.value.toUpperCase();
}

function validar() {
    if ($("#serie").val().length < 17) {
        colorName = 'alert-warning';
        placementAlign = 'right';
        animateEnter = '';
        placementFrom = 'top';
        animateExit = '';
        text = 'El numero de serie tiene que tener 17 digitos';
        showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
    } else if ($("#serie").val().length > 17) {
        colorName = 'alert-warning';
        placementAlign = 'right';
        animateEnter = '';
        placementFrom = 'top';
        animateExit = '';
        text = 'El numero de serie tiene que tener 17 digitos';
        showNotification(colorName, text, placementFrom, placementAlign, animateEnter, animateExit);
    } else {
        validarserie();
        $('#enviarvehiculo').attr('disabled', false);
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
            /*$(".g-modal-next").css("display", "none");*/
        },
        success: function(response) {
            //alert(response);
            if (response) {
                if (response == 1) {
                    //alert(response);
                    $('#enviarvehiculo').attr('disabled', false);
                    colorName = 'alert-success';
                    placementAlign = 'right';
                    animateEnter = '';
                    placementFrom = 'top';
                    animateExit = '';
                    text = 'El número de serie es correcto';
                    showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                        animateExit);
                    /*$(".g-modal-next").css("display", "inline");*/
                } else if (response == 2) {
                    //alert(response);
                    colorName = 'alert-warning';
                    placementAlign = 'right';
                    animateEnter = '';
                    placementFrom = 'top';
                    animateExit = '';
                    text = 'El número de serie no coincide favor de revisar';
                    showNotification(colorName, text, placementFrom, placementAlign, animateEnter,
                        animateExit);
                    /*    $(".g-modal-next").css("display", "none");*/
                }
            } else {
                alert("Error inesperado. Por favor, vuelva a intentarlo");
                /*$(".g-modal-next").css("display", "none");*/
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

        },
        success: function(response) {
            //alert(response);
            if (response) {
                if (response == 1) {
                    //alert(response);
                    $('#enviarvehiculo').attr('disabled', false);
                    /*  $(".g-modal-next").css("display", "inline");*/
                } else if (response == 2) {
                    //alert(response);
                    /*  $(".g-modal-next").css("display", "none");*/
                }
            } else {

                /*$(".g-modal-next").css("display", "none");*/
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

    $(document).on('click', '.g-modal-close', function() {
        $('#marca').select2();
        $('#modelo').select2();
        $('#version').select2();
        $('#tipo').select2();
        $('#color').select2();
        $('#status').select2();
        $('#duenio').select2();
        $('#garantia').select2();
        $('#duplicado').select2();

        tab();

    });

    document.onkeydown = function(e) {
        console.log(e.which);
        if (e.which == 9) {
            return false;
        }
    };

    $('#marca').select2();
    $('#modelo').select2();
    $('#version').select2();
    $('#tipo').select2();
    $('#color').select2();
    $('#status').select2();
    $('#duenio').select2();
    $('#garantia').select2();
    $('#duplicado').select2();

    $('#scroll').removeAttr('hidden');

    myTour();
    return false;
}

var preseOpt = {

    keyboard: false,
    tourMap: {
        open: false
    },
    intro: {
        title: ' AGREGAR VEHICULO PASO A PASO ',
        width: 500,
    },
    continue: {
        enable: false,
    },
    create: function() {
        $('.g-modal-map').hide();
        $('.gMapAction').hide();

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
                $('#modelo').select2();
                $('#marca').select2('open');
                $(".g-modal-next").css("display", "inline");
            },
            title: 'MARCA',
            content: 'Selecciona la marca del vehículo <br> <span id="menu1" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu1()"> Abrir Menu </button></span> ',
            target: '.select2-dropdown',
            event: ['change', '.a1'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll2").scrollIntoView();
                $('#version').select2();
                id = $("#marca").val();

                $.post("precio.php", {
                    id: id
                }, function(data) {
                    $("#modelo").html(data);
                    $('#modelo').select2('open');
                });

                $(".g-modal-next").css("display", "inline");
            },
            title: 'MODELO',
            content: 'Selecciona el modelo del vehículo <br> <span id="menu2" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu2()"> Abrir Menu </button></span>',
            target: '.select2-dropdown',
            event: ['change', '.a2'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll3").scrollIntoView();
                $('#tipo').select2();
                var id = $("#modelo").val();
                $.post("annio.php", {
                    id: id
                }, function(data) {
                    $("#version").html(data);
                    $('#version').select2('open');
                });

                $(".g-modal-next").css("display", "inline");
            },
            title: 'VERSION',
            content: 'Selecciona la versión del vehículo<br> <span id="menu3" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu3()"> Abrir Menu </button></span>',
            target: '.select2-dropdown',
            event: ['change', '.a3'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll4").scrollIntoView();
                $('#color').select2();
                $('#tipo').select2('open');
                $(".g-modal-next").css("display", "inline");
            },
            title: 'TIPO DE CARROCERÍA',
            content: 'Selecciona tipo de carrocería del vehículo <br> <span id="menu4" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu4()"> Abrir Menu </button></span>',
            target: '.select2-dropdown',
            event: ['change', '.a4'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll4").scrollIntoView();
                $('#color').select2('open');
                $(".g-modal-next").css("display", "inline");
            },
            title: 'COLOR',
            content: 'Selecciona el color del vehículo <br> <span id="menu5" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu5()"> Abrir Menu </button></span>',
            target: '.select2-dropdown',
            event: ['change', '.a5'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                $(".g-modal-next").css("display", "inline");
                document.getElementById("scroll7").scrollIntoView();
                $('#color').select2();
            },
            title: 'NUMERO DE EXPEDIENTE',
            content: 'Ingresa número de expediente del vehículo ',
            target: '.a6',
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'Campo Obligatorio!'
            },
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll8").scrollIntoView();
                /*if($("#serie").val().length < 17) {

                    var errorMessage = $('<div>').addClass('gErrorMessage').text('El número de serie no coincide favor de revisar');
                    $('.gErrorMessage').remove();
                    $('.gFooter').after(errorMessage);
                    $(".g-modal-next").css("display", "none");

                }else if($("#serie").val().length > 17) {
                    var errorMessage = $('<div>').addClass('gErrorMessage').text('El número de serie no coincide favor de revisar');
                    $('.gErrorMessage').remove();
                    $('.gFooter').after(errorMessage);
                    $(".g-modal-next").css("display", "none");

                }else{
                    validarserie1();
                    //$(".g-modal-next").css("display", "inline");
                }*/
            },
            title: 'NUMERO DE SERIE',
            content: 'Ingresa número de serie del vehículo. <br><br> ' +
                'El VIN puede haber sido situado en el tablero del lado del conductor, en la orilla de la puerta o en el poste B del ' +
                'chasis. <br> ',
            target: '.a8',
            position: 'rcc',
            autofocus: true,
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'El número de serie no coincide favor de revisar'
            },
        },
        {
            before: function() {
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
            },
            title: 'NUMERO DE MOTOR',
            content: 'Ingresa número de motor del vehículo',
            target: '.a9',
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'Campo Obligatorio!'
            },
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                $(".g-modal-next").css("display", "inline");
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
            },
            title: 'KILOMETRAJE',
            content: 'Ingresa kilometraje del vehículo ',
            target: '.a10',
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'Campo Obligatorio!'
            },
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll16").scrollIntoView();

                if ($("#venta").val() == '0.00') {
                    var errorMessage = $('<div>').addClass('gErrorMessage').text('Campo Obligatorio!');
                    $('.gErrorMessage').remove();
                    $('.gFooter').after(errorMessage);
                    $(".g-modal-next").css("display", "none");

                } else {
                    $(".status1").addClass("g-modal-next");
                    $(".g-modal-next").css("display", "inline");
                }

            },
            title: 'PRECIO VENTA',
            content: 'Ingresa precio de venta del vehículo',
            target: '.a11',
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'Campo Obligatorio!'
            },
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {

                document.getElementById("scroll16").scrollIntoView();
                $('#duenio').select2();

                if ($("#contado").val() == "0.00") {
                    var errorMessage = $('<div>').addClass('gErrorMessage').text('Campo Obligatorio!');
                    $('.gErrorMessage').remove();
                    $('.gFooter').after(errorMessage);
                    $(".g-modal-next").css("display", "none");

                } else {
                    $(".status1").addClass("g-modal-next");
                    $(".g-modal-next").css("display", "inline");
                }

            },
            title: 'PRECIO DE CONTADO',
            content: 'Ingresa precio de contado del vehículo',
            target: '.a12',
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'Campo Obligatorio!'
            },
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
                $('#garantia').select2();
                $('#duenio').select2('open');
                $(".g-modal-next").css("display", "inline");
            },
            title: 'DUEÑOS',
            content: 'Selecciona cantidad de dueños del vehículo<br> <span id="menu7" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu7()"> Abrir Menu </button></span>',
            target: '.select2-dropdown',
            event: ['change', '.a13'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
                $('#garantia').select2('open');
                $(".g-modal-next").css("display", "inline");
            },
            title: 'GARANTIA',
            content: 'Selecciona tipo de garantia del vehículo<br> <span id="menu8" style="display: none"><button class="btn ' +
                'btn-primary" onclick="menu8()"> Abrir Menu </button></span>',
            target: '.select2-dropdown',
            event: ['change', '.a14'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        }, {
            before: function() {
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
                $('#garantia').select2();
                $('#duplicado').select2();
            },
            title: 'PLACA',
            content: 'Ingresa matricula del vehículo',
            target: '.a15',
            checkNext: {
                func: function(target) {
                    return !$.checkValue(target, '');
                },
                messageError: 'Campo Obligatorio!'
            },
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
                $('#duplicado').select2('open');
                $(".g-modal-next").css("display", "inline");
            },
            title: 'DUPLICADO',
            content: 'Selecciona si cuenta con duplicado de llaves el vehículo<br> <span id="menu9" style="display: none"><button ' +
                'class="btn ' +
                'btn-primary" onclick="menu9()"> Abrir Menu </button></span> ',
            target: '.select2-dropdown',
            event: ['change', '.a16'],
            waitElementTime: 3000,
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                $(".status1").addClass("g-modal-next");
                document.getElementById("scroll16").scrollIntoView();
                $('#duplicado').select2();
            },
            title: 'OBSERVACIONES',
            content: 'Ingresa los detalles en general del vehículo',
            target: '.a17',
            position: 'rcc',
            autofocus: true,
        },
        {
            before: function() {
                document.getElementById("scroll16").scrollIntoView();
            },
            title: 'GUARDAR',
            content: '',
            target: '.a18',
            position: 'rcc',
            autofocus: true,
        }

    ],
    debug: false
}


$('.a1').on('select2:closing', function(evt) {

    if ($("#marca").val() == null || $("#marca").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu1").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu1").css("display", "none");
    }

});

$('.a2').on('select2:closing', function(evt) {

    if ($("#modelo").val() == null || $("#modelo").val() == 0) {
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

    if ($("#tipo").val() == null || $("#tipo").val() == 0) {
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

    if ($("#color").val() == null || $("#color").val() == 0) {
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

    if ($("#status").val() == null || $("#status").val() == 0) {
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

$('.a13').on('select2:closing', function(evt) {

    if ($("#duenio").val() == null || $("#duenio").val() == 0) {
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

$('.a14').on('select2:closing', function(evt) {

    if ($("#garantia").val() == null || $("#garantia").val() == 0) {
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

$('.a16').on('select2:closing', function(evt) {

    if ($("#duplicado").val() == null || $("#duplicado").val() == 0) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text(
            'Campo Obligatorio! para volver mostrar las opciones hacer click' +
            ' en Abrir Menu');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);

        $(".g-modal-next").addClass("status1");
        $(".status1").removeClass("g-modal-next");
        $("#menu9").css("display", "");
    } else {
        $(".status1").addClass("g-modal-next");
        $("#menu9").css("display", "none");
    }
});

function myTour() {
    iGuider(preseOpt);
}

function menu1() {
    $('#marca').select2('open');
}

function menu2() {
    $('#modelo').select2('open');
}

function menu3() {
    $('#version').select2('open');
}

function menu4() {
    $('#tipo').select2('open');
}

function menu5() {
    $('#color').select2('open');
}

function menu7() {
    $('#duenio').select2('open');
}

function menu8() {
    $('#garantia').select2('open');
}

function menu9() {
    $('#duplicado').select2('open');
}

function tab() {
    document.onkeydown = function(e) {
        var ev = document.all;
        if (ev.keyCode == 9) {

        }
    }
}


$("input[name=venta]").change(function() {
    //alert($("#venta").val());
    if ($("#venta").val() == '0.00') {

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



/*$("input[name=serie]").change(function(){
    if($("#serie").val().length < 17) {

        var errorMessage = $('<div>').addClass('gErrorMessage').text('El número de serie no coincide favor de revisar');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").css("display", "none");

    }else if($("#serie").val().length > 17) {
        var errorMessage = $('<div>').addClass('gErrorMessage').text('El número de serie no coincide favor de revisar');
        $('.gErrorMessage').remove();
        $('.gFooter').after(errorMessage);
        $(".g-modal-next").css("display", "none");

    }else{
        validarserie1();
        //$(".g-modal-next").css("display", "inline");
    }
});*/
</script>
