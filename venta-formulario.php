
<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";
include_once "entidades/venta.php";

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

$producto = new Producto();
$aProductos = $producto->obtenerTodos();

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

if($_POST){
  if(isset($_POST["btnGuardar"])){
    if(isset($_GET["id"]) && $_GET["id"] > 0){
      $venta->actualizar();
    } else {
      $venta->insertar();
    }
  }else if(isset($_POST["btnBorrar"])){
    $venta->eliminar();
  }

}

if(isset($_GET["id"]) && $_GET["id"] > 0){
  $venta->idventa = $_GET["id"];
  $venta->obtenerPorId();
}

?>


<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Gestión de Ventas | Venta</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

  <link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css">

</head>

<body id="page-top">
  <form action = "" method = "POST">
    <!-- Page Wrapper -->
    <div id="wrapper">

      <?php

      include_once("menu.php")

      ?>

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

          <!-- Topbar -->
          <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
              <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <div class="d-none d-sm-inline-block form-inline ml-md-3 my-2 my-md-0 w-100 navbar-search">
              <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                  </button>
                </div>
              </div>
            </div>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

  
              <div class="topbar-divider d-none d-sm-block"></div>

              <!-- Nav Item - User Information -->
              <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo  isset($_SESSION["nombre"])? $_SESSION["nombre"] : "Invitado" ?></span>
                  <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                  </a>
                  <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                  </a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                  </a>
                </div>
              </li>

            </ul>

          </nav>
          <!-- End of Topbar -->

          <!-- Begin Page Content -->
          <div class="container">
            <?php if(isset($_GET["id"]) && $_GET["id"] > 0 && isset($_POST["btnGuardar"])){
                echo "<div class = ' mb-4 card bg-success text-white shadow'><div class = 'card-body'>La venta se actualizó correctamente </div></div>";
            } else if(isset($_POST["btnGuardar"])){
              echo "<div class = ' mb-4 card bg-success text-white shadow'><div class = 'card-body'>La venta se registró correctamente </div></div>";
             } else if(isset($_POST["btnBorrar"]) && isset($_GET["id"])){
                echo "<div class = ' mb-4 card bg-danger text-white shadow'><div class = 'card-body'>La venta se eliminó correctamente</div></div>";
             }
              ?>
            <h1 class="h3 mb-4 text-gray-800">Venta</h1>

            <div class="row">
              <div class="col-12 mb-3">
                <a href="listado-ventas.php" class="btn btn-primary mr-2">Listado</a>
                <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-sm-6 form-group">
                <label for="txtFecha">Fecha:</label>
                <input required value = "<?php echo isset($_GET["id"])? date_format(date_create($venta->fecha), 'Y-m-d') : date('Y') . '-' . date('m') . '-' . date('d');?>" class = "form-control" type="date" name = "txtFecha" id = "txtFecha">
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="txtHora">Hora:</label>
                <input required value = "<?php echo isset($_GET["id"])? date_format(date_create($venta->hora), "H:i:s") : date('H:i');?>" class = "form-control" type="time" name = "txtHora" id = "txtHora">
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="lstCliente">Cliente:</label>
                <select name="lstCliente" id="lstCliente" class = "form-control selectpicker border" data-live-search="true">
                <?php foreach($aClientes as $cliente){?>
                  <option value="<?php echo $cliente->idcliente ?>"><?php echo $cliente->nombre ?></option>
                <?php } ?>
                </select>
              </div>

              <div class="col-12 col-sm-6 form-group dropdown bootstrap-select">
                <label for="lstProducto">Producto:</label>
                <select name="lstProducto" id="lstProducto" class = "form-control selectpicker border" data-live-search="true">
                <?php foreach($aProductos as $producto){?>
                  <option value="<?php echo $producto->idproducto ?>"><?php echo $producto->nombre ?></option>
                <?php } ?>
                </select>
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="nbPUnitario">Precio unitario:</label>
                <input required type="number" value = "<?php echo $venta->precio;?>" class = "form-control" name ="nbPUnitario" id = "nbPUnitario" >
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="nbCantidad">Cantidad:</label>
                <input required type="number" value = "<?php echo $venta->cantidad;?>" class = "form-control" name ="nbCantidad" id = "nbCantidad" >
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="nbTotal">Total:</label>
                <input required type="number" value = "<?php echo $venta->total;?>" class = "form-control" name ="nbTotal" id = "nbTotal" >
              </div>

              
            </div>



            <script>
        ClassicEditor
            .create( document.querySelector( '#txtDescripcion' ) )
            .catch( error => {
            console.error( error );
            } );
        </script> 


          </div>
          <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright &copy; Your Website 2020</span>
            </div>
          </div>
        </footer>
        <!-- End of Footer -->

      </div>
      <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-select.min.js"></script>
    
    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
  </form>
</body>

</html>
