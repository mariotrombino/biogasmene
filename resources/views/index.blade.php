<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="Mario Trombino">

    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    
    <meta property="og:url" content="https://biogasmenè.it" />
    <meta property="og:sitename" content="Biogasmenè" />
    
    <title>{{ LAConfigs::getByKey('sitename') }}</title>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/la-assets/mobile/css/bootstrap-4.1.1.min.css') }}" rel="stylesheet">

    <script src="{{ asset('/la-assets/mobile/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('/la-assets/mobile/jquery/bootstrap.bundle.min.js') }}"></script>

</head>

<body class="text-center">
<section class="container">
    <form class="form-signin" method="post" action="{{ url('mobile/login') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Per favore esegui il login</h1>
      <div class="form-group">
      <label for="inputEmail" class="sr-only">Indirizzo Email</label>
      <input type="email" id="inputEmail" class="form-control" placeholder="Indirizzo Email" required autofocus>
      </div>
      <div class="form-group">
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
      </div>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" selected="selected" value="remember-me" disable> Ricordami
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
      <p class="mt-5 mb-3 text-muted">&copy; Biogasmenè 2018</p>
    </form>
</section>    
</body>
</html>
