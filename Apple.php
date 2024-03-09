<?php require_once('Connections/Nabverse.php'); ?>


<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "Index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>


<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$colname_cliente = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_cliente = $_SESSION['MM_Username'];
}
mysql_select_db($database_Nabverse, $Nabverse);
$query_cliente = sprintf("SELECT * FROM cliente WHERE Email = %s", GetSQLValueString($colname_cliente, "text"));
$cliente = mysql_query($query_cliente, $Nabverse) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$maxRows_Apple = 1;
$pageNum_Apple = 0;
if (isset($_GET['pageNum_Apple'])) {
  $pageNum_Apple = $_GET['pageNum_Apple'];
}
$startRow_Apple = $pageNum_Apple * $maxRows_Apple;

mysql_select_db($database_Nabverse, $Nabverse);
$query_Apple = "SELECT * FROM produto, marca WHERE produto.IdMarca=2 AND produto.IdMarca=marca.IdMarca AND produto.Status = 'Disponivel'";
$query_limit_Apple = sprintf("%s LIMIT %d, %d", $query_Apple, $startRow_Apple, $maxRows_Apple);
$Apple = mysql_query($query_limit_Apple, $Nabverse) or die(mysql_error());
$row_Apple = mysql_fetch_assoc($Apple);

if (isset($_GET['totalRows_Apple'])) {
  $totalRows_Apple = $_GET['totalRows_Apple'];
} else {
  $all_Apple = mysql_query($query_Apple);
  $totalRows_Apple = mysql_num_rows($all_Apple);
}
$totalPages_Apple = ceil($totalRows_Apple/$maxRows_Apple)-1;

$queryString_Apple = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Apple") == false && 
        stristr($param, "totalRows_Apple") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Apple = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Apple = sprintf("&totalRows_Apple=%d%s", $totalRows_Apple, $queryString_Apple);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['Email'])) {
  $loginUsername=$_POST['Email'];
  $password=$_POST['PalavraPass'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "Index.php";
  $MM_redirectLoginFailed = "PaginaLogin_Error_MSg.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_Nabverse, $Nabverse);
  
  $LoginRS__query=sprintf("SELECT Email, PalavraPass FROM cliente WHERE Email=%s AND PalavraPass=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $Nabverse) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"  href="Imagem/LogoNv2.png" type="image/gif" />
    <link rel="stylesheet" type="text/css" href="MarcaStyle.css" />
    <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
    <title>NabVerse - Apple </title>
    
    <style>

/* Full-width input fields */
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

/* Extra styles for the cancel button */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

/* Center the image and position the close button */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
  position: relative;
  
}
.Reg-Pas{
	text-align:center;
	text-decoration:none;
	
}
.Reg-Pas a{
	text-align:center;
	text-decoration:none;
	
}
img.avatar {
  border-radius: 50%;
  width:100px;
  height:100px;
}

.Logincontainer {
  padding: 16px;
  width:93%;
  text-decoration:none;
}
.Logincontainer p a{
 
  text-decoration:none;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 10% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 30%; /* Could be more or less, depending on screen size */
  border-radius: 30px;
}

/* The Close Button (x) */
.close {
  position: absolute;
  right: 25px;
  top: 0;
  color: #000;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #F00;
  cursor: pointer;
}

/* Add Zoom Animation */
.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}


/*Callout */

.callout {
      position: fixed;
      bottom: 20px;
      max-width: 350px;
      overflow: hidden;
      border-radius: 8px;
      background: #fff;
	  right: -300px;
      transform: translate(0, -50%);
      transition: right 0.9s ease-in-out;
	  
    }

    .callout-header {
      padding: 15px;
      background: url(Imagem/bg1.png);
      font-size: 18px;
	  font-family: Verdana, Geneva, sans-serif;
	  font-weight:600;
      color: #F00;
      border-bottom: 1px solid #ccc;
    }

    .closebtn {
      position: absolute;
      top: 5px;
      right: 15px;
      color: white;
      font-size: 20px;
      cursor: pointer;
    }

    .closebtn:hover {
      color: lightgrey;
    }

    .callout-container {
      padding: 15px;
      color: #333;
    }

    .callout-container p {
      margin-bottom: 10px;
    }

    .callout-container a {
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
    }

    .callout-container a:hover {
      text-decoration: underline;
    }
     
	 .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination a {
        color: #3498db;
        padding: 8px 12px;
        margin: 0 5px;
        text-decoration: none;
        border: 1px solid #3498db;
        border-radius: 4px;
    }

    .pagination a:hover {
        background-color: #3498db;
        color: #fff;
    }


@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)} 
  to {-webkit-transform: scale(1)}
}
  
@keyframes animatezoom {
  from {transform: scale(0)} 
  to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

</head>

<body>
    
    <div id="header-wrapper">
    
    <video autoplay muted loop id="video-background">
      <source src="Imagem/large_2x.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
   
        <div id="header" class="container">
            <div id="logo">
                <h1><a href="Index.php">Nab<span>Verse</span></a></h1>
            </div>
            
            
            <div id="menu">
                <ul>
                    <li><a href="Index.php">Home</a></li>
                    <li><a href="Conta.php">Conta</a></li>
                    <li>
                        <a>Produtos</a>
                        <div class="dropdown_menu">
                            <ul>
                                <li><a href="Produtos.php">Tudo Produtos</a></li>
                                <li><a>Marcas</a>
                                    <div class="sub_dropdown_menu">
                                        <ul>
                                            <li><a href="Samsung.php">Samsung</a></li>
                                            <li><a href="Apple.php">Apple</a></li>
                                            <li><a href="Redmi.php">Redmi</a></li>
                                            <li><a href="Huawei.php">Huawei</a></li>
                                            <li><a href="GooglePixel.php">Google Pixel</a></li>
                                            <li><a href="OnePlus.php">OnePlus</a></li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li><a href="SobraNos.php">Sobre N&oacute;s</a></li>
                    <li><a href="Contate_Nos.php">Contacto N&oacute;s</a></li>
                    
                    <?php
            if(isset( $_SESSION['MM_Username']) && $_SESSION['MM_Username'] == 'Admin@gmail.com')
            {
           echo'<li><a href="Adm.php">Administration</a> </li>';
            }
           ?>
                   
                </ul>
            </div>
            
   
            
            <div id="icon">
                    
                <img src="Imagem/6681204.png" alt="Login Icon" width="30px" height="30px"/>
                
                <div id="loginbtn" class="dropdown_menu">
                    <ul>
                        <li><a href="Login.php" >Login</a></li>
                        <li><a href="Register.php">Registar</a></li>
                        <li><a href="Alterar_Dados.php">Alterar Dados</a></li>
                           
                        <li>
                      
                        <?php
                            if(isset ($_SESSION ['MM_Username']))
						
					       {?>
                           <a class="logoutbutton" href="<?php echo $logoutAction ?>">Log Out </a>
                       <?php } 
					        ?>
                        
                        
                      </li>
                    </ul>
                </div>
            </div>
        </div>
       
    </div>

    &nbsp;
    &nbsp;
<form name="form1" method="post" action="">
      
     <div class="container2">
     
       <div class="imageZFlip">
         <p><img src="Imagem/<?php echo $row_Apple['Imagem1']; ?>"></p>
       </div>
        
       <div class="text">
        <h2><?php echo $row_Apple['Nome']; ?></h2>
            <p>&nbsp;
            <p> Cor:<span id="spryselect1">
            <label for="Cor1"></label>
            </span>
              <?php echo $row_Apple['Cor1']; ?>
            <p>Stock:             
              <?php echo $row_Apple['Stock']; ?>
            <p> Pre&ccedil;o: &euro;
         <?php echo $row_Apple['Preco']; ?>&nbsp;            
         <p>
            <a href="Compras.php?codigo=<?php echo $row_Apple['IdProduto']; ?>" class="button">COMPARAR O PRODUTO</a>
       </div>
        
     </div>
     
  <div class="container2">
     
     <div class="TextDescricao">
     <p>&nbsp;<p>
        <h2><?php echo $row_Apple['Nome']; ?></h2>
        
            <p>&nbsp;<p>
            <p><span><?php echo $row_Apple['Descricao']; ?></span>
            <p>&nbsp;<p>
            
            <h3>Caracter&iacute;sticas t&eacute;cnicas</h3>
            
            <p>&nbsp;<p>
            <p style="color:#000"> Memoria RAM : <?php echo $row_Apple['MemoriaRAM']; ?></p>
            <p style="color:#000"> Memoria Interna : <?php echo $row_Apple['MemoriaInterna']; ?></p>
            <p style="color:#000"> Memoria Externa : <?php echo $row_Apple['MemoriaExterna']; ?></p>
            <p style="color:#000"> Potencia Bateria : <?php echo $row_Apple['PotenciaBateria']; ?></p>
            <p style="color:#000">Entrada Carregador : <?php echo $row_Apple['EntradaCarregador']; ?></p>
            <p style="color:#000">Sistema Operativo : <?php echo $row_Apple['SistemaOperativo']; ?></p>
            <p style="color:#000">Diagonal do Ecra : <?php echo $row_Apple['DiagonaldoEcra']; ?></p>
            <p style="color:#000">Slots SIM : <?php echo $row_Apple['SlotsSIM']; ?></p>
            <p style="color:#000"> Cor : <?php echo $row_Apple['Cor1']; ?></p>
            <p style="color:#000">Marca : <?php echo $row_Apple['Marca']; ?></p>
            <p style="color:#000"> Status : <?php echo $row_Apple['Status']; ?></p>
            <p>&nbsp;</p>
            
      </div>
    </div>
         
  <div class="container2">
          
          <div class="imageZRight">
          <img src="Imagem/<?php echo $row_Apple['Imagem2']; ?>" alt=" Imagem">
          </div>
          
          <div class="imageZLeft">
          
          <img src="Imagem/<?php echo $row_Apple['Imagem3']; ?>" alt=" Imagem">
          
          </div>
          </div>
           <p>&nbsp;</p>
 
   <div class="pagination">
    <a href="<?php printf("%s?pageNum_Apple=%d%s", $currentPage, 0, $queryString_Apple); ?>">Primeiro</a>
    <a href="<?php printf("%s?pageNum_Apple=%d%s", $currentPage, max(0, $totalPages_Apple - 1), $queryString_Apple); ?>">Anterior</a>
    <a href="<?php printf("%s?pageNum_Apple=%d%s", $currentPage, min($totalPages_Apple, $pageNum_Apple + 1), $queryString_Apple); ?>">Próximo</a>
    <a href="<?php printf("%s?pageNum_Apple=%d%s", $currentPage, $totalPages_Apple, $queryString_Apple); ?>">Último</a>
</div>
</form>
    
   <p>&nbsp;</p> 
    


<div id="copyright">
        <p>Copyright &copy; 2024 Nabverse Inc. All rights reserved</p>
    </div>

</body>

</html>
<?php
mysql_free_result($cliente);

mysql_free_result($Apple);
?>

