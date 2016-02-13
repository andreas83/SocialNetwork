<?php echo "<?"; ?>xml version="1.0" encoding="UTF-8"<?php echo "?>"; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    
<?php 
foreach ($data as $content){

$media=json_decode($content->media);
if(!isset($media->type) || $media->type!="img")
{
    continue;
}
?>
<url>
    <loc><?php echo Config::get("address")."permalink/".$content->id; ?></loc>
    <image:image>
        <?php 
        if(isset($content->data) && !empty($content->data))
        { ?>    
        <image:title><?php echo htmlspecialchars($content->data); ?></image:title>
        <?php } ?>

        <image:loc><?php echo Config::get("upload_address").$media->url; ?></image:loc>
    </image:image>
</url>
<?php
}  
?>
</urlset>