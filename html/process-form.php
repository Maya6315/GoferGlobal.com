<?php
$showForm   = true;
$error      = false;
$name       = '';
$email      = '';
$phone      = '';
$message    = '';

function clean_email($input) 
{
    if (preg_match("/cc:/i", $input) ||
        preg_match("/bcc:/i", $input) ||
        preg_match("/from:/i", $input) ||
        preg_match("/to:/i", $input) ||
        preg_match("/content-type:/i", $input) ||
        preg_match("/header/i", $input) ||
        preg_match("/mime-Version:/i", $input)) 
    {
        return false;
    } 
    else 
    {
        return true;
    }
}

function render_message( $name, $phone, $email, $message )
{
    ob_start();
    include dirname( __FILE__ ).'/mail-body.phtml';
    return ob_get_clean();
}

if(isset($_POST['submit'])) 
{
    $name       = filter_input( INPUT_POST, 'name' );
    $email      = filter_input( INPUT_POST, 'email' );
    $phone      = filter_input( INPUT_POST, 'phone' );
    $message    = filter_input( INPUT_POST, 'message' );
    
    if( $name && $email && $phone && $message && clean_email($email) == true )
    {
        $mailSubject = $name.' יצר איתך קשר';
        $mailBody = render_message(
            $name,
            $phone,
            $email,
            $message
        );

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: '.$name.' <'.$email.'>' . "\r\n";
        $headers .= 'From: WebDesk.co.il <contact@webdesk.co.il>' . "\r\n";
        $headers .= 'Cc: '.$email . "\r\n";
        $headers .= 'Bcc: copy@webdesk.co.il' . "\r\n";
        mail($rottenbergmaya@gmail.com,$mailSubject,$mailBody,$headers);
        $showForm = false;
    }
    else 
    {
        $error = true;
    }

<?php if($showForm): ?>
<!-- כאן נכנס הקוד של הטופס -->
    <?php if($error): ?>
        <div class="error">
            <p>עליך למלא את כל השדות!</p>
        </div>
    <?php endif ?>
<?php else: ?>
    <!-- כאן נכנס הקוד של הודעת ההצלחה -->
    <h1>ההודעה נשלחה בהצלחה!</h1>
    <p>שם מלא: <?php echo $name; ?></p>
    <p>טלפון: <?php echo $phone; ?></p>
    <p>דוא"ל: <?php echo $email; ?></p>
    <p>תוכן ההודעה: <?php echo $message; ?></p>
<?php endif ?>
}