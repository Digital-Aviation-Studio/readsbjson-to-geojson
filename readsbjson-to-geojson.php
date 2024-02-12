<?php
if (isset($_POST['submit'])){
    $json_array = json_decode($_POST['readsbjson'], true);
    
    $output = "";
    $coordinates = "";
    if (!empty($json_array['trace'])){
        
        foreach($json_array['trace'] as $json_item){
            $coordinates .= '['.$json_item[2].','.$json_item[1].'],';
        }
        $coordinates = substr($coordinates, 0, -1);
        
        $output .= '{';
            $output .= '"type": "FeatureCollection",';
            $output .= '"features": [';
                $output .= '{';
                    $output .= '"type": "Feature",';
                    $output .= '"geometry": {';
                        $output .= '"type": "LineString",';
                        $output .= '"coordinates": [';
                            $output .= $coordinates;
                        $output .= ']';
                    $output .= '},';
                    $output .= '"properties": {';
                        $output .= '"hex": "'.$json_array['icao'].'"';
                    $output .= '}';
                $output .= '}';
            $output .= ']';
        $output .= '}';
        
        header("Content-type: application/json");
        header("Content-Disposition: attachment; filename=readsbjson-to-geojson-".time().".geojson");
        
        print $output;
        
        die();
    } else {
        header('Location: ' . $_SERVER['PHP_SELF'] . '?error=true');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>readsbjson-to-geojson</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="description" content="" />
  <style>
      textarea{
          width:75%;
          height:350px;
      }
      .form-row{
          margin-bottom:10px;
      }
      .alert{
        padding: 0.75rem 1.25rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;  
      }
      .alert-success{
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
      }
      .alert-error{
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
      }
  </style>
</head>
<body>
    <?php
    if($_GET['success'] == "true"){
        print '<div class="alert alert-success">The GeoJSON file has been successfully downloaded!</div>';
    } 
    if($_GET['error'] == "true"){
        print '<div class="alert alert-error"><b>Error!</b> - Either you provided an invalid JSON content or no "trace:[]" component found in the JSON content.</div>';
    } 
    ?>
  <h1>readsbjson-to-geojson</h1>
  <p>Enter the JSON content from the tar1090 readsb trace JSON file below:</p>
  <form action="" method="post">
        <div class="form-row">
            <textarea id="readsbjson" name="readsbjson"></textarea>
        </div>
        <div class="form-row">
            <input type="submit" name="submit" value="Submit">
        </div>
  </form>
</body>
</html>