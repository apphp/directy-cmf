<html>
<head>
	<meta charset="UTF-8" />
	<style>
		body { font-family: helvetica; font-size:12px; }		
		.project_wrapper { width: 1024px; position: relative; margin: 0 auto; text-align: center; padding: 20px 0; margin-bottom: 30px; }
		h2 { text-align: center; font-size: 22px; }
		.project_table { width: 100%; border: 1px solid #000000; border-spacing: 0; position: relative; margin: 0 auto;	vertical-align: top; }
		table th { font-weight: bold; font-size: 14px; height: 30px; border-bottom: 1px solid black; border-right: 1px solid black; }
		table tr td { vertical-align:top; padding: 5px; padding-left: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 1px solid #ccc; font-size: 12px; text-align: left; }
        table th:last-child { border-right: none; }
        table td { border-right: 1px solid #000000; }
        table td:last-child { }
        .top_design { position: relative; border: none; font-size: 16px; font-weight: bold; color:#d3d3d3; margin-top: 15px; }
        .left_design { text-align: left; border-right: none; border-bottom: none !important; }
        .right_design { text-align: right; border-right: none !important; border-bottom: none; }
        .left { text-align: left; padding-left: 10px; }
        .center { text-align: center; }
        .num { text-align: right; }
        .project_table .td_1 { width: 100px; }
        .project_table .td_2 { width: 225px; font-weight: bold; }
        .spacer { height: 50px; }
        .project_table .td_3 { width: 80px; }
        .project_table .td_4 { width: 100px; }
        .project_table .td_5 { width: 470px; }
        .project_table .td_6 { width: 110px; }
        .project_table .td_7 { width: 130px; }
        .project_table .td_w { width:auto; }
        .project_table .td_ww { width:790px; }
		template { display: none; }
        .header { text-decoration: underline; font-style: italic; }		
		.small_image { width: 110px; height: 90px; }
	</style>
	<template id="rows">
		<tr>
			<td class="td_7 center" >{{field_1}}</td>
			<td class="td_1 num">{{field_2}}{{field_2_append}}</td>
			<td class="td_4 num">{{field_3_prepend}}{{field_3}}</td>
			<td class="td_1 num">{{field_4}}{{field_4_append}}</td>
			<td class="td_4 num">{{field_5_prepend}}{{field_5}}</td>
			<td class="td_6 ">{{field_6}}</td>
			<td class="td_4 num">{{field_7_prepend}}{{field_7}}</td>
			<td class="td_4 num">{{field_8_prepend}}{{field_8}}</td>
			<td class="td_4 num">{{field_9_prepend}}{{field_9}}</td>
            <td class="td_8">{{field_10}}</td>
		</tr>
	</template>

	<template id="comments">
        <tr>
            <td class="td_1 center">{{comment_date}}</td>
            <td class="td_5">{{comment_text}}</td>
            <td class="td_3">{{image_1}}</td>
            <td class="td_3">{{image_2}}</td>
            <td class="td_3">{{image_3}}</td>
            <td class="td_3">{{image_4}}</td>
        </tr>
    </template>
    <template id="not_found">
        <tr>
            <td colspan="3">No comments found!</td>
        </tr>
    </template>
    <template id="not_found1">
        <tr>
            <td colspan="5">No data found!</td>
        </tr>
    </template>
    <title>{{report_name}} | {{project_name}}</title>
</head>
<body>
	<div class="project_wrapper">
        <table class="top_design project_table">
            <tr >
                <td class="left_design">{{project_created}}</td>
                <td class="right_design"></td>
            </tr>
        </table>

		<h2 class="header">{{report_name}}</h2>

        <table class="project_table">
            <tr>
                <td class="td_2">Client name:</td>
                <td class="td_w">{{client_name}}</td>
            </tr>
            <tr>
                <td class="td_2" >Address:</td>
                <td class="td_w">{{client_address}}</td>
            </tr>
            <tr>
                <td class="td_2" >Email:</td>
                <td class="td_w">{{client_email}}</td>
            </tr>
            <tr>
                <td class="td_2" >Phone:</td>
                <td class="td_w">{{client_phone}}</td>
            </tr>
            <tr>
                <td class="td_2">Project Management Price:</td>
                <td class="td_w">{{currency}}{{project_manage_price}}</td>
            </tr>
            <tr>
                <td class="td_2">Project Design Price:</td>
                <td class="td_w">{{currency}}{{project_design_price}}</td>
            </tr>
            <tr>
                <td class="td_2">Total Project Price:</td>
                <td class="td_w">{{currency}}{{project_price}}</td>
            </tr>
        </table>
		<div class="spacer"></div>

		<table class="project_table">
		<thead>
		<tr>			
			<th class="td_7 center">Date</th>
            <th class="td_1">Percent of <br/> Management<br/> Price</th>
            <th class="td_4">Management <br/> Price due</th>
            <th class="td_1">Percent of <br/> Design Price</th>
            <th class="td_4">Design Price<br/> due</th>
			<th class="td_6 left">Milestone</th>
			<th class="td_4">Amount due <br/>(not incl. VAT)</th>
			<th class="td_4">Paid <br/>(incl. VAT)</th>
			<th class="td_4">Balance</th>
			<th class="td_8 center">Video Link</th>
		</tr>
		</thead>
		<tbody>
		{{rows_content}}
        {{not_found1}}
		</tbody>
		</table>
		
		<div class="spacer"></div>
        <table class="project_table">
			<tr>
				<th class="td_1">Date</th>
				<th class="td_5">Comments</th>
				<th colspan="4" class="td_w">Images</th>
			</tr>
            <tbody>
            {{comments_content}}
            {{not_found}}
            </tbody>
        </table>
    </div>
    {{fancybox_scripts}}
</body>
</html>
