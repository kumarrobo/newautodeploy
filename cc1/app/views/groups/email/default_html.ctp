<?php if($params['id'] != -1){ ?>
<table cellpadding="0" cellspacing="0" width="640px">
	<tr>
    	<td style="border:10px solid #0067a9; padding:20px 40px;font-family:Georgia, 'Times New Roman', Times, serif;color:#363636; ">
        	<div style="font-size:24px; text-align:center; padding-bottom:18px;">"A brand new way to stay update"</div>
            <div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">Dear Potential User,</div>
            <div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">SMSTadka.com, this is what I am trying to sell you. Give <strong>exactly 38 seconds</strong> to read this email to understand how SMSTadka.com can help you to make your life better (at least that's what we think).</div>
	    <div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">Ok let's keep it short.</div>

<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">How about really funny Indian <strong>Jokes, Shayaries</strong> and <strong>One-liners on SMS</strong> while at work?</div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">How about <strong>Breaking News on SMS</strong> as soon as it happens? Word cup is just 2 weeks away. Remain update with the <strong>Cricket Live score on SMS</strong> in just <strong>Rs 15 a month?</strong></div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">Well it's just a small introduction; we have <strong>SMS alerts</strong> for more than <strong>150 exciting topics</strong> including <strong>Daily NEWS, Romantic Quotes, Bollywood News/Gossip, Movie reviews, Celebrity Twitter alerts, Beauty and Health tips </strong> and a lot more. </div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">Explore <a href="<?php echo "/main.php?eid=" . $params['id']; ?>" style="color:#425bf4; font-weight:bold;">www.smstadka.com</a> to find more. Start your trial with Rs 20 Free Credits.</div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">You might be thinking it would be great if I get a chance to personalized my SMS Info-alerts. SMSTadka offers you <strong>&quot;First time in India&quot; The Personalized SMS Alert Service</strong>.</div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">Get  <strong>instant NEWS alerts for your preferred stock</strong>? Cool, isn't it? You can setup your alerts on stocks, personalised reminders, PNR alerts and lot more to come.</div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">Trying SMSTadka <a href="<?php echo "/main.php?eid=" . $params['id']; ?>" style="color:#425bf4; font-weight:bold;">www.smstadka.com</a> is very simple and easy (less than 10 sec process).  <br />
<a href="<?php echo "/main.php?eid=" . $params['id']; ?>" style="color:#425bf4; font-weight:bold;">Signup Now</a> and get Rs 20 Trial Credits FREE. </div>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; padding-bottom:18px;">I hope I've sold it well to you.</div>

	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
    	<td style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; ">Thanks and regards,<br />
			<?php echo FROM_NAME; ?></td><td style="text-align:right;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px;"><a href="<?php echo "/main.php?eid=" . $params['id']; ?>"><img height="60" src="/img/logo_new.png?211" alt="SMSTadka" title="SMSTadka" /></a></td>
            </tr>
           
        </table>
        </td>
    </tr>
</table>
<?php } else { echo $params['body']; } ?>