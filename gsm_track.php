<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Tracking cell by Boris Landoni Example</title>
 
    <?php
 
function geturl()
{
 
    if ($_REQUEST["myl"] != "") {
      $temp = split(":", $_REQUEST["myl"]);
      $mcc = substr("00000000".($temp[0]),-8);
      $mnc = substr("00000000".($temp[1]),-8);
      $lac = substr("00000000".($temp[2]),-8);
      $cid = substr("00000000".($temp[3]),-8);    
    } else {
      $hex = $_REQUEST["hex"];
      //echo "hex $hex";
      if ($hex=="1"){
            //echo "da hex to dec"; 
            $mcc=substr("00000000".hexdec($_REQUEST["mcc"]),-8);
            $mnc=substr("00000000".hexdec($_REQUEST["mnc"]),-8);
            $lac=substr("00000000".hexdec($_REQUEST["lac"]),-8);
            $cid=substr("00000000".hexdec($_REQUEST["cid"]),-8);
 
            $nlac[0]=substr("00000000".hexdec($_REQUEST["lac0"]),-8);
            $ncid[0]=substr("00000000".hexdec($_REQUEST["cid0"]),-8);           
            $nlac[1]=substr("00000000".hexdec($_REQUEST["lac1"]),-8);
            $ncid[1]=substr("00000000".hexdec($_REQUEST["cid1"]),-8);
            $nlac[2]=substr("00000000".hexdec($_REQUEST["lac2"]),-8);
            $ncid[2]=substr("00000000".hexdec($_REQUEST["cid2"]),-8);
            $nlac[3]=substr("00000000".hexdec($_REQUEST["lac3"]),-8);
            $ncid[3]=substr("00000000".hexdec($_REQUEST["cid3"]),-8);
            $nlac[4]=substr("00000000".hexdec($_REQUEST["lac4"]),-8);
            $ncid[4]=substr("00000000".hexdec($_REQUEST["cid4"]),-8);
            $nlac[5]=substr("00000000".hexdec($_REQUEST["lac5"]),-8);
            $ncid[5]=substr("00000000".hexdec($_REQUEST["cid5"]),-8);
 
      }else{
            //echo "lascio dec";    
            $mcc = substr("00000000".$_REQUEST["mcc"],-8);
            $mnc = substr("00000000".$_REQUEST["mnc"],-8);
            $lac = substr("00000000".$_REQUEST["lac"],-8);
            $cid = substr("00000000".$_REQUEST["cid"],-8);
 
            $nlac[0]=substr("00000000".($_REQUEST["lac0"]),-8);
            $ncid[0]=substr("00000000".($_REQUEST["cid0"]),-8);         
            $nlac[1]=substr("00000000".($_REQUEST["lac1"]),-8);
            $ncid[1]=substr("00000000".($_REQUEST["cid1"]),-8);
            $nlac[2]=substr("00000000".($_REQUEST["lac2"]),-8);
            $ncid[2]=substr("00000000".($_REQUEST["cid2"]),-8);
            $nlac[3]=substr("00000000".($_REQUEST["lac3"]),-8);
            $ncid[3]=substr("00000000".($_REQUEST["cid3"]),-8);
            $nlac[4]=substr("00000000".($_REQUEST["lac4"]),-8);
            $ncid[4]=substr("00000000".($_REQUEST["cid4"]),-8);
            $nlac[5]=substr("00000000".($_REQUEST["lac5"]),-8);
            $ncid[5]=substr("00000000".($_REQUEST["cid5"]),-8);
      }
    }
    //echo "MCC : $mcc <br> MNC : $mnc <br>LAC : $lac <br>CID : $cid <br>";
    return array ($mcc, $mnc, $lac, $cid, $nlac, $ncid);
}
function decodegoogle($mcc,$mnc,$lac,$cid)
{
 
    $mcch=substr("00000000".dechex($mcc),-8);
    $mnch=substr("00000000".dechex($mnc),-8);
    $lach=substr("00000000".dechex($lac),-8);
    $cidh=substr("00000000".dechex($cid),-8);
 
    echo "<tr><td>Hex </td><td>MCC: $mcch </td><td>MNC: $mnch </td><td>LAC: $lach </td><td>CID: $cidh </td></tr></table>";
 
$data = 
"\x00\x0e". // Function Code?
"\x00\x00\x00\x00\x00\x00\x00\x00". //Session ID?
"\x00\x00". // Contry Code 
"\x00\x00". // Client descriptor
"\x00\x00". // Version
"\x1b". // Op Code?
"\x00\x00\x00\x00". // MNC
"\x00\x00\x00\x00". // MCC
"\x00\x00\x00\x03".
"\x00\x00".
"\x00\x00\x00\x00". //CID
"\x00\x00\x00\x00". //LAC
"\x00\x00\x00\x00". //MNC
"\x00\x00\x00\x00". //MCC
"\xff\xff\xff\xff". // ??
"\x00\x00\x00\x00"  // Rx Level?
;
 
$init_pos = strlen($data);
$data[$init_pos - 38]= pack("H*",substr($mnch,0,2));
$data[$init_pos - 37]= pack("H*",substr($mnch,2,2));
$data[$init_pos - 36]= pack("H*",substr($mnch,4,2));
$data[$init_pos - 35]= pack("H*",substr($mnch,6,2));
$data[$init_pos - 34]= pack("H*",substr($mcch,0,2));
$data[$init_pos - 33]= pack("H*",substr($mcch,2,2));
$data[$init_pos - 32]= pack("H*",substr($mcch,4,2));
$data[$init_pos - 31]= pack("H*",substr($mcch,6,2));
$data[$init_pos - 24]= pack("H*",substr($cidh,0,2));
$data[$init_pos - 23]= pack("H*",substr($cidh,2,2));
$data[$init_pos - 22]= pack("H*",substr($cidh,4,2));
$data[$init_pos - 21]= pack("H*",substr($cidh,6,2));
$data[$init_pos - 20]= pack("H*",substr($lach,0,2));
$data[$init_pos - 19]= pack("H*",substr($lach,2,2));
$data[$init_pos - 18]= pack("H*",substr($lach,4,2));
$data[$init_pos - 17]= pack("H*",substr($lach,6,2));
$data[$init_pos - 16]= pack("H*",substr($mnch,0,2));
$data[$init_pos - 15]= pack("H*",substr($mnch,2,2));
$data[$init_pos - 14]= pack("H*",substr($mnch,4,2));
$data[$init_pos - 13]= pack("H*",substr($mnch,6,2));
$data[$init_pos - 12]= pack("H*",substr($mcch,0,2));
$data[$init_pos - 11]= pack("H*",substr($mcch,2,2));
$data[$init_pos - 10]= pack("H*",substr($mcch,4,2));
$data[$init_pos - 9]= pack("H*",substr($mcch,6,2));
 
if ((hexdec($cid) > 0xffff) && ($mcch != "00000000") && ($mnch != "00000000")) {
  $data[$init_pos - 27] = chr(5);
} else {
  $data[$init_pos - 24]= chr(0);
  $data[$init_pos - 23]= chr(0);
}
 
$context = array (
        'http' => array (
            'method' => 'POST',
            'header'=> "Content-type: application/binary\r\n"
                . "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
            )
        );
 
$xcontext = stream_context_create($context);
$str=file_get_contents("http://www.google.com/glm/mmap",FALSE,$xcontext);
 
if (strlen($str) > 10) {
  $lat_tmp = unpack("l",$str[10].$str[9].$str[8].$str[7]);
  $lat = $lat_tmp[1]/1000000;
  $lon_tmp = unpack("l",$str[14].$str[13].$str[12].$str[11]);
  $lon = $lon_tmp[1]/1000000;
  $raggio_tmp = unpack("l",$str[18].$str[17].$str[16].$str[15]);
  $raggio = $raggio_tmp[1]/1;
  } else {
  echo "Not found!";
  $lat = 0;
  $lon = 0;
  }
  return array($lat,$lon,$raggio);
 
}
 
list($mcc,$mnc,$lac,$cid, $nlac, $ncid)=geturl();
 
echo "<table cellspacing=30><tr><td>Dec</td><td>MCC: $mcc </td><td>MNC: $mnc </td><td>LAC: $lac </td><td>CID: $cid </td></tr>";
 
list ($lat,$lon,$raggio)=decodegoogle($mcc,$mnc,$lac,$cid);
 
  echo "<br>Google result for the main Cell<br>";
  echo "Lat=$lat <br> Lon=$lon <br> Range=$raggio m<br>";
  echo "<a href=\"http://maps.google.it/maps?f=q&source=s_q&hl=it&geocode=&q=$lat,$lon&z=14\" TARGET=\"_blank\" >See on Google maps</a> <BR> <br>";
 
  for ($contatore=0; $contatore < (count($nlac)); $contatore++) {
    if ($nlac[$contatore]==0) {
        //echo "trovato campo vuoto al contatore $contatore<BR>";
        $ncelle=$contatore;
        break;
    }   
}
 
for ($contatore=0; $contatore < ($ncelle); $contatore++) {
    echo "LAC: $nlac[$contatore]\t CID: $ncid[$contatore]<BR>";
    list ($nlat[$contatore],$nlon[$contatore],$nraggio[$contatore])=decodegoogle($mcc,$mnc,$nlac[$contatore],$ncid[$contatore]);
    echo "<br>Google result for the Neighbor Cell $contatore <br>";
    echo "nLat=$nlat[$contatore] <br> nLon=$nlon[$contatore] <br> nRaggio=$nraggio[$contatore] m<br><br>";
}
 
  echo "<div id=\"map\" style=\"width: 100%; height: 700px\"></div>";
  echo "<script type=\"text/javascript\">";
  echo "var latgoogle=$lat;";
  echo "var longoogle=$lon;";
  echo "var raggio=$raggio;";
 
//creo un file contenente le coordinate delle celle ****  
    $stringa_xml_doc = " <markers>\n\t";
    $stringa_xml_doc =$stringa_xml_doc. "<marker lat=\"$lat\" lng=\"$lon\" rag=\"$raggio\" html=\"Main cell\" ico=\"antred\" label=\"Main\" />"; 
        for($contatore= 0; $contatore < $ncelle; $contatore++) 
        { 
            if ($nlat[$contatore]!=0) {
                $stringa_xml_doc =$stringa_xml_doc. "<marker lat=\"$nlat[$contatore]\" lng=\"$nlon[$contatore]\" rag=\"$nraggio[$contatore]\" html=\"Cell $contatore\" ico=\"antbrown\" label=\"Marker $contatore\" />"; 
            }
 
        } 
    $stringa_xml_doc =$stringa_xml_doc."\n </markers>";
 
    echo ($stringa_xml_doc);
    //$stringa_xml = $stringa_xml_dtd.$stringa_xml_doc;
    $stringa_xml = $stringa_xml_doc;
 
    $file_name = "celle_xml.xml";
    $file = fopen ($file_name,"w");
    $num = fwrite ($file, $stringa_xml);
 
    fclose($file);
 
    echo("File XML creato con successo!!");
 
//***
 
  echo "nLat=new Array();";
  echo "nLon=new Array();";
  echo "nraggio=new Array();";
  for ($contatore=0; $contatore < ($ncelle); $contatore++)
            { 
  echo "        nLat [$contatore]   =$nlat[$contatore];
                nLon [$contatore]   =$nlon[$contatore];
                nraggio [$contatore]=$nraggio[$contatore];";
 
            }
 
  echo "</script>";
 
?>
 
        <center>
        <br>
        <br>
        <center>
            <br>
            <br>
            <iframe width='100%' height='540' marginwidth='0' marginheight='0' scrolling='no' frameborder='0'  src='http://open-electronics.org/fb/' ></iframe>
        </center>
<br>
<br>
</center>
 
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAIJFMStkxCl4SCny-4ljyrBRkrgiUOwoahV4KonZmGOdSmhVVVBTizYtL9IQMT4sND3EJvMdlOrIA8g" type="text/javascript"></script> 
 
  </head>
 
 <body onunload="GUnload()">
 
        <center>
        <br>
        <br>
        <script type="text/javascript"><!--
google_ad_client = "ca-pub-5248152858136551";
/* OE Link Banner post */
google_ad_slot = "3312240372";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<br>
<br>
</center>
 
    <script type="text/javascript">
 
   //function initialize() {
      if (GBrowserIsCompatible()) {
        //alert("boris2");
 
    //}
 
    // This function picks up the click and opens the corresponding info window
      function myclick(i) {
        GEvent.trigger(gmarkers[i], "click");
      }
 
        var map = new GMap2(document.getElementById("map"));
        //map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        map.setUIToDefault();   
 
    function cursore() {
        alert("boris");
        var pointg = new GLatLng(latgoogle,longoogle);
        alert(pointg);
    }
 
      // A function to create the marker and set up the event window
      function createMarker(point,name,html,icona) {        
        var marker = new GMarker(point,markerOptions);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }
 
// Read the data from example.xml
      GDownloadUrl("celle_xml.xml", function(doc) {
        var xmlDoc = GXml.parse(doc);
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
 
        for (var i = 0; i < markers.length; i++) {
          // obtain the attribues of each marker
          var lat = parseFloat(markers[i].getAttribute("lat"));
          var lng = parseFloat(markers[i].getAttribute("lng"));
          var raggio = parseFloat(markers[i].getAttribute("rag"));
          var icona = markers[i].getAttribute("ico");
          var Icon = new GIcon(G_DEFAULT_ICON);
            Icon.image = icona + ".png";
 
            // Set up our GMarkerOptions object
 
                markerOptions = { icon:Icon };
 
          var point = new GLatLng(lat,lng);
 
          var html = markers[i].getAttribute("html");
          var label = markers[i].getAttribute("label");
 
          // create the marker
            if (icona=="antred") {
                var color="#FF0000";
                var fillColor="#FF6600";
            }
            else {
                var color="#FF6699";
                var fillColor="#FF99CC";
            }
          var thickness=4;
          var opacity=0.4;
 
          var fillOpacity=0.2;
 
          var polyCircle=drawCircle(point, raggio, color, thickness, opacity, fillColor, fillOpacity);      
          map.addOverlay(polyCircle); 
          var marker = createMarker(point,label,html,markerOptions);
          map.addOverlay(marker);
        }
            map.setCenter(point, 13);
        // put the assembled side_bar_html contents into the side_bar div
        //document.getElementById("side_bar").innerHTML = side_bar_html;
      });
 
        function drawCircle(center, radius, color, thickness, opacity, fillColor, fillOpacity) 
        { 
 
            radiusMiles=radius*0.000621371192;
            //alert(radiusMiles);
            var degreesPerPoint = 8; 
            var radiusLat = radiusMiles * (1/69.046767); // there are >> >> > 69.046767 miles per degree latitude 
            var radiusLon = radiusMiles * (1/(69.046767 * Math.cos(parseFloat(center.lat()) * Math.PI / 180))); 
            var points = new Array(); 
            //Loop through all degrees from 0 to 360 
            for(var i = 0; i < 360; i += degreesPerPoint) 
            { 
                var point = new GLatLng(parseFloat(center.lat()) + (radiusLat * Math.sin(i * Math.PI / 180)), parseFloat(center.lng()) + (radiusLon * Math.cos(i * Math.PI / 180))); 
                    points.push(point); 
            } 
            points.push(points[0]);     // close the circle 
 
            polyCircle = new GPolygon(points, color, thickness, opacity, fillColor, fillOpacity) 
            return polyCircle;
        } 
 }
 
    </script>
 
<script type="text/javascript">   
 
  //cursore();  //carica il marcatore
 
</script>
 
  </body>
</html>
