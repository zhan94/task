
<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
    @include ('layouts.head')
    <body>

        @include ('layouts.header')

        @yield ('content')

        @include ('layouts.footer')
       
    </body>
</html>
