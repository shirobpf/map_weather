<?php
        $name="小倉駅";
        $weather="";
        $Coordinate="33.8860498,130.8824316";

        if(isset($_GET['name'])){
            $name = htmlspecialchars($_GET['name'], ENT_QUOTES, "UTF-8");
        
            #GeocodingAPIより緯度経度を取得
                $urlContents = simplexml_load_file("https://www.geocoding.jp/api/?q=".$name);
                $ary = json_decode(json_encode($urlContents), true);

            #緯度経度を抽出
            //print_r(array($ary));
                $lat = $ary[coordinate][lat];
                $lng = $ary[coordinate][lng]; 
                $Coordinate = $lat.",".$lng;

                //echo $Coordinate;
        
            #取得した緯度経度より天気アイコンを取得
                $weather=file_get_contents("http://api.openweathermap.org/data/2.5/forecast?lat=".$lat."&lon=".$lng."&units=metric&APPID=ad4f064a1b4adc14404ab3bd8553f3ff");

                $weatherArray = json_decode($weather,true);

                //print_r($weatherArray);
            
            #天気アイコンを配列に入れる
                $jpWeather = array('01d'=>'快晴','02d'=>'晴れ','03d'=>'くもり','04d'=>'くもり','09d'=>'小雨','10d'=>'雨','11d'=>'雷雨','13d'=>'雪','50d'=>'霧','01n'=>'快晴(夜)','02n'=>'晴れ(夜)','03n'=>'くもり(夜)','04n'=>'くもり(夜)','09n'=>'小雨(夜)','10n'=>'雨(夜)','11n'=>'雷雨(夜)','13n'=>'雪(夜)','50n'=>'霧(夜)');
                
                $icon= $weatherArray['list'][0]['weather'][0]['icon'];
           
                //print_r($jpWeather);
                //print_r($icon);
            
            #入れた天気アイコンと比較
                foreach($jpWeather as $No => $value){
                    if ($No == $icon){
                        $weatherOut = $name."の天気：".$value;
                    }
                }
            //echo $weatherOut;
            
                $max= $weatherArray['list'][0]['main']['temp_max'];
                $min= $weatherArray['list'][0]['main']['temp_min'];
                            
            //echo $max;
            //echo $min;
        }


?>   

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <!-- bootstrap・jqueryの読み込み-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
     
    <style type="text/css">
        body{
           background-color:#FFEEFF;
        }
        h1{
           padding: 40px;               
           top:  0;                     
           bottom:  0;                  
           left:  0;                    
           right:  0;                   
           margin:  auto; 
           text-align: center;
           color: black;
           background-color:white;
           font-size:28px;
        }               
        .global-nav_item{
    	   display: block;
  	   height: 60px;
  	   line-height: 60px;
           padding-top: 10px;
   	   color: #fff;
   	   font-size: 14px;
   	   font-size: 14px;
   	   font-size: 1.4rem;
   	   font-weight: bold;
	   -webkit-box-shadow: 2px 2px 0 0 black;
           box-shadow: 2px 2px 0 0 black;
 	   border-radius: 7px;
	}
	.global-nav_item:hover{
	  -webkit-transform: translate(2px, 2px);
          transform: translate(2px, 2px);
  	  -webkit-box-shadow: none;
          box-shadow: none;
	}
        .input_wrap{
            margin: 20px; 
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            width: 40%; 
        }
        .btn btn-primary btn-block{
           text-align: center;
            margin-left: auto;
            margin-right: auto;
            width: 40%;
        }
        #map{
            margin: 20px; 
            margin-left: auto;
            margin-right: auto;
            width: 60%; 
        }
        #weather{
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            width: 40%;
            background-color:#FFFFDD;
        }
        .foot{
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            width: 40%;
            background-color:white;
        }



    </style>
    
</head>    
    
<body>

    <h1>都市名やランドマークを入力することで、<br>APIを使用して現在の天気と都市の地図を表示します。</h1>
 	 			
    <nav class="global-nav" id="header-menu">
      <ul class="global-nav_inner">
        <li class="global-nav_item">
          <button type="submit">HOME</button>
        </li>
        <li class="global-nav_item">
          <button type="submit">東京</button>
        </li>
        <li class="global-nav_item">
          <button type="submit">大阪</button>
        </li>
        <li class="global-nav_item">
          <button type="submit">名古屋</button>
        </li>
        <li class="global-nav_item">
          <button type="submit">福岡</button>
        </li>
      </ul>	
   
    <div class="input_wrap">
        <form action~"" method="get">
            <input class="form-control" id="ex3" type="text" name="name" placeholder="入力してください（例：東京タワー）" >
            <button type="submit" class="btn btn-primary btn-block">検索</button>
        </form>
    </div>

 
 <!--見た目上の問題からyahoo地図を表示-->
    <div id="map" style="width:500px; height:400px"></div>

    <script type="text/javascript" charset="utf-8" src="https://map.yahooapis.jp/js/V1/jsapi?appid=dj00aiZpPUVZaDdITXdQN3BhNSZzPWNvbnN1bWVyc2VjcmV0Jng9NTc-"></script>

    <script type="text/javascript">
    
    window.onload = function(){
       var ymap = new Y.Map("map");
       ymap.drawMap(new Y.LatLng(<?=$Coordinate ?>), 16, Y.LayerSetId.NORMAL);
    }
    </script>


    
<!--以下Googleの場合-->

<!--
   <div id="map" style="width:500px; height:400px"></div>

   <script>
        var map;
        function initMap() {
              map = new google.maps.Map(document.getElementById('map'), {
              center: {lat:<?=$lat ?>, lng: <?=$lng ?>},
              zoom: 16
              });
        }
   </script>

   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAu26Ecq4DpDkuGQZCMP7Mn-x_OuvXIkug&callback=initMap"async defer>
    
   </script>

-->
    
         <div id="weather"><?php 
              
              if ($weatherOut) {
                  
                  echo '<div class="alert alert-success" role="alert">
                       '.$weatherOut.'<br>現在の最高気温：'.$max.'℃<br>現在の最低気温：'.$min.'
                        ℃</div>';
                  
              } else if ($error) {
                  
                  echo '<div class="alert alert-danger" role="alert">
                       '.$error.'
                       </div>';                  
              }
        ?></div>
    
    <footer>
    <div class="foot">
        <span>座標取得：Geocoding.jp&nbsp; </span>
        <span>天気API：Openweather&nbsp; </span>
        <span>地図API：yahoo デベロッパーネットワーク&nbsp;</span>
    </div>
    </footer>    
    
</body>

</html>    
    
<!--
　　JavaScriptとJqueryがフロントサイドでは必要なのなのかとは思うが、せめてJavaScriptをしっかり覚えないとと痛感した最初の作品
-->
