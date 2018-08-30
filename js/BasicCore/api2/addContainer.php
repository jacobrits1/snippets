<?php
require_once 'config_local.php';

$dataArray = array('con_number' => $postData->number,
                   'con_location' => $postData->location,
                   'con_gpslat' => $postData->gpsLat,
                   'con_gpslon' => $postData->gpsLon,
                   'con_send_office' => $postData->send_office,
                   'con_send_exporter' => $postData->send_exporter,
                   'con_puc_count' => $postData->puc_count,
                   'con_user_id' => $postData->user_id,
                   'con_created' => $postData->date_created);
rowInsert($databaseConnection,'container',$dataArray);
$result = rowSelect($databaseConnection,'container',"con_created ='".$postData->date_created."' AND con_number='".$postData->number ."'");

$con_id = 0;
$con_created = "000-00-00 00:00:00";
while ($row=mysqli_fetch_assoc($result)){
        $con_id = $row['con_id'];
        $con_created = $row['con_created'];
}

 foreach ($postData->puc  as $puc) {

   $filepathWeight = saveFile('weight',$con_id,$_FILES['weight_file']);
   $filepathPressure = saveFile('pressure',$con_id,$_FILES['pressure_file']);
   $filepathBrix = saveFile('brix',$con_id,$_FILES['brix_file']);
   $filepathTemperature = saveFile('temperature',$con_id,$_FILES['temperature_file']);
   $filepathInternal = saveFile('internal',$con_id,$_FILES['internal_file']);
   $filepathExternal = saveFile('external',$con_id,$_FILES['external_file']);

   $dataArray = array('puc_ref_con_id' => $con_id,
                      'puc_number' => $puc->number ,
                      'puc_fruit_type' => $puc->fruit_type ,
                      'puc_fruit_variety' => $puc->fruit_variety ,
                      'puc_weight' => $puc->weight ,
                      'puc_weight_grading' => $puc->weight_grading ,
                      'puc_weight_image_temp_name' => $filepathWeight ,
                      'puc_weight_image_orientation' => $puc->weight_image_orientation ,
                      'puc_pressure' => $puc->pressure ,
                      'puc_pressure_grading' => $puc->pressure_grading ,
                      'puc_pressure_image_temp_name' => $filepathPressure,
                      'puc_pressure_image_orientation' => $puc->pressure_image_orientation ,
                      'puc_brix' => $puc->brix ,
                      'puc_brix_grading' => $puc->brix_grading ,
                      'puc_brix_image_temp_name' => $filepathBrix,
                      'puc_brix_image_orientation' => $puc->brix_image_orientation ,
                      'puc_temperature' => $puc->temperature ,
                      'puc_temperature_grading' => $puc->temperature_grading ,
                      'puc_temperature_image_temp_name' => $filepathTemperature ,
                      'puc_temperature_image_orientation' => $puc->temperature_image_orientation ,
                      'puc_internal_image_temp_name' => $filepathInternal ,
                      'puc_internal_image_orientation' => $puc->internal_image_orientation ,
                      'puc_external_image_temp_name' => $filepathExternal,
                      'puc_external_image_orientation' => $puc->external_image_orientation ,
                      'puc_custom' => $puc->custom ,
                      'puc_custom_metric' => $puc->custom_metric ,
                      'puc_additional_comments' => $puc->additional_comments ,
                      'puc_overall_grading' => $puc->overall_grading ,
					            'puc_custom_grading' => $puc->custom_grading ,
                      'puc_date_created' => $con_created);

   rowInsert($databaseConnection,'puc_details',$dataArray);

 }

 $user = rowSelect($databaseConnection,'user',"use_id =".$postData->user_id);
 while ($row=mysqli_fetch_assoc($user)){
   $user_name = $row['use_name'];
   $user_surname = $row['use_surname'];
   $user_email = $row['use_email'];
   $user_cellphone = $row['use_cellphone'];

 }
 /*
 Mobilee Apps
 generate pdf file and store it in ../assets/uploads/{id}/report.pdf
 create email body with necesary details and attach it to email
 send email to selected parties
 */
 $pdfBody = buildPdf($postData);

 $pdf = generatePdf($con_id,$pdfBody);
 $attachments = array();
 $attachments[] = $pdf;

 $subject = 'Fruitflo Report - Container No :'.$postData->number;

 $body = ' Report Summary
           <br>Container No :'.$postData->number.'
           <br><br>Location : '.$postData->location.'
           <br>Agent Name : '.$user_name.' '.$user_surname.'
           <br>Agent Contact number : '.$user_cellphone.'
           <br>Date : '.$postData->date_created.'
           <br>Email : '.$user_email.'
           <br><br>FruitFlo System<br>
           <img src="http://fruitflo.medmin.co.za/app/assets/img/ffLogo.png">';

if($postData->send_office  == '1'){
    sendEmail($user_email,$subject,$body,$attachments);
    sendEmail('jaco@mobileeapps.com',$subject,$body,$attachments);
	  sendEmail('robkman@gmail.com',$subject,$body,$attachments);
}

if($postData->send_exporter  == '1'){
    sendEmail('jaco@mobileeapps.com',$subject,$body,$attachments);
}

sendEmail('jaco@mobileeapps.com',$subject,$body,$attachments);

exit(json_encode(['success' => 'Add Container With PUC']));


function buildPdf($postData){

   $gradingImage[0] = 	'../assets/img/blank64x64.png';
   $gradingImage[1] = 	'../assets/img/green64x64.png';
   $gradingImage[2] = 	'../assets/img/amber64x64.png';
   $gradingImage[3] = 	'../assets/img/red64x64.png';
   $image_width = '350px';
   $body = '<body style="font-family:sans-serif;">
   <div style="font-family:"Helvetica"!important; font-size:16px!important;margin:50px 100px;line-height:40px;width:100%;">
	<div>
		<p style="font-size:24px;font-weight:bold;margin:20px 0px;text-align:center;">QC Report </p>

		<p style="font-size:20px;font-weight:bold;margin:20px 0px;">Container Details </p>

		<div>Date & Time: <span style="font-weight:bold;">'.$postData->date_created.'</span></div>
		<div>Container Number: <span style="font-weight:bold;">'.$postData->number.'</span></div>
		<div>Nearest Location: <span style="font-weight:bold;">'.$postData->location.'</span></div>
	</div>


		<p style="font-size:20px;font-weight:bold;margin:20px 0px;">List of PUCs </p>';
		foreach ($postData->puc  as $puc) {
			$body .= '<div>'.$puc->number.'</div>';
		}


      foreach ($postData->puc  as $puc) {
      $body = $body.'
	  <div>
		<p style="font-size:16px;font-weight:bold;margin:50px 0px;text-decoration:underline;">PUC Summary: '.$puc->number.' </p>
			<div>Fruit type : '.$puc->fruit_type.'</div>
			<div>Fruit variety : '.$puc->fruit_variety.'</div>
			<div>&nbsp;</div>
		</div>

		<div style="width:100%;float:left;">
		<table>
			<tr>
				<td style="width:400px;">Weight : '.$puc->weight.' g</td><td><img class="selectImg" src="'.$gradingImage[($puc->weight_grading)].'" style="height:22px;"></td>
			</tr><tr>
				<td>Pressure : '.$puc->pressure.' g</td><td><img class="selectImg" src="'.$gradingImage[($puc->pressure_grading)].'" style="height:22px;"></td>
			</tr><tr>
				<td>Brix : '.$puc->brix.' °Bx </td><td><img class="selectImg" src="'.$gradingImage[($puc->brix_grading)].'" style="height:22px;"></td>
			</tr><tr>
				<td>Temperature : '.$puc->temperature.' °C</td><td><img class="selectImg" src="'.$gradingImage[($puc->temperature_grading)].'" style="height:22px;"></td>
			</tr><tr>
				<td>Other : '.$puc->custom.' '.$puc->custom_metric.' </td><td><img class="selectImg" src="'.$gradingImage[($puc->custom_grading)].'" style="height:22px;"></td>
			</tr>
		</table>
		</div>

		<div>&nbsp;</div>


			<div id="stepPreviewPucComments">Comments : </div>
			<div id="stepPreviewPucComments">'.$puc->additional_comments.'</div>


			<table>
				<tr>
					<td>
						<p style="font-size:16px;font-weight:bold;margin:50px 0px;">Overall Grading:&nbsp;&nbsp;&nbsp;</p>
					</td>
					<td>
						<img class="selectImg" src="'.$gradingImage[($puc->overall_grading)].'" style="height:22px;padding-top:0px;">
					</td>
				</tr>
			</table>

	<p style="font-size:16px;font-weight:bold;margin:50px 0px 20px 0px;text-decoration:underline;">#'.$puc->number.': Photos</p>


	<table>
	<tr><td style="width:300px;text-align:center;margin-right:20px;">
			<img src="'.$puc->weight_image_temp_data.'" style="max-width:'.$image_width.'; max-height:'.$image_width.'; height:auto;width:auto;border: 3px #274775 solid;border-radius: 10px;  margin:20px; /*transform: rotate('.$puc->weight_image_orientation.'deg);transform-origin: 50% 50%;*/" class="img-fluid z-depth-4 placeholderImg imgFrame" alt="1">';

			if (!$puc->weight_image_temp_data) $body.='<p id="imageLabel" class="white-text" style="border: 3px #274775 solid;border-radius: 10px;margin:20px;padding:20px;">No image selected</br></p>';
			$body.='<p id="imageLabel"class="white-text" style="margin:10px 20px;"><strong>Weight</strong></p>
		</td>
		<td>&nbsp;&nbsp;</td>
		<td style="width:300px;text-align:center;margin-right:20px;">
			<img src="'.$puc->pressure_image_temp_data.'" style="max-width:'.$image_width.'; max-height:'.$image_width.'; height:auto;width:auto;border: 3px #274775 solid;border-radius: 10px;margin:20px; /*transform: rotate('.$puc->pressure_image_orientation.'deg);transform-origin: 50% 50%;*/" class="img-fluid z-depth-4 placeholderImg imgFrame" alt="1">';

			if (!$puc->pressure_image_temp_data) $body.='<p id="imageLabel" class="white-text" style="border: 3px #274775 solid;border-radius: 10px;margin:20px;padding:20px;">No image selected</br></p>';
			$body.='<p id="imageLabel"class="white-text" style="margin:10px 20px;"><strong>Pressure</strong></p>
		</td>
	</tr>
	<tr>
		<td style="width:300px;text-align:center;margin-right:20px;">
			<img src="'.$puc->brix_image_temp_data.'" style="max-width:'.$image_width.'; max-height:'.$image_width.'; height:auto;width:auto;border: 3px #274775 solid;border-radius: 10px;margin:20px; /*transform: rotate('.$puc->brix_image_orientation.'deg);transform-origin: 50% 50%;*/" class="img-fluid z-depth-4 placeholderImg imgFrame" alt="1">';

			if (!$puc->brix_image_temp_data) $body.='<p id="imageLabel" class="white-text" style="border: 3px #274775 solid;border-radius: 10px;margin:20px;padding:20px;">No image selected</br></p>';
			$body.='<p id="imageLabel"class="white-text" style="margin:10px 20px;"><strong>Brix</strong></p>
		</td>
		<td>&nbsp;&nbsp;</td>
		<td style="width:300px;text-align:center;margin-right:20px;">
			<img src="'.$puc->temperature_image_temp_data.'" style="max-width:'.$image_width.'; max-height:'.$image_width.'; height:auto;width:auto;border: 3px #274775 solid;border-radius: 10px;margin:20px; /*transform: rotate('.$puc->temperature_image_orientation.'deg);transform-origin: 50% 50%;*/" class="img-fluid z-depth-4 placeholderImg imgFrame" alt="1">';

			if (!$puc->temperature_image_temp_data) $body.='<p id="imageLabel" class="white-text" style="border: 3px #274775 solid;border-radius: 10px;margin:20px;padding:20px;">No image selected</br></p>';
			$body.='<p id="imageLabel"class="white-text" style="margin:10px 20px;"><strong>Temperature</strong></p>
		</td>
	</tr>
	<tr>
		<td style="width:300px;text-align:center;margin-right:20px;">
			<img src="'.$puc->internal_image_temp_data.'" style="max-width:'.$image_width.'; max-height:'.$image_width.'; height:auto;width:auto;border: 3px #274775 solid;border-radius: 10px;margin:20px; /*transform: rotate('.$puc->internal_image_orientation.'deg);transform-origin: 50% 50%;*/" class="img-fluid z-depth-4 placeholderImg imgFrame" alt="1">';

			if (!$puc->internal_image_temp_data) $body.='<p id="imageLabel" class="white-text" style="border: 3px #274775 solid;border-radius: 10px;margin:20px;padding:20px;">No image selected</br></p>';
			$body.='<p id="imageLabel"class="white-text" style="margin:10px 20px;"><strong>Internal</strong></p>
		</td>
		<td>&nbsp;&nbsp;</td>
		<td style="width:300px;text-align:center;margin-right:20px;">
			<img src="'.$puc->external_image_temp_data.'" style="max-width:'.$image_width.'; max-height:'.$image_width.'; height:auto;width:auto;border: 3px #274775 solid;border-radius: 10px;margin:20px; /*transform: rotate('.$puc->external_image_orientation.'deg);transform-origin: 50% 50%;*/" class="img-fluid z-depth-4 placeholderImg imgFrame" alt="1">';

			if (!$puc->external_image_temp_data) $body.='<p id="imageLabel" class="white-text" style="border: 3px #274775 solid;border-radius: 10px;margin:20px;padding:20px;">No image selected</br></p>';
			$body.='<p id="imageLabel"class="white-text" style="margin:10px 20px;"><strong>External</strong></p>
		</td>
	</tr>
	</table>';
    }

   return $body.'</body>';

}

?>
