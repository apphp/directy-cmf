<html>
<head>
	<meta charset="UTF-8" />
    <title>{{report_name}} | {{project_name}}</title>

	<style>
		body { font-family: helvetica; font-size:12px; }
		h2 { text-align: center; font-size: 22px; }
		.additional_wrapper { width: 1024px; position: relative; margin: 0 auto; text-align: center; padding: 20px 0; margin-bottom: 30px; }
        .additional_table { width: 100%; border: 1px solid #000000; border-spacing: 0; position: relative; margin: 0 auto; border-right: 1px solid #000000; border-bottom: 1px solid #000000; vertical-align: top; }
		table th { font-size: 16px; height: 30px; border-bottom: 1px solid black; border-right: 1px solid black; }
		table tr td { vertical-align:top; padding: 5px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #ccc; font-size: 14px; text-align: left; }
        table th:last-child { border-right: none; }
        table td { border-right: 1px solid #000000; }
        table td:last-child { border-right: none; }
        .num { text-align: right; }
        .center { text-align: center; }
        .additional_table .td_1 { width: 95px; }
        .additional_table .td_2 { width: 115px; }
        .additional_table .td_4 { width: 270px; }
		template { display: none; }
		.small_image { width: 110px; height: 90px; }
	</style>

    <template id="not_found1">
        <tr><td colspan="6">Rows not found!</td></tr>
    </template>

	<template id="rows">
	<tr>
		<td class="td_4">{{field_1}}</td>
		<td class="td_1 num">{{field_2_prepend}}{{field_2}}</td>
		<td class="td_2 center">{{field_3}}</td>
        <td class="td_1 num" >{{field_4_prepend}}{{field_4}}</td>
        <td class="td_1 num" >{{field_5_prepend}}{{field_5}}</td>
        <td class="td_4">{{field_6}}</td>
	</tr>
	</template>
</head>
<body>
	<div class="additional_wrapper">
		<h2>{{report_name}}</h2>
		
		<table class="additional_table">
		<thead>
		<tr>			
			<th class="td_4">Item</th>
			<th class="td_1">Original Price</th>
			<th class="td_2 center">Date Paid</th>
			<th class="td_1">Amount Paid</th>
			<th class="td_1">Balance</th>
			<th class="td_4">Comments</th>
		</tr>
		</thead>
		<tbody>
		{{rows_content}}
		{{not_found1}}
		</tbody>
		</table>
    </div>	
</body>
</html>
