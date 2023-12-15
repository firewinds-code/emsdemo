<?php 
require_once(__dir__.'/../Config/init.php');
require_once(CLS.'MysqliDb.php');
date_default_timezone_set('Asia/Kolkata');
		$default = array(
		'host' => "localhost",
		'user' => "root",
		'pass' => "",
		'db' => "modulemaster"); 
		//$myDB=new mysql($default);
		$myDB=new MysqliDb();
		//$result2=$myDB->rawQuery($sqlConnect2);	
		$result=array();
		if(isset($_POST['button']))
		{
			$result=$myDB->rawQuery("SELECT AssignTo,cast(EndDate as date) Date,count(*) as total FROM modulemaster.activitylog where Status = 'Completed'  group by AssignTo,cast(EndDate as date) order by EndDate ; ");
		}
		else
		{
			$result=$myDB->rawQuery("SELECT AssignTo,cast(EndDate as date) Date,count(*) as total FROM modulemaster.activitylog where Status = 'Completed' and month(EndDate) = month(curdate()) and year(EndDate) = year(curdate())  group by AssignTo,cast(EndDate as date) order by EndDate ; ");
		}
	//	echo "SELECT AssignTo,cast(EndDate as date) Date,count(*) as total FROM modulemaster.activitylog where Status = 'Completed' and month(EndDate) = month(curdate()) and year(EndDate) = year(curdate())  group by AssignTo,cast(EndDate as date) order by EndDate ; ";
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		$rowCount = $myDB->count;
		$label_login =$value_login  = $valueN =  array();
		if ($rowCount>0) {
				foreach($result as $key=>$row){
				    $label_login[] ="'".date('d M,Y',strtotime($row['Date']))."'";
				    $value_login[$row['AssignTo']]["'".date('d M,Y',strtotime($row['Date']))."'"]=$row['total'];
				 //   $valueN[] = $row[0];
				 }
		}
	
		$array_data = array();
		foreach(array_unique($label_login) as $k=>$val )
		{
			foreach($value_login as $k=>$v)
			{
				if(isset($value_login[$k][$val])){
					$array_data[$k][$val] = $value_login[$k][$val];
				}
				
			}
			
			
		}
		
		$label_login = implode(",",array_unique($label_login));
		
		
		
?>
<!DOCTYPE html>
<html>
<head>
<title>-::: EMS DASHBOARD :::-</title>
<?php include(ROOT_PATH.'AppCode/head.mpt'); ?>
<script src="<?php echo SCRIPT; ?>hchart_new/highcharts.js"></script>
<script src="<?php echo SCRIPT; ?>hchart_new/highcharts-3d.js"></script>
<script src="<?php echo SCRIPT; ?>hchart_new/exporting.js"></script>

<style>
#container {
	min-width: 310px;
	max-width: 800px;
	height: 400px;
	margin: 0 auto
}
@media (min-width: 768px)
{
	#chart_inactive , #chart_request
	{
		min-width: calc(50% - 5px);  
		max-width: calc(50% - 5px); 
		margin-left: 5px;	
	}	
	
	#chart_emp_count , #chart_active
	{
		min-width: calc(50% - 5px);  
		max-width: calc(50% - 5px); 
		margin-right: 5px;	
	}
}


</style>
</head>
 <body><form method="post" >
 <style>
.loader {
  border: 6px solid #f3f3f3;
  border-radius: 50%;
  border-top: 6px solid #3498db;
  width: 30px;
  height: 30px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}
button {
    border-radius: 3px;
    width: 100%;
    height: 30px;
    border: 1px solid #0a7e8a;
    border-bottom: 3px solid #0e7daf;
    background: linear-gradient(#03A9F4,#51c9ff);
    color: white;
    font-weight: bolder;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.78), 1px 1px 1px rgba(255, 255, 255, 0.67);
}
button:hover {
    border-radius: 3px;
    width: 100%;
    height: 30px;
    border: 1px solid #078d44;
    border-bottom: 3px solid #0db08b;
    background: linear-gradient(#02f4c4,#52fe9b);
    color: white;
    font-weight: bolder;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.78), 1px 1px 1px rgba(255, 255, 255, 0.67);
}
button:focus,button:active {
    border-radius: 3px;
    width: 100%;
    height: 30px;
    border: 1px solid #1c8d07;
    border-bottom: 3px solid #6bb10c;
    background: linear-gradient(#51f402,#93fc54);
    color: white;
    font-weight: bolder;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.78), 1px 1px 1px rgba(255, 255, 255, 0.67);
}
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
  	<div id="div_loading" style="    height: 100%;    width: 100%;    position: fixed;    z-index: 100000;    background: rgba(128, 128, 128, 0.33);">
  		<div style="position: relative;top: 50%;left: 50%;float: left;background: white;padding: 10px;border-radius: 8px;width: 130px;    border: 2px solid gray;"><div class="loader" style="    position: absolute;    top: 4px;"></div> <span style="    padding-left: 39px;">Loading... </span></div>  	</div>
  	<div class="row col-sm-12" style="background-color: #fff;height: 100%;overflow: auto;padding: 0px;margin: 0px;" >
  		
  		
  		<p class="pull-left"  style="   width: 100%; "/>
  		
  		<div id="chart_login" class="col-sm-12" style="height: 650px;">High Chart goes here...</div>
  		<?php 
  		if(isset($_POST['button']))
  		{
			
		
  		?>
  			<button type="submit" name="button1">Current Chart</button>	
  		<?php 
  		}
  		else
  		{
			?>
			<button type="submit" name="button">Complete Chart</button>	
			<?php
		}
  		?>
  		
  		<p class="pull-left"  style="   width: 100%; "/>
  		
  	</div></form>
 </body>
<script>
Highcharts.setOptions({
 colors: [   '#fa2929', '#65e241' ],
 title: {
		        style: {
		            color: '#074a9c',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
credits: {
      enabled: false
  }

});

var colors_rand = ['#a8b814','#09c881','#9f24ea',   '#64E572' ,'#DDDF00', '#24CBE5','#f18c1d', '#64E572', '#FF9655', '#CB2326','#6AF9C4','#295cd3'];
/*function shuffle(array) {
  var currentIndex = array.length, temporaryValue, randomIndex ;

  // While there remain elements to shuffle...
  while (0 !== currentIndex) {

    // Pick a remaining element...
    randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex -= 1;

    // And swap it with the current element.
    temporaryValue = array[currentIndex];
    array[currentIndex] = array[randomIndex];
    array[randomIndex] = temporaryValue;
  }

  return array;
}*/
function shuffle(arra1) {
    var ctr = arra1.length, temp, index;

// While there are elements in the array
    while (ctr > 0) {
// Pick a random index
        index = Math.floor(Math.random() * ctr);
// Decrease ctr by 1
        ctr--;
// And swap the last element with it
        temp = arra1[ctr];
        arra1[ctr] = arra1[index];
        arra1[index] = temp;
    }
    return arra1;
}
colors_rand = shuffle(colors_rand);
Highcharts.setOptions({
 //colors: [   '#64E572' ,'#DDDF00', '#24CBE5','#f18c1d', '#64E572', '#FF9655', '#CB2326',      '#6AF9C4'],
 colors: colors_rand,
 title: {
		        style: {
		            color: '#074a9c',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
 legend: {
		        itemStyle: {
		            font: '9pt Trebuchet MS, Verdana, sans-serif',
		            color: '#040200'
		        },
		        itemHoverStyle:{
		            color: '#ff0000'
		        }   
		    },
	lang: {
        decimalPoint: '.',
        thousandsSep: ','
    }
		    
		    
});
Highcharts.chart('chart_login', {
	chart: {
        type: 'spline'
    },
    title: {
        text: 'Module Completed Count'
    },

    subtitle: {
        text: 'Source: COGENT Module Manager '
    },
	xAxis: {
        categories: [
            <?php echo $label_login ; ?>
        ],
        crosshair: true
    },
    yAxis: {
        title: {
            text: 'Number of Modules'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
       series: {
            dataLabels: {
                enabled: true,
                borderRadius: 5,
                backgroundColor: 'rgba(252, 255, 255, 0.2)',
                borderWidth: 1,
                borderColor: '#b3f3aa',
                color:"#33a034",
                y: -6
            }
        }
       /* spline: {
            lineWidth: 4,
            states: {
                hover: {
                    lineWidth: 5
                }
            },
            marker: {
                enabled: false
            },
            
        }*/
    },

    series: 
    [
    	<?php 
    	$count = 0;
    	foreach($array_data as $key=>$value)
    	{
    		$count++;
    		$data_v = Array();
    		foreach($value as $vl)
    		{
    			if(!empty($vl) && $vl != 0)
    			{
					$data_v[] = $vl;
				}
				else
				{
					$data_v[] = 0;	
				}
				
			} 
    		
    		$datas = implode(",",$data_v);
    		
			echo '{ name: \'<b>'.$key.'</b>\',data:['.$datas.'] }';		
			if($count !== count($value_login))
			{
				echo ',';	
			}
			
		}
    	?>
	        
	]

});


$(function(){
	$('#div_loading').remove();
	
});
</script>
  
</html>