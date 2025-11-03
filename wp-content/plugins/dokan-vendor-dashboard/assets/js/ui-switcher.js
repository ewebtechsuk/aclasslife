(function ($) {
	const Dashboard = {
		init() {
			$('body').on(
				'click',
				'.old-dashboard-switch-ui',
				this.switchDashboard
			);
		},

		// add functions here

		switchDashboard(e) {
			e.preventDefault();
			const status = $(this).data('status');

			wp.apiRequest({
				path: '/dokan-vendor-dashboard/v1/ui-switch', // API path to fetch WordPress posts
				method: 'POST',
				data: {
					status,
				},
			})
				.done(function () {
					window.location.reload();
				})
				.fail(function () {
					window.location.reload();
				});
		},
	};

	$(document).ready(function () {
		Dashboard.init();
	});
})(jQuery);
