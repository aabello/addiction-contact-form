<?php
/**
 * Plugin Name: Addiction Contact Form
 * Plugin URI: http://bruceleedev.ninja/addiction-contact-form
 * Description: An addiction contact form for users with drug and alcohol problems seeking help.
 * Version: 1.0.0
 * Author: Andres Abello
 * Author URI: http://www.andresabello.com
 * License: GPL2. License in includes/license.txt
 */

/*Step 1: Register the database for the plugin*/
function pi_form_init() {
    $args = array(
      'public' => false,
      'label'  => 'Pi Forms'
    );
    register_post_type( 'pi_form', $args );
}
add_action( 'init', 'pi_form_init' );


/*Step 2: Require the script*/
function pi_form_scripts()
{	
	wp_enqueue_script('jquery');
	wp_enqueue_style( 'pi_forms_css', plugin_dir_url( __FILE__ ) . 'includes/css/form-styles.css', false, '1.0.0' );
	wp_enqueue_script( 'pi_forms_js', plugin_dir_url( __FILE__ ) . 'includes/js/pi-script.js');
}
add_action('wp_enqueue_scripts' , 'pi_form_scripts', 1);

function load_pi_forms_wp_admin_style() {
    wp_enqueue_style( 'pi_forms_admin_css', plugin_dir_url( __FILE__ ) . 'includes/css/admin-styles.css', false, '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'load_pi_forms_wp_admin_style' );

/**
 * Step 3: Creates the option page menu item for the admin menu
 *
 * @since 4.0.1
 */
function register_pi_forms_menu_page(){
    add_menu_page( 'Forms Report', 'Forms Performance', 'manage_options', 'pi_forms_menu', 'pi_forms_menu_page', '', 6 );
}
add_action( 'admin_menu', 'register_pi_forms_menu_page' );


/*The Page itself*/
function pi_forms_menu_page(){
	/*Get posts with post type pi_forms*/
	$args = array(
		'posts_per_page'   => -1,
		'offset'           => 0,
		'orderby'          => 'post_date',
		'order'            => 'DESC',
		'post_type'        => 'pi_form',
		'post_status'      => 'publish',
		'suppress_filters' => true 
	);
	$posts = get_posts($args);

	/*Start count at 1*/
	$count = 1;

	echo '<div class="pi-admin-show wrap">';
	echo '<h1 class="widefat">Form Reports: </h1><hr style="margin-bottom: 40px;">';
	/*Present the data*/



	foreach ($posts as $post) {



		$post_meta = get_post_meta( $post->ID );







		echo '<h3>Submission ' . $count . ':</h3>'; 



		echo '<ul class="pi-data-show">';



		



		foreach ($post_meta as $field => $value) {



			echo '<li>'. $field .': '. $value[0] .'</li>';



		}







		echo '</ul>';



		echo '<hr>';



		/*Increment the count only within this loop*/



		$count ++;



	}



	echo '</div>';







}







/**
 * Step 4: Create the widget for the contact form 
 *Pi Form widget class 
 *
 * @since 4.0.1
 */
class pi_form extends WP_Widget {




	/*Class Constructor and naming*/
	function __construct() {
		parent::__construct(
			// Base ID of your widget
			'pi_form', 
			// Widget name will appear in UI
			'Addiction Contact Form', 
			// Widget description
			array( 'description' => 'Addiction Contact Form for clients seeking help.'  ) 
		);
	}

	public function widget( $args, $instance ) {
		/*Variables*/
		/**
		 * Filter the content of the Pi Form widget title.
		 *
		 * @since 4.0.1
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$title = apply_filters( 'widget_title', $instance['title'] );

		/**
		 * Filter the content of the Pi Form widget description.
		 *
		 * @since 4.0.1
		 *
		 * @param string    $widget_text The widget content.
		 * @param WP_Widget $instance    WP_Widget instance.
		 */
		$description = apply_filters( 'widget_text', empty( $instance['description'] ) ? '' : $instance['description'], $instance );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		?>
		<div id="pi-form">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">

				<?php

				

				/*Image*/

				echo '<img src="' . plugins_url( 'includes/css/email.png', __FILE__ ) . '" > '; 

				if ( ! empty( $title ) )



				echo $args['before_title'] . $title . $args['after_title'];

				echo $description;

				?>				

				

				<!-- Name -->

				<label for="pi_name">Name:</label>

				<input type="text" name="pi_name">

				

				<!-- Phone -->

				<label for="pi_phone">Phone number:</label>

				<input type="text" name="pi_phone">



				<!-- Email -->

				<label for="pi_email">Email:</label>

				<input type="email" name="pi_email">



				<!-- Treatment for -->

				<label for="pi_select">Seeking treatment for:</label>

				<select name="pi_select">

					<option value="Choose an option">Choose an option</option>

					<option value="Addicted person’s spouse / significant other">Addicted person’s spouse / significant other</option>

					<option value="Addicted person’s mother">Addicted person’s mother</option>

					<option value="Addicted person’s father">Addicted person’s father</option>

					<option value="Addicted person’s grandparent">Addicted person’s grandparent</option>

					<option value="Addicted person’s brother/sister">Addicted person’s brother/sister</option>

					<option value="Addicted person’s family">Addicted person’s family</option>

					<option value="Addicted person’s friend">Addicted person’s friend</option>

					<option value="Self">Self</option>

					<option value="Other">Other</option>

				</select>



				<!-- Drug of Coice -->

				<label for="pi_choice">Drug of choice:</label>

				<input type="text" name="pi_choice">



				<!-- Time using drug -->

				<label for="pi_time">How long have you been using?</label>

				<input type="text" name="pi_time">



				<!-- Insurance -->

				<fieldset>

					<label for="pi_insurance">Do you have Insurance?</label><input type="radio" name="pi_insurance" value="yes">Yes &nbsp; <input type="radio" name="pi_insurance" value="no">No					

				</fieldset>



				<fieldset>

					<label for="pi_treatment">Have you been in treatment before?</label><input type="radio" name="pi_treatment" value="yes">Yes &nbsp; <input type="radio" name="pi_treatment" value="no">No					

				</fieldset>



				<label for="pi_message">Questions or comments:</label>

				<textarea name="pi_message" class="widefat"></textarea>				



				<!-- Submit -->

				<button id="pi-submit" name="pi-submit" type="submit">Take the First Step</button>



				<div class="info">We respect your <a href="<?php home_url();?>privacy-policy/">privacy</a>.<br>

				All information provided is confidential.</div>



			</form>		



		</div>



		<?php



		// This is where you run the code and display the output



		echo $args['after_widget'];



	}



			



	// Widget Backend 



	public function form( $instance ) {



		/*Title*/



		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'description' => '') );



			



		$title = strip_tags($instance[ 'title' ]);





		/*Description*/	



		$description = esc_textarea($instance['description']);







		// Widget admin form



		?>



		<!-- Title field -->



		<p>



			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 



			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />



		</p>
		<!-- Desctiption field -->
		<p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description before form (HTML enabled):' ); ?></label> 
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" rows="10"><?php echo $description; ?></textarea>
		</p>
		<?php 
	}
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/*Title of the form*/
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		/*Description*/		
		if ( current_user_can('unfiltered_html') )
			$instance['description'] =  $new_instance['description'];
		else
			$instance['description'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['description']) ) ); // wp_filter_post_kses() expects slashed
		/*Return the instances*/
		return $instance;
	}
} // Class pi_form ends here

/*Register and load widget*/
function pi_load_widget() {
	register_widget( 'pi_form' );
}
add_action( 'widgets_init', 'pi_load_widget' );

/*Allow html in emails*/
function pi_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','pi_set_content_type' );

/*Step 4: Send email, store data, and present data. Also, auto respond and send to other database*/
function pi_send_form(){
	/*Get ip information from user*/
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	/*Get page url*/
	$url = home_url();

	/*Time*/
	$current_time = time(); 
	
	/*Start new form class to get widgets information*/
	$pi_form_class = new pi_form();
	$settings = $pi_form_class->get_settings();

	/*Start Session*/
	session_start();
	if(isset($_POST['pi-submit'])){
		$to = 'helpline@fordetox.com';
	$subject = home_url() . ' Contact Form';

	/*Variables from Form*/
$name = $_POST['pi_name'];
$phone = $_POST['pi_phone'];
$email = $_POST['pi_email'];
$option = $_POST['pi_select'];
$choice = $_POST['pi_choice'];
$pi_time = $_POST['pi_time'];
$insurance = $_POST['pi_insurance'];
$treatment = $_POST['pi_treatment'];
$questions = $_POST['pi_message'];

		if (strpos($questions,'href') !== false || strpos($name,'href') !== false || strpos($phone,'href') !== false || strpos($choice,'href') !== false || strpos($pi_time,'href') !== false || strpos($insurance,'href') !== false || strpos($treatment,'href') !== false ){

			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				echo "Email is not Valid!";
			}
			?>
			<script  type="text/javascript">
				alert('No links allowed!');
			</script>
			<?php
		}else{
	 		$message  = 'Name: ' . $name . '<br>';
		   	$message .= 'Phone: ' . $phone . '<br>'; 
		    $message .= 'Email: ' . $email . '<br>';
		    $message .= 'Select: ' . $option . '<br>';
		    $message .= 'Drug of Choice: ' . $choice . '<br>';
		    $message .= 'Time using drug: ' . $pi_time . '<br>';
		    $message .= 'Insurance: ' . $insurance . '<br>';
		    $message .= 'Treatment: ' . $treatment . '<br>';	
		    $message .= 'Message: ' . $questions ;
		    $headers[] = 'From: ' . home_url() . '<helpline@fordetox.com>';
			// $headers[] = 'bcc: vdomains1@gmail.com';

			// $headers[] = 'bcc: newimage100@aol.com';

			// $headers[] = 'bcc: pbrooke@wstreatment.com';

		    /*Send Email*/
		    wp_mail( $to, $subject, $message, $headers );

		    /*Insert Post with Post Meta*/
		    $pi_post = array(
				'post_title'    => $name . 'contact form',
				'post_content'  => $message,
				'post_status'   => 'publish',
				'post_type'		=> 'pi_form'
			);
			/*Insert the post while getting the id*/
			$post_id =  wp_insert_post( $pi_post );

			/*Update the meta to the database*/
			add_post_meta( $post_id, 'name', $name, true);
			add_post_meta( $post_id, 'phone', $phone, true);
			add_post_meta( $post_id, 'email', $email, true);
			add_post_meta( $post_id, 'select', $option, true);
			add_post_meta( $post_id, 'choice', $choice, true);
			add_post_meta( $post_id, 'time', $pi_time, true);
			add_post_meta( $post_id, 'insurance', $insurance, true);
			add_post_meta( $post_id, 'treatment', $treatment, true);
			add_post_meta( $post_id, 'questions', $questions, true);
			add_post_meta( $post_id, 'ip', $ip, true);
			add_post_meta( $post_id, 'current_time', $current_time, true);
			add_post_meta( $post_id, 'url', $url, true);
			if( $insurance === "no"){
				$insurance = false;
			}
			else{
				$insurance = true;
			}
			if( $treatment === "no"){
				$treatment = false;
			}
			else{
				$treatment = true;
			}
			
			//Send Form to Reports
			$username = 'Bh2P64xc30Ojq51NaXBvgWzDpzrqkHyd';
			$password = 'goleador7';
			$pi_url = 'http://formresults123.com/v1/forms';
			$method = 'POST';
			$data = array(
				'name'		=> $name, 
				'phone'		=> $phone, 
				'email'		=> $email, 
				'person' 	=> $option,
				'drug'		=> $choice, 
				'time'		=> $pi_time, 
				'insurance' => $insurance, 
				'treatment'	=> $treatment, 
				'comment'	=> $questions, 
				'ip' 		=> $ip, 
				'url' 		=> $url, 
				'sent'		=> $current_time 	
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $pi_url);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
			curl_setopt($ch, CURLOPT_USERPWD,"$username:$password"); 
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			curl_exec($ch);

		    //Redirect User to homepage
			header('Location: ' . home_url() . '');
		}
	}
}
add_action('init', 'pi_send_form');
