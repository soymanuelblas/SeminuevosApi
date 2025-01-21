<!DOCTYPE html>
<html lang="en">
<?php include "structure/head.php"?>

<body data-spy="scroll" data-target=".navbar-collapse" data-offset="50">

<!-- preloader section -->
<div class="preloader">
    <div class="sk-spinner sk-spinner-circle">
        <div class="sk-circle1 sk-circle"></div>
        <div class="sk-circle2 sk-circle"></div>
        <div class="sk-circle3 sk-circle"></div>
        <div class="sk-circle4 sk-circle"></div>
        <div class="sk-circle5 sk-circle"></div>
        <div class="sk-circle6 sk-circle"></div>
        <div class="sk-circle7 sk-circle"></div>
        <div class="sk-circle8 sk-circle"></div>
        <div class="sk-circle9 sk-circle"></div>
        <div class="sk-circle10 sk-circle"></div>
        <div class="sk-circle11 sk-circle"></div>
        <div class="sk-circle12 sk-circle"></div>
    </div>
</div>

<!-- navigation section -->
<section class="navbar navbar-fixed-top custom-navbar2" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a href="<?php echo base_url("")?>" class="logo-tema" style="font-family: Copperplate"><img src="<?php echo base_url("assets/images/Benjamin_Gonzales.png")?>" width="195px" alt="Logo"></a>
        </div>
    </div>
</section>

<!-- home section -->
<section id="entrenamiento">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <h3>PLAN DE ASESORÍA EN ENTRENAMIENTO</h3>
                <h1>BENJAMÍN GONZÁLEZ</h1>
                <h5><i class="fas fa-stethoscope"></i> Nutriólogo Deportivo</h5>
                <hr>
                <a href="<?php echo base_url("");?>" class="smoothScroll btn btn-default" style="font-family: Copperplate">Regresar a la Página Principal</a>
            </div>
        </div>
    </div>
</section>

<!-- pricing section -->
<section id="pricing2">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-center">
                <div class="section-title">
                    <br><br><br>
                    <strong><h1 class="heading bold">PLAN DE ASESORÍA EN ENTRENAMIENTO</h1></strong>
                    <hr>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan plan-one wow bounceIn" data-wow-delay="0.3s">
                    <div class="plan_title">
                        <i class="fas fa-weight medium-icon"></i>
                        <h3>PLAN 1</h3>
                        <h2>$400 <span> por rutina 1 mes</span></h2>
                    </div>
                    <ul>
                        <li><i class="fas fa-home"></i> En casa o gimnasio.</li>
                        <li><i class="fas fa-signature"></i> Mejora del rendimiento deportivo.</li>
                        <li><i class="fas fa-clipboard-list"></i> Plan de entrenamientos.</li>
                        <li><i class="fas fa-mobile-alt"></i> Rutina por aplicación.</li>
                    </ul>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#adquirir" plan = "1">¡Adquirir Ahora! <i class="fas fa-coins"></i> </button>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan plan-one wow bounceIn" data-wow-delay="0.3s">
                    <div class="plan_title">
                        <i class="fas fa-weight medium-icon"></i>
                        <h3>PLAN 2</h3>
                        <h2>$700<span> por rutina 2 meses</span></h2>
                    </div>
                    <ul>
                        <li><i class="fas fa-home"></i> En casa o gimnasio.</li>
                        <li><i class="fas fa-signature"></i> Mejora del rendimiento deportivo.</li>
                        <li><i class="fas fa-clipboard-list"></i> Plan de entrenamientos.</li>
                        <li><i class="fas fa-mobile-alt"></i> Rutina por aplicación.</li>
                    </ul>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#adquirir2" plan = "2">¡Adquirir Ahora!  <i class="fas fa-coins"></i> </button>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="plan plan-one wow bounceIn" data-wow-delay="0.3s">
                    <div class="plan_title">
                        <i class="fas fa-weight medium-icon"></i>
                        <h3>PLAN 3</h3>
                        <h2>$900<span> por rutina 3 meses</span></h2>
                    </div>
                    <ul>
                        <li><i class="fas fa-home"></i> En casa o gimnasio.</li>
                        <li><i class="fas fa-signature"></i> Mejora del rendimiento deportivo.</li>
                        <li><i class="fas fa-clipboard-list"></i> Plan de entrenamientos.</li>
                        <li><i class="fas fa-mobile-alt"></i> Rutina por aplicación.</li>
                    </ul>
                    <button class="btn btn-warning" data-toggle="modal" data-target="#adquirir3" plan = "3">¡Adquirir Ahora!  <i class="fas fa-coins"></i> </button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include "structure/whatsApp.php"?>

<!-- footer section -->
<footer>
    <?php include "structure/footer.php"?>
</footer>

<div class="modal fade" id="adquirir" role="dialog">
    <form id="formularioPlan1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Datos Personales</h4>
                </div>
                <div class="modal-body">
                    <h4>Llena los siguientes datos</h4>
                    <div class="col-md-6 col-sm-12">
                        <br>
                        <label for="Nombre Completo">Escribe tu nombre completo</label>
                        <input type="text" class="form-control" placeholder="Nombre Completo" name="nameUser" id="nameUser" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <br>
                        <label for="Edad">Escribe tu edad</label>
                        <input type="text" class="form-control" placeholder="Edad" name="edadUser" id="edadUser" required>
                        <br>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Fecha de Nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" placeholder="Fecha de Nacimiento" name="fNUser" id="fNUser" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Estatura">Estatura (cm)</label>
                        <input type="text" class="form-control" placeholder="Estatura" name="estaturaUser" id="estaturaUser" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Peso">Peso (kg)</label>
                        <input type="text" class="form-control" placeholder="Peso" name="pesoUser" id="pesoUser" required>
                        <br>
                    </div>
                    <!--<input type="hidden" class="form-control" name="plan" id="plan" value="PLAN DE ASESORÍA EN ENTRENAMIENTO PLAN 1 $600 / 1 MES" required>-->
                    <input type="hidden" class="form-control" name="plan" id="plan" value="PLAN DE ASESORÍA EN ENTRENAMIENTO PLAN 1 $400 / 1 MES" required>
                    <p style="font-family: Copperplate">Sus datos personales, serán utilizados para ajustar su peso y estura a nuestros tabuladores y tener un resultado más óptimo.</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar mis datos</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="adquirir2" role="dialog">
    <form id="formularioPlan2">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Datos Personales</h4>
                </div>
                <div class="modal-body">
                    <h4>Llena los siguientes datos</h4>
                    <div class="col-md-6 col-sm-12">
                        <br>
                        <label for="Nombre Completo">Escribe tu nombre completo</label>
                        <input type="text" class="form-control" placeholder="Nombre Completo" name="nameUser2" id="nameUser2" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <br>
                        <label for="Edad">Escribe tu edad</label>
                        <input type="text" class="form-control" placeholder="Edad" name="edadUser2" id="edadUser2" required>
                        <br>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Fecha de Nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" placeholder="Fecha de Nacimiento" name="fNUser2" id="fNUser2" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Estatura">Estatura (cm)</label>
                        <input type="text" class="form-control" placeholder="Estatura" name="estaturaUser2" id="estaturaUser2" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Peso">Peso (kg)</label>
                        <input type="text" class="form-control" placeholder="Peso" name="pesoUser2" id="pesoUser2" required>
                        <br>
                    </div>
                    <!--<input type="hidden" class="form-control" name="plan2" id="plan2" value="PLAN DE ASESORÍA EN ENTRENAMIENTO PLAN 2 $1,100 / 2 MESES" required>-->
                    <input type="hidden" class="form-control" name="plan2" id="plan2" value="PLAN DE ASESORÍA EN ENTRENAMIENTO PLAN 2 $700 / 2 MESES" required>
                    <p style="font-family: Copperplate">Sus datos personales, serán utilizados para ajustar su peso y estura a nuestros tabuladores y tener un resultado más óptimo.</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar mis datos</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="adquirir3" role="dialog">
    <form id="formularioPlan3">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Datos Personales</h4>
                </div>
                <div class="modal-body">
                    <h4>Llena los siguientes datos</h4>
                    <div class="col-md-6 col-sm-12">
                        <br>
                        <label for="Nombre Completo">Escribe tu nombre completo</label>
                        <input type="text" class="form-control" placeholder="Nombre Completo" name="nameUser3" id="nameUser3" required>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <br>
                        <label for="Edad">Escribe tu edad</label>
                        <input type="text" class="form-control" placeholder="Edad" name="edadUser3" id="edadUser3" required>
                        <br>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Fecha de Nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="form-control" placeholder="Fecha de Nacimiento" name="fNUser3" id="fNUser3" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Estatura">Estatura (cm)</label>
                        <input type="text" class="form-control" placeholder="Estatura" name="estaturaUser3" id="estaturaUser3" required>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <label for="Peso">Peso (kg)</label>
                        <input type="text" class="form-control" placeholder="Peso" name="pesoUser3" id="pesoUser3" required>
                        <br>
                    </div>
                    <!--<input type="hidden" class="form-control" name="plan3" id="plan3" value="PLAN DE ASESORÍA EN ENTRENAMIENTO PLAN 3 $1,600 / 3 MESES" required>-->
                    <input type="hidden" class="form-control" name="plan3" id="plan3" value="PLAN DE ASESORÍA EN ENTRENAMIENTO PLAN 3 $900 / 3 MESES" required>
                    <p style="font-family: Copperplate">Sus datos personales, serán utilizados para ajustar su peso y estura a nuestros tabuladores y tener un resultado más óptimo.</p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Enviar mis datos</button>
                </div>
            </div>
        </div>
    </form>
</div>


<script src="<?php echo base_url("assets/js/jquery.js")?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap.min.js")?>"></script>
<script src="<?php echo base_url("assets/js/smoothscroll.js")?>"></script>
<script src="<?php echo base_url("assets/js/isotope.js")?>"></script>
<script src="<?php echo base_url("assets/js/imagesloaded.min.js")?>"></script>
<script src="<?php echo base_url("assets/js/nivo-lightbox.min.js")?>"></script>
<script src="<?php echo base_url("assets/js/jquery.backstretch.min.js")?>"></script>
<script src="<?php echo base_url("assets/js/wow.min.js")?>"></script>
<script src="<?php echo base_url("assets/js/custom.js")?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/bega/entrenamiento.js');?>"></script>
</body>
</html>