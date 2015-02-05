<?
require "dbFunctions.php";

import_request_variables("gp", "form_");
$id = fd_filter($form_id, true);
if(!isLoggedIn() || $id != $_SESSION["user_id"]) trigger_error("You can't access this user's info");

fd_connect();
$result = mysql_fetch_assoc(fd_query("select * from user where id = $id"));
if($result == "") { die("I can't find a user of that id"); }

$userFestResult = fd_query("select * from userFest where userID = $id and
	relation = 'admin'");
$isAdmin = mysql_num_rows($userFestResult) > 0;
?>

<html>
<head>
<title>FilmDevil - Edit User Information</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="styles/central.css" rel="stylesheet" type="text/css">
</head>

<style media="screen" type="text/css"><!--
#formStack {

	visibility: visible;
	position: relative
	}
#states,#canada,#countries     {
	padding: 0px;
	visibility: hidden;
	position: relative
	}

--></style>


<script src="findDOMNested.js"></script>
<script src="common.js"></script>

<script language="javascript">
function validateForm() {
	return validateURL(document.form1.favWebSite, "Your favorite web site")
		&& validateURL(document.form1.webSite, "Your web site")
    && validatePassword() && validateEmail() && validatePrimaryType();
}

function validatePrimaryType() {
	var box = document.form1.primaryType;
	if(box.options[box.selectedIndex].value != "") return true;
  alert("Please select your relation to film festivals so we can provide you customized services");
  return false;
}

function validateEmail() {
	if(document.form1.email.value != document.form1.oldEmail.value) {
		if(!confirm("Are you sure you want to change your email address? We will have to revalidate it.")) {
			document.form1.email.value = document.form1.oldEmail.value;
        	return false;
		}
  }
	var reg = new RegExp("@");
    if(document.form1.email.value.match(reg)) return true;
    alert("Sorry, this does not appear to be a real email address. Please check your spelling.");
    return false;
}

function showHideOnType() {
	var display;
    if(document.form1.primaryType.selectedIndex == 4)
    	display = 'none';
    else
		display = 'inline';
 	document.all('interestRow1').style.display = display;
 	document.all('interestRow2').style.display = display;
}


function preloadSelects() {
	form = document.form1;
	setOption(form.primaryType, "<?= $result["primaryType"] ?>");
	setOption(form.howFound, "<?= $result["howFound"] ?>");
	setOption(form.commLevel, "<?= $result["commLevel"] ?>");
	setOption(form.mailFormat, "<?= $result["mailFormat"] ?>");
	setRadio("submittingFilm", "<?= $result["submittingFilm"] ?>");

	<? $birthday = getdate(strtotime($result["birthday"])); ?>
  setOption(form.year, "<?= $birthday["year"] ?>");
	setOption(form.month, "<?= $birthday["mon"] ?>");
	setOption(form.day, "<?= $birthday["mday"] ?>");
  showHideOnType();
}

function preloadCountrySelection() {
	<? if($result["country"] == "USA") { ?>
    	document.form1.usaRadio.click();
   		setOption(document.form1.stateSelect, "<?= $result["state"] ?>");
    <? } else if($result["country"] == "Canada") { ?>
        document.form1.canadaRadio.click();
        setOption(document.form1.canadaRegionSelect, "<?= $result["region"] ?>");
    <? } else if($result["continent"] != "") { ?>
        document.form1.otherRadio.click();
        setOption(document.form1.continentSelect, "<?= $result["continent"] ?>");
        setOption(document.form1.countrySelect, "<?= $result["country"] ?>");
    <? } else { ?>
    	// do nothing
    <? } ?>
    return true;
}


var oldDom = null;
function swapForm(objectID){
	dom = findDOM('formStack',objectID,1);
    if (oldDom) oldDom.visibility = 'hidden';
	dom.visibility = 'visible';
	oldDom = dom;
	oldObjectID = objectID;
}

function Country() {
	document.form1.state.value='';
	document.form1.region.value='';
	var continent = 1;
	}

function Canada() {
	document.form1.country.value="Canada";
	document.form1.state.value='';
	document.form1.continent.value="North America";
	var continent = 1;
	}

function USA() { //and finally we get to that function...
	var state = document.form1.state.value;
	document.form1.country.value="USA";
	document.form1.continent.value="North America";
	var continent = 1;
		if (state == "CT" ||
			state == "MA" ||
			state == "ME" ||
			state == "NH" ||
			state == "NJ" ||
			state == "NY" ||
			state == "RI" ||
			state == "VT") { document.form1.region.value="North East"; }

		if (state == "DC" ||
			state == "DE" ||
			state == "MD" ||
			state == "PA" ||
			state == "NC" ||
			state == "VA") { document.form1.region.value="Mid-Atlantic"; }

		if (state == "AL" ||
			state == "FL" ||
			state == "GA" ||
			state == "LA" ||
			state == "MS" ||
			state == "SC") { document.form1.region.value="South East"; }

		if (state == "IL" ||
			state == "MI" ||
			state == "MN" ||
			state == "WI") { document.form1.region.value="Great Lakes"; }

		if (state == "IN" ||
			state == "KY" ||
			state == "OH" ||
			state == "TN" ||
			state == "WV") { document.form1.region.value="Mid-West"; }

		if (state == "AR" ||
			state == "IA" ||
			state == "KS" ||
			state == "MO" ||
			state == "ND" ||
			state == "NE" ||
			state == "OK" ||
			state == "SD") { document.form1.region.value="Plains"; }

		if (state == "CO" ||
			state == "MT" ||
			state == "UT" ||
			state == "WY") { document.form1.region.value="Rockies"; }

		if (state == "AZ" ||
			state == "NM" ||
			state == "TX") { document.form1.region.value="South West"; }

		if (state == "ID" ||
			state == "OR" ||
			state == "AK" ||
			state == "WA") { document.form1.region.value="North West"; }

		if (state == "CA" ||
			state == "NV") { document.form1.region.value="Western"; }

		if (state == "HI" ||
			state == "Guam") { document.form1.region.value="Pacific"; }

		if (state == "Puerto Rico" ||
			state == "US Virgin Isles") { document.form1.region.value="Caribbean"; }
}
</script>

<body onLoad="preloadCountrySelection(); preloadSelects();">
<h1 align="center">Edit User Information</h1>
<p>Hi, this short form is to tell us more about you. Besides
  for telling us your email and your relation to film festivals,
	you can fill out as much or as little as you'd
  like. We will not sell or distribute your e-mail address.
	It will only be used for FilmDevil communications.</p>
<form name="form1" method="post" action="editUser2.php" onSubmit="return validateForm();">
<input type="hidden" name="id" value="<?= $id ?>">
<input type="hidden" name="oldEmail" value="<?= $result["email"] ?>">
<table width="500" align="center" cellspacing="0">
	<tr>
    <td colspan="2"><hr color="#000000"></td>
  </tr>
  <tr class="alt1">
    <td width="250">* Email</td>
    <td width="248">
      <input type="text" name="email" value="<?= $result["email"] ?>">
    </td>
  </tr>
  <tr class="alt2">
    <td>Password<br>(type twice to change)</td>
    <td><input type="password" name="password1" value="">
		<br><input type="password" name="password2" value=""></td>
  </tr>
  <tr class="alt1">
    <td width="250">Last Name</td>
    <td width="248">
      <input type="text" name="lastName" value="<?= $result["lastName"] ?>">
    </td>
  </tr>
  <tr class="alt2">
    <td width="250">First Name</td>
    <td width="248">
      <input type="text" name="firstName" value="<?= $result["firstName"] ?>">
    </td>
  </tr>
  <tr class="alt1">
    <td>* Relation to film festivals?</td>
    <td><select name="primaryType" onchange="showHideOnType();">
<?if($result["primaryType"] == "devil") { ?>
			<option value="devil">FilmDevil Superuser </option>
<?} else { ?>
<?  if(!$isAdmin) { ?>
    	<option value="">Please choose one...
      <option value="proFilmmaker">Professional Filmmaker</option>
      <option value="studentFilmmaker">Student Filmmaker</option>
      <option value="amateurFilmmaker">Amateur Filmmaker</option>
<?	} ?>
		  <option value="administrator">Filmfest Administrator</option>
<? 	if(!$isAdmin) { ?>
      <option value="industryPro">Industry Pro</option>
      <option value="filmLover">Film Enthusiast</option>
<? 	}
	} ?>
 		</select></td>
  </tr>
  <tr class="alt2">
    <td width="250">Username</td>
    <td width="248">
      <input type="text" name="username" value="<?= $result["username"] ?>">
    </td>
  </tr>
  <tr class="alt1">
    <td width="250">Web Site</td>
    <td width="248">
      <input type="text" name="webSite" value="<?= $result["webSite"] ?>">
    </td>
  </tr>
  <tr class="alt2">
    <td>Birthday</td>
    <td>
      <select name="month">
				<option>Month
				<option value="1">Jan
	     	<option value="2">Feb
	     	<option value="3">Mar
	     	<option value="4">Apr
	     	<option value="5">May
	     	<option value="6">Jun
	     	<option value="7">Jul
	     	<option value="8">Aug
	     	<option value="9">Sep
	     	<option value="10">Oct
	     	<option value="11">Nov
	     	<option value="12">Dec
	    </select>
      <select name="day">
      <option>Day
	      <option value="1">1
	      <option value="2">2
	      <option value="3">3
	      <option value="4">4
	      <option value="5">5
	      <option value="6">6
	      <option value="7">7
	      <option value="8">8
	      <option value="9">9
	      <option value="10">10
	      <option value="11">11
	      <option value="12">12
	      <option value="13">13
	      <option value="14">14
	      <option value="15">15
	      <option value="16">16
	      <option value="17">17
	      <option value="18">18
	      <option value="19">19
	      <option value="20">20
	      <option value="21">21
	      <option value="22">22
	      <option value="23">23
	      <option value="24">24
	      <option value="25">25
	      <option value="26">26
	      <option value="27">27
	      <option value="28">28
	      <option value="29">29
	      <option value="30">30
	      <option value="31">31
      </select>
      <select name="year">
        <option value="">Year</option>
        <?	$date = getdate();
			for($i = $date["year"] - 100; $i <= $date["year"]; $i++) { ?>
            <option value="<?= $i ?>"><?= $i ?></option>
	  <? } ?>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><hr color="#000000"></div></td>
    </tr>
  <tr class="alt1">
    <td>City</td>
    <td><input type="text" name="city" value="<?= $result["city"] ?>"></td>
  </tr>
  <tr class="alt2">
    <td>Postal Code</td>
    <td><input type="text" name="postalCode" value="<?= $result["postalCode"] ?>"></td>
  </tr>
  <tr class="alt1">
    <td height="16">Location</td>
    <td>
          <input type="radio" class="radio" onclick="swapForm('states')" name="context" id="usaRadio">
          US
          <input type="radio" class="radio" onclick="swapForm('canada')" name="context" id="canadaRadio">
          Canada<br>
          <input type="radio" class="radio" onclick="swapForm('countries')" name="context" id="otherRadio">
          Other
		  <input type="hidden" name="continent" value='<?= $result["continent"] != "" ? $result["continent"] : "" ?>' >
          <input type="hidden" name="country" value='<?= $result["country"] != "" ? $result["country"] : "" ?>' >
          <input type="hidden" name="region" value='<?= $result["region"] != "" ? $result["region"] : "" ?>' >
          <input type="hidden" name="state" value='<?= $result["state"] != "" ? $result["state"] : "" ?>' >
        <div id="formStack">
		 <div id="countries">
		 Continent: <br>
          <select name="continentSelect" onchange="Country(); document.form1.continent.value=this.value;"">
            <option value=></option>
            <option value="Africa">Africa</option>
            <option value="Asia">Asia</option>
            <option value="Australia">Australia</option>
            <option value="Europe">Europe</option>
            <option value="North America">North America</option>
            <option value="South America">South America</option>
          </select><br>
		 Country: <br>
          <select name="countrySelect" onchange="document.form1.country.value=this.value;">
            <option value=></option>
            <option value="Afghanistan">Afghanistan</option>
            <option value="Albania">Albania</option>
            <option value="Algeria">Algeria</option>
            <option value="American Samoa">American Samoa</option>
            <option value="Andorra">Andorra</option>
            <option value="Angola">Angola</option>
            <option value="Anguilla">Anguilla</option>
            <option value="Antarctica">Antarctica</option>
            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
            <option value="Argentina">Argentina</option>
            <option value="Armenia">Armenia</option>
            <option value="Aruba">Aruba</option>
            <option value="Australia">Australia</option>
            <option value="Austria">Austria</option>
            <option value="Azerbaijan">Azerbaijan</option>
            <option value="Bahamas">Bahamas</option>
            <option value="Bahrain">Bahrain</option>
            <option value="Bangladesh">Bangladesh</option>
            <option value="Barbados">Barbados</option>
            <option value="Belarus">Belarus</option>
            <option value="Belgium">Belgium</option>
            <option value="Belize">Belize</option>
            <option value="Benin">Benin</option>
            <option value="Bermuda">Bermuda</option>
            <option value="Bhutan">Bhutan</option>
            <option value="Bolivia">Bolivia</option>
            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
            <option value="Botswana">Botswana</option>
            <option value="Bouvet Island">Bouvet Island</option>
            <option value="Brazil">Brazil</option>
            <option value="Brunei">Brunei</option>
            <option value="Bulgaria">Bulgaria</option>
            <option value="Burkina Faso">Burkina Faso</option>
            <option value="Burundi">Burundi</option>
            <option value="Cambodia">Cambodia</option>
            <option value="Cameroon">Cameroon</option>
            <option value="Cape Verde">Cape Verde</option>
            <option value="Cayman Islands">Cayman Islands</option>
            <option value="Central African Republic">Central African Republic</option>
            <option value="Chad">Chad</option>
            <option value="Chile">Chile</option>
            <option value="China">China</option>
            <option value="Christmas Island">Christmas Island</option>
            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
            <option value="Colombia">Colombia</option>
            <option value="Comoros">Comoros</option>
            <option value="Congo">Congo</option>
            <option value="Cook Islands">Cook Islands</option>
            <option value="Costa Rica">Costa Rica</option>
            <option value="C&ocirc;te d'Ivoire">C&ocirc;te d'Ivoire</option>
            <option value="Croatia (Hrvatska)">Croatia (Hrvatska)</option>
            <option value="Cuba">Cuba</option>
            <option value="Cyprus">Cyprus</option>
            <option value="Czech Republic">Czech Republic</option>
            <option value="Congo (DRC)">Congo (DRC)</option>
            <option value="Denmark">Denmark</option>
            <option value="Djibouti">Djibouti</option>
            <option value="Dominica">Dominica</option>
            <option value="Dominican Republic">Dominican Republic</option>
            <option value="East Timor">East Timor</option>
            <option value="Ecuador">Ecuador</option>
            <option value="Egypt">Egypt</option>
            <option value="El Salvador">El Salvador</option>
            <option value="Equatorial Guinea">Equatorial Guinea</option>
            <option value="Eritrea">Eritrea</option>
            <option value="Estonia">Estonia</option>
            <option value="Ethiopia">Ethiopia</option>
            <option value="Falkland Islands">Falkland Islands</option>
            <option value="Faroe Islands">Faroe Islands</option>
            <option value="Fiji Islands">Fiji Islands</option>
            <option value="Finland">Finland</option>
            <option value="France">France</option>
            <option value="French Guiana">French Guiana</option>
            <option value="French Polynesia">French Polynesia</option>
            <option value="Gabon">Gabon</option>
            <option value="Gambia">Gambia</option>
            <option value="Georgia">Georgia</option>
            <option value="Germany">Germany</option>
            <option value="Ghana">Ghana</option>
            <option value="Gibraltar">Gibraltar</option>
            <option value="Greece">Greece</option>
            <option value="Greenland">Greenland</option>
            <option value="Grenada">Grenada</option>
            <option value="Guadeloupe">Guadeloupe</option>
            <option value="Guam">Guam</option>
            <option value="Guatemala">Guatemala</option>
            <option value="Guinea">Guinea</option>
            <option value="GuineaBissau">GuineaBissau</option>
            <option value="Guyana">Guyana</option>
            <option value="Haiti">Haiti</option>
            <option value="Heard Island">Heard Island</option>
            <option value="Honduras">Honduras</option>
            <option value="Hong Kong SAR">Hong Kong SAR</option>
            <option value="Hungary">Hungary</option>
            <option value="Iceland">Iceland</option>
            <option value="India">India</option>
            <option value="Indonesia">Indonesia</option>
            <option value="Iran">Iran</option>
            <option value="Iraq">Iraq</option>
            <option value="Ireland">Ireland</option>
            <option value="Israel">Israel</option>
            <option value="Italy">Italy</option>
            <option value="Jamaica">Jamaica</option>
            <option value="Japan">Japan</option>
            <option value="Jordan">Jordan</option>
            <option value="Kazakhstan">Kazakhstan</option>
            <option value="Kenya">Kenya</option>
            <option value="Kiribati">Kiribati</option>
            <option value="Korea">Korea</option>
            <option value="Kuwait">Kuwait</option>
            <option value="Kyrgyzstan">Kyrgyzstan</option>
            <option value="Laos">Laos</option>
            <option value="Latvia">Latvia</option>
            <option value="Lebanon">Lebanon</option>
            <option value="Lesotho">Lesotho</option>
            <option value="Liberia">Liberia</option>
            <option value="Libya">Libya</option>
            <option value="Liechtenstein">Liechtenstein</option>
            <option value="Lithuania">Lithuania</option>
            <option value="Luxembourg">Luxembourg</option>
            <option value="Macau SAR">Macau SAR</option>
            <option value="Macedonia">Macedonia</option>
            <option value="Madagascar">Madagascar</option>
            <option value="Malawi">Malawi</option>
            <option value="Malaysia">Malaysia</option>
            <option value="Maldives">Maldives</option>
            <option value="Mali">Mali</option>
            <option value="Malta">Malta</option>
            <option value="Marshall Islands">Marshall Islands</option>
            <option value="Martinique">Martinique</option>
            <option value="Mauritania">Mauritania</option>
            <option value="Mauritius">Mauritius</option>
            <option value="Mayotte">Mayotte</option>
            <option value="Mexico">Mexico</option>
            <option value="Micronesia">Micronesia</option>
            <option value="Moldova">Moldova</option>
            <option value="Monaco">Monaco</option>
            <option value="Mongolia">Mongolia</option>
            <option value="Montserrat">Montserrat</option>
            <option value="Morocco">Morocco</option>
            <option value="Mozambique">Mozambique</option>
            <option value="Myanmar">Myanmar</option>
            <option value="Namibia">Namibia</option>
            <option value="Nauru">Nauru</option>
            <option value="Nepal">Nepal</option>
            <option value="Netherlands">Netherlands</option>
            <option value="Netherlands Antilles">Netherlands Antilles</option>
            <option value="New Caledonia">New Caledonia</option>
            <option value="New Zealand">New Zealand</option>
            <option value="Nicaragua">Nicaragua</option>
            <option value="Niger">Niger</option>
            <option value="Nigeria">Nigeria</option>
            <option value="Niue">Niue</option>
            <option value="Norfolk Island">Norfolk Island</option>
            <option value="North Korea">North Korea</option>
            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
            <option value="Norway">Norway</option>
            <option value="Oman">Oman</option>
            <option value="Pakistan">Pakistan</option>
            <option value="Palau">Palau</option>
            <option value="Panama">Panama</option>
            <option value="Papua New Guinea">Papua New Guinea</option>
            <option value="Paraguay">Paraguay</option>
            <option value="Peru">Peru</option>
            <option value="Philippines">Philippines</option>
            <option value="Pitcairn Islands">Pitcairn Islands</option>
            <option value="Poland">Poland</option>
            <option value="Portugal">Portugal</option>
            <option value="Puerto Rico">Puerto Rico</option>
            <option value="Qatar">Qatar</option>
            <option value="Reunion">Reunion</option>
            <option value="Romania">Romania</option>
            <option value="Russia">Russia</option>
            <option value="Rwanda">Rwanda</option>
            <option value="St. Kitts and Nevis">St. Kitts and Nevis</option>
            <option value="St. Lucia">St. Lucia</option>
            <option value="St. Vincent">St. Vincent</option>
            <option value="Samoa">Samoa</option>
            <option value="San Marino">San Marino</option>
            <option value="S&atilde;o Tom&eacute; and Pr&iacute;ncipe">S&atilde;oTm&eacute; and Pr&iacute;ncipe</option>
            <option value="Saudi Arabia">Saudi Arabia</option>
            <option value="Senegal">Senegal</option>
            <option value="Seychelles">Seychelles</option>
            <option value="Sierra Leone">Sierra Leone</option>
            <option value="Singapore">Singapore</option>
            <option value="Slovakia">Slovakia</option>
            <option value="Slovenia">Slovenia</option>
            <option value="Solomon Islands">Solomon Islands</option>
            <option value="Somalia">Somalia</option>
            <option value="South Africa">South Africa</option>
            <option value="South Sandwich Islands">South Sandwich Islands</option>
            <option value="Spain">Spain</option>
            <option value="Sri Lanka">Sri Lanka</option>
            <option value="St. Helena">St. Helena</option>
            <option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
            <option value="Sudan">Sudan</option>
            <option value="Suriname">Suriname</option>
            <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
            <option value="Swaziland">Swaziland</option>
            <option value="Sweden">Sweden</option>
            <option value="Switzerland">Switzerland</option>
            <option value="Syria">Syria</option>
            <option value="Taiwan">Taiwan</option>
            <option value="Tajikistan">Tajikistan</option>
            <option value="Tanzania">Tanzania</option>
            <option value="Thailand">Thailand</option>
            <option value="Togo">Togo</option>
            <option value="Tokelau">Tokelau</option>
            <option value="Tonga">Tonga</option>
            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
            <option value="Tunisia">Tunisia</option>
            <option value="Turkey">Turkey</option>
            <option value="Turkmenistan">Turkmenistan</option>
            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
            <option value="Tuvalu">Tuvalu</option>
            <option value="Uganda">Uganda</option>
            <option value="Ukraine">Ukraine</option>
            <option value="United Arab Emirates">United Arab Emirates</option>
            <option value="United Kingdom">United Kingdom</option>
            <option value="US Minor Outlying Islands">US Minor Outlying Islands</option>
            <option value="Uruguay">Uruguay</option>
            <option value="Uzbekistan">Uzbekistan</option>
            <option value="Vanuatu">Vanuatu</option>
            <option value="Vatican City">Vatican City</option>
            <option value="Venezuela">Venezuela</option>
            <option value="Viet Nam">Viet Nam</option>
            <option value="Virgin Islands (British)">Virgin Islands (British)</option>
            <option value="Virgin Islands">Virgin Islands</option>
            <option value="Wallis and Futuna">Wallis and Futuna</option>
            <option value="Yemen">Yemen</option>
            <option value="Yugoslavia">Yugoslavia</option>
            <option value="Zambia">Zambia</option>
            <option value="Zimbabwe">Zimbabwe</option>
          </select></div>
		  <div id="canada" name="canada">
		  Canadian Province: <br>
		  <select name="canadaRegionSelect" class="select" onchange="Canada();document.form1.region.value=this.value">
            <option value=></option>
            <option value="Alberta">Alberta </option>
	        <option value="British Columbia">British Columbia </option>
            <option value="Manitoba">Manitoba </option>
            <option value="New Brunswick">New Brunswick </option>
            <option value="Newfoundland">Newfoundland </option>
            <option value="Northwest Territories">Northwest Territories </option>
            <option value="Nova Scotia">Nova Scotia </option>
            <option value="Ontario">Ontario </option>
            <option value="Prince Edward Island">Prince Edward Island </option>
            <option value="Quebec">Quebec </option>
            <option value="Saskatchewan">Saskatchewan </option>
            <option value="Yukon Territory">Yukon Territory </option>
			</select>
			</div>
			<div id="states" name="states">

			 US State: <br>
          <select name="stateSelect" class="select" onchange="document.form1.state.value=this.value; USA()">
              <option value=></option>
			  <option value="AL">Alabama </option>
              <option value="AK">Alaska </option>
              <option value="AZ">Arizona </option>
              <option value="AR">Arkansas </option>
              <option value="CA">California </option>
              <option value="CO">Colorado </option>
              <option value="CT">Connecticut </option>
              <option value="DE">Delaware </option>
              <option value="DC">District of Columbia </option>
              <option value="FL">Florida </option>
              <option value="GA">Georgia </option>
              <option value="Guam">Guam </option>
              <option value="HI">Hawaii </option>
              <option value="ID">Idaho </option>
              <option value="IL">Illinois </option>
              <option value="IN">Indiana </option>
              <option value="IA">Iowa </option>
              <option value="KS">Kansas </option>
              <option value="KY">Kentucky </option>
              <option value="LA">Louisiana </option>
              <option value="ME">Maine </option>
              <option value="MD">Maryland </option>
              <option value="MA">Massachusetts </option>
              <option value="MI">Michigan </option>
              <option value="MN">Minnesota </option>
              <option value="MS">Mississippi </option>
              <option value="MO">Missouri </option>
              <option value="MT">Montana </option>
              <option value="NE">Nebraska </option>
              <option value="NV">Nevada </option>
              <option value="NH">New Hampshire </option>
              <option value="NJ">New Jersey </option>
              <option value="NM">New Mexico </option>
              <option value="NY">New York </option>
              <option value="NC">North Carolina </option>
              <option value="ND">North Dakota </option>
              <option value="OH">Ohio </option>
              <option value="OK">Oklahoma </option>
              <option value="OR">Oregon </option>
              <option value="PA">Pennsylvania </option>
              <option value="Puerto Rico">Puerto Rico </option>
              <option value="RI">Rhode Island </option>
              <option value="SC">South Carolina </option>
              <option value="SD">South Dakota </option>
              <option value="TN">Tennessee </option>
              <option value="TX">Texas </option>
              <option value="US Virgin Isles">US Virgin Isles </option>
              <option value="UT">Utah </option>
              <option value="VT">Vermont </option>
              <option value="VA">Virginia </option>
              <option value="WA">Washington </option>
              <option value="WV">West Virginia </option>
              <option value="WI">Wisconsin </option>
              <option value="WY">Wyoming </option>
            </select>
		  </div>
		 </td>
  </tr>
  <tr class="alt2">
    <td height="16" colspan="2"><hr color="#000000"></td>
    </tr>
  <tr class="alt1">
    <td>Favorite Film</td>
    <td><input type="text" name="favFilm" value="<?= $result['favFilm'] ?>"></td>
  </tr>
  <tr class="alt2">
    <td>Favorite Film-Related Web Site</td>
    <td><input type="text" name="favWebSite" value="<?= $result['favWebSite'] ?>"></td>
  </tr>
  <tr>
    <td colspan="2"><hr color="#000000"></td>
    </tr>
  <tr class="alt1">
    <td>How did you hear about us?</td>
    <td><select name="howFound">
	    <option value="">Please choose one
	    <option value="google">Google
	    <option value="email">I recieved an email from you
	    <option value="filmWebSite">A film web site
	    <option value="festival">A film festival
	    <option value="forum">A forum, bulletin board or discussion group
	    <option value="magazine">A film magazine
	    <option value="school">A film school
	    <option value="friend">From a friend
	    <option value="other">Something else
    </select></td>
  </tr>
  <tr id="interestRow1" class="alt2">
    <td>Would you like us to e-mail you with a list of festivals that we
			think you'll be interested in?</td>
    <td><select name="commLevel">
    <option value="no">No
    <option value="monthly">Yes, monthly
    <option value="weekly">Yes, weekly
    </select></td>
  </tr>
	<tr id="interestRow2" class="alt1">
    <td>Do you have a film you are currently submitting to festivals?</td>
    <td>
			<input type="radio" class="radio" name="submittingFilm" value="1">Yes
			<input type="radio" class="radio" name="submittingFilm" value="0">No
		</td>
  </tr>
  <tr class="alt2">
    <td>What mail format do you prefer?</td>
    <td><select name="mailFormat">
    	<option value="HTML">HTML (pictures and fonts)
      <option value="plain">Plain text</option>
 		</select></td>
  </tr>
	<tr>
    <td colspan="2"><hr color="#000000"></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
        <input type="submit" class="button" name="Submit" value="Done"
        	onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
        <input type="reset" class="button" name="reset" value="Reset"
        	onmouseover="this.className = 'buttonSelect';" onMouseOut="this.className = 'button';">
    </div></td>
  </tr>
</table>

</form>
</body>
</html>