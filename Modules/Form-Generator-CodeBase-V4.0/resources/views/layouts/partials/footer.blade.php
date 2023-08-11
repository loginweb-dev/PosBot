<footer class="main-footer no-print">
    <div class="float-right d-none d-sm-inline">
        @lang('messages.application_copyright',[
            'name' => config('app.name', 'Laravel'),
            'version' => config('author.app_version'),
            'year' => date('Y')
        ])
    </div>

    <!-- Default to the left -->
    <!-- <strong>Copyright © 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved. -->
</footer>