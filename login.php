<?
$cacheLimiter = "public";
require_once "dbFunctions.php";

if(!isLoggedIn())
	redirectToLogin();

function getBlankRow() {
	$row = fd_query("select * from fests limit 1")->fetch_assoc();
    foreach($row as $key => $value)
    	$row[$key] = "";
	return $row;
}

import_request_variables("gp", "form_");
$operation = fd_filter($form_operation);

fd_connect();
if ($operation == "update"){
	$form_festID = fd_filter($form_festID);
    if (!festEditAuthorized($_SESSION["user_id"], $form_festID)) {
    	trigger_error("user " . $_SESSION["user_id"]
				. " not authorized to view festival $form_festID");
        // TODO: bring to access error page?
			print "You can't edit this information";
			die();
    } else {
    	$result = fd_query("select * from fests where ID = $form_festID")->fetch_assoc();
    }
} else {
	if(!isLoggedIn()) {
		redirectToLogin();
	}
  $result = getBlankRow();
	$result["submission"] = 1;
	$result["email"] = $_SESSION["user_email"];
}
?>


<html>
<head>
<title>Festival Update - Page 1 of 6</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles/info.css" type="text/css">
<script src="findDOMNested.js"></script>

<script language="JavaScript">
<!--
function preloadCountrySelection() {
	<? if($result["country"] == "USA") { ?>
    	document.form1.usaRadio.click();
   		setOption(document.form1.stateSelect, "<?= $result["vState"] ?>");
    <? } else if($result["country"] == "Canada") { ?>
        document.form1.canadaRadio.click();
        setOption(document.form1.canadaRegionSelect, "<?= $result["region"] ?>");
    <? } else if($result["continent"] == "Online") { ?>
        document.form1.onlineRadio.click();
    <? } else if($result["continent"] != "") { ?>
        document.form1.otherRadio.click();
        setOption(document.form1.continentSelect, "<?= $result["continent"] ?>");
        setOption(document.form1.countrySelect, "<?= $result["country"] ?>");
    <? } else { ?>
    	// do nothing
    <? } ?>
    return true;
}

// Returns true if option was found and selected, false otherwise
function setOption(selectBox, option) {
    for(i = 0; i < selectBox.options.length; i++) {
    	if(selectBox.options(i).value == option)
            break;
    }
    if(i == selectBox.options.length) {
    	//alert("Couldn't find option: " + option + " for select box " + selectBox.name);
        return false;
    }
    selectBox.selectedIndex = i;
    return true;
}

function validate(object,text) {
    if (object.value.length > 0)
        return true;
    else {
        alert(text + ' field empty!');
        if (navigator.appName.indexOf('Netscape') > -1) {
            object.focus();
        }
        return false;
    }
}


function validate2(object,text) {
    if (object.value.length > 0){
     	rexp = /http(s?):\/\//;
		if(rexp.test(object.value))
   			return true;
		else {
        	alert(text + ' field must begin with http:// or https://');
        	if (navigator.appName.indexOf('Netscape') > -1) {
            	object.focus();
        	}
			return false;
		}
	}
	else { return true; }
}

function validate3(object1,object2,text) {
    if (object1.value == 'Not selected' || object2.value == 'Not selected'){
		alert(text);
		return false;
	}
	else { return true; }
}


function formvalidate() {
    var validated = true;
    if (!validate(document.form1.title,'Title'))
        validated = false;
    if (!validate(document.form1.email,'Email'))
        validated = false;
    if (!validate2(document.form1.URL,'URL'))
        validated = false;
    if (!validate2(document.form1.logoURL,'logoURL'))
        validated = false;
    if (!validate2(document.form1.appURL,'appURL'))
        validated = false;
	if (!validate3(document.form1.continent,document.form1.country,'Please select a continent and country!'))
        validated = false;
    return validated;
}

var oldDom = null;
function swapForm(objectID){
	dom = findDOM('formStack',objectID,1);
	if (oldDom) oldDom.visibility = 'hidden';
	dom.visibility = 'visible';
	oldDom = dom;
	oldObjectID = objectID;
}
function USA() { //and finally we get to that function...
	var state = document.form1.vState.value;
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

function Country() {
	document.form1.vState.value='';
	document.form1.region.value='';
	var continent = 1;
	}

function Canada() {
	document.form1.country.value="Canada";
	document.form1.vState.value='';
	document.form1.continent.value="North America";
	var continent = 1;
	}

function online() {
	document.form1.country.value='';
	document.form1.vState.value='';
	document.form1.region.value='';
	document.form1.continent.value="Online";
	var continent = 1;
	}
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
		<style media="screen" type="text/css"><!--
#formStack {

	visibility: visible;
	position: relative
	}
#states,#canada,#countries     {
	padding: 0px;
	visibility: hidden;
	position: absolute
	}


--></style>

</head>

<body bgcolor="#000000" text="#FFFFFF" class="description" onLoad="preloadCountrySelection();">
<span align="center"><img src="images/login1.gif"></span><br>
Welcome<br>
<span class="title">
<? print($result["title"]); ?>
</span><br>
  Please update the following information for your festival:
<form name="form1" method="post" action="login2.php" class="description" onSubmit="return formvalidate()">
  <table border="0" cellspacing="0" cellpadding="10" class="description">
    <tr valign="top">
      <td colspan="3">
	  <input type="hidden" name="ID" value="<? print$result["ID"] ?>">
	  <input type="hidden" name="operation" value="<?= $operation ?>">

        <p>Title:
          <input type="text" name="title" class="select" size="60" value="<? print $result["title"] ?>" onChange="validate(this.form.title,'Title')">
          &nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page1','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a>
        </p>
        </td>
    </tr>
    <tr valign="top">
      <td width="600">
        <p> <span class="popupheading"> VENUE:</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page1','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
          Name / Address line 1:<br>
          <input type="text" name="vName" value="<? print $result["vName"] ?>" class="select">
          <br>
          Address: <br>
          <input type="text" name="vAdr" value="<? print $result["vAdr"] ?>" class="select">
          <br>
          City: <br>
          <input type="text" name="vCity" value="<? print $result["vCity"] ?>" class="select">
          <br>
          ------------------------------<br>
          Country: <br>
          <input type="radio" onclick="swapForm('states')" name="context" id="usaRadio">
          US
          <input type="radio" onclick="swapForm('canada')" name="context" id="canadaRadio">
          Canada<br>
          <input type="radio" onclick="swapForm('countries')" name="context" id="otherRadio">
          Other
          <input type="radio" onClick="swapForm('online'),online()" name="context" id="onlineRadio">
          Online Festival<br>
          <input type="hidden" name="continent" value='<?= $result["continent"] != "" ? $result["continent"] : "Not selected" ?>' >
          <input type="hidden" name="country" value='<?= $result["country"] != "" ? $result["country"] : "Not selected" ?>' >
          <input type="hidden" name="region" value='<?= $result["region"] != "" ? $result["region"] : "Not selected" ?>' >
          <input type="hidden" name="vState" value='<?= $result["vState"] != "" ? $result["vState"] : "Not selected" ?>' >
        <div id="formStack">
		 <div id="countries">
		 Continent: <br>
          <select name="continentSelect" class="select" onchange="Country();document.form1.continent.value=this.value">
            <option value=></option>
            <option value="Africa">Africa</option>
            <option value="Asia">Asia</option>
            <option value="Australia">Australia</option>
            <option value="Europe">Europe</option>
            <option value="North America">North America</option>
            <option value="South America">South America</option>
          </select><br>
		 Country: <br>
          <select name="countrySelect" class="select" onchange="document.form1.country.value=this.value">
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
          <select name="stateSelect" class="select" onchange="document.form1.vState.value=this.value; USA()">
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
		  <div id="online"></div>
		  </div>
          <br><br><br><br><br>
          Zip: <br>
          <input type="text" name="vZip" value="<? print $result["vZip"] ?>" class="select">
          <br>
          Telephone: <br>
          <input type="text" name="vTel" value="<? print $result["vTel"] ?>" class="select">
          <br>
        </p>
      </td>
      <td width="300"><span class="popupheading">ORGANIZATION:</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page1','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
        Name / Address line 1:<br>
        <input type="text" name="oName" value="<? print $result["oName"] ?>" class="select">
        <br>
        Address: <br>
        <input type="text" name="oAdr" value="<? print $result["oAdr"] ?>" class="select">
        <br>
        City: <br>
        <input type="text" name="oCity" value="<? print $result["oCity"] ?>" class="select">
        <br>
        State / Country: <br>
        <input type="text" name="oState" value="<? print $result["oState"] ?>" class="select">
        <br>
        Zip: <br>
        <input type="text" name="oZip" value="<? print $result["oZip"] ?>" class="select">
        <br>
        Telephone: <br>
        <input type="text" name="oTel" value="<? print $result["oTel"] ?>" class="select">
        <br>
        Fax: <br>
        <input type="text" name="oFax" value="<? print $result["oFax"] ?>" class="select"><br><br>
        <table border="0" cellspacing="0" cellpadding="0" class="popupheading">
          <tr>
            <td><span class="popupheading">Does this festival accept submissions?&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page1','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
              <input type="radio" name="submission" value="1"<? if($result["submission"]==1){print " checked";} ?>>
              Yes
              <input type="radio" name="submission" value="0"<? if($result["submission"]==0){print " checked";} ?>>
              No </span></td>
          </tr>
        </table>
        <br>
        </td>
      <td> <span class="popupheading">WEB:</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="MM_openBrWindow('help.php#page1','','scrollbars=yes,resizable=yes,width=400,height=200')"><span class="help">&nbsp;?&nbsp;</span></a><br>
        Email: <br>
        <input type="text" name="email" value="<? print $result["email"] ?>" class="select" onChange="validate(this.form.email,'Email')">
        <br>
        URL: <br>
        <input type="text" name="URL" value="<? print $result["URL"] ?>" class="select" onChange="validate2(this.form.URL,'URL')">
        <br>
        Logo URL: <br>
        <input type="text" name="logoURL" value="<? print $result["logoURL"] ?>" class="select" onChange="validate2(this.form.logoURL,'logoURL')">
        <br>
        Application URL: <br>
        <input type="text" name="appURL" value="<? print $result["appURL"] ?>" class="select" onChange="validate2(this.form.appURL,'appURL')">
        <br>
        <input type="submit" name="Submit2" value="Next ->" class="select">
      </td>
    </tr>
  </table>
  </form>
</body>
</html>