<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pulmón Verde</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}"> </head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background: url('https://wallpapercave.com/wp/wp3219866.jpg') no-repeat center center fixed;
      background-size: cover;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.35);
      z-index: 0;
    }

    .fondo-opaco {
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 1rem;
      z-index: 1;
      position: relative;
    }

    .social-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid #ccc;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      color: #444;
      background-color: #fff;
      text-decoration: none;
      transition: all 0.2s;
    }

    .social-btn:hover {
      background-color: #e6f2e6;
    }
  </style>
</head>
<body class="d-flex flex-column align-items-center justify-content-center" style="min-height: 100vh;">

  <!-- Fondo blanco opaco sobre la imagen -->
  <div class="overlay"></div>

  <!--  CARD LOGIN CON LOGO -->
  <div class="card shadow-lg p-4 fondo-opaco position-relative" style="width: 100%; max-width: 420px; padding-top: 3.5rem;">

    <!-- Logo -->
    <img 
      src="{{ asset('LogoParque.jpg') }}" 
      alt="Logo" 
      style="width: 60px; position: absolute; top: 20px; left: 15px; border-radius: 50%; box-shadow: 0 2px 6px rgba(3,0,0,0.7);">

    <div class="text-center mb-4">
      <h3 class="text-success mb-1"><strong>BIENVENIDO</strong></h3>
      <h5 class="text-success mt-0 fw-normal">Parque Forestal Pulmón Verde</h5>
    </div>

    <form id="form-login">
      <div class="mb-3">
        <label for="usuario" class="form-label">Nombre de Usuario</label>
        <div class="input-group">
          <span class="input-group-text bg-success text-white"><i class="bi bi-person-circle"></i></span>
          <input type="text" class="form-control" id="usuario" placeholder="Ingrese su usuario" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="contrasena" class="form-label">Contraseña</label>
        <div class="input-group">
          <span class="input-group-text bg-success text-white"><i class="bi bi-lock-fill"></i></span>
          <input type="password" class="form-control" id="contrasena" placeholder="••••••••" required>
          <button class="btn btn-outline-success" type="button" id="togglePassword">
            <i class="bi bi-eye-slash" id="iconoOjo"></i>
          </button>
        </div>
      </div>
      <button type="submit" id="btn-login" class="btn btn-success w-100">
        <span id="btn-texto">Iniciar Sesión</span>
        <span id="btn-cargando" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>
    </form>

    <div id="mensaje" class="mt-3 text-center fw-semibold text-danger"></div>

    <hr>
    <p class="text-center mb-2 text-muted">O accede con:</p>
    <div class="d-flex justify-content-center gap-3 mb-2">
      <a href="https://accounts.google.com" target="_blank" class="social-btn"><i class="bi bi-google"></i></a>
      <a href="https://facebook.com" target="_blank" class="social-btn"><i class="bi bi-facebook"></i></a>
      <a href="https://twitter.com" target="_blank" class="social-btn"><i class="bi bi-x"></i></a>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="text-center text-muted mt-3" style="z-index: 2; position: relative;">
    <small><strong>&copy; 2025 Universidad Nacional Autónoma de Honduras</strong></small>
  </footer>

  <!-- Scripts -->
  <script>
    const form = document.getElementById('form-login');
    const mensaje = document.getElementById('mensaje');
    const btnLogin = document.getElementById('btn-login');
    const btnTexto = document.getElementById('btn-texto');
    const btnCargando = document.getElementById('btn-cargando');
    const inputPass = document.getElementById('contrasena');
    const toggleBtn = document.getElementById('togglePassword');
    const iconoOjo = document.getElementById('iconoOjo');

    toggleBtn.addEventListener('click', () => {
      const tipo = inputPass.type === 'password' ? 'text' : 'password';
      inputPass.type = tipo;
      iconoOjo.classList.toggle('bi-eye');
      iconoOjo.classList.toggle('bi-eye-slash');
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      mensaje.textContent = '';
      
      const nombre_usuario = document.getElementById('usuario').value.trim();
      const contrasena = inputPass.value.trim();

      btnLogin.disabled = true;
      btnTexto.classList.add('d-none');
      btnCargando.classList.remove('d-none');

      // Primer paso: Llamada a la API de Node.js
      fetch('http://localhost:3000/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nombre_usuario, contrasena })
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          mensaje.textContent = data.mensaje || 'Credenciales incorrectas.';
        } else {
          localStorage.setItem('usuarioLogueado', JSON.stringify(data.usuario));
          const permiso = data.usuario.cod_permiso;

          // Segundo paso: Llamada a la ruta de Laravel para establecer la sesión
          fetch("{{ route('login.post') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              success: true,
              usuario: data.usuario
            })
          })
          .then(res => res.json())
          .then(laravelData => {
            if (laravelData.success) {
              switch (permiso) {
                case 1:
                case 2:
                  window.location.href = "{{ url('/') }}";
                  break;
                case 3:
                  window.location.href = "{{ url('/invitado') }}";
                  break;
                default:
                  mensaje.textContent = 'Permiso no reconocido.';
              }
            } else {
              mensaje.textContent = 'Error al establecer la sesión en Laravel.';
            }
          })
          .catch(err => {
            console.error('Error al establecer la sesión en Laravel:', err);
            mensaje.textContent = 'Error de comunicación con el servidor de Laravel.';
          });
        }
      })
      .catch(err => {
        console.error('Error en login:', err);
        mensaje.textContent = "No se pudo conectar con el servidor de la API.";
      })
      .finally(() => {
        btnLogin.disabled = false;
        btnTexto.classList.remove('d-none');
        btnCargando.classList.add('d-none');
      });
    });
  </script>
</body>
</html>
