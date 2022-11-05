<html>
<head>
	<meta charset="UTF-8" />
	<style>
		body { font-family: helvetica; font-size:12px; }		
		.cf2_wrapper { width: 1024px; position: relative; margin: 0 auto; text-align: center; padding: 20px 0; margin-bottom: 30px; }
		h2 { text-align: center; font-size: 22px; }
        .cf2_table { width: 100%; border: 1px solid #000000; border-spacing: 0; position: relative; margin: 0 auto; border-right: 1px solid #000000; border-bottom: 1px solid #000000; vertical-align: top; }
		table th { font-size: 16px; height: 30px; border-right: 1px solid #000000; border-bottom: 1px solid #000000; }
        table th:last-child { border-right: none; }
        table td { border-right: 1px solid #000000; }
        table td:last-child { border-right: none; }
		table tr td { vertical-align:top; padding: 5px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #ccc; font-size: 14px; text-align: left; }
        .num { text-align: right; }
        .center { text-align: center; }
        .cf2_table .td_1 { width: 130px; }
        .cf2_table .td_2 { width: 110px; }
        .cf2_table .td_3 { width: 90px; }
        .cf2_table .td_4 { width: 240px; }
        .cf2_table .td_6 { width: 80px; }
        .cf2_table .td_7 { width: 150px; }
		template { display: none; }		
		.small_image { width: 110px; height: 90px; }
	</style>
	
    <template id="not_found1">
        <tr><td colspan="12">No data found!</td></tr>
    </template>
    <template id="rows">
        <tr>
            <td class="td_1">{{field_1}}</td>
            <td class="td_2">{{field_2}}</td>
            <td class="td_2 center">{{field_3}}</td>
            <td class="td_3 num">{{field_4_prepend}}{{field_4}}</td>
            <td class="td_3 num">{{field_5_prepend}}{{field_5}}</td>
            <td class="td_6 num">{{field_6_prepend}}{{field_6}}</td>
            <td class="td_3 num">{{field_15_prepend}}{{field_15}}</td>
            <td class="td_3 num">{{field_16_prepend}}{{field_16}}</td>
            <td class="td_7">{{field_17}}</td>
        </tr>
    </template>
    <title>{{report_name}} | {{project_name}}</title>
</head>
<body>
<div class="cf2_wrapper">
    <h2>{{report_name}}</h2>

    <table class="cf2_table">
	<thead>
	<tr>
		<th class="td_1">Supplier/Sub-constractor name</th>
		<th class="td_2">Item</th>
		<th class="td_2 center">Date Ordered</th>
		<th class="td_3">Original Price</th>
		<th class="td_3">Extra's Price</th>
		<th class="td_6">Total incl. extras</th>
		<th class="td_3">Amount Paid</th>
		<th class="td_3">Balance</th>
		<th class="td_7">Comments</th>
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
