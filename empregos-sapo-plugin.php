<?php
/*
Plugin Name: Empregos Sapo Plugin
Plugin URI: http://emprego.sapo.pt/emprego/ofertas.htm/
Description: O plugin Empregos Sapo adiciona um widget ao teu blog que mostra as ultimas ofertas de emprego em Portugal. Pode ser integrado em qualquer sitio no do teu site.
Version: 1.0
Author: Ricardo Mendes
Author URI: http://ricardo-mendes.com
License: GPL3
*/

function empregossapo(){
	$options = get_option("widget_empregossapo");
	if (!is_array($options)){
		$options = array(
			'country' => 'portugal',
      'city' => 'aveiro',
      'title' => 'Empregos Sapo',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed
  $rss = simplexml_load_file(
  'http://emprego.sapo.pt/emprego/ofertas.htm/pais/'.$options["country"].'/distrito/'.$options["city"].'/rss/');
  ?>
  <ul>
  <?php
  // max number of news slots
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary
  $max_length = $options['chars'];

  $cnt = 0;
  foreach($rss->channel->item as $i) {
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?>
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><b><?=$title?></b></a>
    <?php
    // Description
    $description = $i->description;
    // Length of description
    $length = strlen($description);
    // if the description is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($description > $max_length){
      $description = substr($description, 0, $max_length)."...";
    }
    ?>
    <p><?=$description?></p>
    </li>
    <?php
    $cnt++;
  }
  ?>
  </ul>
<?php
}

function widget_empregossapo($args){
	extract($args);
  $options = get_option("widget_empregossapo");
  if (!is_array($options)){
    $options = array(
		'country' => 'portugal',
		'city' => 'aveiro',
		'title' => 'Empregos Sapo',
		'news' => '5',
		'chars' => '30'
    );
  }

  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  empregossapo();
  echo $after_widget;
}

function empregossapo_control(){
  $options = get_option("widget_empregossapo");
  if (!is_array($options)){
    $options = array(
			'country' => 'portugal',
      'city' => 'aveiro',
      'title' => 'Empregos Sapo',
      'news' => '5',
      'chars' => '30'
    );
  }

  if($_POST['empregossapo-Submit']){
		$options['country'] = htmlspecialchars($_POST['empregossapo-WidgetCountry']);
    $options['city'] = htmlspecialchars($_POST['empregossapo-WidgetCity']);
    $options['title'] = htmlspecialchars($_POST['empregossapo-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['empregossapo-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['empregossapo-CharCount']);
    update_option("widget_empregossapo", $options);
  }
?>
  <p>
		<label for="empregossapo-WidgetCountry">Country: </label>
    <input type="text" id="empregossapo-WidgetCountry" name="empregossapo-WidgetCountry" value="<?php echo $options['country'];?>" />
    <br /><br />
		<label for="empregossapo-WidgetCity">City: </label>
    <input type="text" id="empregossapo-WidgetCity" name="empregossapo-WidgetCity" value="<?php echo $options['city'];?>" />
    <br /><br />
    <label for="empregossapo-WidgetTitle">Widget Title: </label>
    <input type="text" id="empregossapo-WidgetTitle" name="empregossapo-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="empregossapo-NewsCount">Max. News: </label>
    <input type="text" id="empregossapo-NewsCount" name="empregossapo-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="empregossapo-CharCount">Max. Characters: </label>
    <input type="text" id="empregossapo-CharCount" name="empregossapo-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="empregossapo-Submit"  name="empregossapo-Submit" value="1" />
  </p>
<?php
}

function empregossapo_init(){
	wp_register_sidebar_widget('EmpregosSapo','Empregos Sapo', 'widget_empregossapo');
  	wp_register_widget_control('EmpregosSapo','Empregos Sapo', 'empregossapo_control', 300, 200);
}
add_action("plugins_loaded", "empregossapo_init");
?>
