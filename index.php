<?php

require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';	

use \Firebase\JWT\JWT;

class Zoom_Api {

		private $zoom_api_key = 'gcHrZJyVQ1q-gxB2VC1UMw';

		private $zoom_api_secret = 'iLE5VOc8KVLanJK9J1Cj91rEzoH2ErZbB2Yx';

		

		protected function sendRequest($data) {

            $request_url = 'https://api.zoom.us/v2/users/me/meetings';

            $headers = array(

				
					'authorization: Bearer'.$this->generateJWTKey(),
					'content-type: application/json'
            );

           $postFields = json_encode($data);

		   $ch = curl_init();

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

			curl_setopt($ch, CURLOPT_URL, $request_url);

			curl_setopt($ch, CURLOPT_POST, 1);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

			$response = curl_exec($ch);

			$err = curl_error($ch);

			curl_close($ch);

			if(!$response){

				return $err;

			}

			return json_decode($response);

		}
		
		
		public function getMeetings($page_size, $page_number) {
			$request_url = "https://api.zoom.us/v2/users/me/meetings?page_size=".$page_size."&page_number=".$page_number;

            $headers = array(
					'authorization: Bearer'.$this->generateJWTKey(),
					'content-type: application/json'
            );
            
            
            $ch = curl_init();

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

			curl_setopt($ch, CURLOPT_URL, $request_url);

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

			$response = curl_exec($ch);
			
			curl_close($ch);

			if(!$response){

				return $err;

			}

			return json_decode($response);
		}


		public function getMeetingDetails($meeting_id) {
			$request_url = "https://api.zoom.us/v2/meetings/".$meeting_id;

            $headers = array(
					'authorization: Bearer'.$this->generateJWTKey(),
					'content-type: application/json'
            );
            
            
            $ch = curl_init();

			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

			curl_setopt($ch, CURLOPT_URL, $request_url);

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

			$response = curl_exec($ch);
			
			curl_close($ch);

			if(!$response){

				return $err;

			}

			return json_decode($response);
		}



		//function to generate JWT

        private function generateJWTKey() {

            $key = $this->zoom_api_key;

            $secret = $this->zoom_api_secret;

            $token = array(

                "iss" => $key,

                "exp" => time() + 3600 //60 seconds as suggested

            );

            return JWT::encode( $token, $secret );

        }

		

		public function createAMeeting( $data = array() ) {

            $post_time  = $data['start_date'];

			$start_time = date( "Y-m-d\TH:i:s", strtotime( $post_time ) );

            $createAMeetingArray = array();

            if ( ! empty( $data['alternative_host_ids'] ) ) {

                if ( count( $data['alternative_host_ids'] ) > 1 ) {

                    $alternative_host_ids = implode( ",", $data['alternative_host_ids'] );

                } else {

                    $alternative_host_ids = $data['alternative_host_ids'][0];

                }

            }

            $createAMeetingArray['topic']      = $data['meetingTopic'];

            $createAMeetingArray['agenda']     = ! empty( $data['agenda'] ) ? $data['agenda'] : "";

            $createAMeetingArray['type']       = ! empty( $data['type'] ) ? $data['type'] : 2; //Scheduled

            $createAMeetingArray['start_time'] = $start_time;

            $createAMeetingArray['timezone']   = 'Africa/Johannesburg'; // $data['timezone'];

            $createAMeetingArray['password']   = ! empty( $data['password'] ) ? $data['password'] : "";

            $createAMeetingArray['duration']   = ! empty( $data['duration'] ) ? $data['duration'] : 60;

            $createAMeetingArray['settings']   = array(

                'join_before_host'  => true,

                'host_video'        => ! empty( $data['option_host_video'] ) ? true : false,

                'participant_video' => ! empty( $data['option_participants_video'] ) ? true : false,

                'mute_upon_entry'   => ! empty( $data['option_mute_participants'] ) ? true : false,

                'enforce_login'     => ! empty( $data['option_enforce_login'] ) ? true : false,

                'auto_recording'    => ! empty( $data['option_auto_recording'] ) ? $data['option_auto_recording'] : "none",

                'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""

            );
			
			//echo '<pre>'; print_r( $data);print_r( $createAMeetingArray);die;

            return $this->sendRequest($createAMeetingArray);

        }



	}

 	 $zoom_meeting = new Zoom_Api();

 if($_POST['meeting_name'] && $_POST['meeting_scheduled_date']){
 	 $meeting_name = $_POST['meeting_name'];
 	 $meeting_scheduled_date = $_POST['meeting_scheduled_date'];

		try{
		if($meeting_name !='' && $meeting_scheduled_date!=''){
			$z = $zoom_meeting->createAMeeting(
				array(
					//'start_date'=>date("Y-m-d h:i:s", strtotime('tomorrow')),
					'start_date'=>$meeting_scheduled_date,
					'meetingTopic'=>$meeting_name
				)
			);
			echo $z->message;
		}
		



		} catch (Exception $ex) {
			echo $ex;
		}

 }
 
 try{
 	 	
		$meeting_details = $zoom_meeting->getMeetings(20, 1);
		

		$page_count = $meeting_details->page_count;
		$page_number = $meeting_details->page_number;
		$page_size = $meeting_details->page_size;
		$meetings = $meeting_details->meetings;

		//echo '<pre>'; print_r($meetings);die;
		foreach($meetings as $val){
			$val->status = $zoom_meeting->getMeetingDetails($val->id)->status;
		}
	} catch (Exception $ex) {

			echo $ex;

		}
?>


<!doctype html>
<html>

<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</head>
<body>
	
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8">
		<form method="post" action="">
		  <div class="form-group">
		    <label for="exampleInputEmail1">Meeting Name</label>
		    <input type="text" class="form-control" id="meeting_name" name="meeting_name" aria-describedby="emailHelp" placeholder="Enter Meeting name">
		  </div>
		  <div class="form-group">
		    <label for="exampleInputPassword1">Meeting scheduled date</label>
		    <input type="text" class="form-control" id="meeting_scheduled_date" name="meeting_scheduled_date" placeholder="2020-05-07 01:00:00">
		  </div>
		  <button type="submit" id="create_meeting" class="btn btn-primary">Create</button>
		</form>
		</div>
		<div class="col-md-2">
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8">
			Meetings List
		</div>
		<div class="col-md-2">
		</div>
	</div>	
	<div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8">
			<table class="table">
				<tr>
					<td>
						Meeting Name
					</td>
					<td>
						Meeting Number
					</td>
					<td>
						Meeting Scheduled
					</td>
					<td>
						Meeting Created
					</td>
					<td>
						Meeting Status
					</td>
					<td>
						Action
					</td>
				</tr>
				<?php foreach($meetings as $val) { ?>
					<tr>
						<td>
							<?php echo $val->topic;?>
						</td>
						<td>
							<?php echo $val->id;?>
						</td>
						<td>
							<?php echo $val->start_time;?>
						</td>
						<td>
							<?php echo $val->created_at;?>
						</td>
						<td>
							<?php echo $val->status;?>
						</td>
						<td>
							<form method="post" action="https://dev.solsoft.co.za/dev2/public/zoom/meeting.php">
								<input type="hidden" name="meeting_status" value="<?php echo $val->status;?>" />
 								<input type="hidden" name="meeting_id" value="<?php echo $val->id;?>" />
								<?php if($val->status == 'waiting') { ?>
									<button type="submit" class="btn btn-success start_meeting" rel="<?php echo $val->id;?>">Start</button>
								<?php } else if($val->status == 'started') { ?>
									<button type="submit" class="btn btn-warning join_meeting" rel="<?php echo $val->id;?>">Join</button>
								<?php } ?>
							</form>
							
 							
							
						</td>
					</tr>
					
				<?php } ?>
				
			
		</div>
		<div class="col-md-2">
		</div>
	</div>
		
		


</body>
</html>