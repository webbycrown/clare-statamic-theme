# Clare — Statamic Starter Kit

Clare is a fashion catalog starter kit for apparel, accessories, and lifestyle brands. Built for Statamic 5, it ships a warm editorial storefront with product taxonomies, a session cart, and full Control Panel content management.

**One Page template.** Every marketing and listing page is composed from Theme sections in the Control Panel — no per-page templates to edit. Add, remove, or reorder sections without touching code. Functional templates (cart, account, product detail, blog detail, category archive) remain dedicated.

The kit ships three home layouts, four shop layouts, category landing pages, three product-detail layouts, and blog variants. Shoppers can add items to a session cart and send a cart inquiry — there is no card checkout or payment capture.

---

## Pages

| Page | URL | Notes |
|---|---|---|
| Home V1 | `/` | page_builder |
| Home V2 | `/home-v2` | page_builder |
| Home V3 | `/home-v3` | page_builder |
| Shop grid | `/shop` | page_builder + shop_listing |
| Shop list | `/shop/list` | page_builder + shop_listing |
| Shop right sidebar | `/shop/right-sidebar` | page_builder + shop_listing |
| Shop three column | `/shop/three-column` | page_builder + shop_listing |
| Categories | `/categories` | page_builder + categories_grid |
| Category archive | `/categories/{slug}` | dedicated `category.antlers.html` |
| Product detail 1 | `/products/{slug}` | dedicated `products/show.antlers.html` |
| Product detail 2 | `/products/{slug}` + template | `products/show-v2.antlers.html` |
| Product detail 3 | `/products/{slug}` + template | `products/show-v3.antlers.html` |
| Cart | `/cart` | dedicated `cart.antlers.html` |
| Blog grid | `/blog` | page_builder + blogs section |
| Blog grid 2 | `/blog/grid-2` | page_builder + blogs section |
| Blog classic | `/blog/classic` | page_builder + blogs section |
| Blog detail | `/blog/{slug}` | dedicated `blogs/show.antlers.html` |
| About | `/about` | page_builder |
| Contact | `/contact` | page_builder + contact_form |
| FAQ | `/faqs` | page_builder + faqs |
| Privacy policy | `/privacy` | page_builder + legal_copy |
| Terms | `/terms` | page_builder + legal_copy |
| Sitemap | `/sitemap` | page_builder + pages_index |
| Account | `/account` | dedicated |
| Sign in / Register | `/login`, `/register` | dedicated |

---

## Theme Sections (page_builder)

Add any of these to any page via the Control Panel → Theme sections tab.

**Home** — `home_hero`, `hero_slider`, `hero_grid`  
**Products** — `icon_boxes`, `promo_banners`, `popular_categories`, `trending_collection`, `offer_banner`, `featured_products`, `weekly_bestsellers`, `season_collection`, `bestsellers`, `new_arrival`, `last_chance_sale`, `top_collection`  
**Content** — `heading_banner`, `newsletter`, `image_text`, `testimonials`, `shop_social`, `instagram_grid`, `blogs`, `legal_copy`, `pages_index`, `categories_grid`  
**About** — `about_content`, `about_video`, `team`  
**Contact** — `contact_form`  
**FAQs** — `faqs`  
**Shop** — `shop_listing` (styles: grid / list / right_sidebar / three_column)

---

## Collections

- **Products** — catalog entries with images, price, and option sets
- **Blogs** — editorial stories and lookbooks
- **Pages** — all site pages using the page_builder

## Taxonomies

- **Product category** — filter and group products
- **Blog category** — organise journal posts

---

## Features

- **One Page template** — compose any page from Theme sections in the CP, drag to reorder
- **Fashion catalog** — product listings, category filters, three product-detail layouts
- **Product options** — size, color, and similar choices via `{{ clare_options }}`
- **Session cart** — add, update, remove lines; submit as a cart inquiry
- **Inquiry instead of checkout** — product inquiry and cart inquiry replace card payment
- **AJAX forms** — subscribe, cart inquiry, contact, and product inquiry post without page reload
- **Header navigation** — all three headers wired to `nav:header_menu`; edit destinations in the CP
- **Logo from settings** — change the logo in Settings → Setting, no code edit needed
- **Currencies** — storefront switch for USD, INR, and GBP
- **Locales** — English, Spanish, German
- **Global settings** — header, footer, logo, and site-wide copy from the CP
- **Responsive** — desktop, tablet, and mobile

---

## Forms (Core kit)

The Core kit ships one form active by default. Additional forms are included but treated as Pro features when sold commercially.

| Form | Handle | Notes |
|---|---|---|
| Contact us | `contact_us` | AJAX; honeypot included |
| Subscription | `subscription` | AJAX |
| Cart inquiry | `cart_inquiry` | AJAX |
| Product inquiry | `product_inquiry` | AJAX |

---

## Installation

Follow the [Starter Kit installation instructions](https://statamic.dev/starter-kits/installing-a-starter-kit).  
Requires **Statamic 5.x**.

```bash
# Into a new project
statamic new my-site webbycrown/clare-statamic-theme

# Into an existing site
php please starter-kit:install webbycrown/clare-statamic-theme
```

---

## Third-party libraries

See [THIRD_PARTY.md](THIRD_PARTY.md) for the full list of bundled front-end libraries (jQuery, Swiper, Bootstrap, Font Awesome, and others) with licences.

---

## Support

Open a [GitHub issue](https://github.com/webbycrown/clare-statamic-theme/issues) for bugs, questions, or feature requests.

---

## Changelog

### v2.0.0

- **Page builder architecture** — one `page.antlers.html` template, all pages use Theme sections
- Full `page_builder.yaml` fieldset with 25+ section types
- Header menus wired to `nav:header_menu` — edit in the CP without touching code
- Logo served from Settings global via `media-url` helper
- `seo.yaml` and `section_heading.yaml` fieldsets
- `THIRD_PARTY.md` licence listing
- `composer.json` pinned to `statamic/cms: ^5.0`
- Removed individual marketing templates (home, about, contact, faqs, shop, blogs, sitemap, categories, privacy, terms)
- All page entries migrated to `template: page` with `page_builder` YAML

### v1.0.0

- Initial release — fashion catalog pages, shop layouts, product options, session cart, inquiry forms
