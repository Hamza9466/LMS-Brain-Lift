<!doctype html>
<html>
<head><meta charset="utf-8"><title>Redirecting to JazzCash…</title></head>
<body>
@if(empty($action))
  <p><strong>JazzCash POST URL is missing.</strong> Set <code>JAZZCASH_POST_URL</code> in .env and run <code>php artisan config:clear</code>.</p>
@else
  <p>Redirecting to JazzCash…</p>
  <form id="jc" method="POST" action="{{ $action }}">
    @foreach($fields as $name => $value)
      <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
    <noscript><button>Continue</button></noscript>
  </form>
  <script>document.getElementById('jc').submit();</script>
@endif
</body>
</html>
