<!-- *************** DHTML Outline (begin) ***************** --> 
<STYLE TYPE='text/css'>
<!--
/*Define elements with children with the class='outlineParentItem', and all elements without children with class='outlineItem'*/
li.oItem { color: #FFFFFF; cursor: text; ; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 10px; text-transform: none} ;
li.oParent { color: #FFFFFF; cursor: hand; ; font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; text-transform: uppercase} ;
ul ul { display: none; } ;
// a:link {  }
a:link {  font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; color: #CCCCCC; text-decoration: none}
a:hover { font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold ; text-decoration: none}
a:active { font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; color: #CCCCCC ; text-decoration: none}
a:visited { font-family: Arial, Helvetica, sans-serif; font-size: 10px; font-weight: bold; color: #CCCCCC ; text-decoration: none}
.all {  position: absolute; left: -20px; clip:  rect(   )}
-->
</STYLE>
<SCRIPT LANGUAGE='Javascript'>
<!--
	// Returns the closest parent tag with tagName containing
	// the src tag. If no such tag is found - null is returned.
	function checkParent( src, tagName ) {
		while ( src != null ) {
			if (src.tagName == tagName) 
				return src;
			src = src.parentElement;
		}
		return null;
	}

	
	// Returns the first tag with tagName contained by
	// the src tag. If no such tag is found - null is returned.
	function checkContent( src, tagName ) {
		var pos = src.sourceIndex ;
		while ( src.contains( document.all[++pos] ) )
			if ( document.all[pos].tagName == tagName )
				return document.all[pos] ;
		return null ;
	}

      
	// Handle onClick event in the outline box
	function outlineAction() {     
		var src = event.srcElement ;
		var item = checkParent( src, "LI" ) ;

		if ( parent != null ) {
			var content = checkContent( item, "UL" ) ;

			if ( content != null )
				if ( content.style.display == "" )
					content.style.display = "block" ;
				else
					content.style.display = "" ;
		}
		event.cancelBubble = true;
	}
    
// -->
</SCRIPT>
<body bgcolor="#000000">
<DIV onClick="JavaScript: outlineAction();" class="all"> 
  <UL>
    <LI class='oParent'>About FilmDevil.com 
      <UL>
        <LI class='oItem'><a href="whatwedo.php" target="mainFrame">What We Do</a></LI>
        <LI class='oItem'><a href="whoweare.php" target="mainFrame">Who We Are</a></LI>
        <LI class='oItem'><a href="errors.php" target="mainFrame">Reporting Errors</a></LI>
        <LI class='oItem'><a href="inappropriate.php" target="mainFrame">Reporting 
          Inappropriate Use</a></LI>
        <LI class='oItem'><a href="terms.php" target="mainFrame">Terms of Use</a></LI>
        <LI class='oItem'><a href="contacting.php" target="mainFrame">Contact 
          Us</a></LI>
      </UL>
    </LI>
    <LI class='oParent'>Filmmakers & Enthusiasts 
      <UL>
        <LI class='oItem'><a href="filmfind.php">Finding Festivals</a></LI>
        <LI class='oItem'><a href="filmwrite.php">Writing/Reading Reviews</a></LI>
      </UL>
    </LI>
    <LI class='oParent'>Festivals 
      <UL>
        <LI class='oItem'><a href="festget.php">Getting the most out of FilmDevil</a></LI>
        <LI class='oItem'><a href="festadd.php">Adding a Festival</a></LI>
        <LI class='oItem'><a href="festupdate.php">Updating Festival Info</a></LI>
        <LI class='oItem'><a href="festtips.php">Tips on Entering Festival Info</a></LI>
      </UL>
    </LI>
    <LI class='oParent'>Advertisers & Sponsors 
      <UL>
        <LI class='oItem'><a href="adtarget.php">Targeting your audience</a></LI>
        <LI class='oItem'><a href="adrates.php">Rates and Packages</a></LI>
      </UL>
    </LI>
    <LI class='oParent'>FAQs 
      <UL>
        <LI class='oItem'><a href="faqfest.php">Festivals</a></LI>
        <LI class='oItem'><a href="faqfilm.php">Filmmakers</a></LI>
      </UL>
    </LI>
  </UL>
</DIV>

</body>
<!-- *************** DHTML Outline (end) ***************** -->
