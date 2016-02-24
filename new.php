<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">
    <title>Analysis page</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="tabsStyle.css" rel="stylesheet">
   <link rel="stylesheet" href="newG.css">
     <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="tabScript.js"></script>
    <script src="table.js"></script>
    <link rel="stylesheet" href="table.css">
     <link rel="stylesheet" href="googleTableCss.css">
    <script src= "http://www.google.com/uds/modules/gviz/gviz-api.js"> </script>
    <script src= "https://www.google.com/jsapi"> </script>
    <script type="text/javascript">
        google.load('visualization', '1', {packages: ['table']});
    </script>
</head>
<body onload="init()">
    <div class="container">
        <!-- Static navbar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Cancer Research Database</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="Home.html">Home</a></li>
                        <li><a href="Search.php">Search</a></li>
                        <li><a href="Analysis.php">Analysis</a></li>
                         <li class="active"><a href="miRNA-disease.php">miRNA-disease</a></li>
                         <li><a href="miRNA-TF-Gene.php">miRNA-TF-Gene</a></li>
                        <li><a>miRNA-Drug</a></li>
                        <li><a>miRNA methylation</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
        </nav>
        <!-- Main component for a primary marketing message or call to action -->
        <div class="home">
         
            <p>This page will alow you to search the database.</p>
        </div>
    
        <ul id="tabs">
            <li><a href="#about">Min</a></li>
        </ul>

        <div class="tabContent" id="about">
            <h2>Please enter one or more mirna_name</h2>
            <div>
                <form ="" method='post'>
                
               <tr><td><textarea name="user" value="" rows = "3" cols="80"></textarea></td></tr>
                
                <input type="submit" name="submit" value="Submit"></form>
                              
                
            </div>

        <ul id="tabs">  
        <li><a href="#tab2">Max</a></li>
    </ul>

        

           <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6"> </div>
      <!--  style="display: none" -->
      <div class="col-sm-3"></div>
    </div>
    <div id="table"></div>
    <span id="pageCnt"></span>
    <div  id="graph">
                        </div>


<?php
if ( ! empty($_POST['user'])){
    $name = $_POST['user'];
require_once("dBaseAccess.php");
$mirna = mysqli_real_escape_string($mirnabDb, $_POST['user']);
        
       $trimmed = array();
             $str = preg_split('/,/', $mirna);
            $str = preg_split('/[,|\r\n|\r|\n]+/', $mirna);
        //echo json_encode($str);
            for ($i=0;$i<count($str);$i++) {
                    $trimmed[$i] = trim($str[$i]);
            }
        $strNew = implode("','", $trimmed);
        $strNew = str_replace('\r\n',"','" , $strNew);
        echo $strNew;
        
        $query = 
           "SELECT mirna_name AS source
           , dis_name AS target
           , dis_reguln AS type
           , dis_pubId 
                from main_v2, disease 
                where main_v2.link_id = disease.link_id
                AND mirna_name in ('".$strNew."')
                LIMIT 30
                ";
$result = mysqli_query($mirnabDb,$query);
//echo $result;
 if ( ! $result ) {
        echo mysql_error();
        die;
    }
$data = array();
  for ($x = 0; $x < mysqli_num_rows($result); $x++) {
        $data[] = mysqli_fetch_assoc($result);
    }
$jsonForm = json_encode($data);
//echo $jsonForm;
}
?>
                  <script src="newGraph.js"></script>

<script>
   function drawTable(jsonData) {
    
    var dataT = new google.visualization.DataTable();
    var numRows = jsonData.length;
    dataT.addRows(numRows);
    console.log(numRows, " rows added");

    dataT.addColumn('string', 'miRNA');
    dataT.addColumn('string', 'Disease');
    dataT.addColumn('string', 'Regulation');
    dataT.addColumn('string', 'PubId');

    var url = '',
        pubmed = '';

    for (var iter = 0; iter < numRows; iter++) {
        dataT.setCell(iter, 0, jsonData[iter]['source']);
        dataT.setCell(iter, 1, jsonData[iter]['target']);
        dataT.setCell(iter, 2, jsonData[iter]['type']);
        dataT.setCell(iter, 3, jsonData[iter]['dis_pubId']);
    };

    var options = {allowHtml: true, alternatingRowStyle: true}; 
    //options['cssClassNames'] = cssNamesOne;
    options['page'] = 'enable'; options['pageSize'] = 10; options['pagingSymbols'] = {prev: 'prev', next: 'next'}; //options['pagingButtonsConfiguration'] = 'auto';
    
    var table = new google.visualization.Table(document.getElementById('table'));
    table.draw(dataT, options); // , {showRowNumber: true, width: '100%', height: '100%'});
  }
</script>

<script>
var jsonForm = <?php echo $jsonForm;?>;
drawTable(jsonForm);
createGraph(jsonForm,"#graph");
</script>  
 
        </div>

<div class="tabContent" id="tab2">
            <h2>Please enter one or more dis_name</h2>
            <div>
                <form ="" method='post'>
                
           <tr><td><textarea name="user2" value="" rows = "15" cols="80"></textarea></td></tr>
                
                <input type="submit" name="submit" value="Submit"></form>
                    
            </div>

            <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6"> </div>
      <!--  style="display: none" -->
      <div class="col-sm-3"></div>
    </div>
    <div id="table"></div>
    <span id="pageCnt"></span>
    <div class="col-lg-8 col-md-6 col-sm-6" id="graph">
                        </div>    

<?php
if ( ! empty($_POST['user2'])){
    $name = $_POST['user2'];
require_once("dBaseAccess.php");
$mirna = mysqli_real_escape_string($mirnabDb, $_POST['user2']);
$trimmed = array();
            $str = preg_split('/,/', $mirna);
            $str = preg_split('/[,|\r\n|\r|\n]+/', $mirna);
        //echo json_encode($str);
            for ($i=0;$i<count($str);$i++) {
                    $trimmed[$i] = trim($str[$i]);
            }
        $strNew = implode("','", $trimmed);
        $strNew = str_replace('\r\n',"','" , $strNew);
        echo $strNew;
        
        $query = 
           "SELECT mirna_name AS source
           , dis_name AS target
           , dis_reguln AS type
           , dis_pubId 
                from main_v2, disease 
                where main_v2.link_id = disease.link_id
                AND dis_name in ('".$strNew."')
                LIMIT 30
                ";
$result = mysqli_query($mirnabDb,$query);
//echo $result;
 if ( ! $result ) {
        echo mysql_error();
        die;
    }
$data = array();
  for ($x = 0; $x < mysqli_num_rows($result); $x++) {
        $data[] = mysqli_fetch_assoc($result);
    }
$jsonForm2 = json_encode($data);
}
?>

<script>
var jsonForm2 = <?php echo $jsonForm2; ?>;
drawTable(jsonForm2);
createGraph(jsonForm2,"#graph");
</script>
 
        </div>


<div>
        <input type="range" min="0" max="1" value="0" step=".1" onchange="showValue(this.value)" />
<span id="range">0</span>
<script type="text/javascript">
function showValue(newValue)
{
    document.getElementById("range").innerHTML=newValue;
}
</script>
</div>

    </div><!--/.container-fluid -->

    
            
     <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
   
    

   
</bodyonload="init()">
</html>