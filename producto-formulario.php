
<?php

include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";
include_once "entidades/tipoproducto.php";
include_once "entidades/venta.php";

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);

$tipoproducto = new TipoProducto();
$aTipoProductos = $tipoproducto->obtenerTodos();

if($_POST){
  if(isset($_POST["btnGuardar"])){
    if($_FILES["fileImagen"]["error"] === UPLOAD_ERR_OK){
      $nombreAleatorio = date("Ymdhmsi");
      $archivo_temp = $_FILES["fileImagen"]["tmp_name"];
      $nombreArchivo = $_FILES["fileImagen"]["name"];
      $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
      $nombreImagen = $nombreAleatorio . "." . $extension;
      move_uploaded_file($archivo_temp, "images/$nombreImagen");
      $producto->imagen = $nombreImagen;
    }

    if(isset($_GET["id"]) && $_GET["id"] > 0){

      // Si se actualiza

      $productoAnt = new Producto();
      $productoAnt->idproducto = $_GET["id"];
      $productoAnt->obtenerPorId();

      $imagenAnterior = $productoAnt->imagen;

      // Si sube imagen, actualiza y elimina la anterior

      if($_FILES["fileImagen"]["error"] === UPLOAD_ERR_OK){
        if($imagenAnterior != ""){
          unlink("images/".$imagenAnterior);
        }
      } else{
        $nombreImagen = $imagenAnterior;
      }

      $producto->imagen = $nombreImagen;
      $producto->actualizar();
      $mensaje = "El producto se actualizó correctamente";
    } else{
      // Se inserta

      $producto->insertar();
      $mensaje = "El producto se registró correctamente";
    }
  } else if(isset($_POST["btnBorrar"])){
    $producto->eliminar();
    $mensaje = "El producto se eliminó correctamente";
  }
}
if(isset($_GET["id"]) && $_GET["id"] > 0){
  $producto->idproducto = $_GET["id"];
  $producto->obtenerPorId();
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

  <title>Gestión de Ventas | Producto</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

  <link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css">

</head>

<body id="page-top">
  <form action = "" method = "POST" enctype = "multipart/form-data">
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
            <div class="d-none d-sm-inline-block form-inline  ml-md-3 my-2 my-md-0 w-100 navbar-search">
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
          <?php if(isset($_POST["btnGuardar"])){
                echo "<div class = ' mb-4 card bg-success text-white shadow'><div class = 'card-body'>$mensaje</div></div>";
            } else if(isset($_POST["btnBorrar"]) && isset($_GET["id"])){
              echo "<div class = ' mb-4 card bg-danger text-white shadow'><div class = 'card-body'>$mensaje</div></div>";
            } else if(isset($_POST["btnGuardar"]) && isset($_GET["id"])){
              echo "<div class = ' mb-4 card bg-danger text-white shadow'><div class = 'card-body'>$mensaje</div></div>";
            }
            
            ?>
            <h1 class="h3 mb-4 text-gray-800">Producto</h1>

            <div class="row">
              <div class="col-12 mb-3">
                <a href="listado-productos.php" class="btn btn-primary mr-2">Listado</a>
                <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                <button type="submit" class="btn btn-danger" id="btnBorrar" name="btnBorrar">Borrar</button>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-sm-6 form-group">
                <label for="txtNombre">Nombre:</label>
                <input required value = "<?php echo $producto->nombre;?>" class = "form-control" type="text" name = "txtNombre" id = "txtNombre">
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="lstTipoProducto">Tipo de producto:</label>
                <select name="lstTipoProducto" id="lstTipoProducto" class = "form-control selectpicker border" data-live-search="true">
                  <?php foreach($aTipoProductos as $tipo_producto){?>
                    <option value="<?php echo $tipo_producto->idtipoproducto ?>"><?php echo $tipo_producto->nombre ?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="col-12 col-sm-6 form-group">
                <label for="nbCantidad">Cantidad:</label>
                <input required value = "<?php echo $producto->cantidad;?>" class = "form-control" type="number" name = "nbCantidad" id = "nbCantidad">
              </div>

              <div class="col-12 col-sm-6">
                <label for="nbPrecio">Precio:</label>
                <input required value = "<?php echo $producto->precio;?>" class = "form-control" type="number" name = "nbPrecio" id = "txtPrecio">
              </div>


              <div class="col-12 mt-2">
                <label for="txtDescripcion">Descripcion:</label>
                <textarea type="text" name="txtDescripcion" id="txtDescripcion"><?php echo $producto->descripcion;?></textarea>

              </div>

              <div class="col-12 col-sm-6 mt-2">
                <label for="fileImagen">Imagen:</label>
                <input class = "form-control-file" type="file" name = "fileImagen" id = "fileImagen">
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
