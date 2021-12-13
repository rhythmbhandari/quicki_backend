<!DOCTYPE html>
<html lang="en">
@include('layouts.admin.head')
<!--begin::Body-->

<body id="kt_body"
	class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
	{{-- <div class="se-pre-con"></div> --}}
	@if (auth()->guest())
	@yield('guest')
	@else
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			@include('layouts.admin.sidebar')
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
				<!--begin::Header-->
				@include('layouts.admin.header')
				@include('layouts.admin.breadcrumb')
				<!--end::Header-->
				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
					<!--begin::Entry-->
					<div class="d-flex flex-column-fluid">
						<!--begin::Container-->
						<div class="container-fluid">
							@yield('content')
						</div>
						<!--end::Container-->
					</div>
				</div>
			</div>
		</div>
		<!--end::Page-->
	</div>
	@endif
	@include('layouts.admin.footer')
</body>
<!--end::Body-->

</html>