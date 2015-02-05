<?require_once "dbFunctions.php" ?>
<html>
<head>
<title>Festival Update Help</title>

<script language="JavaScript">

<!--

function MM_openBrWindow(theURL,winName,features) { //v2.0

  window.open(theURL,winName,features);

}

//-->

</script>
<link rel="stylesheet" href="styles/info.css" type="text/css">
</head>
<body bgcolor="#222222" text="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#000000">
    <td class="popupheading">Before You Begin</td>
  </tr>
    <tr bgcolor="#000000">
    <td class="description">
      <p><font size="2">Please take a moment to review the following list of specific
        information we will need about your festival. Be sure to fill out as much
        info as possible, even if your website answers it all. Keep in mind, the
        information you enter here will be used by filmmakers to find your festival
        and decide if it is appropriate, so make your Festival Profile as complete
        as possible.
				<br><br>
				<center><a style="color: #FF0000" href="login.php<?= makeRequestString() ?>">
					I'm ready to start entering my information</a></center>
      </td>
  </tr>
  <tr>
    <td align="center"><table width="50%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><br><br><p class="description"><a name="page1"></a><span class="popupheading">Title</span><br>
  Please enter the exact title of the festival. Do not use words like &quot;a&quot;
  or &quot;the&quot; in the beginning of the title. Also avoid using abbreviations,
  extraneous punctuation, or any kind of numbers, dates, or years in the title.<br>
  <span class="details">Bad title: &quot; The 2nd Annual NY Int'l Independent
  Film &amp; Video Festival, in LA, Feb .2002 &quot;</span><br>
  <span class="details">Good title &quot;New York International Independent Film
  and Video Festival, The&quot;</span></p>
<p class="description"><span class="popupheading">Venue</span><br>
Please provide as much information as possible about where the films will be played.
</p>
<p class="description"><span class="popupheading">Organization</span><br>
  Please provide the name, address, and telephone number by which people can contact
  the festival and submit their films. </p>
<p class="description"><span class="popupheading">Submissions</span><br>
  Although some festivals do not accept film submissions, like the Maryland Film Fest, which is "by invite only", we would like those festivals to update as much of their information as possible to ensure that we have the most complete database of fests. </p>
<p class="description"><span class="popupheading">Web</span><br>
<UL class="description">
  <LI>Email: the email of the primary contact for the festivals through whom more
    information can be requested.</LI>
  <LI>URL: Full Web address of the Festival's website including &quot;http://&quot;.</LI>
  <LI>Logo URL: Please enter the full web address of the Festival's logo (if available),
    including "http://". Ideally the logo should be as close to a square as possible, and in JPEG format, not exceeding
    200 pixels in either dimension.<br>
    <span class="details">Example: "http://www.filmdevil.com/images/logo.jpg"</span>
  </LI>

  <LI>Application URL: Full Web address of the Festival's submission application
    (PDF, DOC, HTML, TEXT formats, etc.).<br>
	<span class="details">Example: "http://www.filmdevil.com/images/submissionform.doc"</span></LI>
</UL>

<p class="description"><a name="page2"></a><span class="popupheading">Dates</span><br>
  Please enter the next start and end date of the Festival. </p>
  <p class="description"><span class="popupheading">Submission Deadlines</span><br>
  Please enter the early, normal, and late submission deadlines. It is required that you enter the normal deadline. The early and late ones are optional and can be left blank. Make sure to fill out the month, day, and year on each line. If one of the deadlines doesn't exist, make sure it's blank.</p>
   <p class="description"><span class="popupheading">Statistics</span><br>
<UL class="description">
  <LI>Please enter the number of films submitted to the festival last year</LI>
  <LI>Please enter the number of official selections (films that were actually shown last year)</LI>
  </UL>

<p class="description"><span class="popupheading">Prizes / Competition</span><br>
  Please describe in as much detail as possible the kinds of prizes and/or awards
  that are given out in each category of competition. Prizes may include, cash,
  equipment, scholarships, gift certificates, etc.</p>
   <p class="description"><a name="page3"></a><span class="popupheading">Theme</span><br>
Please describe in a few sentences the festival's theme for this year. Please leave it blank if the festival has no theme and accepts films about any subject matter.</p>

<p class="description"><span class="popupheading">Tagline</span><br>
  Please provide your festival's slogan (25 words or less).<br>
  <span class="details">Example: "World's Friendliest Film Festival"</span><br>
</p>
<p class="description"><span class="popupheading">Description</span><br>
  Please describe the festival in as much detail as possible. Include things like
  when the festival was founded, what its main purpose is, what types of films
  and audiences it caters to, etc.</p>
<p class="description"><span class="popupheading">Professional Description</span><br>
Please address the concerns of professional filmmakers applying to your festival regarding competition, prizes, rules, limitations, etc. Also answer questions like: "Will investors, distributors, or studio representatives be attending, and how can I get them to attend my screening, and network with them?", "What kind of distribution deals have past films gotten?"</p>
   <p class="description"><span class="popupheading">Student Description</span><br>
  Please address the concerns of student and amateur filmmakers applying to your
  festival regarding discounts, student categories, and special considerations,
  etc. Also answer questions like the following: <span class="details">"How many
  college filmmakers get in or attend this festival?", "My film caters to a college
  audience, how young will the screening committee be?", "I couldn't afford Tom
  Hanks or big explosions, by what criteria are you going to judge my low budget
  student film?", &quot;How easy is it to network with other students?&quot;,
  etc.</span></p>

<p class="description"><span class="popupheading">Student Oriented?</span><br>
  Check this box if the festival has special consideration for student and ameteur
  filmmakers, such as discounts, separate student competitions or categories,
  or if the festival takes place on a college campus.</p>

<p class="description"><a name="page4"></a><span class="popupheading"> Submission Checklist</span><br>
  Please list out the contents of a complete application for this film Festival,
  (IE production stills, director's biography, etc.).</p>
<p class="description"><span class="popupheading">Eligibility Requirements</span><br>
  Please describe the requirements for submitting to this festival or for getting
  special discounts or consideration. Please include language, subtitle, or location
  requirements for the film. (IE<span class="details"> &quot;must be a Kansas
  City resident for a 15% discount&quot;, &quot;Entries must have a bona fide
  connection to Westchester County, which includes any ONE of the following conditions
  to satisfy entry requirements: Entry shot on location in Westchester County
  OR Directed, produced, shot (DP) or edited by a Westchester County resident
  OR Written by a Westchester County resident&quot;</span>)</p>
<p class="description"><span class="popupheading"> Categories</span><br>
  Please check all the categories in which you accept films. If a category is
  not listed, please mention it in the suggestion box at the end of the update
  procedure.</p>
<p class="description"><span class="popupheading"> Projections</span><br>
  Please check all the formats that your festival is capable of projecting in.
  If a projection format is not listed, please mention it in the suggestion box
  at the end of the update procedure.</p>
<p class="description"><a name="page5"></a><span class="popupheading"> Fees</span><br>
  Please use this fee table to represent your festival's specific fee structure
  as closely as possible. If you offer discounts for certain groups of people,
  other than students (IE Westchester County residents, etc.) fill in the 'OTHER'
  field with the discounted fee. We understand this isn't as complete a fee table
  as some festivals require, but we want to offer filmmakers an approximate, but
  concise table of fees, to make this tedious process easier.</p>
<p class="description"><span class="popupheading"> Fee Note</span><br>
  Use this field to explain any of the fees, the 'OTHER' field, and any special discounts or rates. </p>
<p class="description"><span class="popupheading"> What Sets you Apart</span><br>
  What is your festival best known for? Have you premiered any films that became very successful? How's the food, the scenery, the city life, night life? What sets you apart? Is it the type of filmmakers that attend? Is it the free promotional material? Feel free to be as creative and original as you like.</p>
<p class="description"><span class="popupheading"> Party Scene</span><br>
Please describe how wild your parties get. Is there one every night? Or just a big bash at the end? Free booze? Does it take place in the Playboy Mansion?</p>
<p class="description"><span class="popupheading"> Press Coverage</span><br>
What kind of media attention does your festival get? (Magazines, newspapers, websites, radio, TV stations, etc.) How far-reaching is this coverage? </p></td>
  </tr>
</table>

<p><p><center><a style="color: #FF0000" href="login.php<?= makeRequestString() ?>">
    I'm ready to start entering my information</a></center></p>

</body>
</html>