<html>
<head>
	<meta charset="UTF-8" />
	<style>
		body { font-family: helvetica; font-size:12px; }
		h2 { text-align: center; font-size: 22px; }
		table th { font-size: 18px; height: 30px; border-bottom: 1px solid black; border-right: 1px solid black; }
        table th:last-child { border-right: none; }
        table td { border-right: 1px solid #000000; }
        table td:last-child { border-right: none; }
		table tr td { vertical-align:top; padding: 5px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #ccc; font-size: 16px; text-align: left; }
        ol { }
        ol li { text-align: left; font-size: 14px; }
		template { display: none; }		
		.weekly_wrapper { width: 1024px; position: relative; margin: 0 auto; text-align: center; padding: 20px 0; margin-bottom: 30px; }
		.weekly_table { width: 100%; border: 1px solid #000000; border-spacing: 0; position: relative; margin: 0 auto; border-right: 1px solid #000000; border-bottom: 1px solid #000000; vertical-align: top; }
        .center { text-align: center; }
		.toRight { text-align: right; }
        .weekly_table .td_1 { width: 85px; }
        .weekly_table .td_2 { width: 165px; }
        .weekly_table .td_4 { width: 235px;	}
		.small_image { width: 110px; height: 90px; }
        .header { text-decoration: underline; }
	</style>
    <template id="not_found1">
        <tr>
            <td colspan="5">No data found!"</td>
        </tr>
    </template>
	<template id="rows">
		<tr>
			<td class="td_1">Week {{field_1}}</td>
			<td class="td_2 center">{{field_2}} - {{field_3}}</td>
			<td class="td_4 {{left4}}">{{field_4}}</td>
			<td class="td_4 {{left5}} ">{{field_5}}</td>
			<td class="td_4 {{left6}}">{{field_6}}</td>
		</tr>
	</template>
    <title>{{report_name}} | {{project_name}}</title>
</head>
<body>
	<div class="weekly_wrapper">
        <img src="{{logo2_path}}" alt="Logo" />
		<h2 class="header">{{report_name}}</h2>
		
		<table class="weekly_table">
		<thead>
		<tr>			
			<th class="td_1">Week</th>
			<th class="td_2 center">Date</th>
			<th class="td_4">What happening</th>
			<th class="td_4">Comments</th>
			<th class="td_4">Payments to<br/> contractor</th>
		</tr>
		</thead>
		<tbody>
		{{rows_content}}
		{{not_found1}}
		</tbody>
		</table>
        <br/>
    </div>
	
</body>
</html>
