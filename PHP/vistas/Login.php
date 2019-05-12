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
               <p>MySpot recupera datos de tu cuenta de Spotify para funcionar, por lo que necesita tener acceso a tu cuenta de Spotify.</p>

               <h2>He entrado con una cuenta de Spotify equivocada</h2>
                   <p>No te preocupes, sólo tienes que pulsar en el botón de cerrar sesión de tu perfil que te llevará a <a href="http://accounts.spotify.com/" target="_blank" rel="noopener noreferrer">accounts.spotify.com</a> donde debes pulsar el botón Cerrar sesión. <br> A continuación, vuelve a <a href="http://myspot.epizy.com/">MySpot</a> e inicia sesión con tu cuenta.</p>

               <h2>Privacidad</h2>
                   <p>MySpot guarda los mínimos datos personales necesarios para poder identificarte cuando accedas en un futuro. Si tienes algún inconveniente o quieres obtener más información contacta con nosotros a través del siguiente correo: <a href="mailto:privacidad@myspot.com">privacidad@myspot.com</a></p>

         </div>

         <!-- Modal footer -->
         <div class="modal-footer">
 			<div class="enlacesApp">
                <div class="container">
                    <div class="row">
                        <div class="col-3 col-sm-3">
                            <a class="enlaceFooter github" href="">
             	                <i class="fab fa-github"></i>
             	            </a>
                        </div>
                        <div class="col-3 col-sm-3">
             	            <a class="enlaceFooter spotify" href="">
             	                <i class="fab fa-spotify"></i>
             	            </a>
                        </div>
                        <div class="col-3 col-sm-3">
             	            <a class="enlaceFooter twitter" href="">
             	                <i class="fab fa-twitter"></i>
             	            </a>
                        </div>
                        <div class="col-3 col-sm-3">
             	            <a class="enlaceFooter fwitter" href="https://fwitter.net/">
             	                <img src="IMG/Fwitter30.png">
             	            </a>
                        </div>
                    </div>
                </div>
 	        </div>
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
