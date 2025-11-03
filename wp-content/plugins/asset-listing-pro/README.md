# Asset Listing Pro

Asset Listing Pro provides a complete workflow for capturing asset listings from site contributors, managing submissions, and tracking commissions once a listing has been approved. The plugin registers a dedicated `aclasslife_listing` custom post type with optional asset type taxonomy support, enables front-end submission forms, and includes dashboards tailored to administrators and sellers.

## Features

- **Custom Post Type** &mdash; Manage listings with featured images, excerpts, author attribution, and REST API visibility.
- **Taxonomy Support** &mdash; Categorize assets via the `asset_type` taxonomy for easier browsing and filtering.
- **Front-end Submissions** &mdash; Authenticated sellers can submit listings using the `[acl_listing_submission_form]` shortcode.
- **Seller Dashboard** &mdash; Sellers can review listing statuses and commission progress via `[acl_seller_dashboard]`.
- **Admin Oversight** &mdash; Custom dashboard pages summarize listing metrics and provide commission workflows for managers.
- **Meta Fields** &mdash; Capture price, location, and specification details stored as dedicated meta fields.
- **Media Handling** &mdash; Support for featured image uploads with WordPress media management.
- **Internationalization** &mdash; Translation ready with a starter POT file.
- **Asset Pipeline** &mdash; Lightweight CSS and JavaScript bundles are loaded where needed.

## Installation

1. Copy the `asset-listing-pro` directory into `wp-content/plugins/` on your WordPress installation.
2. Activate **Asset Listing Pro** from the **Plugins** screen in the WordPress admin area.
3. Visit **Asset Listings → Asset Listings** to review the dashboard and configure taxonomy terms if needed.

## Shortcodes

| Shortcode | Description |
|-----------|-------------|
| `[acl_listing_submission_form]` | Displays the seller-facing submission form. Requires the visitor to be logged in with a role capable of editing posts. |
| `[acl_seller_dashboard]` | Outputs a table of the current user’s submissions, including commission status. |

## Commission Workflow

Administrators can navigate to **Asset Listings → Commissions** to track outstanding commissions. Update the commission status to `Unpaid`, `Pending`, or `Paid` via the form actions provided in the listing table. Actions trigger the `acl_listing_commission_status_updated` hook for integrations.

## Development

- Assets are located under `assets/css/` and `assets/js/`.
- Translation templates live in `languages/`.
- Front-end logic is encapsulated in `includes/frontend-submission.php` and initialized from `asset-listing-pro.php`.

## License

Licensed under the [GPL2](https://www.gnu.org/licenses/gpl-2.0.html). See the plugin header for details.
