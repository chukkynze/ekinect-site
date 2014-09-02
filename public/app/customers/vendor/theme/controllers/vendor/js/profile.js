/**
 * profile.js
 *
 * @author      Chukwuma J. Nze <chukkynze@notarytoolz.com>
 * @since 4/3/14 3:49 PM
 *
 * @copyright   Copyright (c) 2014 www.NotaryToolz.com
 */



function callMessengerError(msg)
{
	//Set theme
	Messenger.options 	= 	{
								extraClasses	: 	'messenger-fixed messenger-on-top',
								theme			:	 'flat'
							};
	var i;
		i = 0;

	Messenger().run
	({
		errorMessage		: 	msg,
		showCloseButton	: 	true,
		action			: 	function(opts)
							{
								if (++i < 3)
								{
									return 	opts.error();
								}
								else
								{
									return opts.success();
								}
							}
	});
}

function callMessengerSuccess(msg)
{
	//Set theme
	Messenger.options 	= 	{
								extraClasses	: 	'messenger-fixed messenger-on-top',
								theme			:	 'flat'
							};
	Messenger().post
	({
		message			:	msg,
		showCloseButton	: 	true
	});
}

function showBox(boxShortName)
{
	//alert(boxShortName);
	hideAllUserProfileSections();
	$("#"+boxShortName+"BoxLink" ).addClass("active");
	$("#"+boxShortName+"BoxContent" ).removeClass("hidden");
}

function hideAllUserProfileSections()
{
	$('.userProfileContent').each(function(i, obj)
	{
		if(!$(this).hasClass("hidden"))
		{
			$(this).addClass("hidden");
		}
	});

	$(".userProfileLink" ).removeClass("active");
}

function getProfileCommissionsList()
{
	$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-commission-list/0',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	false,
        	processData	: 	true,
			data		: 	0,
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								var listText	=	'';
								var textUnit 	=	'<div id="commissionWell_unitID" class="well well-lg commissionWell"><h3><button id="deleteCommissionWellButton_unitID" class="hidden deleteCommissionWellButton"><i id="deleteCommissionWellIcon_unitID" class="fa fa-times hidden deleteCommissionWellIcon"></i></button>&nbsp;&nbsp;&nbsp;unitTitle</h3><dl class="dl-horizontal font-16" style="margin-bottom: 40px;"><dt>Start Date</dt><dd>unitStartDate</dd><dt>Duration</dt><dd>unitDuration</dd><dt>State &amp; County</dt><dd>unitCountyState</dd></dl></div>';

								for(var i= 0; i < returnedData.storedData.count; i++)
								{
									var txtUnit 		= 	textUnit;
									var rawData			=	returnedData.storedData.data[i];
									console.log(rawData);
									var finalText		=	txtUnit.replace("unitID", rawData.id )
										.replace("unitID", rawData.id )
										.replace("unitID", rawData.id )
										.replace("unitTitle", rawData.title)
										.replace("unitStartDate", rawData.start_date)
										.replace("unitDuration", rawData.duration)
										.replace("unitCountyState", rawData.state_county);

									listText	=	listText + finalText;
								}

								$("#ProfileCommissionsListDiv" ).html(listText);

							},
			error		: 	function (returnedData)
							{
								console.log('Retrieval Error');
								callMessengerError("There was an error with your data retrieval. Please refresh the page.");
							}
			});
}

function getProfileCertificationsList()
{
	$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-certification-list/0',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	false,
        	processData	: 	true,
			data		: 	0,
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								var listText	=	'';
								var textUnit 	=	'<div id="certificationWell_unitID" class="well well-lg certificationWell"><h3><button id="deleteCertificationWellButton_unitID" class="hidden deleteCertificationWellButton"><i id="deleteCertificationWellIcon_unitID" class="fa fa-times hidden deleteCertificationWellIcon"></i></button>&nbsp;&nbsp;&nbsp;unitTitle</h3><dl class="dl-horizontal font-16" style="margin-bottom: 40px;"><dt>Start Date</dt><dd>unitStartDate</dd><dt>Duration</dt><dd>unitDuration</dd><dt>Certification ID</dt><dd>unitCertID</dd></dl></div>';

								for(var i= 0; i < returnedData.storedData.count; i++)
								{
									var txtUnit 		= 	textUnit;
									var rawData			=	returnedData.storedData.data[i];
									console.log(rawData);
									var finalText		=	txtUnit.replace("unitID", rawData.id )
										.replace("unitID", rawData.id )
										.replace("unitID", rawData.id )
										.replace("unitTitle", rawData.title)
										.replace("unitStartDate", rawData.start_date)
										.replace("unitDuration", rawData.duration)
										.replace("unitCertID", rawData.cert_id);

									listText	=	listText + finalText;
								}

								$("#ProfileCertificationsListDiv" ).html(listText);

							},
			error		: 	function (returnedData)
							{
								console.log('Retrieval Error');
								callMessengerError("There was an error with your data retrieval. Please refresh the page.");
							}
			});
}

function getProfileAffiliationsList()
{
	$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-affiliation-list/0',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	false,
        	processData	: 	true,
			data		: 	0,
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								var listText	=	'';
								var textUnit 	=	'<div id="affiliationWell_unitID" class="well well-lg affiliationWell"><h3><button id="deleteAffiliationWellButton_unitID" class="hidden deleteAffiliationWellButton"><i id="deleteAffiliationWellIcon_unitID" class="fa fa-times hidden deleteAffiliationWellIcon"></i></button>&nbsp;&nbsp;&nbsp;unitTitle</h3><dl class="dl-horizontal font-16" style="margin-bottom: 40px;"><dt>Start Date</dt><dd>unitStartDate</dd><dt>Duration</dt><dd>unitDuration</dd><dt>Organizatioin ID</dt><dd>unitOrgID</dd></dl></div>';

								for(var i= 0; i < returnedData.storedData.count; i++)
								{
									var txtUnit 		= 	textUnit;
									var rawData			=	returnedData.storedData.data[i];
									console.log(rawData);
									var finalText		=	txtUnit.replace("unitID", rawData.id )
										.replace("unitID", rawData.id )
										.replace("unitID", rawData.id )
										.replace("unitTitle", rawData.title)
										.replace("unitStartDate", rawData.start_date)
										.replace("unitDuration", rawData.duration)
										.replace("unitOrgID", rawData.org_id);

									listText	=	listText + finalText;
								}

								$("#ProfileAffiliationsListDiv" ).html(listText);

							},
			error		: 	function (returnedData)
							{
								console.log('Retrieval Error');
								callMessengerError("There was an error with your data retrieval. Please refresh the page.");
							}
			});
}

$(document).ready(function() {

	var profileBox 			=	$("#ProfileBoxLink");
	var contactBox 			=	$("#ContactBoxLink");
	var commissionBox 		=	$("#CommissionBoxLink");
	var certificationsBox 	=	$("#CertificationsBoxLink");
	var affiliationsBox 	=	$("#AffiliationsBoxLink");
	var capabilitiesBox 	=	$("#CapabilitiesBoxLink");

	getProfileCommissionsList();
	getProfileCertificationsList();
	getProfileAffiliationsList();


    profileBox.on( "click", function(){showBox("Profile");});
    contactBox.on( "click", function(){showBox("Contact");});
    commissionBox.on( "click", function(){showBox("Commission");});
    certificationsBox.on( "click", function(){showBox("Certifications");});
    affiliationsBox.on( "click", function(){showBox("Affiliations");});
    capabilitiesBox.on( "click", function(){showBox("Capabilities");});

	var profilePicFormField		=	$('#ProfilePicForm_pic_Field');

	// Profile Form Submission
	$("#ProfileForm").submit(function(e)
	{
		e.preventDefault();

		// Remove Error Class
		$(".form-group").removeClass("has-error");

		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	$("#ProfileForm").serialize(),
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								console.log(returnedData);
								if(returnedData.status == false)
								{
									$.each(returnedData.message, function(validationIndex, validationObjectValues)
									{
										var formMessage;
										$.each(validationObjectValues, function(objectIndex, objectValue)
										{
											formMessage	=	objectValue;
										});

										// Add Error Class to each div id
										$("#ProfileFormGroup_" + validationIndex ).addClass("has-error");

										// Display Error Message
										callMessengerError((formMessage == "" ? "There is an error with your form. Please re-check your entries." : formMessage))
									});

								}
								else
								{
									// Process Returned Data
									callMessengerSuccess("Your updates were submitted successfully.");

									// Update Modal Form Values

									// Update Page Data
									$("#ProfileBox_prefix" 			).text(returnedData.formData.prefix);
									$("#ProfileBox_first_name"		).text(returnedData.formData.first_name);
									$("#ProfileBox_mid_name1"		).text(returnedData.formData.mid_name1);
									$("#ProfileBox_mid_name2"		).text(returnedData.formData.mid_name2);
									$("#ProfileBox_last_name"		).text(returnedData.formData.last_name);
									$("#ProfileBox_display_name"	).text(returnedData.formData.display_name);
									$("#ProfileBox_suffix"			).text(returnedData.formData.suffix);
									$("#ProfileBox_birth_date"		).text(returnedData.formData.birth_date);
									$("#ProfileBox_gender"			).text(returnedData.formData.gender);

									$("#ProfileBox_personal_summary").text(returnedData.formData.personal_summary);
									$("#ProfileDetails_personal_summary").text(returnedData.formData.personal_summary);

									$("#ProfileBox_website"			).text(returnedData.formData.website);
									$("#ProfileDetails_website"		).attr('href', returnedData.formData.website).text(returnedData.formData.website);

									$("#ProfileBox_linkedin" 		).text(returnedData.formData.linkedin);
									$("#ProfileDetails_linkedin"	).attr('onclick', "window.open('" + returnedData.formData.linkedin + "');");

									$("#ProfileBox_google_plus"		).text(returnedData.formData.google_plus);
									$("#ProfileDetails_google_plus"	).attr('onclick', "window.open('" + returnedData.formData.google_plus + "');");

									$("#ProfileBox_twitter"			).text(returnedData.formData.twitter);
									$("#ProfileDetails_twitter"		).attr('onclick', "window.open('" + returnedData.formData.twitter + "');");

									$("#ProfileBox_facebook"		).text(returnedData.formData.facebook);
									$("#ProfileDetails_facebook"	).attr('onclick', "window.open('" + returnedData.formData.facebook + "');");

								}
							},
			error		: 	function (returnedData)
							{
								console.log('Submission Error');
								callMessengerError("There was an error with your form submission. Please refresh the page.");
							}
			});

	});

	// Profile Pic Form Submission
	$("#ProfilePicForm").submit(function(e)
	{
		e.preventDefault();

		// Remove Error Class
		$(".form-group").removeClass("has-error");

		var formData 	= 	new FormData();
			formData.append("pic", profilePicFormField[0].files[0]);
			formData.append("profile_pic_csrf", $("#ProfilePicFormCsrfField" ).val());

    	var picFile 	= 	profilePicFormField[0].files[0];
    	name 	= 	picFile.name;
    	size 	= 	picFile.size;
    	type 	= 	picFile.type;

		console.log(picFile);
		console.log(picFile.name);
		console.log(picFile.size);
		console.log(picFile.type);


    	if(picFile.name.length < 1)
		{
			console.log("File is empty");
    	}
    	else if(picFile.size > 100000)
		{
        	console.log("File is to big");
    	}
    	else if
		(		picFile.type != 'image/png'
			&& 	picFile.type != 'image/jpg'
			&&  picFile.type != 'image/jpeg'
		)
		{
			console.log("File doesn't match png or jpg");
		}
    	else
		{
			$.ajax(
			{
                url		: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-pic',
                type	: 	'POST',
                xhr		: 	function()
							{
								// custom xhr
								myXhr 	= 	$.ajaxSettings.xhr();
								if(myXhr.upload)
								{
									// if upload property exists
									// progressbar
								}
								return myXhr;
							},
                //Ajax events
                success		: 	function(returnedData)
								{
									console.log("success");
									console.log(returnedData);

									if(returnedData.status == false)
									{
										$.each(returnedData.message, function(validationIndex, validationObjectValues)
										{
											var formMessage;
											$.each(validationObjectValues, function(objectIndex, objectValue)
											{
												formMessage	=	objectValue;
											});

											// Add Error Class to each div id
											$("#ProfileFormGroup_" + validationIndex ).addClass("has-error");

											// Display Error Message
											callMessengerError((formMessage == "" ? "There is an error with your form. Please re-check your entries." : formMessage))
										});

									}
									else
									{
										// Process Returned Data
										callMessengerSuccess("Your updates were submitted successfully.");

										// Update Modal Form Values


										// Update Page Data
										$("#ProfileBox_pic_Image").attr('src', returnedData.formData.pic);
										$("#ProfileBox_pic_ImageUserIcon").attr('src', returnedData.formData.pic);
									}
								},
                error		: 	function(returnedData)
								{
									console.log("error");
									console.log(returnedData);
								},
                // Form data
                data		:	formData,
                //Options to tell JQuery not to process data or worry about content-type
                cache		: 	false,
                contentType	: 	false,
                processData	: 	false
            }, 'json');
    	}


	});




	// Profile Contact Info Form Submission
	$("#ProfileContactInfoForm").submit(function(e)
	{
		e.preventDefault();

		// Remove Error Class
		$(".form-group").removeClass("has-error");

		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	$("#ProfileContactInfoForm").serialize(),
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								console.log(returnedData);
								if(returnedData.status == false)
								{
									$.each(returnedData.message, function(validationIndex, validationObjectValues)
									{
										var formMessage;
										$.each(validationObjectValues, function(objectIndex, objectValue)
										{
											formMessage	=	objectValue;
										});

										// Add Error Class to each div id
										$("#ProfileContactInfoFormGroup_" + validationIndex ).addClass("has-error");

										// Display Error Message
										callMessengerError((formMessage == "" ? "There is an error with your form. Please re-check your entries." : formMessage))
									});

								}
								else
								{
									// Process Returned Data
									callMessengerSuccess("Your updates were submitted successfully.");

									// Update Modal Form Values

									// Update Page Data
									$("#ContactInfoBox_pri_business_name" 	).text(returnedData.formData.pri_business_name);
									$("#ContactInfoBox_pri_address_line1"	).text(returnedData.formData.pri_address_line1);
									$("#ContactInfoBox_pri_address_line2"	).text(returnedData.formData.pri_address_line2);
									$("#ContactInfoBox_pri_address_line3"	).text(returnedData.formData.pri_address_line3);
									$("#ContactInfoBox_pri_county"			).text(returnedData.formData.pri_county_text);
									$("#ContactInfoBox_pri_city"			).text(returnedData.formData.pri_city);
									$("#ContactInfoBox_pri_state"			).text(returnedData.formData.pri_state);
									$("#ContactInfoBox_pri_zip"				).text(returnedData.formData.pri_zip);

									$("#ContactInfoBox_alt_business_name" 	).text(returnedData.formData.alt_business_name);
									$("#ContactInfoBox_alt_address_line1"	).text(returnedData.formData.alt_address_line1);
									$("#ContactInfoBox_alt_address_line2"	).text(returnedData.formData.alt_address_line2);
									$("#ContactInfoBox_alt_address_line3"	).text(returnedData.formData.alt_address_line3);
									$("#ContactInfoBox_alt_county"			).text(returnedData.formData.alt_county_text);
									$("#ContactInfoBox_alt_city"			).text(returnedData.formData.alt_city);
									$("#ContactInfoBox_alt_state"			).text(returnedData.formData.alt_state);
									$("#ContactInfoBox_alt_zip"				).text(returnedData.formData.alt_zip);

									$("#ContactInfoBox_business_email" 	).text(returnedData.formData.business_email);
									$("#ContactInfoBox_work_number"		).text(returnedData.formData.work_phone_number);
									$("#ContactInfoBox_fax_number"		).text(returnedData.formData.fax_number);
									$("#ContactInfoBox_cell_number"		).text(returnedData.formData.mobile_number);
								}
							},
			error		: 	function (returnedData)
							{
								console.log('Submission Error');
								callMessengerError("There was an error with your form submission. Please refresh the page.");
							}
			});

	});

	// Profile Contact Info Form Populate Dropdown - Primary County
	$('#ProfileContactInfoForm_pri_state_Field').change(function(e)
	{
		e.preventDefault();
		var priCountyField 	=	$("#ProfileContactInfoForm_pri_county_Field option:gt(0)");
		var priZipField 	=	$("#ProfileContactInfoForm_pri_zip_Field option:gt(0)");
		var priCityField 	=	$("#ProfileContactInfoForm_pri_city_Field option:gt(0)");
		var stateVarText	= 	$("#ProfileContactInfoForm_pri_state_Field option:selected").text();
		var stateVar 		= 	$("#ProfileContactInfoForm_pri_state_Field").val();

		// Revert County
		priCountyField.remove();
		$("#ProfileContactInfoForm_pri_county_Field option:first").text('Choose a State first.');

		// Revert Zip Code
		priZipField.remove();
		$("#ProfileContactInfoForm_pri_zip_Field option:first").text('Choose a County first.');

		// Revert City
		priCityField.remove();
		$("#ProfileContactInfoForm_pri_city_Field option:first").text('Choose a Zip Code first.');


		if(stateVar == '' || stateVar == 0)
		{
			callMessengerError("Please choose a valid state.");
		}
		else
		{
			// Get Counties
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info-counties/' + stateVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	stateVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									priCountyField.remove();
									$("#ProfileContactInfoForm_pri_county_Field option:first").text('Please choose a(n) ' + stateVarText + ' county.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileContactInfoForm_pri_county_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your county request. Please refresh the page.");
								}
				});
		}
	});

	// Profile Contact Info Form Populate Dropdown - Primary Zip Code
	$('#ProfileContactInfoForm_pri_county_Field').change(function(e)
	{
		e.preventDefault();
		var priZipField 	=	$("#ProfileContactInfoForm_pri_zip_Field option:gt(0)");
		var priCityField 	=	$("#ProfileContactInfoForm_pri_city_Field option:gt(0)");
		var countyVarText	= 	$("#ProfileContactInfoForm_pri_county_Field option:selected").text();
		var countyVar 		= 	$("#ProfileContactInfoForm_pri_county_Field").val();

		// Revert Zip Code
		priZipField.remove();
		$("#ProfileContactInfoForm_pri_zip_Field option:first").text('Choose a County first.');

		// Revert City
		priCityField.remove();
		$("#ProfileContactInfoForm_pri_city_Field option:first").text('Choose a Zip Code first.');


		if(countyVar == '' || countyVar == 0)
		{
			callMessengerError("Please choose a valid county.");
		}
		else
		{
			// Get Zip Codes
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info-zipcodes/' + countyVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	countyVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									priZipField.remove();
									$("#ProfileContactInfoForm_pri_zip_Field option:first").text('Please choose ' + countyVarText + ' County Zip Code.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileContactInfoForm_pri_zip_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your zip code request. Please refresh the page.");
								}
				});
		}
	});

	// Profile Contact Info Form Populate Dropdown - Primary City
	$('#ProfileContactInfoForm_pri_zip_Field').change(function(e)
	{
		e.preventDefault();
		var priCityField 	=	$("#ProfileContactInfoForm_pri_city_Field option:gt(0)");
		var zipVarText		= 	$("#ProfileContactInfoForm_pri_zip_Field option:selected").text();
		var zipVar 			= 	$("#ProfileContactInfoForm_pri_zip_Field").val();

		// Revert City
		priCityField.remove();
		$("#ProfileContactInfoForm_pri_city_Field option:first").text('Choose a Zip Code first.');

		if(zipVar == '' || zipVar == 0)
		{
			callMessengerError("Please choose a valid zip.");
		}
		else
		{
			// Get Zip Codes
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info-cities/' + zipVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	zipVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									priCityField.remove();
									$("#ProfileContactInfoForm_pri_city_Field option:first").text('Please choose a city in ' + zipVarText + '.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileContactInfoForm_pri_city_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your zip code request. Please refresh the page.");
								}
				});
		}
	});

	// Profile Contact Info Form Populate Dropdown - Alternative County
	$('#ProfileContactInfoForm_alt_state_Field').change(function(e)
	{
		e.preventDefault();
		var altCountyField 	=	$("#ProfileContactInfoForm_alt_county_Field option:gt(0)");
		var altZipField 	=	$("#ProfileContactInfoForm_alt_zip_Field option:gt(0)");
		var altCityField 	=	$("#ProfileContactInfoForm_alt_city_Field option:gt(0)");
		var stateVarText	= 	$("#ProfileContactInfoForm_alt_state_Field option:selected").text();
		var stateVar 		= 	$("#ProfileContactInfoForm_alt_state_Field").val();

		// Revert County
		altCountyField.remove();
		$("#ProfileContactInfoForm_alt_county_Field option:first").text('Choose a State first.');

		// Revert Zip Code
		altZipField.remove();
		$("#ProfileContactInfoForm_alt_zip_Field option:first").text('Choose a County first.');

		// Revert City
		altCityField.remove();
		$("#ProfileContactInfoForm_alt_city_Field option:first").text('Choose a Zip Code first.');


		if(stateVar == '' || stateVar == 0)
		{
			callMessengerError("Please choose a valid state.");
		}
		else
		{
			// Get Counties
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info-counties/' + stateVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	stateVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									altCountyField.remove();
									$("#ProfileContactInfoForm_alt_county_Field option:first").text('Please choose a(n) ' + stateVarText + ' county.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileContactInfoForm_alt_county_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your county request. Please refresh the page.");
								}
				});
		}
	});

	// Profile Contact Info Form Populate Dropdown - Alternative Zip Code
	$('#ProfileContactInfoForm_alt_county_Field').change(function(e)
	{
		e.preventDefault();
		var altZipField 	=	$("#ProfileContactInfoForm_alt_zip_Field option:gt(0)");
		var altCityField 	=	$("#ProfileContactInfoForm_alt_city_Field option:gt(0)");
		var countyVarText	= 	$("#ProfileContactInfoForm_alt_county_Field option:selected").text();
		var countyVar 		= 	$("#ProfileContactInfoForm_alt_county_Field").val();

		// Revert Zip Code
		altZipField.remove();
		$("#ProfileContactInfoForm_alt_zip_Field option:first").text('Choose a County first.');

		// Revert City
		altCityField.remove();
		$("#ProfileContactInfoForm_alt_city_Field option:first").text('Choose a Zip Code first.');


		if(countyVar == '' || countyVar == 0)
		{
			callMessengerError("Please choose a valid county.");
		}
		else
		{
			// Get Zip Codes
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info-zipcodes/' + countyVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	countyVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									altZipField.remove();
									$("#ProfileContactInfoForm_alt_zip_Field option:first").text('Please choose ' + countyVarText + ' County Zip Code.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileContactInfoForm_alt_zip_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your zip code request. Please refresh the page.");
								}
				});
		}
	});

	// Profile Contact Info Form Populate Dropdown - Alternative City
	$('#ProfileContactInfoForm_alt_zip_Field').change(function(e)
	{
		e.preventDefault();
		var altCityField 	=	$("#ProfileContactInfoForm_alt_city_Field option:gt(0)");
		var zipVarText		= 	$("#ProfileContactInfoForm_alt_zip_Field option:selected").text();
		var zipVar 			= 	$("#ProfileContactInfoForm_alt_zip_Field").val();

		// Revert City
		altCityField.remove();
		$("#ProfileContactInfoForm_alt_city_Field option:first").text('Choose a Zip Code first.');

		if(zipVar == '' || zipVar == 0)
		{
			callMessengerError("Please choose a valid zip.");
		}
		else
		{
			// Get Zip Codes
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-contact-info-cities/' + zipVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	zipVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									altCityField.remove();
									$("#ProfileContactInfoForm_alt_city_Field option:first").text('Please choose a city in ' + zipVarText + '.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileContactInfoForm_alt_city_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your zip code request. Please refresh the page.");
								}
				});
		}
	});




	// Profile Commission Form Submission
	$("#ProfileCommissionForm").submit(function(e)
	{
		e.preventDefault();

		// Remove Error Class
		$(".form-group").removeClass("has-error");

		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-commission',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	$("#ProfileCommissionForm").serialize(),
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								console.log(returnedData);
								if(returnedData.status == false)
								{
									$.each(returnedData.message, function(validationIndex, validationObjectValues)
									{
										var formMessage;
										$.each(validationObjectValues, function(objectIndex, objectValue)
										{
											formMessage	=	objectValue;
										});

										// Add Error Class to each div id
										$("#ProfileCommissionFormGroup_" + validationIndex ).addClass("has-error");

										// Display Error Message
										callMessengerError((formMessage == "" ? "There is an error with your form. Please re-check your entries." : formMessage))
									});

								}
								else
								{
									// Process Returned Data
									callMessengerSuccess("Your updates were submitted successfully.");

									// Update Modal Form Values
									$("#ProfileCommissionForm").trigger('reset');

									// Update Page Data
									getProfileCommissionsList();
								}
							},
			error		: 	function (returnedData)
							{
								console.log('Submission Error');
								callMessengerError("There was an error with your form submission. Please refresh the page.");
							}
			});

	});

	// Profile Commission Form Populate Dropdown - County
	$('#ProfileCommissionForm_state_Field').change(function(e)
	{
		e.preventDefault();
		var countyField 	=	$("#ProfileCommissionForm_county_fips_Field option:gt(0)");
		var stateVarText	= 	$("#ProfileCommissionForm_state_Field option:selected").text();
		var stateVar 		= 	$("#ProfileCommissionForm_state_Field").val();

		// Revert County
		countyField.remove();
		$("#ProfileCommissionForm_county_fips_Field option:first").text('Choose a State first.');

		if(stateVar == '' || stateVar == 0)
		{
			callMessengerError("Please choose a valid state.");
		}
		else
		{
			// Get Counties
			$.ajax({
				url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-commission-counties/' + stateVar,
				type		: 	'POST',
				contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
				async		: 	true,
				cache		: 	false,
				processData	: 	true,
				data		: 	stateVar,
				success		: 	function (returnedData)
								{
									handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
									console.log(returnedData);
									// Process Returned Data
									// remove old options
									countyField.remove();
									$("#ProfileCommissionForm_county_fips_Field option:first").text('Please choose a(n) ' + stateVarText + ' county.');
									$.each(returnedData.storedData, function(value, key)
									{
									  $("#ProfileCommissionForm_county_fips_Field").append($("<option></option>").attr("value", value).text(key));
									});
								},
				error		: 	function (returnedData)
								{
									console.log('Submission Error');
									callMessengerError("There was an error with your county request. Please refresh the page.");
								}
				});
		}
	});

	// Profile Certification Form Submission
	$("#ProfileCertificationForm").submit(function(e)
	{
		e.preventDefault();

		// Remove Error Class
		$(".form-group").removeClass("has-error");

		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-certification',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	$("#ProfileCertificationForm").serialize(),
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								console.log(returnedData);
								if(returnedData.status == false)
								{
									$.each(returnedData.message, function(validationIndex, validationObjectValues)
									{
										var formMessage;
										$.each(validationObjectValues, function(objectIndex, objectValue)
										{
											formMessage	=	objectValue;
										});

										// Add Error Class to each div id
										$("#ProfileCertificationFormGroup_" + validationIndex ).addClass("has-error");

										// Display Error Message
										callMessengerError((formMessage == "" ? "There is an error with your form. Please re-check your entries." : formMessage))
									});

								}
								else
								{
									// Process Returned Data
									callMessengerSuccess("Your updates were submitted successfully.");

									// Update Modal Form Values
									$("#ProfileCertificationForm").trigger('reset');

									// Update Page Data
									getProfileCertificationsList();
								}
							},
			error		: 	function (returnedData)
							{
								console.log('Submission Error');
								callMessengerError("There was an error with your form submission. Please refresh the page.");
							}
			});

	});

	// Profile Affiliation Form Submission
	$("#ProfileAffiliationForm").submit(function(e)
	{
		e.preventDefault();

		// Remove Error Class
		$(".form-group").removeClass("has-error");

		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-affiliation',
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	$("#ProfileAffiliationForm").serialize(),
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								console.log(returnedData);
								if(returnedData.status == false)
								{
									$.each(returnedData.message, function(validationIndex, validationObjectValues)
									{
										var formMessage;
										$.each(validationObjectValues, function(objectIndex, objectValue)
										{
											formMessage	=	objectValue;
										});

										// Add Error Class to each div id
										$("#ProfileAffiliationFormGroup_" + validationIndex ).addClass("has-error");

										// Display Error Message
										callMessengerError((formMessage == "" ? "There is an error with your form. Please re-check your entries." : formMessage))
									});

								}
								else
								{
									// Process Returned Data
									callMessengerSuccess("Your updates were submitted successfully.");

									// Update Modal Form Values
									$("#ProfileAffiliationForm").trigger('reset');

									// Update Page Data
									getProfileAffiliationsList();
								}
							},
			error		: 	function (returnedData)
							{
								console.log('Submission Error');
								callMessengerError("There was an error with your form submission. Please refresh the page.");
							}
			});

	});

});

// Commission Wells
$(document).on({
    mouseenter: function (event) {
        var wellID 				= 	event.target.id;
		var deleteButtonDivID	=	wellID.replace("c", "deleteC").replace("Well", "WellButton");
		var deleteIconDivID		=	wellID.replace("c", "deleteC").replace("Well", "WellIcon");
		$("#" + deleteButtonDivID ).removeClass("hidden");
		$("#" + deleteIconDivID ).removeClass("hidden");
    },
    mouseleave: function (event) {
        var wellID 		= 	event.target.id;
		var deleteButtonDivID	=	wellID.replace("c", "deleteC").replace("Well", "WellButton");
		var deleteIconDivID		=	wellID.replace("c", "deleteC").replace("Well", "WellIcon");
		$("#" + deleteButtonDivID ).addClass("hidden");
		$("#" + deleteIconDivID ).addClass("hidden");
    }
}, ".commissionWell");

$(document).on({
    click: function (event) {
        // extract the id
		var iconID 			= 	event.target.id;
		var commissionID 	= 	iconID.replace("deleteCommissionWellIcon_","");

		// send the id via ajax to the model
		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-commission-delete/' + commissionID,
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	commissionID,
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								getProfileCommissionsList();
							},
			error		: 	function (returnedData)
							{
								console.log('Retrieval Error');
								callMessengerError("There was an error with your data retrieval. Please refresh the page.");
								getProfileCommissionsList();
							}
			});


    }
}, ".deleteCommissionWellIcon");

// Certification Wells
$(document).on({
    mouseenter: function (event) {
        var wellID 				= 	event.target.id;
		var deleteButtonDivID	=	wellID.replace("c", "deleteC").replace("Well", "WellButton");
		var deleteIconDivID		=	wellID.replace("c", "deleteC").replace("Well", "WellIcon");
		$("#" + deleteButtonDivID ).removeClass("hidden");
		$("#" + deleteIconDivID ).removeClass("hidden");
    },
    mouseleave: function (event) {
        var wellID 		= 	event.target.id;
		var deleteButtonDivID	=	wellID.replace("c", "deleteC").replace("Well", "WellButton");
		var deleteIconDivID		=	wellID.replace("c", "deleteC").replace("Well", "WellIcon");
		$("#" + deleteButtonDivID ).addClass("hidden");
		$("#" + deleteIconDivID ).addClass("hidden");
    }
}, ".certificationWell");

$(document).on({
    click: function (event) {
        // extract the id
		var iconID 			= 	event.target.id;
		var certificationID = 	iconID.replace("deleteCertificationWellIcon_","");

		// send the id via ajax to the model
		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-certification-delete/' + certificationID,
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	certificationID,
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								getProfileCertificationsList();
							},
			error		: 	function (returnedData)
							{
								console.log('Retrieval Error');
								callMessengerError("There was an error with your data retrieval. Please refresh the page.");
								getProfileCertificationsList();
							}
			});


    }
}, ".deleteCertificationWellIcon");

// Affiliation
$(document).on({
    mouseenter: function (event) {
        var wellID 				= 	event.target.id;
		var deleteButtonDivID	=	wellID.replace("a", "deleteA").replace("Well", "WellButton");
		var deleteIconDivID		=	wellID.replace("a", "deleteA").replace("Well", "WellIcon");
		$("#" + deleteButtonDivID ).removeClass("hidden");
		$("#" + deleteIconDivID ).removeClass("hidden");
    },
    mouseleave: function (event) {
        var wellID 		= 	event.target.id;
		var deleteButtonDivID	=	wellID.replace("a", "deleteA").replace("Well", "WellButton");
		var deleteIconDivID		=	wellID.replace("a", "deleteA").replace("Well", "WellIcon");
		$("#" + deleteButtonDivID ).addClass("hidden");
		$("#" + deleteIconDivID ).addClass("hidden");
    }
}, ".affiliationWell");

$(document).on({
    click: function (event) {
        // extract the id
		var iconID 			= 	event.target.id;
		var affiliationID 	= 	iconID.replace("deleteAffiliationWellIcon_","");

		// send the id via ajax to the model
		$.ajax({
			url			: 	window.location.protocol + "//" + window.location.host + '/ajax-profile-forms/profile-affiliation-delete/' + affiliationID,
			type		: 	'POST',
			contentType	: 	"application/x-www-form-urlencoded; charset=utf-8",
			async		: 	true,
			cache		: 	true,
        	processData	: 	true,
			data		: 	affiliationID,
			success		: 	function (returnedData)
							{
								handleForceRefreshInAjaxResponse(returnedData.forcePageRefresh);
								getProfileAffiliationsList();
							},
			error		: 	function (returnedData)
							{
								console.log('Retrieval Error');
								callMessengerError("There was an error with your data retrieval. Please refresh the page.");
								getProfileAffiliationsList();
							}
			});


    }
}, ".deleteAffiliationWellIcon");
