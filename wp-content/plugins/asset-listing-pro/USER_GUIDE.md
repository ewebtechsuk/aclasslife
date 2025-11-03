# Asset Listing Pro User Guide

This guide walks administrators and sellers through the most common Asset Listing Pro workflows.

## 1. Getting Started

1. Log in to your WordPress dashboard with administrator privileges.
2. Activate **Asset Listing Pro** under **Plugins → Installed Plugins**.
3. Navigate to **Asset Listings → Asset Listings** to review the summary dashboard.

## 2. Configuring Asset Types

Asset types help buyers filter listings.

1. Visit **Asset Listings → Asset Listings**.
2. Click **Asset Types** in the left sidebar under the **Asset Listings** menu.
3. Add new types such as *Vehicle*, *Machinery*, or *Real Estate*.

## 3. Collecting Listings from Sellers

### Via Shortcode

1. Create a new WordPress page titled *Submit an Asset*.
2. Add the `[acl_listing_submission_form]` shortcode to the page content.
3. Publish the page and share it with authenticated sellers.

### Submission Flow

- Sellers must be logged in and have the capability to edit posts.
- The form captures title, description, price, location, specifications, and an optional image.
- Submitted listings are stored with a *Pending* status awaiting review.

## 4. Reviewing Listings

1. Go to **Asset Listings → Asset Listings**.
2. Click on individual listings to read the details.
3. Approve listings by changing the status to **Published** or request revisions by setting them to **Draft**.

## 5. Managing Commissions

1. Visit **Asset Listings → Commissions**.
2. Each row displays the seller, listing price, and current commission status.
3. Use the dropdown to switch between *Unpaid*, *Pending*, or *Paid* and click **Update**.
4. Integrations can hook into the `acl_listing_commission_status_updated` action to trigger external systems.

## 6. Seller Dashboard

Sellers can track their own submissions and commission status.

1. Create a page titled *My Listings*.
2. Insert the `[acl_seller_dashboard]` shortcode.
3. Publish the page so logged-in sellers can review their data.

## 7. Internationalization

- Translation template: `languages/asset-listing-pro.pot`.
- Place compiled translation files within the same directory following the `asset-listing-pro-<locale>.mo` naming pattern.

## 8. Troubleshooting

- Ensure sellers have the **Author** role or higher to access submission features.
- If image uploads fail, confirm the WordPress uploads directory is writable.
- When styles/scripts do not load, verify that your theme calls `wp_head()` and `wp_footer()`.

For additional assistance, contact your site administrator.
