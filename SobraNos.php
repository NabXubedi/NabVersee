<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="SobraNos_style.css" />
    
    <title>NabVerse</title>
    
</head>

<body>
    <div id="header-wrapper">
      <div id="header" class="container">
            <div id="logo">
                <h1><a href="Index.php">Nab<span>Verse</span></a></h1>
            </div>
            <div id="menu">
                <ul>
                    <li><a href="Index.php">Home</a></li>
                    <li><a>Clientes</a></li>
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
                    <li><a href="SobraNos.php">Sobre Nós</a></li>
                    <li><a href="Contate_Nos.php">Contacto Nós</a></li>
                </ul>
            </div>
            <div id="icon">
                <img src="Imagem/login.png" alt="Login Icon" width="30px" height="30px" />
                <div id="loginbtn" class="dropdown_menu">
                    <ul>
                        <li><a href="Login.php">Login</a></li>
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
    <div id="banner" class="container">
    </div> 
    </div>
     <div class="container">
            <div class="title">
                <h2>Sobre Nossa Loja <hr /><br /></h2>
                
              <h3> Bem-vindo à Nabverse – O Seu Destino Último para Soluções Móveis de Vanguarda!</h3>
              
            <p>Na Nabverse, não somos apenas uma loja de venda de telemóveis; somos os arquitetos da conectividade sem falhas, os promotores da inovação e os seus parceiros de confiança no mundo em constante evolução da tecnologia móvel. Embarque connosco nesta jornada enquanto redefinimos a forma como você experimenta e interage com o reino digital.</p>
            <p><span>A Nossa História :</span> Foi fundada em 2023 com o objetivo de aproximar o público nacional aos produtos oficiais da marca Nabverse, acrescentando valor aos Nabverse Fans e impulsionando o desenvolvimento. <br /> A Nabverse nasceu de uma paixão por estar à frente da curva tecnológica. Compreendemos que o seu smartphone não é apenas um dispositivo; é uma extensão do seu estilo de vida, um condutor para as suas aspirações digitais. Com esta visão, lançamo-nos na criação de um espaço onde a vanguarda encontra a centricidade no cliente, e assim nasceu a Nabverse.</p>
            </div>
        </div>
        <div class="container2">
        <div class="image">
            <img src="Imagem/Store.jpg" alt="Nabverse Store Img">
        </div>
        <div class="text">
            <h2>Lojas NabVerse</h2><br /><br />
            <p>Dispomos de várias lojas físicas em território nacional, bem como loja online, e estamos em processo de expansão, com o intuito de chegarmos a cada vez mais NabVerse Fans.<br /><br/>
O nosso foco é "Customer Experience" (CX), sendo o cliente a prioridade do nosso negócio. Assim, procuramos fornecer um serviço personalizado de excelência e, também por isso, ambicionamos alargar a nossa rede de lojas a todo o país.
<br /><br/>
Atualmente estamos presentes em várias localizações.
</p>
        </div>
    </div>
    
<div class="container3">
     <div class="image1">
       <img src="Imagem/Screenshot 2023-12-09 233654.png" alt="Banner">
     </div>
</div>


     
        <div id="copyright" class="container" >
        <p>Copyright &copy; 2024 Nabverse Inc. All rights reserved</p>
        </div>
    </body>
</html>
