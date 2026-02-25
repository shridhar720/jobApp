<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Application Viewed</title>
  </head>
  <body>
    <p>Hello,</p>

    <p>Your application for the job "{{ optional($application->job)->title ?? 'the position' }}" was viewed by the employer.</p>

    <p>Status: {{ $application->status ?? 'viewed' }}</p>

    <p>Thank you for applying.</p>

    <p>— {{ config('app.name') }}</p>
  </body>
</html>
