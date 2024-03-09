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

$colname_cliente = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_cliente = $_SESSION['MM_Username'];
}
mysql_select_db($database_Nabverse, $Nabverse);
$query_cliente = sprintf("SELECT * FROM cliente WHERE Email = %s", GetSQLValueString($colname_cliente, "text"));
$cliente = mysql_query($query_cliente, $Nabverse) or die(mysql_error());
$row_cliente = mysql_fetch_assoc($cliente);
$totalRows_cliente = mysql_num_rows($cliente);

$maxRows_Produto = 6;
$pageNum_Produto = 0;
if (isset($_GET['pageNum_Produto'])) {
  $pageNum_Produto = $_GET['pageNum_Produto'];
}
$startRow_Produto = $pageNum_Produto * $maxRows_Produto;

mysql_select_db($database_Nabverse, $Nabverse);
$query_Produto = "SELECT * FROM produto, marca WHERE produto.IdMarca=marca.IdMarca AND produto.Status='Disponivel'";
$query_limit_Produto = sprintf("%s LIMIT %d, %d", $query_Produto, $startRow_Produto, $maxRows_Produto);
$Produto = mysql_query($query_limit_Produto, $Nabverse) or die(mysql_error());
$row_Produto = mysql_fetch_assoc($Produto);

if (isset($_GET['totalRows_Produto'])) {
  $totalRows_Produto = $_GET['totalRows_Produto'];
} else {
  $all_Produto = mysql_query($query_Produto);
  $totalRows_Produto = mysql_num_rows($all_Produto);
}
$totalPages_Produto = ceil($totalRows_Produto/$maxRows_Produto)-1;
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
    <link rel="stylesheet" type="text/css" href="style.css" />
     <link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
     
    <title>NabVerse</title>
    
    <style>
	
	</style>
    
    
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>

</head>

<body>

    <div id="line" class="">
    <p>Entregas Gr&aacute;tis em compras > 200&euro; em produtos vendidos pela NabVerse</p>
    
   </div>
   <div id="line_UserName">
     
    <p> </p>
    
    <?php if(isset($_SESSION['MM_Username'])) {echo "<p style='color:#000;'> Bem Vindo(a), &nbsp;".$_SESSION['MM_Username']."</p>"; }?>
    
</div>
    
    <div id="header-wrapper">
   
        <div id="header" class="container">
            <div id="logo">
                <h1><a href="Index1.php">Nab<span>Verse</span></a></h1>
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
                                            <li><a href="Apple - Copy.php">Apple</a></li>
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
                    
                <img src="Imagem/login.png" alt="Login Icon" width="30px" height="30px"/>
                
                <div id="loginbtn" class="dropdown_menu">
                    <ul>
                        <li><a onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</a></li>
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
        <div id="banner" class="container3">
            <div class="title">
                <h2>Descubra ofertas exclusivas em diversos produtos.</h2>
                <span class="byline">Aproveite rapidamente para garantir as suas compras e desfrutar de poupanças significativas.</span>
            </div>
           &nbsp; 
            <ul class="actions">
                <li><a href="Produtos.php" class="button1">Saber-Mais</a></li>
            </ul>
        </div>
    </div>

    &nbsp;
    
    
  <div id="id01" class="modal">
  
  <form class="modal-content animate" action="<?php echo $loginFormAction; ?>" method="POST">
  
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Fechar">&times;</span>
      <img src="Imagem/6681204.png" alt="Avatar" class="avatar">
    </div>

    <div class="Logincontainer">
    
    <span id="sprytextfield1">
     <p>
      <label for="Email"><b>Email</b></label>
      <input type="text" name="Email" placeholder="Introduza Email" id="Email2">
      <span class="textfieldRequiredMsg">Email obrigatória.</span><span class="textfieldInvalidFormatMsg">E-mail inválido.</span><span id="sprytextfield2">
      </span></p>
    
      <label for="psw"><b>Password</b></label>
      <span id="sprytextfield4">
      <label for="PalavraPass"></label>
      <input type="text" name="PalavraPass" placeholder="Introduza Password" id="PalavraPass">
      <span class="textfieldRequiredMsg">Password obrigatória.</span></span>
      <label>
        <input type="checkbox" checked="checked" name="remember"> Lembrar-me
      </label>
      <p style="float:right;"> <a href="Alterar_Dados.php">Esqueceu a senha </a></p>
     
      <button type="submit">Login</button>
      
    </div>
     
    <div class="Reg-Pas">
    <p style="text-align:center;">Ainda não tem conta? <a href="Register.php">Register</a></p>
    </div>
    
    
  </form>
  
</div>

<script type="text/javascript">

var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}


       document.addEventListener("DOMContentLoaded", function() {
            const callout = document.querySelector('.callout');
            const closeBtn = document.querySelector('.closebtn');

            // Open the callout
            callout.style.right = '15px';

            // Close the callout
            closeBtn.addEventListener('click', function() {
                callout.style.right = '-300px';
            });
        });
		
</script>
    
    
    
    
    

    <div class="container2">
        <div class="imageZFlip">
            <img src="Imagem/GalaxyZFlip5.jpg" alt=" Imagem - Galaxy Z Flip 5">
        </div>
        <div class="text">
            <h2>Galaxy Z Flip5</h2>
            <p><span>E se o seu telefone fosse uma extens&atilde;o de si?</span><br />
                Um ajuste perfeito, tanto na vida como no bolso. Uma Flex Window luminosa que pode personalizar. Uma c&acirc;mara que capta selfies a partir de &acirc;ngulos arrojados e ainda, uma bateria que se mant&eacute;m
                <br /><span> Galaxy Z Flip5 | Junte-se ao lado Flip </span>
            </p>
            <a href="Detalhe.php?codigo=<?php echo $row_Produto['IdProduto']=18; ?>" class="button">SABER MAIS SOBRE O PRODUTO</a>
        </div>
    </div>&nbsp;
<div class="deal-container">
<img src="Imagem/iphone13verde1.jpg" alt="iPhone 13 APPLE (6.1'' - 128 GB - Verde)" class="deal-image">
        <div class="deal-text">
            <h1>Oferta do Dia</h1>
            <p>iPhone 14 APPLE (6.1'' - 128 GB - Verde)</p>
            <p class="price">$799.99</p>
          <p class="discounted-price">$649.99</p>
            <a href="Detalhe.php?codigo=<?php echo $row_Produto['IdProduto']=31; ?>" class="deal-button">Ver Produto</a>
            <a href="Compras.php?codigo=<?php echo $row_Produto['IdProduto']=31; ?>" class="deal-button">Comprar Agora</a>
  </div>
        <div style="clear: both;"></div>
        
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<div style="text-align:center;">
    <h2 style="margin: 20px 0; font-size: 24px; color: #333; text-transform: uppercase;">Produtos em Destaque</h2>
  </div>
  <hr>
</div>


<div class="product-container">
   <?php
  do {
?>
   <div class="card">
      <div class="card-inner">
        <div class="card-front">
        
    <img src="Imagem/<?php echo $row_Produto['Imagem1']; ?>" alt="Produtos Imagem" class="product-image">
    <p>&nbsp;</p>
    <p class="Product-price"><?php echo '$' . $row_Produto['Preco']; ?></p>
    <h1 class="product-title"><?php echo $row_Produto['Nome']; ?></h1>
    
    <div class="button-container">
      <button class="add-to-cart">Add to Cart</button>
      <button class="view-details">View Details</button>
    </div>
    </div>
    
    <div class="card-back">
    <img src="Imagem/<?php echo $row_Produto['Imagem3']; ?>" alt="Produtos Imagem" class="Back_product-image">   
    <div class="button-container">
      <button class="add-to-cart">Add to Cart</button>
      <button class="view-details">View Details</button>
    </div>
    </div>
    
  </div>
  </div>
  <?php
  } while ($row_Produto = mysql_fetch_assoc($Produto));
?>
</div>



<div class="sobranos-container">
        <div class="title">
           
                <h5>QUEM N&Oacute;S SOMOS </h5>
                <h2>Conhe&ccedil;a a nossa Empresa<br /><hr /><br /></h2>
                <h3> Bem-vindo &agrave; Nabverse &ndash; O Seu Destino &Uacute;ltimo para Solu&ccedil;&otilde;es M&oacute;veis de Vanguarda!</h3><br />

                <p style="color:#CCC;">Na Nabverse, n&atilde;o somos apenas uma loja de venda de telem&oacute;veis; somos os arquitetos da conectividade sem falhas, os promotores da inova&ccedil;&atilde;o e os seus parceiros de confian&ccedil;a no mundo em constante evolu&ccedil;&atilde;o da tecnologia m&oacute;vel.
                <br /> &nbsp;Embarque connosco nesta jornada enquanto redefinimos a forma como voc&ecirc; experimenta e interage com o reino digital.</p>
                <ul class="actions">
                    <li><a href="SobraNos.php" class="button2">Saber Mais</a></li>
                </ul>
            
        </div>
</div>

   
<div class="callout">
  <div class="callout-header">BLACK FRIDAY - Promoções </div>
  <span class="closebtn" onclick="this.parentElement.style.display='none';">×</span>
  <div class="callout-container">
    <p>Explore promoções exclusivas numa variedade de produtos. <a href="Promocoes.php">Compre agora</a> e poupe mais. </p>
  </div>
</div>




    <div id="copyright">
        <p>Copyright &copy; 2024 Nabverse Inc. All rights reserved</p>
    </div>

</body>

</html>
<?php
mysql_free_result($cliente);
mysql_free_result($Produto);
?>

