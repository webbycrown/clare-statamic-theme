# Clare - Statamic Starter Kit

Clare is a fashion catalog starter kit for apparel, accessories, and lifestyle brands. It is built for Statamic 5 with a warm editorial storefront, product taxonomies, and Control Panel content management.

The kit ships a full catalog experience: three home layouts, four shop layouts, category landing pages, three product-detail layouts, and blog variants. Shoppers can add items to a **session cart** and send a **cart inquiry** — there is no card checkout or payment capture.


## Pages of Clare

The Clare starter kit includes a complete set of pages for a fashion catalog site:

- **Home Pages**: 3 variants (`/`, `/home-v2`, `/home-v3`)
- **Shop**:
  - Shop grid (`/shop`)
  - Shop list (`/shop/list`)
  - Shop right sidebar (`/shop/right-sidebar`)
  - Shop three column (`/shop/three-column`)
  - Categories landing (`/categories`)
  - Category archive (`/product-category/{slug}`)
  - Product detail (3 layouts via `/product/{slug}`)
- **Cart & inquiry**: session cart and cart inquiry form (no payment)
- **Blog**:
  - Blog grid
  - Blog grid 2
  - Blog classic
  - Blog detail
- **Account**:
  - Sign in, sign up, forgot password, reset password
  - Account profile and addresses
- **Other Pages**:
  - About
  - Contact
  - FAQ
  - Privacy policy
  - Terms and conditions
  - Sitemap

## Collections

Organize your content with built-in collections:

- **Pages**: Site structure and static content pages.
- **Products**: Apparel and accessory catalog entries with images, price, and option sets.
- **Blogs**: Editorial stories, lookbooks, and brand news.

## Taxonomies

- **Product category**: Filter and group products (coats, knits, denim, accessories, and more).
- **Blog category**: Organize journal posts.

## Features of Clare

- **Fashion catalog**: Product listings, category filters, and three product-detail layouts.
- **Product options**: Replicator field (`product_options`) for size, color, and similar choices, rendered with `{{ clare_options }}`.
- **Session cart**: Add, update, and remove lines in session. Submit the whole basket through the **cart inquiry** form.
- **Inquiry instead of checkout**: Product inquiry and cart inquiry replace card payment.
- **Currencies**: Storefront currency switch for USD, INR, and GBP.
- **Locales**: English, Spanish, and German.
- **Global settings**: Header, footer, logo, and site-wide copy from the Control Panel.
- **Responsive layout**: Desktop, tablet, and mobile.
- **Statamic 5 ready**: Built for Statamic 5.x.

## Control Panel Forms

- Cart inquiry
- Product inquiry
- Contact us
- Subscription

## Global Settings

- Setting (brand, header, storefront copy)
- Footer

## Installation

Follow the [Starter Kit installation instructions](https://statamic.dev/starter-kits/installing-a-starter-kit) to get started with Clare.
Make sure you're running **Statamic 5.x** for compatibility.

### Installing into an existing site

```bash
php please starter-kit:install webbycrown/clare-statamic-theme
```

### Installing via the Statamic CLI Tool

If you have the [Statamic CLI Tool](https://github.com/statamic/cli) installed, create a new Statamic installation with Clare in one command:

```bash
statamic new my-site webbycrown/clare-statamic-theme
```

## Changelog

### v1.0.0

- Initial release
- Fashion catalog pages, shop layouts, and product options
- Session cart and inquiry forms
- Responsive storefront with currency and locale switches

---
<div align="center">
  <strong>Made with ❤️ by <a href="https://www.webbycrown.com/custom-statamic-development-services-company/">WebbyCrown Solutions</a></strong>
</div>
