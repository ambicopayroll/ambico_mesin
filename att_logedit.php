<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "att_loginfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$att_log_edit = NULL; // Initialize page object first

class catt_log_edit extends catt_log {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'att_log';

	// Page object name
	var $PageObjName = 'att_log_edit';

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

		// Table object (att_log)
		if (!isset($GLOBALS["att_log"]) || get_class($GLOBALS["att_log"]) == "catt_log") {
			$GLOBALS["att_log"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["att_log"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'att_log', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("att_loglist.php"));
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
		$this->sn->SetVisibility();
		$this->scan_date->SetVisibility();
		$this->pin->SetVisibility();
		$this->verifymode->SetVisibility();
		$this->inoutmode->SetVisibility();
		$this->reserved->SetVisibility();
		$this->work_code->SetVisibility();
		$this->att_id->SetVisibility();

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
		global $EW_EXPORT, $att_log;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($att_log);
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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;

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

		// Load key from QueryString
		if (@$_GET["sn"] <> "") {
			$this->sn->setQueryStringValue($_GET["sn"]);
		}
		if (@$_GET["scan_date"] <> "") {
			$this->scan_date->setQueryStringValue($_GET["scan_date"]);
		}
		if (@$_GET["pin"] <> "") {
			$this->pin->setQueryStringValue($_GET["pin"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->sn->CurrentValue == "") {
			$this->Page_Terminate("att_loglist.php"); // Invalid key, return to list
		}
		if ($this->scan_date->CurrentValue == "") {
			$this->Page_Terminate("att_loglist.php"); // Invalid key, return to list
		}
		if ($this->pin->CurrentValue == "") {
			$this->Page_Terminate("att_loglist.php"); // Invalid key, return to list
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("att_loglist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "att_loglist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->sn->FldIsDetailKey) {
			$this->sn->setFormValue($objForm->GetValue("x_sn"));
		}
		if (!$this->scan_date->FldIsDetailKey) {
			$this->scan_date->setFormValue($objForm->GetValue("x_scan_date"));
			$this->scan_date->CurrentValue = ew_UnFormatDateTime($this->scan_date->CurrentValue, 0);
		}
		if (!$this->pin->FldIsDetailKey) {
			$this->pin->setFormValue($objForm->GetValue("x_pin"));
		}
		if (!$this->verifymode->FldIsDetailKey) {
			$this->verifymode->setFormValue($objForm->GetValue("x_verifymode"));
		}
		if (!$this->inoutmode->FldIsDetailKey) {
			$this->inoutmode->setFormValue($objForm->GetValue("x_inoutmode"));
		}
		if (!$this->reserved->FldIsDetailKey) {
			$this->reserved->setFormValue($objForm->GetValue("x_reserved"));
		}
		if (!$this->work_code->FldIsDetailKey) {
			$this->work_code->setFormValue($objForm->GetValue("x_work_code"));
		}
		if (!$this->att_id->FldIsDetailKey) {
			$this->att_id->setFormValue($objForm->GetValue("x_att_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->sn->CurrentValue = $this->sn->FormValue;
		$this->scan_date->CurrentValue = $this->scan_date->FormValue;
		$this->scan_date->CurrentValue = ew_UnFormatDateTime($this->scan_date->CurrentValue, 0);
		$this->pin->CurrentValue = $this->pin->FormValue;
		$this->verifymode->CurrentValue = $this->verifymode->FormValue;
		$this->inoutmode->CurrentValue = $this->inoutmode->FormValue;
		$this->reserved->CurrentValue = $this->reserved->FormValue;
		$this->work_code->CurrentValue = $this->work_code->FormValue;
		$this->att_id->CurrentValue = $this->att_id->FormValue;
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
		$this->sn->setDbValue($rs->fields('sn'));
		$this->scan_date->setDbValue($rs->fields('scan_date'));
		$this->pin->setDbValue($rs->fields('pin'));
		$this->verifymode->setDbValue($rs->fields('verifymode'));
		$this->inoutmode->setDbValue($rs->fields('inoutmode'));
		$this->reserved->setDbValue($rs->fields('reserved'));
		$this->work_code->setDbValue($rs->fields('work_code'));
		$this->att_id->setDbValue($rs->fields('att_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->sn->DbValue = $row['sn'];
		$this->scan_date->DbValue = $row['scan_date'];
		$this->pin->DbValue = $row['pin'];
		$this->verifymode->DbValue = $row['verifymode'];
		$this->inoutmode->DbValue = $row['inoutmode'];
		$this->reserved->DbValue = $row['reserved'];
		$this->work_code->DbValue = $row['work_code'];
		$this->att_id->DbValue = $row['att_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// sn
		// scan_date
		// pin
		// verifymode
		// inoutmode
		// reserved
		// work_code
		// att_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// sn
		$this->sn->ViewValue = $this->sn->CurrentValue;
		$this->sn->ViewCustomAttributes = "";

		// scan_date
		$this->scan_date->ViewValue = $this->scan_date->CurrentValue;
		$this->scan_date->ViewValue = ew_FormatDateTime($this->scan_date->ViewValue, 0);
		$this->scan_date->ViewCustomAttributes = "";

		// pin
		$this->pin->ViewValue = $this->pin->CurrentValue;
		$this->pin->ViewCustomAttributes = "";

		// verifymode
		$this->verifymode->ViewValue = $this->verifymode->CurrentValue;
		$this->verifymode->ViewCustomAttributes = "";

		// inoutmode
		$this->inoutmode->ViewValue = $this->inoutmode->CurrentValue;
		$this->inoutmode->ViewCustomAttributes = "";

		// reserved
		$this->reserved->ViewValue = $this->reserved->CurrentValue;
		$this->reserved->ViewCustomAttributes = "";

		// work_code
		$this->work_code->ViewValue = $this->work_code->CurrentValue;
		$this->work_code->ViewCustomAttributes = "";

		// att_id
		$this->att_id->ViewValue = $this->att_id->CurrentValue;
		$this->att_id->ViewCustomAttributes = "";

			// sn
			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";
			$this->sn->TooltipValue = "";

			// scan_date
			$this->scan_date->LinkCustomAttributes = "";
			$this->scan_date->HrefValue = "";
			$this->scan_date->TooltipValue = "";

			// pin
			$this->pin->LinkCustomAttributes = "";
			$this->pin->HrefValue = "";
			$this->pin->TooltipValue = "";

			// verifymode
			$this->verifymode->LinkCustomAttributes = "";
			$this->verifymode->HrefValue = "";
			$this->verifymode->TooltipValue = "";

			// inoutmode
			$this->inoutmode->LinkCustomAttributes = "";
			$this->inoutmode->HrefValue = "";
			$this->inoutmode->TooltipValue = "";

			// reserved
			$this->reserved->LinkCustomAttributes = "";
			$this->reserved->HrefValue = "";
			$this->reserved->TooltipValue = "";

			// work_code
			$this->work_code->LinkCustomAttributes = "";
			$this->work_code->HrefValue = "";
			$this->work_code->TooltipValue = "";

			// att_id
			$this->att_id->LinkCustomAttributes = "";
			$this->att_id->HrefValue = "";
			$this->att_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// sn
			$this->sn->EditAttrs["class"] = "form-control";
			$this->sn->EditCustomAttributes = "";
			$this->sn->EditValue = $this->sn->CurrentValue;
			$this->sn->ViewCustomAttributes = "";

			// scan_date
			$this->scan_date->EditAttrs["class"] = "form-control";
			$this->scan_date->EditCustomAttributes = "";
			$this->scan_date->EditValue = $this->scan_date->CurrentValue;
			$this->scan_date->EditValue = ew_FormatDateTime($this->scan_date->EditValue, 0);
			$this->scan_date->ViewCustomAttributes = "";

			// pin
			$this->pin->EditAttrs["class"] = "form-control";
			$this->pin->EditCustomAttributes = "";
			$this->pin->EditValue = $this->pin->CurrentValue;
			$this->pin->ViewCustomAttributes = "";

			// verifymode
			$this->verifymode->EditAttrs["class"] = "form-control";
			$this->verifymode->EditCustomAttributes = "";
			$this->verifymode->EditValue = ew_HtmlEncode($this->verifymode->CurrentValue);
			$this->verifymode->PlaceHolder = ew_RemoveHtml($this->verifymode->FldCaption());

			// inoutmode
			$this->inoutmode->EditAttrs["class"] = "form-control";
			$this->inoutmode->EditCustomAttributes = "";
			$this->inoutmode->EditValue = ew_HtmlEncode($this->inoutmode->CurrentValue);
			$this->inoutmode->PlaceHolder = ew_RemoveHtml($this->inoutmode->FldCaption());

			// reserved
			$this->reserved->EditAttrs["class"] = "form-control";
			$this->reserved->EditCustomAttributes = "";
			$this->reserved->EditValue = ew_HtmlEncode($this->reserved->CurrentValue);
			$this->reserved->PlaceHolder = ew_RemoveHtml($this->reserved->FldCaption());

			// work_code
			$this->work_code->EditAttrs["class"] = "form-control";
			$this->work_code->EditCustomAttributes = "";
			$this->work_code->EditValue = ew_HtmlEncode($this->work_code->CurrentValue);
			$this->work_code->PlaceHolder = ew_RemoveHtml($this->work_code->FldCaption());

			// att_id
			$this->att_id->EditAttrs["class"] = "form-control";
			$this->att_id->EditCustomAttributes = "";
			$this->att_id->EditValue = ew_HtmlEncode($this->att_id->CurrentValue);
			$this->att_id->PlaceHolder = ew_RemoveHtml($this->att_id->FldCaption());

			// Edit refer script
			// sn

			$this->sn->LinkCustomAttributes = "";
			$this->sn->HrefValue = "";

			// scan_date
			$this->scan_date->LinkCustomAttributes = "";
			$this->scan_date->HrefValue = "";

			// pin
			$this->pin->LinkCustomAttributes = "";
			$this->pin->HrefValue = "";

			// verifymode
			$this->verifymode->LinkCustomAttributes = "";
			$this->verifymode->HrefValue = "";

			// inoutmode
			$this->inoutmode->LinkCustomAttributes = "";
			$this->inoutmode->HrefValue = "";

			// reserved
			$this->reserved->LinkCustomAttributes = "";
			$this->reserved->HrefValue = "";

			// work_code
			$this->work_code->LinkCustomAttributes = "";
			$this->work_code->HrefValue = "";

			// att_id
			$this->att_id->LinkCustomAttributes = "";
			$this->att_id->HrefValue = "";
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
		if (!$this->sn->FldIsDetailKey && !is_null($this->sn->FormValue) && $this->sn->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->sn->FldCaption(), $this->sn->ReqErrMsg));
		}
		if (!$this->scan_date->FldIsDetailKey && !is_null($this->scan_date->FormValue) && $this->scan_date->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scan_date->FldCaption(), $this->scan_date->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->scan_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->scan_date->FldErrMsg());
		}
		if (!$this->pin->FldIsDetailKey && !is_null($this->pin->FormValue) && $this->pin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pin->FldCaption(), $this->pin->ReqErrMsg));
		}
		if (!$this->verifymode->FldIsDetailKey && !is_null($this->verifymode->FormValue) && $this->verifymode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->verifymode->FldCaption(), $this->verifymode->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->verifymode->FormValue)) {
			ew_AddMessage($gsFormError, $this->verifymode->FldErrMsg());
		}
		if (!$this->inoutmode->FldIsDetailKey && !is_null($this->inoutmode->FormValue) && $this->inoutmode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->inoutmode->FldCaption(), $this->inoutmode->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->inoutmode->FormValue)) {
			ew_AddMessage($gsFormError, $this->inoutmode->FldErrMsg());
		}
		if (!$this->reserved->FldIsDetailKey && !is_null($this->reserved->FormValue) && $this->reserved->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reserved->FldCaption(), $this->reserved->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->reserved->FormValue)) {
			ew_AddMessage($gsFormError, $this->reserved->FldErrMsg());
		}
		if (!$this->work_code->FldIsDetailKey && !is_null($this->work_code->FormValue) && $this->work_code->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->work_code->FldCaption(), $this->work_code->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->work_code->FormValue)) {
			ew_AddMessage($gsFormError, $this->work_code->FldErrMsg());
		}
		if (!$this->att_id->FldIsDetailKey && !is_null($this->att_id->FormValue) && $this->att_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->att_id->FldCaption(), $this->att_id->ReqErrMsg));
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// sn
			// scan_date
			// pin
			// verifymode

			$this->verifymode->SetDbValueDef($rsnew, $this->verifymode->CurrentValue, 0, $this->verifymode->ReadOnly);

			// inoutmode
			$this->inoutmode->SetDbValueDef($rsnew, $this->inoutmode->CurrentValue, 0, $this->inoutmode->ReadOnly);

			// reserved
			$this->reserved->SetDbValueDef($rsnew, $this->reserved->CurrentValue, 0, $this->reserved->ReadOnly);

			// work_code
			$this->work_code->SetDbValueDef($rsnew, $this->work_code->CurrentValue, 0, $this->work_code->ReadOnly);

			// att_id
			$this->att_id->SetDbValueDef($rsnew, $this->att_id->CurrentValue, "", $this->att_id->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("att_loglist.php"), "", $this->TableVar, TRUE);
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($att_log_edit)) $att_log_edit = new catt_log_edit();

// Page init
$att_log_edit->Page_Init();

// Page main
$att_log_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$att_log_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fatt_logedit = new ew_Form("fatt_logedit", "edit");

// Validate form
fatt_logedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_sn");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->sn->FldCaption(), $att_log->sn->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scan_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->scan_date->FldCaption(), $att_log->scan_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scan_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($att_log->scan_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->pin->FldCaption(), $att_log->pin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verifymode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->verifymode->FldCaption(), $att_log->verifymode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_verifymode");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($att_log->verifymode->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_inoutmode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->inoutmode->FldCaption(), $att_log->inoutmode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_inoutmode");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($att_log->inoutmode->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reserved");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->reserved->FldCaption(), $att_log->reserved->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reserved");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($att_log->reserved->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_work_code");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->work_code->FldCaption(), $att_log->work_code->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_work_code");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($att_log->work_code->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_att_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $att_log->att_id->FldCaption(), $att_log->att_id->ReqErrMsg)) ?>");

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
fatt_logedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fatt_logedit.ValidateRequired = true;
<?php } else { ?>
fatt_logedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$att_log_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $att_log_edit->ShowPageHeader(); ?>
<?php
$att_log_edit->ShowMessage();
?>
<form name="fatt_logedit" id="fatt_logedit" class="<?php echo $att_log_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($att_log_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $att_log_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="att_log">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($att_log_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($att_log->sn->Visible) { // sn ?>
	<div id="r_sn" class="form-group">
		<label id="elh_att_log_sn" for="x_sn" class="col-sm-2 control-label ewLabel"><?php echo $att_log->sn->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->sn->CellAttributes() ?>>
<span id="el_att_log_sn">
<span<?php echo $att_log->sn->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $att_log->sn->EditValue ?></p></span>
</span>
<input type="hidden" data-table="att_log" data-field="x_sn" name="x_sn" id="x_sn" value="<?php echo ew_HtmlEncode($att_log->sn->CurrentValue) ?>">
<?php echo $att_log->sn->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->scan_date->Visible) { // scan_date ?>
	<div id="r_scan_date" class="form-group">
		<label id="elh_att_log_scan_date" for="x_scan_date" class="col-sm-2 control-label ewLabel"><?php echo $att_log->scan_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->scan_date->CellAttributes() ?>>
<span id="el_att_log_scan_date">
<span<?php echo $att_log->scan_date->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $att_log->scan_date->EditValue ?></p></span>
</span>
<input type="hidden" data-table="att_log" data-field="x_scan_date" name="x_scan_date" id="x_scan_date" value="<?php echo ew_HtmlEncode($att_log->scan_date->CurrentValue) ?>">
<?php echo $att_log->scan_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->pin->Visible) { // pin ?>
	<div id="r_pin" class="form-group">
		<label id="elh_att_log_pin" for="x_pin" class="col-sm-2 control-label ewLabel"><?php echo $att_log->pin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->pin->CellAttributes() ?>>
<span id="el_att_log_pin">
<span<?php echo $att_log->pin->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $att_log->pin->EditValue ?></p></span>
</span>
<input type="hidden" data-table="att_log" data-field="x_pin" name="x_pin" id="x_pin" value="<?php echo ew_HtmlEncode($att_log->pin->CurrentValue) ?>">
<?php echo $att_log->pin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->verifymode->Visible) { // verifymode ?>
	<div id="r_verifymode" class="form-group">
		<label id="elh_att_log_verifymode" for="x_verifymode" class="col-sm-2 control-label ewLabel"><?php echo $att_log->verifymode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->verifymode->CellAttributes() ?>>
<span id="el_att_log_verifymode">
<input type="text" data-table="att_log" data-field="x_verifymode" name="x_verifymode" id="x_verifymode" size="30" placeholder="<?php echo ew_HtmlEncode($att_log->verifymode->getPlaceHolder()) ?>" value="<?php echo $att_log->verifymode->EditValue ?>"<?php echo $att_log->verifymode->EditAttributes() ?>>
</span>
<?php echo $att_log->verifymode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->inoutmode->Visible) { // inoutmode ?>
	<div id="r_inoutmode" class="form-group">
		<label id="elh_att_log_inoutmode" for="x_inoutmode" class="col-sm-2 control-label ewLabel"><?php echo $att_log->inoutmode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->inoutmode->CellAttributes() ?>>
<span id="el_att_log_inoutmode">
<input type="text" data-table="att_log" data-field="x_inoutmode" name="x_inoutmode" id="x_inoutmode" size="30" placeholder="<?php echo ew_HtmlEncode($att_log->inoutmode->getPlaceHolder()) ?>" value="<?php echo $att_log->inoutmode->EditValue ?>"<?php echo $att_log->inoutmode->EditAttributes() ?>>
</span>
<?php echo $att_log->inoutmode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->reserved->Visible) { // reserved ?>
	<div id="r_reserved" class="form-group">
		<label id="elh_att_log_reserved" for="x_reserved" class="col-sm-2 control-label ewLabel"><?php echo $att_log->reserved->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->reserved->CellAttributes() ?>>
<span id="el_att_log_reserved">
<input type="text" data-table="att_log" data-field="x_reserved" name="x_reserved" id="x_reserved" size="30" placeholder="<?php echo ew_HtmlEncode($att_log->reserved->getPlaceHolder()) ?>" value="<?php echo $att_log->reserved->EditValue ?>"<?php echo $att_log->reserved->EditAttributes() ?>>
</span>
<?php echo $att_log->reserved->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->work_code->Visible) { // work_code ?>
	<div id="r_work_code" class="form-group">
		<label id="elh_att_log_work_code" for="x_work_code" class="col-sm-2 control-label ewLabel"><?php echo $att_log->work_code->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->work_code->CellAttributes() ?>>
<span id="el_att_log_work_code">
<input type="text" data-table="att_log" data-field="x_work_code" name="x_work_code" id="x_work_code" size="30" placeholder="<?php echo ew_HtmlEncode($att_log->work_code->getPlaceHolder()) ?>" value="<?php echo $att_log->work_code->EditValue ?>"<?php echo $att_log->work_code->EditAttributes() ?>>
</span>
<?php echo $att_log->work_code->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($att_log->att_id->Visible) { // att_id ?>
	<div id="r_att_id" class="form-group">
		<label id="elh_att_log_att_id" for="x_att_id" class="col-sm-2 control-label ewLabel"><?php echo $att_log->att_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $att_log->att_id->CellAttributes() ?>>
<span id="el_att_log_att_id">
<input type="text" data-table="att_log" data-field="x_att_id" name="x_att_id" id="x_att_id" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($att_log->att_id->getPlaceHolder()) ?>" value="<?php echo $att_log->att_id->EditValue ?>"<?php echo $att_log->att_id->EditAttributes() ?>>
</span>
<?php echo $att_log->att_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$att_log_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $att_log_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fatt_logedit.Init();
</script>
<?php
$att_log_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$att_log_edit->Page_Terminate();
?>
