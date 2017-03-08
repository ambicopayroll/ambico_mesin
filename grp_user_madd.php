<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "grp_user_minfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$grp_user_m_add = NULL; // Initialize page object first

class cgrp_user_m_add extends cgrp_user_m {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'grp_user_m';

	// Page object name
	var $PageObjName = 'grp_user_m_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (grp_user_m)
		if (!isset($GLOBALS["grp_user_m"]) || get_class($GLOBALS["grp_user_m"]) == "cgrp_user_m") {
			$GLOBALS["grp_user_m"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["grp_user_m"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'grp_user_m', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("grp_user_mlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->grp_user_id->SetVisibility();
		$this->grp_user_name->SetVisibility();
		$this->lastupdate_date->SetVisibility();
		$this->lastupdate_user->SetVisibility();
		$this->detector->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $grp_user_m;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($grp_user_m);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["grp_user_id"] != "") {
				$this->grp_user_id->setQueryStringValue($_GET["grp_user_id"]);
				$this->setKey("grp_user_id", $this->grp_user_id->CurrentValue); // Set up key
			} else {
				$this->setKey("grp_user_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("grp_user_mlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "grp_user_mlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "grp_user_mview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->grp_user_id->CurrentValue = NULL;
		$this->grp_user_id->OldValue = $this->grp_user_id->CurrentValue;
		$this->grp_user_name->CurrentValue = NULL;
		$this->grp_user_name->OldValue = $this->grp_user_name->CurrentValue;
		$this->lastupdate_date->CurrentValue = NULL;
		$this->lastupdate_date->OldValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_user->CurrentValue = NULL;
		$this->lastupdate_user->OldValue = $this->lastupdate_user->CurrentValue;
		$this->detector->CurrentValue = NULL;
		$this->detector->OldValue = $this->detector->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->grp_user_id->FldIsDetailKey) {
			$this->grp_user_id->setFormValue($objForm->GetValue("x_grp_user_id"));
		}
		if (!$this->grp_user_name->FldIsDetailKey) {
			$this->grp_user_name->setFormValue($objForm->GetValue("x_grp_user_name"));
		}
		if (!$this->lastupdate_date->FldIsDetailKey) {
			$this->lastupdate_date->setFormValue($objForm->GetValue("x_lastupdate_date"));
			$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		}
		if (!$this->lastupdate_user->FldIsDetailKey) {
			$this->lastupdate_user->setFormValue($objForm->GetValue("x_lastupdate_user"));
		}
		if (!$this->detector->FldIsDetailKey) {
			$this->detector->setFormValue($objForm->GetValue("x_detector"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->grp_user_id->CurrentValue = $this->grp_user_id->FormValue;
		$this->grp_user_name->CurrentValue = $this->grp_user_name->FormValue;
		$this->lastupdate_date->CurrentValue = $this->lastupdate_date->FormValue;
		$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		$this->lastupdate_user->CurrentValue = $this->lastupdate_user->FormValue;
		$this->detector->CurrentValue = $this->detector->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->grp_user_id->setDbValue($rs->fields('grp_user_id'));
		$this->grp_user_name->setDbValue($rs->fields('grp_user_name'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
		$this->detector->setDbValue($rs->fields('detector'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->grp_user_id->DbValue = $row['grp_user_id'];
		$this->grp_user_name->DbValue = $row['grp_user_name'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
		$this->detector->DbValue = $row['detector'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("grp_user_id")) <> "")
			$this->grp_user_id->CurrentValue = $this->getKey("grp_user_id"); // grp_user_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// grp_user_id
		// grp_user_name
		// lastupdate_date
		// lastupdate_user
		// detector

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// grp_user_id
		$this->grp_user_id->ViewValue = $this->grp_user_id->CurrentValue;
		$this->grp_user_id->ViewCustomAttributes = "";

		// grp_user_name
		$this->grp_user_name->ViewValue = $this->grp_user_name->CurrentValue;
		$this->grp_user_name->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

		// detector
		$this->detector->ViewValue = $this->detector->CurrentValue;
		$this->detector->ViewCustomAttributes = "";

			// grp_user_id
			$this->grp_user_id->LinkCustomAttributes = "";
			$this->grp_user_id->HrefValue = "";
			$this->grp_user_id->TooltipValue = "";

			// grp_user_name
			$this->grp_user_name->LinkCustomAttributes = "";
			$this->grp_user_name->HrefValue = "";
			$this->grp_user_name->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";

			// detector
			$this->detector->LinkCustomAttributes = "";
			$this->detector->HrefValue = "";
			$this->detector->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// grp_user_id
			$this->grp_user_id->EditAttrs["class"] = "form-control";
			$this->grp_user_id->EditCustomAttributes = "";
			$this->grp_user_id->EditValue = ew_HtmlEncode($this->grp_user_id->CurrentValue);
			$this->grp_user_id->PlaceHolder = ew_RemoveHtml($this->grp_user_id->FldCaption());

			// grp_user_name
			$this->grp_user_name->EditAttrs["class"] = "form-control";
			$this->grp_user_name->EditCustomAttributes = "";
			$this->grp_user_name->EditValue = ew_HtmlEncode($this->grp_user_name->CurrentValue);
			$this->grp_user_name->PlaceHolder = ew_RemoveHtml($this->grp_user_name->FldCaption());

			// lastupdate_date
			$this->lastupdate_date->EditAttrs["class"] = "form-control";
			$this->lastupdate_date->EditCustomAttributes = "";
			$this->lastupdate_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->lastupdate_date->CurrentValue, 8));
			$this->lastupdate_date->PlaceHolder = ew_RemoveHtml($this->lastupdate_date->FldCaption());

			// lastupdate_user
			$this->lastupdate_user->EditAttrs["class"] = "form-control";
			$this->lastupdate_user->EditCustomAttributes = "";
			$this->lastupdate_user->EditValue = ew_HtmlEncode($this->lastupdate_user->CurrentValue);
			$this->lastupdate_user->PlaceHolder = ew_RemoveHtml($this->lastupdate_user->FldCaption());

			// detector
			$this->detector->EditAttrs["class"] = "form-control";
			$this->detector->EditCustomAttributes = "";
			$this->detector->EditValue = ew_HtmlEncode($this->detector->CurrentValue);
			$this->detector->PlaceHolder = ew_RemoveHtml($this->detector->FldCaption());

			// Add refer script
			// grp_user_id

			$this->grp_user_id->LinkCustomAttributes = "";
			$this->grp_user_id->HrefValue = "";

			// grp_user_name
			$this->grp_user_name->LinkCustomAttributes = "";
			$this->grp_user_name->HrefValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";

			// detector
			$this->detector->LinkCustomAttributes = "";
			$this->detector->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->grp_user_id->FldIsDetailKey && !is_null($this->grp_user_id->FormValue) && $this->grp_user_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_user_id->FldCaption(), $this->grp_user_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->grp_user_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->grp_user_id->FldErrMsg());
		}
		if (!$this->grp_user_name->FldIsDetailKey && !is_null($this->grp_user_name->FormValue) && $this->grp_user_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->grp_user_name->FldCaption(), $this->grp_user_name->ReqErrMsg));
		}
		if (!$this->lastupdate_date->FldIsDetailKey && !is_null($this->lastupdate_date->FormValue) && $this->lastupdate_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_date->FldCaption(), $this->lastupdate_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->lastupdate_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->lastupdate_date->FldErrMsg());
		}
		if (!$this->lastupdate_user->FldIsDetailKey && !is_null($this->lastupdate_user->FormValue) && $this->lastupdate_user->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lastupdate_user->FldCaption(), $this->lastupdate_user->ReqErrMsg));
		}
		if (!$this->detector->FldIsDetailKey && !is_null($this->detector->FormValue) && $this->detector->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->detector->FldCaption(), $this->detector->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// grp_user_id
		$this->grp_user_id->SetDbValueDef($rsnew, $this->grp_user_id->CurrentValue, 0, FALSE);

		// grp_user_name
		$this->grp_user_name->SetDbValueDef($rsnew, $this->grp_user_name->CurrentValue, "", FALSE);

		// lastupdate_date
		$this->lastupdate_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// lastupdate_user
		$this->lastupdate_user->SetDbValueDef($rsnew, $this->lastupdate_user->CurrentValue, "", FALSE);

		// detector
		$this->detector->SetDbValueDef($rsnew, $this->detector->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['grp_user_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("grp_user_mlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($grp_user_m_add)) $grp_user_m_add = new cgrp_user_m_add();

// Page init
$grp_user_m_add->Page_Init();

// Page main
$grp_user_m_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$grp_user_m_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fgrp_user_madd = new ew_Form("fgrp_user_madd", "add");

// Validate form
fgrp_user_madd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_grp_user_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_m->grp_user_id->FldCaption(), $grp_user_m->grp_user_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_grp_user_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($grp_user_m->grp_user_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_grp_user_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_m->grp_user_name->FldCaption(), $grp_user_m->grp_user_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_m->lastupdate_date->FldCaption(), $grp_user_m->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($grp_user_m->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_m->lastupdate_user->FldCaption(), $grp_user_m->lastupdate_user->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_detector");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $grp_user_m->detector->FldCaption(), $grp_user_m->detector->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fgrp_user_madd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fgrp_user_madd.ValidateRequired = true;
<?php } else { ?>
fgrp_user_madd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$grp_user_m_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $grp_user_m_add->ShowPageHeader(); ?>
<?php
$grp_user_m_add->ShowMessage();
?>
<form name="fgrp_user_madd" id="fgrp_user_madd" class="<?php echo $grp_user_m_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($grp_user_m_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $grp_user_m_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="grp_user_m">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($grp_user_m_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($grp_user_m->grp_user_id->Visible) { // grp_user_id ?>
	<div id="r_grp_user_id" class="form-group">
		<label id="elh_grp_user_m_grp_user_id" for="x_grp_user_id" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_m->grp_user_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_m->grp_user_id->CellAttributes() ?>>
<span id="el_grp_user_m_grp_user_id">
<input type="text" data-table="grp_user_m" data-field="x_grp_user_id" name="x_grp_user_id" id="x_grp_user_id" size="30" placeholder="<?php echo ew_HtmlEncode($grp_user_m->grp_user_id->getPlaceHolder()) ?>" value="<?php echo $grp_user_m->grp_user_id->EditValue ?>"<?php echo $grp_user_m->grp_user_id->EditAttributes() ?>>
</span>
<?php echo $grp_user_m->grp_user_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_m->grp_user_name->Visible) { // grp_user_name ?>
	<div id="r_grp_user_name" class="form-group">
		<label id="elh_grp_user_m_grp_user_name" for="x_grp_user_name" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_m->grp_user_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_m->grp_user_name->CellAttributes() ?>>
<span id="el_grp_user_m_grp_user_name">
<input type="text" data-table="grp_user_m" data-field="x_grp_user_name" name="x_grp_user_name" id="x_grp_user_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_m->grp_user_name->getPlaceHolder()) ?>" value="<?php echo $grp_user_m->grp_user_name->EditValue ?>"<?php echo $grp_user_m->grp_user_name->EditAttributes() ?>>
</span>
<?php echo $grp_user_m->grp_user_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_m->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_grp_user_m_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_m->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_m->lastupdate_date->CellAttributes() ?>>
<span id="el_grp_user_m_lastupdate_date">
<input type="text" data-table="grp_user_m" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($grp_user_m->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $grp_user_m->lastupdate_date->EditValue ?>"<?php echo $grp_user_m->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $grp_user_m->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_m->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_grp_user_m_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_m->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_m->lastupdate_user->CellAttributes() ?>>
<span id="el_grp_user_m_lastupdate_user">
<input type="text" data-table="grp_user_m" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($grp_user_m->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $grp_user_m->lastupdate_user->EditValue ?>"<?php echo $grp_user_m->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $grp_user_m->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($grp_user_m->detector->Visible) { // detector ?>
	<div id="r_detector" class="form-group">
		<label id="elh_grp_user_m_detector" for="x_detector" class="col-sm-2 control-label ewLabel"><?php echo $grp_user_m->detector->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $grp_user_m->detector->CellAttributes() ?>>
<span id="el_grp_user_m_detector">
<textarea data-table="grp_user_m" data-field="x_detector" name="x_detector" id="x_detector" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($grp_user_m->detector->getPlaceHolder()) ?>"<?php echo $grp_user_m->detector->EditAttributes() ?>><?php echo $grp_user_m->detector->EditValue ?></textarea>
</span>
<?php echo $grp_user_m->detector->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$grp_user_m_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $grp_user_m_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fgrp_user_madd.Init();
</script>
<?php
$grp_user_m_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$grp_user_m_add->Page_Terminate();
?>
