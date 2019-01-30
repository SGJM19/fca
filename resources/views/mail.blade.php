<html>
<head style="width:100%;">

<style type="text/css">
	#table_issues {
	    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	    border-collapse: collapse;
	    width: 100%;
	}

	#table_issues td, #table_issues th {
	    border: 1px solid #ddd;
	    padding: 8px;
	}

	#table_issues tr:nth-child(even){background-color: #f2f2f2;}

	#table_issues tr:hover {background-color: #ddd;}

	#table_issues th {
	    padding-top: 12px;
	    padding-bottom: 12px;
	    text-align: left;
	    background-color: #4CAF50;
	    color: white;
	}

	.container_mail {
    display:flex;
    flex-wrap:wrap;
    flex-direction:row;
    justify-content:flex-start;
    align-items:stretch;
	}

	.left {order:1; background:transparent; flex-basis:100%; height:300px}
/*	.middle {order:3; background: green;flex-basis:100%; height:300px;}*/
	.right {order:2; background:transparent;flex-basis:100%; height:300px;}

	@media screen and (min-width:600px) {
	   .container_mail {
	       flex-wrap:nowrap;
	   } 
	   
	    .left {
	        flex-basis:100%;
	        order:1;
	    }
	    /*.middle {
	        flex-basis:100%;
	        order:2;
	    }*/
	    .right {
	        flex-basis:100%;
	        order:3;
	    }
	}
	.mercy {
	   background-color: #6288a5;
	   color: #fff;
	   height: 102px;
	   line-height: 102px;
	   font-size: 13px;
	   padding: 0 28px;
	   letter-spacing: 1.5px;
	   position: relative;
	   text-transform: uppercase;
	   text-align: center;
	   text-decoration: none;
	   cursor: pointer;
	   border: 1px solid rgba(0, 0, 0, 0.15);
	   vertical-align: bottom;
	   white-space: nowrap;
	   text-rendering: auto;
	   box-sizing: border-box;
	   border-radius: 3px;
	   font-family: "Roboto", Helvetica, Arial, sans-serif;
	   font-weight: bold;
	   font-style: normal;
	}
	.mercy:hover {
	   background-color: #4d6e87;
	   color: #fff;
	   text-decoration: none;
	}
</style>
</head>

<body style="width:100%;">	

	<div style="border:0px solid #000; width:1000px !important;">
		<div style="display: inline-block;">
			{{-- <img src="{{$message->embed('storage/app/public/images/logo.png')}}" height="50px" width="50px"> --}}
		</div>
		<div style="display: inline-block; vertical-align: top;">
			<div style="font-size:24px; margin-bottom: -10px;">FCA Report Update</div>
			<div>
				{{nl2br($msg_head)}}
			</div><br><br>
			<div>
				{{nl2br($msg_body)}}
			</div>
		</div>
		<hr style="border:0px; border-bottom:1px solid #000; width:1000px;">
		<div class="container_mail" style="width:600px !important;">
			<div class="left">
	    	<div style="height:50px; background-color:#f1f1f1; vertical-align:middle; line-height: 50px;">
	    		<center>
	    			<div><h3>Report details</h3></div>
	    		</center>
	    	</div>
	    	<table id="table_issues" style="width:500px;">	
				<tr>
					<td style="text-align:right;">Store Id</td>
					<td style="text-align:center;">{{$store}}</td>
				</tr>
				<tr>
					<td style="text-align:right; width:60%;">Ratings </td>
					<td style="text-align:center;">{{$ratings}} %</td>
				</tr>	
				<tr>
					<td style="text-align:right;">Remarks</td>
					<td style="text-align:center;">{{$remarks}}</td>
				</tr>
				<tr>
					<td style="text-align:right;">Audit date: </td>
					<td style="text-align:center;">{{$audit_date}}</td>
				</tr>
				<tr>
					<td style="text-align:right;">Audit by: </td>
					<td style="text-align:center;">{{$audit_by}}</td>
				</tr>
			</table>
	    </div>
		</div>
		<center>
			{{-- change the link upon finishing --}}
			<a href="http://icweb.hiflyer.ca/ktool" class="mercy">Log In</a>
		</center>
	</div>
</body>

	{{-- <script src="{{asset('js/myscripts.js')}}"></script> --}}
</html>