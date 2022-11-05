<html>
<head>
	<meta charset="UTF-8" />
	<style>
		body { font-family: helvetica; font-size:12px; }
		.wrapper{ width: 1024px; position: relative; margin: 0 auto; text-align: center; padding: 20px 0; margin-bottom: 30px; }
		h2 { text-align: center; font-size: 22px; }
		table { width: 100%; border: 1px solid #000000; border-spacing: 0; position: relative; margin: 0 auto; border-right: 1px solid #000000; border-bottom: 1px solid #000000; vertical-align: top; }
		table th { background-color: #002a80; color: #ffffff; font-weight: bold; font-size: 18px; height: 30px;	}
		table tr td { vertical-align:top; padding: 5px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #ccc; font-size: 16px; text-align: left; }
		.td_1 { width: 100px; }		
		.td_4 { width: 240px; }
		template { display: none; }		
		.small_image { width: 110px; height: 90px; }
	</style>

	<template id="rows">
	<tr>
		<td class="td_1">{{field_1}}</td>
		<td>{{field_2}}</td>
		<td>{{field_3}}</td>
		<td class="td_4">
			{{field_4}}
			{{field_5}}
			{{field_6}}
			{{field_7}}
		</td>
	</tr>
	</template>
    <title>{{report_name}} | {{project_name}}</title>
</head>
<body>
    <h2>{{report_name}}</h2>	
</body>
</html>
