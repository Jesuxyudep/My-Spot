<link rel="stylesheet" href="CSS/login.css">

 <!-- Modal -->
   <div class="modal fade" id="infoModal">
     <div class="modal-dialog modal-dialog-scrollable modal-lg">
       <div class="modal-content">

         <div class="modal-header">
           <h1>MySpot - Cambia cómo descubres nueva música</h1>
           <button type="button" class="close" data-dismiss="modal">×</button>
         </div>

         <div class="modal-body">
 		  <p>Con MySpot puedes encontrar tus antiguas gemas de Spotify. Algunas de las canciones que quizás ya hayas olvidado.
 		  <p>Refresca tus recuerdos y crea listas de reproducción de Spotify a partir de tus canciones y artistas favoritos.</p>
 		  <p>Administra tus playlist rápidamente de una manera cómoda y simple</p>


 		  <h2>Spotify access</h2>
               <p>La aplicación requiere una cuenta de Spotify. También necesita tener acceso a tu cuenta de Spotify. La aplicación funciona tanto en cliente como en servidor y solamente almacenaremos los datos estrictamente necesarios.</p>

           <h2>He entrado con una cuenta de Spotify equivocada</h2>
               <p>No te preocupes, sólo tienes que ir a <a href="http://accounts.spotify.com/" target="_blank" rel="noopener noreferrer">accounts.spotify.com</a> y pulsar el botón Cerrar sesión. A continuación, vuelve a <a href="">MySpot.es</a> e inicia sesión con tu cuenta.</p>

           <h2>Privacidad</h2>
               <p>La aplicación almacena tu ID de Spotify para conocerte en el futuro junto con tu
               "tokens" para ofrecerte un mejor servicio. Si tienes algún inconveniente o duda puedes enviar un mensaje a <a href="">privacidad@myspot.com</a></p>

         </div>

         <!-- Modal footer -->
         <div class="modal-footer">
 			<div class="enlacesApp">
 	            <a class="enlaceFooter github" href="">
 	                <i class="fab fa-github"></i>
 	            </a>
 	            <a class="enlaceFooter spotify" href="">
 	                <i class="fab fa-spotify"></i>
 	            </a>
 	            <a class="enlaceFooter twitter" href="">
 	                <i class="fab fa-twitter"></i>
 	            </a>
 	            <a class="enlaceFooter fwitter" href="https://fwitter.net/">
 	                <img src="IMG/Fwitter30.png">
 	            </a>
 	        </div>
           	<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
         </div>

       </div>
     </div>
   </div>

<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="infoLogin">
				<div class="d-flex justify-content-center">
					<div class="logoContainer">
						<img src="IMG/logo.png" class="logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center tituloContainer">
					<h1>Cambia el modo en el que descubres nueva música</h1>
				</div>
				<div class="d-flex justify-content-center mt-3 loginContainer">
					<button class="btn btn-primary btn-login"><a href="PHP/login/auth.php">Inicia sesión con Spotify</a></button>
				</div>
				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						<a class="btn-link" href="" data-toggle="modal" data-target="#infoModal">¿Qué es esto?</a>
					</div>
				</div>
			</div>
		</div>
</div>
