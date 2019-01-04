<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><?php echo Router::url('/',true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
	</url>
	<!-- category pages -->	
	<?php foreach ($categoriesP as $category):?>
	<url>
		<loc><?php echo Router::url('/categories/view/'.$objGeneral->nameToUrl($category['Category']['name']),true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>
	<?php endforeach; ?>
	<?php foreach ($categoriesC as $category):?>
	<url>
		<loc><?php echo Router::url('/categories/view/'.$objGeneral->nameToUrl($categoryMapping[$category['Category']['parent']]).'/'.$objGeneral->nameToUrl($category['Category']['name']),true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>
	<?php endforeach; ?>
	<!-- apps pages -->	
	<?php foreach ($apps as $app):?>
	<url>
		<loc><?php echo Router::url('/apps/view/'.$app['SMSApp']['url'],true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>
	<?php endforeach; ?>
	<!-- package pages-->	
	<?php foreach ($packages as $package):?>
	<url>
		<loc><?php echo Router::url('/packages/view/'.$package['Package']['url'],true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
	</url>
	<?php endforeach; ?>
	<!-- message pages-->	
	<?php //foreach ($messages as $message):?>
	<!-- <url>
		<loc><?php echo Router::url(array('controller' => 'messages', 'action' => 'view',$objGeneral->nameToUrl($message['Category']['name']),$message['Message']['url']),true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>0.64</priority>
	</url> -->
	<?php //endforeach; ?>
	
	<!-- tag pages-->	
	<?php foreach ($tags as $tag):?>
	<url>
		<loc><?php echo Router::url('/tags/view/'.$tag['Tag']['url'],true); ?></loc>
		<changefreq>daily</changefreq>
		<priority>0.64</priority>
	</url>
	<?php endforeach; ?>
</urlset>