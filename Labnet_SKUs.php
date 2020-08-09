<?php


$dbdsn = "mysql:host=localhost;dbname=local_labnet";
$dbuser = 'root';
$dbpass = 'pass';
$pdo = new PDO($dbdsn, $dbuser, $dbpass);

//Main SKU Query
$sql = '
SELECT
    p.product_id as "Product ID",
    p.sku as "SKU",
    p.type as "Type",   
    p.status,
	  p.title as "SKU Title",
	  node_ref.`entity_id` as "Attached to Product Display Page ID (Combined)",
    "" as "Attached to Product Display Page ID (1)",
    "" as "Attached to Product Display Page ID (2)",
    "" as "Attached to Product Display Page ID (3)",
    "" as "Attached to Product Display Page ID (4)",
    "" as "Attached to Product Display Page ID (5)",
    "" as "Attached to Product Display Page ID (6)",
    "" as "Attached to Product Display Page ID (7)",
    "" as "Attached to Product Display Page ID (8)",
    "" as "Attached to Product Display Page ID (9)",
    "" as "Attached to Product Display Page ID (10)",
    "" as "Attached to Product Display Page ID (11)",
    "" as "Attached to Product Display Page ID (12)",
    "" as "Attached to Product Display Page ID (13)",
    "" as "Attached to Product Display Page ID (14)",
    "" as "Attached to Product Display Page ID (15)",
    "" as "Attached to Product Display Page ID (16)",
    "" as "Attached to Product Display Page ID (17)",
    "" as "Attached to Product Display Page ID (18)",
    "" as "Attached to Product Display Page ID (19)",
    price_na.field_product_price_2_amount as "Price - North America",
    price_eu.field_product_price_3_amount as "Price - Europe",
    price_la.field_product_price_5_amount as "Price - Latin America",
    price_mea.field_product_price_6_amount as "Price - Middle East and Asia",
    price_labvalue.field_lab_values_price_amount as "Price - Lab Value",
    price.commerce_price_amount as "Price - Fallback",
    descr.field_descr_value as "SKU Description"
FROM
	  commerce_product as p
LEFT JOIN 
	  `field_data_field_product_entities` as node_ref
		    ON
	  node_ref.`field_product_entities_product_id` = p.`product_id`
LEFT JOIN
	    `field_data_field_product_price_2` as price_na
            ON
        price_na.entity_id = p.product_id
LEFT JOIN
	    `field_data_field_product_price_3` as price_eu
            ON
        price_eu.entity_id = p.product_id
LEFT JOIN
	    `field_data_field_product_price_5` as price_la
            ON
        price_la.entity_id = p.product_id
LEFT JOIN
	    `field_data_field_product_price_6` as price_mea
            ON
        price_mea.entity_id = p.product_id
LEFT JOIN
	    `field_data_commerce_price` as price
            ON
        price.entity_id = p.product_id
LEFT JOIN
	      `field_data_field_lab_values_price` as price_labvalue
            ON
        price_labvalue.entity_id = p.product_id
LEFT JOIN
	      `field_data_field_descr` as descr
            ON
        descr.entity_id = p.product_id

UNION

SELECT
    p.product_id as "Product ID",
    p.sku as "SKU",
    p.type as "Type",
    p.status,
	  p.title as "SKU Title",
	  node_ref2.`entity_id` as "Attached to Product Display Page ID (Combined)",
    "" as "Attached to Product Display Page ID (1)",
    "" as "Attached to Product Display Page ID (2)",
    "" as "Attached to Product Display Page ID (3)",
    "" as "Attached to Product Display Page ID (4)",
    "" as "Attached to Product Display Page ID (5)",
    "" as "Attached to Product Display Page ID (6)",
    "" as "Attached to Product Display Page ID (7)",
    "" as "Attached to Product Display Page ID (8)",
    "" as "Attached to Product Display Page ID (9)",
    "" as "Attached to Product Display Page ID (10)",
    "" as "Attached to Product Display Page ID (11)",
    "" as "Attached to Product Display Page ID (12)",
    "" as "Attached to Product Display Page ID (13)",
    "" as "Attached to Product Display Page ID (14)",
    "" as "Attached to Product Display Page ID (15)",
    "" as "Attached to Product Display Page ID (16)",
    "" as "Attached to Product Display Page ID (17)",
    "" as "Attached to Product Display Page ID (18)",
    "" as "Attached to Product Display Page ID (19)",
    price_na.field_product_price_2_amount as "Price - North America",
    price_eu.field_product_price_3_amount as "Price - Europe",
    price_la.field_product_price_5_amount as "Price - Latin America",
    price_mea.field_product_price_6_amount as "Price - Middle East and Asia",
    price_labvalue.field_lab_values_price_amount as "Price - Lab Value",
    price.commerce_price_amount as "Price - Fallback",
    descr.field_descr_value as "SKU Description"
FROM
	  commerce_product as p
LEFT JOIN 
	  `field_data_field_product_parts` as node_ref2
		    ON
	  node_ref2.`field_product_parts_product_id` = p.`product_id`
LEFT JOIN
	    `field_data_field_product_price_2` as price_na
            ON
        price_na.entity_id = p.product_id
LEFT JOIN
	    `field_data_field_product_price_3` as price_eu
            ON
        price_eu.entity_id = p.product_id
LEFT JOIN
	    `field_data_field_product_price_5` as price_la
            ON
        price_la.entity_id = p.product_id
LEFT JOIN
	    `field_data_field_product_price_6` as price_mea
            ON
        price_mea.entity_id = p.product_id
LEFT JOIN
	    `field_data_commerce_price` as price
            ON
        price.entity_id = p.product_id
LEFT JOIN
	      `field_data_field_lab_values_price` as price_labvalue
            ON
        price_labvalue.entity_id = p.product_id
LEFT JOIN
	      `field_data_field_descr` as descr
            ON
        descr.entity_id = p.product_id

';
$query = $pdo->query($sql);
$results = $query->fetchAll(PDO::FETCH_ASSOC);
$data = array();
$headers = array();
$product_ids = array();
$attached_nodes = array();
$result_count = 0;
$header_count = 0;
$empties = array();
foreach($results as $r_id => $row) {
  if($result_count===0) {
    $t=1;
    foreach(array_keys($row) as $header) {
      $headers[$header_count] = $header;
      $header_count++;
    }
  }
//  else {
    $t=1;
    //if there is more then on attached node.
    if(isset($data[$row["Product ID"]]) && !empty($row["Attached to Product Display Page ID (Combined)"])) {
      if(!empty($data[$row["Product ID"]]["Attached to Product Display Page ID (Combined)"])) {
        $data[$row["Product ID"]]["Attached to Product Display Page ID (Combined)"] .= ",";
      }
      $data[$row["Product ID"]]["Attached to Product Display Page ID (Combined)"] .= $row["Attached to Product Display Page ID (Combined)"];
      $attached_nodes[$row["Product ID"]][] = $row["Attached to Product Display Page ID (Combined)"];
    }
    elseif(isset($data[$row["Product ID"]]) && empty($row["Attached to Product Display Page ID (Combined)"])) {
      $empties[]=$row;
    }
    else {
      $data[$row["Product ID"]] = $row;
      if(!empty($row["Attached to Product Display Page ID (Combined)"])){
        $attached_nodes[$row["Product ID"]][] = $row["Attached to Product Display Page ID (Combined)"];
      }
    }
//  }
  $product_ids[$row["Product ID"]] = $row["Product ID"];

  $result_count++;
}
$t=1;
//Get SKU Region
//  2|North America
//  3|Europe
//  5|Latin America
//  6|Pacific Rim, Africa, and the Middle East
//add a header per region
$headers[$header_count] = "North America Region";
$header_count++;
$headers[$header_count] = "Europe Region";
$header_count++;
$headers[$header_count] = "Latin American Region";
$header_count++;
$headers[$header_count] = "Pacific Rim, Africa, and the Middle East";
$header_count++;
foreach($product_ids as $product_id) {
  $sql_region = "
      SELECT
          region.field_product_region_value as region_id
      FROM
          field_data_field_product_region as region
      WHERE
         region.entity_id = ".$product_id."
    ";
    $query_region = $pdo->query($sql_region);
    $results_region = $query_region->fetchAll(PDO::FETCH_ASSOC);
    $region=array(
      "2" => "false",
      "3" => "false",
      "5" => "false",
      "6" => "false",
    );
    foreach($results_region as $result_region) {
      $t=1;
      $region[$result_region["region_id"]] = "true";
    }
    $data[$product_id][] = $region[2];
    $data[$product_id][] = $region[3];
    $data[$product_id][] = $region[5];
    $data[$product_id][] = $region[6];
}


foreach($attached_nodes as $product_id => $attached_node) {
  $starting_attached_node_col = 1;
  foreach($attached_node as $nid) {
    $data[$product_id]["Attached to Product Display Page ID (".$starting_attached_node_col.")"] = $nid;
    $starting_attached_node_col++;
  }
}


//Add Basic Node Data.
//flatten this list nodes.
$nids = array();
foreach ($attached_nodes as $attached_node) {
  foreach($attached_node as $attached_nid) {
    $nids[$attached_nid] = $attached_nid;
  }
}

$sql_node_data = '
    SELECT
      n.nid as "Product Display Page ID",
      n.status as "Status",
      n.title as "Title",
      b.body_value as "Body",
      t.field_intro_teaser_text_value as "Teaser",
      ls.field_is_a_lab_savings_product__value as "Is Lab Savings",
      lr.field_loyal_reader_product_value as "Is Loyal Reader",
      CONCAT("https://www.labnetinternational.com/node/",n.nid) AS "URL"
    FROM
      `node` as n
    LEFT JOIN
      `field_data_body` as b
          ON
      b.entity_id = n.nid
    LEFT JOIN
      `field_data_field_intro_teaser_text` as t
          ON
      t.entity_id = n.nid
    LEFT JOIN
        `field_data_field_is_a_lab_savings_product_` as ls
            ON
        ls.entity_id = n.nid
    LEFT JOIN
        `field_data_field_loyal_reader_product` as lr
            ON
        lr.entity_id = n.nid
    WHERE
        n.nid IN ('.implode(',',$nids).')
';
$query_node_data = $pdo->query($sql_node_data);
$results_node_data = $query_node_data->fetchAll(PDO::FETCH_ASSOC);

//Get the Images
//Do this by sku,
//$skus_for_images_products = array();
//$skus_for_images_parts = array();
//foreach($data as $sku_id => $sku_data) {
//  if($sku_data["Type"]=="product") {
//    $skus_for_images_products[$sku_id] = $sku_id;
//  }
//  else if($sku_data["Type"]=="part") {
//    $t=1;
//    $skus_for_images_parts[$sku_id] = $sku_id;
//  }
//  else {
//    $t=1;
//  }
//}
//$t=1;
//
//foreach($skus_for_images_products as $product_id) {
//
//
//
//}

//redo the query, this time only query the main product field as images mostly do not relate to assoc parts.
//foreach($data as $sku_id => $sku_data) {
  $sql_image_data = '
    SELECT
        p.product_id,
        p.sku,
        i.entity_id as "Product Display Page ID",
        f.uri as "URL",
        f.filename as "Filename",
        i.field_product_images_title as "Title",
        i.field_product_images_alt as "Alt Text",
        i.field_product_images_width as "Width",
        i.field_product_images_height as "Height",
        f.filesize as "Image File Size"
  FROM
      commerce_product as p
  INNER JOIN 
      `field_data_field_product_entities` as node_ref
          ON
      node_ref.`field_product_entities_product_id` = p.`product_id`
  INNER JOIN
      field_data_field_product_images as i
        ON
      i.entity_id = node_ref.entity_id
  INNER JOIN
        `file_managed` as f
            ON
        f.fid = i.field_product_images_fid
    ';
//}

//
//$sql_image_data = '
//    SELECT
//      i.entity_id as "Product Display Page ID",
//      f.uri as "URL",
//      f.filename as "Filename",
//      i.field_product_images_title as "Title",
//      i.field_product_images_alt as "Alt Text",
//      i.field_product_images_width as "Width",
//      i.field_product_images_height as "Height",
//      f.filesize as "Image File Size"
//    FROM
//      `field_data_field_product_images` as i
//    LEFT JOIN
//      `file_managed` as f
//          ON
//      f.fid = i.field_product_images_fid
//    WHERE
//        i.entity_id IN ('.implode(',',$nids).')
//';
$query_image_data = $pdo->query($sql_image_data);
$results_image_data = $query_image_data->fetchAll(PDO::FETCH_ASSOC);

//Replace the uri field with absolute link.
foreach($results_image_data as $key => $image_data) {
  $image_uri = $image_data["URL"];
  $image_url = str_replace("public://","https://www.labnetinternational.com/sites/www.labnetinternational.com/files/",$image_uri);
  $results_image_data[$key]["URL"] = $image_url;
}


/******
 * OUTPUT
 ******/

//HTML
$rowcount=1;
$output_skus = "<table>";
$output_skus .= "<thead><tr>";
//#To-DO Add Headers
foreach($headers as $header) {
  $output_skus .= "<th>".$header."</th>";
}
$t=1;
$output_skus .= "</tr></thead><tbody>";
foreach($data as $row) {
  $output_skus .= "<tr>";
  foreach($row as $value) {
    $output_skus .= "<td>".$value."</td>";
  }
  $output_skus .= "</tr>";
  $rowcount++;
  $t=1;
}
$output_skus .= "</tbody></table>";



$rowcount=1;
$output_node_data = "<table>";
$output_node_data .= "<thead><tr>";
$output_node_data .= "<th>Product Page ID</th>";
$output_node_data .= "<th>Status</th>";
$output_node_data .= "<th>Title</th>";
$output_node_data .= "<th>Body</th>";
$output_node_data .= "<th>Teaser</th>";
$output_node_data .= "<th>Is Lab Savings</th>";
$output_node_data .= "<th>Is Loyal Reader</th>";
$output_node_data .= "<th>URL</th>";
$output_node_data .= "</tr></thead><tbody>";
foreach($results_node_data as $row) {
  $output_node_data .= "<tr>";
  foreach($row as $value) {
    $output_node_data .= "<td>".$value."</td>";
  }
  $output_node_data .= "</tr>";
  $rowcount++;
  $t=1;
}
$output_node_data .= "</tbody></table>";

//CSV
$fp = fopen('/vagrant/labnet_skus.csv', 'w');
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($fp, $headers);
foreach ($data as $fields) {
  fputcsv($fp, $fields);
}
fclose($fp);

$fp = fopen('/vagrant/labnet_product_pages.csv', 'w');
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($fp, array_keys($results_node_data[0]));
foreach ($results_node_data as $fields) {
  fputcsv($fp, $fields);
}
fclose($fp);

$fp = fopen('/vagrant/labnet_images.csv', 'w');
fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($fp, array_keys($results_image_data[0]));
foreach ($results_image_data as $fields) {
  fputcsv($fp, $fields);
}
fclose($fp);

//$fp = fopen('/vagrant/labnet_documents.csv', 'w');
//fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
//fputcsv($fp, array_keys($results_node_data[0]));
//foreach ($results_node_data as $fields) {
//  fputcsv($fp, $fields);
//}
//fclose($fp);

//End
//print $output_skus;
//print $output_node_data;

?>