<html>
<head>
	<meta charset="UTF-8" />
	<style>
		body { font-family: helvetica; font-size:12px; }
		.design_wrapper { width: 1024px; position: relative; margin: 0 auto; text-align: center; padding: 20px 0; margin-bottom: 30px; }
		#design_wrapper h2 { text-align: center; font-size: 22px; }
        .design_wrapper_table { width: 100%; border: 1px solid #000000; border-spacing:0; position:relative; margin:0 auto; vertical-align: top; }
		.design_wrapper_table th { background-color: #002a80; color: #ffffff; font-weight: bold; font-size: 18px; height: 30px; }
        table tr td { vertical-align:top; padding: 5px; padding-left: 10px; padding-top: 3px; padding-bottom: 10px; border-bottom: 1px solid #ccc; font-size: 16px; text-align: left; }
        .top_design { position: relative; border: none; font-size: 16px; font-weight: bold; color:#d3d3d3; margin-top: 15px; }
        .left_design{ text-align: left; border-right: none; border-bottom: none !important; }
        .right_design{ text-align: right; border-right: none !important; border-bottom: none; }
        .design_wrapper_table .td_1 { width: 180px; }
		.design_wrapper_table .td_2 { width: 520px; }
		.design_wrapper_table .td_4 { width: 255px; top: 5px; }
		.design_wrapper_table .td_5 { width: 300px; }
		template { display: none; }
		.small_image { width: 110px; height: 90px; }
	</style>
    <template id="not_found1">
	<tr>
		<td colspan="4">No data found!</td>
	</tr>
    </template>
	<template id="rows">
	<tr>
		<td class="td_1">{{field_1}}</td>
		<td class="td_2">{{field_2}}</td>
		<td class="td_4">
            {{field_3}}
			{{field_4}}
			{{field_5}}
			{{field_6}}
		</td>
	</tr>
	</template>
    <title>{{report_name}} | {{project_name}}</title>
</head>
<body>
	<div class="design_wrapper">
        <table class="top_design design_wrapper_table">
            <tr >
                <td class="left_design">{{project_created}}</td>
                <td class="right_design"></td>
            </tr>
        </table>
		<img src="{{logo_path}}" alt="Logo" />
		<h2>{{report_name}}</h2>
        <h3>[ {{client_address}} ]</h3>
		<table class="design_wrapper_table">
		<thead>
		<tr>			
			<th class="td_1">Name</th>
			<th class="td_2">Information</th>
			<th class="td_4">Images</th>
		</tr>
		</thead>
		<tbody>
		{{rows_content}}
		{{not_found1}}
		</tbody>
		</table>
    </div>
    {{fancybox_scripts}}
</body>
</html>
