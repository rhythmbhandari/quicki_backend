<!--begin::Head-->

<head>
	<meta charset="utf-8" />
	<title>{{  !empty(config('settings.site_name')) ? config('settings.site_name') : config('app.name', 'Puryaideu V2') }}-@yield('title')</title>
	<meta name="description" content="Login page example" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

	<!--csrf token -->
	<!-- <meta name="csrf-token" content="{{ csrf_token() }}" /> -->
	{{-- <link rel="canonical" href="https://keenthemes.com/metronic" /> --}}
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="{{asset('assets/admin/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('assets/admin/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('assets/admin/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<link href="{{asset('assets/admin/css/themes/layout/header/base/light.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('assets/admin/css/themes/layout/header/menu/light.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('assets/admin/css/themes/layout/brand/dark.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('assets/admin/css/themes/layout/aside/dark.css')}}" rel="stylesheet" type="text/css" />
	<!--end::Layout Themes-->
	{{-- <link rel="shortcut icon" href="{{asset('favicon.ico')}}" /> --}}

	<!-- FAVICON -->
	@if(!empty(config('settings.site_favicon'))) 
    
    <link rel="icon" type="image/ico" sizes="32x32" href="{{asset(config('settings.site_favicon_image_path'))}}"> 
    @else
	<link rel="shortcut icon" href="{{asset('assets/media/logo.png')}}" />

	@endif


	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	@yield('page-specific-styles')
	<link href="{{asset('assets/admin/css/style.custom.css')}}" rel="stylesheet" type="text/css" />

</head>
<!--end::Head-->