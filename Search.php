<?php
    require_once('dBaseAccess.php');
    //fetch table rows from mysql db
    $sql = "SELECT distinct d1 from consensus_output";
    $result = mysqli_query($mirnabDb, $sql) or die("Error in Selecting " . mysqli_error($mirnabDb));

    //create an array
    $reparray = array();
    for ($x = 0; $x < mysqli_num_rows($result); $x++) {
        $reparray[] = mysqli_fetch_assoc($result);
    }
    $dropnames = json_encode($reparray);
    //close the db connection
   // mysqli_close($connection);
    
?>

<script>
var pech = <?php echo $dropnames; ?>;

</script>
<html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">
    <title>Basic Search</title>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="googleTableCss.css">
    <link rel="stylesheet" href="newG.css">
     <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
   
</head>
<body>
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
                    <a class="navbar-brand" href="#">MRNA Database</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="Home.html">Home</a></li>
                        <li class="active"><a href="Search.php">Search</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            
        </nav>
        <!-- Main component for a primary marketing message or call to action -->
                <div class="container">
                    <p>This page will allow you to search the database.</p>
                    <select id="selector"> </select>

    <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6"> </div>
      <!--  style="display: none" -->
      <div class="col-sm-3"></div>
    </div>
    <div id="graph"></div>

                </div>

       <div>
                <form ="" method='post'>
                
           <tr><td><textarea name="min" value="" rows = "1" cols="60"></textarea></td></tr>

           <tr><td><textarea name="max" value="" rows = "1" cols="60"></textarea></td></tr>
                
                <input type="submit" name="submit" value="Submit">


            </form>
                    
            </div>

<?php

if ( ! empty($_POST['min'])){
    $name1 = $_POST['min'];
    echo $name1;
}

else
{
    $name1 = 0;
}

if ( ! empty($_POST['max'])){
    $name2 = $_POST['max'];
    echo $name2;
}

else
{
    $name2 =1;
}

?>

    <!--/.container-fluid -->
     <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

    <script>
        //console.log(pech[0]);
        window.onload = function () 
        {
            var JSON = pech, 
            select = document.getElementById("selector");
            var option = document.createElement("option");
            option.value = "Select disease";
            option.textContent = "Select disease";
            select.appendChild(option);


            for (var i = 0 ;  i < pech.length; i++)
            {   at = JSON[i];
                //console.log(at);
                name = at.d1;
                var option = document.createElement("option");
                option.value = name;
                option.textContent = name;
                select.appendChild(option);
            };
        };
    </script>

 <script src="http://code.jquery.com/jquery-1.11.3.js"></script>
 <script src="https://rawgit.com/gka/d3-jetpack/master/d3-jetpack.js"></script>
<script src="newGraph.js"></script>


 
<script>

    $("#selector").change(function() {
        // gets the miRNA selected using dropdown
        
        var grabMin = <?php echo $name1; ?>;
        var grabMax = <?php echo $name2; ?>;

        var mirnaSelected = $(this).val();
        var phpJson = [];
      
        $.ajax({
            url: 'queries.php',
            type: 'POST',

            data: {'mirna': mirnaSelected, 
                    'min': grabMin,
                    'max': grabMax,
                    'flag': 100},

            //dataType: "json",
            success: function(data) {
               // if data is not empty
               if(data){
                    $("#graph").empty();
                    createGraph(JSON.parse(data),"#graph");
                    //$("#table").show();
                }
                else {
                    alert("No results for the selected miRNA. Select a different miRNA.");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                console.log(errorThrown);
            }
        }); // end of ajax request
    }); // end of select change
</script>

    <script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  
</body>
</html>