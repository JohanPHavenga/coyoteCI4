<div id="titlebar" class="gradient">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<h2>How To Enter</h2>
				<!-- Breadcrumbs -->
				<nav id="breadcrumbs" class="">
					<ul>
						<li><a href="<?= base_url(); ?>">Home</a></li>
						<li><a href="<?= base_url('event/' . $slug); ?>"><?= $edition_data['event_name']; ?></a></li>
						<li>How to Enter</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">

		<div class="col-xl-8 col-lg-8 content-right-offset">
			<?= view('event/detail/entries'); ?>
			<div class="margin-top-40">
				<a href="<?= base_url('event/' . $slug); ?>" class="button ripple-effect gray">
					<i class="icon-material-outline-arrow-back"></i> Back to Race Summary</a>
			</div>
		</div>

		<!-- Sidebar -->
		<div class="col-xl-4 col-lg-4">
			<div class="sidebar-container">
			</div>
		</div>

	</div>
</div>