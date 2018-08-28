<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap-timepicker.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <link href="{{ asset('css/v-toaster.css') }}" rel="stylesheet">
   
    <link rel="stylesheet" href="{{ asset('css/admin_custom.css') }} ">

    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css">
    @endif

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    @if(config('adminlte.plugins.datatables'))
        <!-- DataTables with bootstrap 3 style -->
        <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css">
    @endif

    @yield('adminlte_css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap-timepicker.min.js') }}"></script>
@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endif

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
    <script src="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
@endif

@if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
@endif

<script src="{!! asset('js/validators.min.js') !!}"></script>
<script src="{!! asset('js/vuelidate.min.js') !!}"></script>
<script src="{!! asset('js/vue.js') !!}"></script>
<script src="{!! asset('js/vue-resource.js') !!}"></script>
<script src="{!! asset('js/v-toaster.js') !!}"></script>
<script src="{!! asset('js/v-mask.min.js') !!}"></script>
<script>
    var urlPrefix = "/admin/";

    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        Vue.http.interceptors.push(function(request, next) {
            request.headers.set('X-CSRF-TOKEN', token.content)
            next()
        })
    } 
    var tokenDt = token.content;
    Vue.use(window.vuelidate.default)
    Vue.use(VToaster, {timeout: 5000})
    Vue.use(VueMask.VueMaskPlugin);

    var required     = window.validators.required,
    sameAs          = window.validators.sameAs,
    regexhelpers    = window.validators.helpers.regex,
    email           = window.validators.email,
    minLength       = window.validators.minLength,
    maxLength       = window.validators.maxLength,
    numeric         = window.validators.numeric,
    url             = window.validators.url,
    pwdRegx = regexhelpers('pwdRegx', /^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[$@$!%*?&])[A-Za-z\d$@$!%*?&]{6,}/),
    phoneRegx = regexhelpers('phoneRegx',/^[+]?[0-9]\d{9,}$/);
</script>
<script src="{!! asset('js/notification-app.js') !!}"></script>

@yield('adminlte_js')

</body>
</html>
