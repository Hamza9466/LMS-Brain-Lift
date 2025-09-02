<!doctype html>
<html>
<head><meta charset="utf-8"><title>Redirecting to EasyPaisa…</title></head>
<body>
@if(empty($action))
  <p><strong>EasyPaisa POST URL is missing.</strong> Set <code>EASYPaisa_POST_URL</code> in .env and run <code>php artisan config:clear</code>.</p>
@else
  <p>Redirecting to EasyPaisa…</p>
  <form id="ep" method="POST" action="{{ $action }}">
    @foreach($fields as $name => $value)
      <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
    <noscript><button>Continue</button></noscript>
  </form>
  <script>document.getElementById('ep').submit();</script>
@endif
</body>
</html>
