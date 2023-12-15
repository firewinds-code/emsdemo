<?php 
//echo "lkfjlakg";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(CLS1.'MysqliDb_replica1.php');
//echo "fhgkjdsf";
$label = array();
$value = array();
$value1 = array();
$value_client = array();
$value_process = array();		
$label_active = array();
$value_active = array();
$label_login  = array();
		$value_login = array();

		$myDB=new MysqliDb1();
		$result=$myDB->query("select concat(YEAR(dol), ' - ' , MONTHNAME(dol) ) as Label, ifnull(count(*),0) `Data` from exit_emp  where year(dol) = year(curdate()) group by month(dol) order by  month(dol);");// where employee_map.emp_status = 'InActive'and inner join employee_map on employee_map.EmployeeID = exit_emp.EmployeeID
		
		
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		
		
		if(count($result) > 0 && $result)
		{
			foreach($result as $row) {
			    $label[] ="'".$row['Label']."'";
			    $value[]=$row['Data'];
			}
			$label = implode(",",$label);
			$value = implode(",",$value);
		
		}
		
		
		$myDB=new MysqliDb1();
		$result1=$myDB->query("select 'Active' as `Column`,count(*) Count from employee_map where month(dateofjoin) = month(curdate()) and year(dateofjoin) = year(curdate()) and employee_map.emp_status = 'Active' union all select 'In-Active',count(*) from exit_emp where month(dol) = month(curdate()) and year(dol) = year(curdate());");// inner join employee_map on employee_map.EmployeeID = exit_emp.EmployeeID 
		
		
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		if(count($result1) > 0 && $result1)
		{
			foreach($result1 as $row1) {
			    
			    $value1[] = "{ name: '".$row1['Column']."',  y: ".$row1['Count']."}";
			}
		}
		
		$value1 = implode(",",$value1);
		
		
		$myDB=new MysqliDb1();
		$result1=$myDB->query("SELECT new_client_master.client_name as client_id,client_master.client_name,count(EmployeeID) as count FROM ems.employee_map inner join new_client_master on new_client_master.cm_id=employee_map.cm_id left outer join client_master on new_client_master.client_name=client_master.client_id WHERE emp_status ='Active' group by new_client_master.client_name order by count desc;");
		
		
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		$allCount =0;
		if(count($result1) > 0 && $result1)
		{
			foreach($result1 as $row1) {
			    
			    $value_client[] = "{ name: '".$row1['client_name']."',  y: ".$row1['count']."}";
				$allCount = $allCount + $row1['count'];
			}
		}
		$value_client = implode(",",$value_client);
		
		
		
		
		$myDB=new MysqliDb1();
		$result1=$myDB->query("SELECT  c.client_name,count(distinct process) as count FROM new_client_master nc inner join client_master c on c.client_id = nc.client_name left join client_status_master cs on nc.cm_id=cs.cm_id where cs.cm_id is null group by nc.client_name order by count;");
		
		
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		if(count($result1) > 0 && $result1)
		{
			foreach($result1 as $row1) {
		    
			    $value_process[] = "{ name: '".$row1['client_name']."',  y: ".$row1['count']."}";
			}
		}
		$value_process = implode(",",$value_process);
		
		$myDB=new MysqliDb1();
		$result=$myDB->query("select concat(YEAR(dateofjoin), ' - ' , MONTHNAME(dateofjoin) ) as Label, ifnull(count(*),0) `Data` from  employee_map  where  employee_map.emp_status = 'Active' and year(dateofjoin) = year(curdate()) group by month(dateofjoin) order by  MONTH(dateofjoin);");
		
		
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		
		if(count($result) > 0 && $result)
		{
			
			foreach($result as $row1) {
			    
			    $label_active[] ="'".$row1['Label']."'";
			    $value_active[]=$row1['Data'];
			}
		}
		$label_active = implode(",",$label_active);
		$value_active = implode(",",$value_active);
		
		
		$myDB=new MysqliDb1();
		$result=$myDB->query("select count(distinct EmployeeID) as Employee,cast(CreatedOn as date) as Date  from  login_history  where  year(CreatedOn) = year(curdate()) and month(CreatedOn) = month(curdate()) and day(CreatedOn)<= day(curdate())  group by cast(CreatedOn as date)  ;");
		 
		
		
		//SELECT concat(monthname(DateCreated),' ',year(DateCreated)) `Date Created` ,count(*) from employeepinfo group by MONTH(DateCreated) limit 12
		
		if(count($result) > 0 && $result)
		{
			foreach($result as $row1) {
			    
			    $label_login[] ="'".date('D ,j',strtotime($row1['Date']))."'";
			    $value_login[]=$row1['Employee'];
			}
		}
		$label_login = implode(",",$label_login);
		$value_login = implode(",",$value_login);
		
		
		
		
		
		
?>

<script src="<?php echo SCRIPT; ?>hchart_new/highcharts.js"></script>
<script src="<?php echo SCRIPT; ?>hchart_new/highcharts-3d.js"></script>
<script src="<?php echo SCRIPT; ?>hchart_new/exporting.js"></script>

</head>
 <body>
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

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
  	<div id="div_loading">
  		<div><div class="loader"></div> 
  			<span style="    padding-left: 39px;">Loading... </span>
  		</div>  	
  	</div>
  	
  	<div class="row col s12 m12 l12 no-padding" >
  		<div class="col s12 m12 l12">
  			<div id="chart_client" class="card">Chart goes here...</div>
  		</div>
  		<div class="col s6 m6 l6">
  			<div id="chart_clientwise_count" class="card">Chart goes here...</div>
  		</div>
		<div class="col s6 m6 l6">
  			<div id="chart_process" class="card">Chart goes here...</div>
  		</div>
  		<div class="col s6 m6 l6">
  			<div id="chart_emp_count" class="card">Chart goes here...</div>
  		</div>
  		<div class="col s6 m6 l6">
  			<div id="chart_inactive" class="card">Chart goes here...</div>
  		</div>
  		<div class="col s6 m6 l6">
  			<div id="chart_login" class="card">Chart goes here...</div>
  		</div>
  		<div class="col s6 m6 l6">
  			<div id="chart_request" class="card">Chart goes here...</div>
  		</div>
  	</div>
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
Highcharts.chart('chart_inactive', {
	
    title: {
        text: 'Monthly Active / In-Active Employee Count - '+ '<?php echo date("Y",time());?>'
    },

    subtitle: {
        text: 'Source: COGENT EMS '
    },
	xAxis: {
        categories: [
            <?php echo $label ; ?>
        ],
        crosshair: true
    },
    yAxis: {
        title: {
            text: 'Number of Employees'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        
    },

    series: 
    [{
	        name: '<b>Employee In-Active</b>',data: <?php echo '['.$value.']'; ?>
	},{
	        name: '<b>Employee Active</b>',data: <?php echo '['.$value_active.']'; ?>
	}]

});
Highcharts.setOptions({
 colors: [  '#43de01', '#eb3e1f'],
 title: {
		        style: {
		            color: '#074a9c',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    }
});
Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
        radialGradient: {
            cx: 0.5,
            cy: 0.3,
            r: 0.7
        },
        stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.1).get('rgb')] // darken
        ]
    };
});
Highcharts.chart('chart_emp_count', {
        chart: {
            
            type: 'pie',
            options3d: {
	            enabled: true,
	            alpha: 70,
	            beta: 0
	        }

        },
        title: {
            text: 'Active / In -Active Employee Count For - '+ '<?php echo date("M, Y",time());?>'
        },
        subtitle: {
	        text: 'Source: COGENT EMS '
	    },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}% , Count : <b>{point.y:.0f}</b> </b>'
        },
        plotOptions: {
            pie: {
            	
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                depth: 30,
                showInLegend: true
            }
        },
        series: [{
            name: 'Employee ',
            colorByPoint: true,
            data: [
            		
		            <?php  echo $value1; ?>
	            ]
        }]
    });
Highcharts.theme = {
			colors: ['#a5e007','#fd561e','#0bc9d9','#f3b523','#1cf94e','#b6f314', '#f05a22', '#495ff5', '#37c6a9'],
		    chart: {
		    	
		       
		        
		    },
		    title: {
		        style: {
		            color: '#074a9c',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
		    subtitle: {
		        style: {
		            color: '#437720',
		            font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
			
		    legend: {
		        itemStyle: {
		            font: '9pt Trebuchet MS, Verdana, sans-serif',
		            color: '#cd7810'
		        },
		        itemHoverStyle:{
		            color: 'gray'
		        }   
		    }
		};
Highcharts.setOptions(Highcharts.theme);
Highcharts.chart('chart_client', {
	
	chart: {
        type: 'column',
        options3d: {
          	    enabled: true,
                alpha: 5
            }
    },
    title: {
        text: 'Client Wise Employee Count As On - '+ '<?php echo date("M Y, d",time());?>'
    },	 
    subtitle: {
        text: 'Source: COGENT EMS (Total Active Employee :'+ '<?php echo $allCount; ?>)'
    },tooltip: {
    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b>',	
    //shared: true  ,
  formatter: function() {
    var pcnt = (this.y / this.series.data.map(p => p.y).reduce((a, b) => a + b, 0)) * 100;
	var xaxis = this.x ;
    var cname = this.series.data[xaxis].name;
	//console.log(this.series.data); 	
	return  cname +' ('+ Highcharts.numberFormat(pcnt) + '%, ' + this.y+'/'+this.series.data.map(p => p.y).reduce((a, b) => a + b, 0)+')';
  },
  },
	xAxis: {
        type: 'category'
    },
	
    yAxis: {
        title: {
            text: 'Number of Employees' 
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle',
        x: -40,
        y: 15,
        floating: true,
        borderWidth: 1,
        backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#f3f2f1'),
        shadow: true
    },
 plotOptions: {
	 series: {
                dataLabels: {
                    enabled: true,
                    format: '<span style="color:{point.color}">{point.name}</span>: {point.y:.f} '
                },
                borderWidth:0,
               	borderColor:'gray'
            }
        },

    series: 
    [{
	    name: '<b> Employee Count </b>',
        colorByPoint: true,
        data: [ <?php  echo $value_client; ?> ]
	}]

});

Highcharts.theme = {
			colors: ['#64E572' ,'#DDDF00', '#24CBE5','#26c8a4','#f18c1d', '#5488f5', '#f571fd', '#dc3236','#6AF9C4','#b8f810','#fd6931','#0ce2f3','#f4a022','#1ffa50','#b6f314', '#f55c0a', '#586cf5', '#37c6a9'],
		    chart: {
		    	
		        backgroundColor: {
		            linearGradient: [0, 0, 0, 0],
		            stops: [
		                [0, 'rgb(255, 255, 255)'],
		                [1, 'rgb(255, 255, 255)']
		            ]
		        },
		        borderWidth:0,
		        borderColor:'#abadb4'
		        
		    },
		    title: {
		        style: {
		            color: '#074a9c',
		            font: 'bold 16px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
		    subtitle: {
		        style: {
		            color: '#549528',
		            font: 'bold 12px "Trebuchet MS", Verdana, sans-serif'
		        }
		    },
			
		    legend: {
		        itemStyle: {
		            font: '9pt Trebuchet MS, Verdana, sans-serif',
		            color: '#ee9426'
		        },
		        itemHoverStyle:{
		            color: '#ff0000'
		        }   
		    }
		};
Highcharts.setOptions(Highcharts.theme);
Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
    return {
        radialGradient: {
            cx: 0.5,
            cy: 0.3,
            r: 0.7
        },
        stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.1).get('rgb')] // darken
        ]
    };
});
Highcharts.chart('chart_process', {
        chart: {
            
            type: 'pie',
            options3d: {
	            enabled: true,
	            alpha: 30,
	            beta: 0
	        }

        },
        title: {
            text: 'Client Wise Process Count As On - '+ '<?php echo date("Y M, d",time());?>'
        },
        subtitle: {
	        text: 'Source: COGENT EMS '
	    },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> , Count {point.y}'
        },
        plotOptions: {
            pie: {
            	innerSize: 60,
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                depth: 25,
                showInLegend: false
            }
        },
        series: [{
            name: 'Process ',
            colorByPoint: true,
            data: [
            		
		            <?php  echo $value_process; ?>
	            ]
        }]
    });

Highcharts.setOptions({
 colors: [   '#64E572' ,'#DDDF00', '#24CBE5','#17d739','#f18c1d', '#64E572', '#FF9655', '#CB2326',      '#6AF9C4'],
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

// Client wise employee count and %
 Highcharts.chart('chart_clientwise_count', {
        chart: {            
            type: 'pie',
            options3d: {
	            enabled: true,
	            alpha: 30,
	            beta: 0
	        }

        },
        title: {
            text: 'Client Wise Employee Count As On - '+ '<?php echo date("Y M, d",time());?>'
        },
        subtitle: {
	        text: 'Source: COGENT EMS '
	    },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> , Count {point.y}'
        },
        plotOptions: {
            pie: {
            	innerSize: 60,
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                depth: 25,
                showInLegend: false
            }
        },
        series: [{
            name: 'Employee Count ',
            colorByPoint: true,
            data: [
            		
		            <?php  echo $value_client; ?>
	            ]
        }]
    });

Highcharts.chart('chart_login', {
	
    title: {
        text: 'Monthly Employee Login Count - '+ '<?php echo date("M, Y",time());?>'
    },

    subtitle: {
        text: 'Source: COGENT EMS '
    },
	xAxis: {
        categories: [
            <?php echo $label_login ; ?>
        ],
        crosshair: true
    },
    yAxis: {
        title: {
            text: 'Number of Employees'
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
    },

    series: 
    [{
	        name: '<b>Employee Login</b>',data: <?php echo '['.$value_login.']'; ?>
	}]

});

Highcharts.setOptions({
 colors: [   '#64E572' ,'#f90f0f', '#24CBE5','#17d739','#f18c1d', '#64E572', '#FF9655', '#CB2326','#6AF9C4']
 });
Highcharts.chart('chart_request', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,alpha: 10,
	            beta: 0
        }
    },
    title: {
        text: 'Monthly Request Count - ' + '<?php echo date("M, Y",time());?>'
    },

    subtitle: {
        text: 'Source: COGENT EMS '
    },
    xAxis: {
        categories: ['Leave', 'Exception', 'Downtime','H2H']
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Total Request'
        },
        stackLabels: {
            enabled: true,
            style: {
                fontWeight: 'bold',
                color: 'darkgray'
            },
            formatter: function () {
                return this.total ;
            }
        }

    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal'
        },
        column: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                color:"white"
            }
        }

    },
    
    <?php 
    
    	$myDB=new MysqliDb1();
		$result=$myDB->query("select count(*) as Count_comm,RTStatus,'DownTime' as ReqType from downtime where (month(LoginDate) = month(curdate()) and year(LoginDate) = year(curdate())) group by RTStatus union all select count(*) Count_comm,EmployeeComment Count,'Leave' as ReqType from leavehistry where (month(DateFrom) = month(curdate()) and year(DateFrom) = year(curdate())) or  (month(DateTo) = month(curdate()) and year(DateTo) = year(curdate())) group by EmployeeComment union all select count(*)  as Count_comm,MgrStatus Count,'Exception' as ReqType from exception where (month(DateFrom) = month(curdate()) and year(DateFrom) = year(curdate())) or  (month(DateTo) = month(curdate()) and year(DateTo) = year(curdate())) group by MgrStatus union all 
 select count(*)  as Count_comm, status Count,'H2H' as ReqType from issue_tracker where (month(request_date) = month(curdate()) and year(request_date) = year(curdate()))  group by status;");
		
		
		
		$downtime_approve = 0;
		$downtime_pending = 0;
		$downtime_decline = 0;
		
		$leave_approve = 0;
		$leave_pending = 0;
		$leave_decline = 0;
		
		$exception_approve = 0;
		$exception_pending = 0;
		$exception_decline = 0;
		
		$h2h_approve = 0;
		$h2h_pending = 0;
		$h2h_decline = 0;
		/*$h2h_close= 0;
		$h2h_Inprogress= 0;
		$h2h_Resolve= 0;
		$h2h_PayoutRequest= 0;
		$h2h_reopen= 0;
		*/
		
		foreach($result as $row)
		{
				if($row['RTStatus'] == 'close')
				{
					$h2h_approve = $row['Count_comm'];
				}
				else if($row['RTStatus'] == 'Resolve')
				{
					$h2h_approve =$h2h_approve+ $row['Count_comm'];
				}
				else if($row['RTStatus'] == 'Reopen')
				{
					$h2h_decline = $row['Count_comm'];
				}
				else if($row['RTStatus'] == 'Inprogress')
				{
					$h2h_pending = $h2h_pending+$row['Count_comm'];
				}
				else if($row['RTStatus'] == 'Payout Request')
				{					
				$h2h_pending = $h2h_pending+$row['Count_comm'];
				}
				else if($row['RTStatus'] == 'Payout Reviewed')
				{
					$h2h_pending = $h2h_pending+$row['Count_comm'];
				}
				else if($row['RTStatus'] == 'Pending' && $row['ReqType'] == 'H2H')
				{
					$h2h_pending = $h2h_pending+$row['Count_comm'];
				}
			
			if($row['RTStatus'] == 'Approve')
			{
				
				if($row['ReqType'] == 'DownTime')
				{
					$downtime_approve = $row['Count_comm'];
				}
				else if($row['ReqType'] == 'Leave')
				{
					$leave_approve = $row['Count_comm'];
				}
				else if($row['ReqType'] == 'Exception')
				{
					$exception_approve = $row['Count_comm'];
				}
				

			}
			else if($row['RTStatus'] == 'Decline')
			{
				if($row['ReqType'] == 'DownTime')
				{
					$downtime_decline = $row['Count_comm'];
				}
				else if($row['ReqType'] == 'Leave')
				{
					$leave_decline = $row['Count_comm'];
				}
				else if($row['ReqType'] == 'Exception')
				{
					$exception_decline = $row['Count_comm'];
				}
				
			}
			else
			{
				if($row['ReqType'] == 'DownTime')
				{
					$downtime_pending = $row['Count_comm'];
				}
				else if($row['ReqType'] == 'Leave')
				{
					$leave_pending = $row['Count_comm'];					
				}
				else if($row['ReqType'] == 'Exception')
				{
					$exception_pending = $row['Count_comm'];
				}
				
			}
		   
		}
    ?>
    series: [{
        name: 'Approve',
        data: [<?php echo $leave_approve.','.$exception_approve.','.$downtime_approve.','.$h2h_approve; ?>]
    }, {
        name: 'Decline',
        data: [<?php echo $leave_decline.','.$exception_decline.','.$downtime_decline.','.$h2h_decline; ?>]
    }, {
        name: 'Pending',
        data: [<?php echo $leave_pending.','.$exception_pending.','.$downtime_pending.','.$h2h_pending; ?>]
    }]
});
$(function(){
	$('#div_loading').remove();
	
});
</script>
  
</html>