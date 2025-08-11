@extends('adminlte::page')

@section('title', 'Pulmon Verde')

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@section('content_header')
@stop

@section('content')
<div class="container-fluid mt-4">

    <!-- Carrusel de Imágenes -->
    <div id="carruselParques" class="carousel slide mb-5 mx-auto" data-bs-ride="carousel" style="max-width: 700px;">
      <div class="carousel-inner rounded shadow">
        <div class="carousel-item active">
          <img src="https://img.freepik.com/foto-gratis/hermoso-parque_1417-1417.jpg?semt=ais_hybrid&w=740" class="d-block w-100" alt="Parque 1" style="height: 400px; object-fit: cover;">
        </div>
        <div class="carousel-item">
          <img src="https://img.lovepik.com/photo/50072/2231.jpg_wh860.jpg" class="d-block w-100" alt="Parque 2" style="height: 400px; object-fit: cover;">
        </div>
        <div class="carousel-item">
          <img src="https://www.honduras.com/wp-content/uploads/2020/09/parque-la-tigra.jpg" class="d-block w-100" alt="Parque 3" style="height: 400px; object-fit: cover;">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carruselParques" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carruselParques" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>

    <!-- Tarjetas de Parques -->
    <h4 class="text-success mb-3 text-center">Más Visitados</h4>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
          <img src="https://thumbs.dreamstime.com/b/santiago-region-metropolitana-chile-december-people-walking-forestal-park-more-traditional-urban-park-city-150443248.jpg" 
               class="card-img-top" alt="Parque Central" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Parque Central</h5>
            <p class="card-text">Un espacio verde ideal para caminar, descansar o disfrutar de un picnic. Cuenta con senderos, bancos, árboles y fuentes.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
          <img src="https://a-static.besthdwallpaper.com/central-park-forest-road-with-its-unique-colors-in-autumn-wallpaper-1280x800-93191_3.jpg" 
               class="card-img-top" alt="Parque Japonés" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Parque Japonés</h5>
            <p class="card-text">Inspirado en la cultura japonesa, este parque ofrece un entorno tranquilo con jardines, lagos y arquitectura oriental.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Álbum de Parques -->
    <h4 class="text-success mb-3 text-center">Galería de Parques</h4>
    <div class="row row-cols-2 row-cols-md-3 g-4 mb-5">
      @foreach ([
        'https://www.poloc.org/wp-content/uploads/2024/02/DSC04292-scaled.jpg',
        'https://p4.wallpaperbetter.com/wallpaper/903/946/516/the-hall-of-mosses-in-hoh-river-valley-olympic-national-park-washington-wallpaper-preview.jpg',
        'https://cdn.wallpapersafari.com/88/80/U56g4k.jpg',
        'https://img.lovepik.com/photo/50199/8933.jpg_wh860.jpg',
        'https://www.aratours.com/templates/yootheme/cache/40/pacifico-norte-40eb9ed1.jpeg',
        'https://previews.123rf.com/images/1xpert/1xpert1907/1xpert190700325/127429208-park-forest-ecology-background-summer-nature-landscape.jpg'
      ] as $foto)
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="{{ $foto }}" class="card-img-top" alt="Parque" style="height: 150px; object-fit: cover;">
          </div>
        </div>
      @endforeach
    </div>

    <!-- Tarjetas de Recomendaciones -->
    <h4 class="text-success mb-3 text-center">Recomendaciones</h4>
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col">
        <div class="card h-100 border-success shadow">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="fas fa-tree me-2"></i>Respetar la Naturaleza
            </h5>
            <p class="card-text">Evita dañar árboles o plantas. No dejes basura y respeta los senderos señalizados.</p>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100 border-success shadow">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="fas fa-users me-2"></i>Actividades Permitidas
            </h5>
            <p class="card-text">Puedes hacer caminatas, yoga, fotografía o leer tranquilamente. Revisa si se permiten mascotas.</p>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100 border-success shadow">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="fas fa-tint me-2"></i>Hidratación y Protección Solar
            </h5>
            <p class="card-text">Trae contigo una botella de agua y usa bloqueador solar para protegerte durante tu visita.</p>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100 border-success shadow">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="fas fa-volume-mute me-2"></i>Respeto al Silencio
            </h5>
            <p class="card-text">Evita ruidos fuertes. Disfruta del canto de las aves y el sonido natural del entorno.</p>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100 border-success shadow">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="fas fa-shield-alt me-2"></i>Seguridad Personal
            </h5>
            <p class="card-text">Mantente alerta de tus pertenencias, evita zonas solitarias y sigue las indicaciones del personal del parque.</p>
          </div>
        </div>
      </div>

      <div class="col">
        <div class="card h-100 border-success shadow">
          <div class="card-body">
            <h5 class="card-title text-success">
              <i class="fas fa-clock me-2"></i>Horarios del Parque
            </h5>
            <p class="card-text">Infórmate sobre los horarios de apertura y cierre. Sal con tiempo para evitar contratiempos.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center text-muted mt-5 mb-3">
       <small><strong>&copy; 2025 Universidad Nacional Autónoma de Honduras</strong></small>
    </footer>

</div>
@stop

@section('css')
    {{-- Bootstrap CSS para el carrusel --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
    <style>
        .carousel-item img {
            border-radius: 10px;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .border-success {
            border-color: #28a745 !important;
        }
        .text-success {
            color: #28a745 !important;
        }
    </style>
@stop

@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.querySelector('a[href="{{ url('logout') }}"]');

            if (logoutLink) {
                logoutLink.addEventListener('click', function(event) {
                    event.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }
        });
    </script>
    
    {{-- Bootstrap JS para el carrusel --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar el carrusel
        var myCarousel = document.querySelector('#carruselParques')
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 4000,
            wrap: true
        })
    </script>
@stop
