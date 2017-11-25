================================================================================
OPENCART SEO PACK PRO v.9.42                                         17 MAR 2017

[Compatibility]
Supports OpenCart Version 2.x (vqmod required)

================================================================================

Free installation & support: ovife21@gmail.com

Make sure you include in email's subject to ovife21@gmail.com the following details:
- Product Name (Opencart SEO Pack PRO)
- Request (Installation or Support)
- Your site
- Opencart.com OrderID

Example of email subject: Opencart SEO Pack PRO - Support - www.mysite.com - 193215

For free installations, ftp and opencart admin area access is needed.
Installation will be made in up to 48 hours from the moment your request is received.

Expect an answer to your question from support team in up to 24 hours from the moment 
your email is received. 

================================================================================

--------------------------------------------------------------------------------
[Installation]
--------------------------------------------------------------------------------
		
		0. Make sure you have vqmod installed. If you don't have vqmod installed:
			- To install vqmod, you will have to download it from https://github.com/vqmod/vqmod/releases 
			- Download latest version (eg. vqmod-2.6.1-opencart.zip) 
			- Copy it into vqmod folder in the root of your site and install it ( http://yoursite.com/vqmod/install )
		1. No files will be overridden
		2. Copy 'vqmod', 'catalog' and 'admin' folders in the root folder of your website
		3. Set permissions for Opencart SEO Pack PRO's menus in admin area -> System -> Users -> User Groups
		4. Enter in Opencart SEO Pack interface admin area -> Catalog -> SEO -> SEO Pack  
			so Opencart SEO Pack PRO's extensions can initialize themselves.
		5. If your store is multi-language, install Multi-Language SEO URLs from for_multi_language_stores folder and
			set multi-language titles and meta descriptions for your home page in admin area -> System -> Settings -> Edit your store -> Store tab.

--------------------------------------------------------------------------------
[Updating]
--------------------------------------------------------------------------------
		
		1. When you update your Opencart SEO Pack PRO to a newer version, make sure you'll overwrite 
			all its old exising files with new version's files and then browse to Opencart SEO Pack PRO's menu
			in admin area -> Catalog -> SEO -> SEO Pack so Opencart SEO Pack PRO's extensions can initialize themselves.
		2. Browse to System -> Settings -> Edit your store -> Store tab and set titles, meta descriptions and meta keywords
			for home page if they are empty.
		3. Make sure permissions are set for all Opencart SEO Pack PRO's menus in admin area -> System -> Users -> User Groups
		
--------------------------------------------------------------------------------
[Others]

Enable Opencart SEO

In order to enable Opencart SEO, you will have to make 2 important steps:

1. You will have to enable SEO URLS  in Admin Area -> System -> Settings -> Your main store -> Edit -> Server tab -> Use SEO URL's 

2. The second step is to assure that your hosting has mod-rewrite enabled and then you will have to rename htaccess.txt to .htaccess in the root of your site.

If your OC is installed in a subfolder of your domain, edit .htaccess:
	- FIND 					RewriteBase /
	- REPLACE WITH 			RewriteBase /yoursubfolder/