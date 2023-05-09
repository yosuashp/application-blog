<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:admin="http://webns.net/mvcb/"
     xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title><?php echo convert_to_xml_character(xml_convert($feed_name)); ?></title>
    <link><?php echo $feed_url; ?></link>
    <description><?php echo convert_to_xml_character(xml_convert($page_description)); ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>
    <dc:rights><?php echo convert_to_xml_character(xml_convert($this->settings->copyright)); ?></dc:rights>
<?php foreach ($posts as $post): ?>

    <item>
        <title><?php echo convert_to_xml_character(xml_convert($post->title)); ?></title>
        <link><?php echo generate_post_url($post); ?></link>
        <guid><?php echo generate_post_url($post); ?></guid>
        <description><![CDATA[ <?php echo html_escape($post->summary); ?> ]]></description>
<?php
if (!empty($post->image_url)):
$image_path = $post->image_url;
if (strpos($image_path, '?') !== false) {
$image_path = strtok($image_path, "?");
}
$image_path = str_replace('https://', 'http://', $image_path); ?>
        <enclosure url="<?php echo $image_path; ?>" length="49398" type="image/jpeg"/>
<?php else:
$image_path = base_url() . $post->image_big;
if ($post->image_storage == "aws_s3") {
    $image_path = $this->aws_base_url . $post->image_big;
}
if (!empty($image_path)) {
$file_size = @filesize(FCPATH . $post->image_big);
}
$image_path = str_replace('https://', 'http://', $image_path);
if(empty($file_size) || $file_size<1){
    $file_size=49398;
}
if (!empty($image_path)):?>
        <enclosure url="<?php echo $image_path; ?>" length="<?php echo $file_size; ?>" type="image/jpeg"/>
<?php endif;
endif; ?>
        <pubDate><?php echo date('r', strtotime($post->created_at)); ?></pubDate>
        <dc:creator><?php echo convert_to_xml_character($post->author_username); ?></dc:creator>
    </item>
<?php endforeach; ?>
    </channel>
</rss>