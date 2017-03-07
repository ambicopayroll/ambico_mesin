<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_process_minfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_process_m_edit = NULL; // Initialize page object first

class cz_pay_process_m_edit extends cz_pay_process_m {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'z_pay_process_m';

	// Page object name
	var $PageObjName = 'z_pay_process_m_edit';

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

		// Table object (z_pay_process_m)
		if (!isset($GLOBALS["z_pay_process_m"]) || get_class($GLOBALS["z_pay_process_m"]) == "cz_pay_process_m") {
			$GLOBALS["z_pay_process_m"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_process_m"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_process_m', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_process_mlist.php"));
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
		$this->process_id->SetVisibility();
		$this->process_name->SetVisibility();
		$this->date1->SetVisibility();
		$this->date2->SetVisibility();
		$this->payment_date->SetVisibility();
		$this->round_value->SetVisibility();
		$this->tot_process->SetVisibility();
		$this->create_by->SetVisibility();
		$this->check_by->SetVisibility();
		$this->approve_by->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->lastupdate_date->SetVisibility();
		$this->lastupdate_user->SetVisibility();

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
		global $EW_EXPORT, $z_pay_process_m;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_process_m);
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
		if (@$_GET["process_id"] <> "") {
			$this->process_id->setQueryStringValue($_GET["process_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->process_id->CurrentValue == "") {
			$this->Page_Terminate("z_pay_process_mlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("z_pay_process_mlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "z_pay_process_mlist.php")
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
		if (!$this->process_id->FldIsDetailKey) {
			$this->process_id->setFormValue($objForm->GetValue("x_process_id"));
		}
		if (!$this->process_name->FldIsDetailKey) {
			$this->process_name->setFormValue($objForm->GetValue("x_process_name"));
		}
		if (!$this->date1->FldIsDetailKey) {
			$this->date1->setFormValue($objForm->GetValue("x_date1"));
			$this->date1->CurrentValue = ew_UnFormatDateTime($this->date1->CurrentValue, 0);
		}
		if (!$this->date2->FldIsDetailKey) {
			$this->date2->setFormValue($objForm->GetValue("x_date2"));
			$this->date2->CurrentValue = ew_UnFormatDateTime($this->date2->CurrentValue, 0);
		}
		if (!$this->payment_date->FldIsDetailKey) {
			$this->payment_date->setFormValue($objForm->GetValue("x_payment_date"));
			$this->payment_date->CurrentValue = ew_UnFormatDateTime($this->payment_date->CurrentValue, 0);
		}
		if (!$this->round_value->FldIsDetailKey) {
			$this->round_value->setFormValue($objForm->GetValue("x_round_value"));
		}
		if (!$this->tot_process->FldIsDetailKey) {
			$this->tot_process->setFormValue($objForm->GetValue("x_tot_process"));
		}
		if (!$this->create_by->FldIsDetailKey) {
			$this->create_by->setFormValue($objForm->GetValue("x_create_by"));
		}
		if (!$this->check_by->FldIsDetailKey) {
			$this->check_by->setFormValue($objForm->GetValue("x_check_by"));
		}
		if (!$this->approve_by->FldIsDetailKey) {
			$this->approve_by->setFormValue($objForm->GetValue("x_approve_by"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->lastupdate_date->FldIsDetailKey) {
			$this->lastupdate_date->setFormValue($objForm->GetValue("x_lastupdate_date"));
			$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		}
		if (!$this->lastupdate_user->FldIsDetailKey) {
			$this->lastupdate_user->setFormValue($objForm->GetValue("x_lastupdate_user"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->process_id->CurrentValue = $this->process_id->FormValue;
		$this->process_name->CurrentValue = $this->process_name->FormValue;
		$this->date1->CurrentValue = $this->date1->FormValue;
		$this->date1->CurrentValue = ew_UnFormatDateTime($this->date1->CurrentValue, 0);
		$this->date2->CurrentValue = $this->date2->FormValue;
		$this->date2->CurrentValue = ew_UnFormatDateTime($this->date2->CurrentValue, 0);
		$this->payment_date->CurrentValue = $this->payment_date->FormValue;
		$this->payment_date->CurrentValue = ew_UnFormatDateTime($this->payment_date->CurrentValue, 0);
		$this->round_value->CurrentValue = $this->round_value->FormValue;
		$this->tot_process->CurrentValue = $this->tot_process->FormValue;
		$this->create_by->CurrentValue = $this->create_by->FormValue;
		$this->check_by->CurrentValue = $this->check_by->FormValue;
		$this->approve_by->CurrentValue = $this->approve_by->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->lastupdate_date->CurrentValue = $this->lastupdate_date->FormValue;
		$this->lastupdate_date->CurrentValue = ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0);
		$this->lastupdate_user->CurrentValue = $this->lastupdate_user->FormValue;
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
		$this->process_id->setDbValue($rs->fields('process_id'));
		$this->process_name->setDbValue($rs->fields('process_name'));
		$this->date1->setDbValue($rs->fields('date1'));
		$this->date2->setDbValue($rs->fields('date2'));
		$this->payment_date->setDbValue($rs->fields('payment_date'));
		$this->round_value->setDbValue($rs->fields('round_value'));
		$this->tot_process->setDbValue($rs->fields('tot_process'));
		$this->create_by->setDbValue($rs->fields('create_by'));
		$this->check_by->setDbValue($rs->fields('check_by'));
		$this->approve_by->setDbValue($rs->fields('approve_by'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->process_id->DbValue = $row['process_id'];
		$this->process_name->DbValue = $row['process_name'];
		$this->date1->DbValue = $row['date1'];
		$this->date2->DbValue = $row['date2'];
		$this->payment_date->DbValue = $row['payment_date'];
		$this->round_value->DbValue = $row['round_value'];
		$this->tot_process->DbValue = $row['tot_process'];
		$this->create_by->DbValue = $row['create_by'];
		$this->check_by->DbValue = $row['check_by'];
		$this->approve_by->DbValue = $row['approve_by'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->round_value->FormValue == $this->round_value->CurrentValue && is_numeric(ew_StrToFloat($this->round_value->CurrentValue)))
			$this->round_value->CurrentValue = ew_StrToFloat($this->round_value->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_process->FormValue == $this->tot_process->CurrentValue && is_numeric(ew_StrToFloat($this->tot_process->CurrentValue)))
			$this->tot_process->CurrentValue = ew_StrToFloat($this->tot_process->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// process_id
		// process_name
		// date1
		// date2
		// payment_date
		// round_value
		// tot_process
		// create_by
		// check_by
		// approve_by
		// keterangan
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// process_id
		$this->process_id->ViewValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// process_name
		$this->process_name->ViewValue = $this->process_name->CurrentValue;
		$this->process_name->ViewCustomAttributes = "";

		// date1
		$this->date1->ViewValue = $this->date1->CurrentValue;
		$this->date1->ViewValue = ew_FormatDateTime($this->date1->ViewValue, 0);
		$this->date1->ViewCustomAttributes = "";

		// date2
		$this->date2->ViewValue = $this->date2->CurrentValue;
		$this->date2->ViewValue = ew_FormatDateTime($this->date2->ViewValue, 0);
		$this->date2->ViewCustomAttributes = "";

		// payment_date
		$this->payment_date->ViewValue = $this->payment_date->CurrentValue;
		$this->payment_date->ViewValue = ew_FormatDateTime($this->payment_date->ViewValue, 0);
		$this->payment_date->ViewCustomAttributes = "";

		// round_value
		$this->round_value->ViewValue = $this->round_value->CurrentValue;
		$this->round_value->ViewCustomAttributes = "";

		// tot_process
		$this->tot_process->ViewValue = $this->tot_process->CurrentValue;
		$this->tot_process->ViewCustomAttributes = "";

		// create_by
		$this->create_by->ViewValue = $this->create_by->CurrentValue;
		$this->create_by->ViewCustomAttributes = "";

		// check_by
		$this->check_by->ViewValue = $this->check_by->CurrentValue;
		$this->check_by->ViewCustomAttributes = "";

		// approve_by
		$this->approve_by->ViewValue = $this->approve_by->CurrentValue;
		$this->approve_by->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// process_id
			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";
			$this->process_id->TooltipValue = "";

			// process_name
			$this->process_name->LinkCustomAttributes = "";
			$this->process_name->HrefValue = "";
			$this->process_name->TooltipValue = "";

			// date1
			$this->date1->LinkCustomAttributes = "";
			$this->date1->HrefValue = "";
			$this->date1->TooltipValue = "";

			// date2
			$this->date2->LinkCustomAttributes = "";
			$this->date2->HrefValue = "";
			$this->date2->TooltipValue = "";

			// payment_date
			$this->payment_date->LinkCustomAttributes = "";
			$this->payment_date->HrefValue = "";
			$this->payment_date->TooltipValue = "";

			// round_value
			$this->round_value->LinkCustomAttributes = "";
			$this->round_value->HrefValue = "";
			$this->round_value->TooltipValue = "";

			// tot_process
			$this->tot_process->LinkCustomAttributes = "";
			$this->tot_process->HrefValue = "";
			$this->tot_process->TooltipValue = "";

			// create_by
			$this->create_by->LinkCustomAttributes = "";
			$this->create_by->HrefValue = "";
			$this->create_by->TooltipValue = "";

			// check_by
			$this->check_by->LinkCustomAttributes = "";
			$this->check_by->HrefValue = "";
			$this->check_by->TooltipValue = "";

			// approve_by
			$this->approve_by->LinkCustomAttributes = "";
			$this->approve_by->HrefValue = "";
			$this->approve_by->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// process_id
			$this->process_id->EditAttrs["class"] = "form-control";
			$this->process_id->EditCustomAttributes = "";
			$this->process_id->EditValue = $this->process_id->CurrentValue;
			$this->process_id->ViewCustomAttributes = "";

			// process_name
			$this->process_name->EditAttrs["class"] = "form-control";
			$this->process_name->EditCustomAttributes = "";
			$this->process_name->EditValue = ew_HtmlEncode($this->process_name->CurrentValue);
			$this->process_name->PlaceHolder = ew_RemoveHtml($this->process_name->FldCaption());

			// date1
			$this->date1->EditAttrs["class"] = "form-control";
			$this->date1->EditCustomAttributes = "";
			$this->date1->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date1->CurrentValue, 8));
			$this->date1->PlaceHolder = ew_RemoveHtml($this->date1->FldCaption());

			// date2
			$this->date2->EditAttrs["class"] = "form-control";
			$this->date2->EditCustomAttributes = "";
			$this->date2->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->date2->CurrentValue, 8));
			$this->date2->PlaceHolder = ew_RemoveHtml($this->date2->FldCaption());

			// payment_date
			$this->payment_date->EditAttrs["class"] = "form-control";
			$this->payment_date->EditCustomAttributes = "";
			$this->payment_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->payment_date->CurrentValue, 8));
			$this->payment_date->PlaceHolder = ew_RemoveHtml($this->payment_date->FldCaption());

			// round_value
			$this->round_value->EditAttrs["class"] = "form-control";
			$this->round_value->EditCustomAttributes = "";
			$this->round_value->EditValue = ew_HtmlEncode($this->round_value->CurrentValue);
			$this->round_value->PlaceHolder = ew_RemoveHtml($this->round_value->FldCaption());
			if (strval($this->round_value->EditValue) <> "" && is_numeric($this->round_value->EditValue)) $this->round_value->EditValue = ew_FormatNumber($this->round_value->EditValue, -2, -1, -2, 0);

			// tot_process
			$this->tot_process->EditAttrs["class"] = "form-control";
			$this->tot_process->EditCustomAttributes = "";
			$this->tot_process->EditValue = ew_HtmlEncode($this->tot_process->CurrentValue);
			$this->tot_process->PlaceHolder = ew_RemoveHtml($this->tot_process->FldCaption());
			if (strval($this->tot_process->EditValue) <> "" && is_numeric($this->tot_process->EditValue)) $this->tot_process->EditValue = ew_FormatNumber($this->tot_process->EditValue, -2, -1, -2, 0);

			// create_by
			$this->create_by->EditAttrs["class"] = "form-control";
			$this->create_by->EditCustomAttributes = "";
			$this->create_by->EditValue = ew_HtmlEncode($this->create_by->CurrentValue);
			$this->create_by->PlaceHolder = ew_RemoveHtml($this->create_by->FldCaption());

			// check_by
			$this->check_by->EditAttrs["class"] = "form-control";
			$this->check_by->EditCustomAttributes = "";
			$this->check_by->EditValue = ew_HtmlEncode($this->check_by->CurrentValue);
			$this->check_by->PlaceHolder = ew_RemoveHtml($this->check_by->FldCaption());

			// approve_by
			$this->approve_by->EditAttrs["class"] = "form-control";
			$this->approve_by->EditCustomAttributes = "";
			$this->approve_by->EditValue = ew_HtmlEncode($this->approve_by->CurrentValue);
			$this->approve_by->PlaceHolder = ew_RemoveHtml($this->approve_by->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

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

			// Edit refer script
			// process_id

			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";

			// process_name
			$this->process_name->LinkCustomAttributes = "";
			$this->process_name->HrefValue = "";

			// date1
			$this->date1->LinkCustomAttributes = "";
			$this->date1->HrefValue = "";

			// date2
			$this->date2->LinkCustomAttributes = "";
			$this->date2->HrefValue = "";

			// payment_date
			$this->payment_date->LinkCustomAttributes = "";
			$this->payment_date->HrefValue = "";

			// round_value
			$this->round_value->LinkCustomAttributes = "";
			$this->round_value->HrefValue = "";

			// tot_process
			$this->tot_process->LinkCustomAttributes = "";
			$this->tot_process->HrefValue = "";

			// create_by
			$this->create_by->LinkCustomAttributes = "";
			$this->create_by->HrefValue = "";

			// check_by
			$this->check_by->LinkCustomAttributes = "";
			$this->check_by->HrefValue = "";

			// approve_by
			$this->approve_by->LinkCustomAttributes = "";
			$this->approve_by->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
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
		if (!$this->process_id->FldIsDetailKey && !is_null($this->process_id->FormValue) && $this->process_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->process_id->FldCaption(), $this->process_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->process_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->process_id->FldErrMsg());
		}
		if (!$this->process_name->FldIsDetailKey && !is_null($this->process_name->FormValue) && $this->process_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->process_name->FldCaption(), $this->process_name->ReqErrMsg));
		}
		if (!$this->date1->FldIsDetailKey && !is_null($this->date1->FormValue) && $this->date1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date1->FldCaption(), $this->date1->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date1->FormValue)) {
			ew_AddMessage($gsFormError, $this->date1->FldErrMsg());
		}
		if (!$this->date2->FldIsDetailKey && !is_null($this->date2->FormValue) && $this->date2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->date2->FldCaption(), $this->date2->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->date2->FormValue)) {
			ew_AddMessage($gsFormError, $this->date2->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->payment_date->FormValue)) {
			ew_AddMessage($gsFormError, $this->payment_date->FldErrMsg());
		}
		if (!$this->round_value->FldIsDetailKey && !is_null($this->round_value->FormValue) && $this->round_value->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->round_value->FldCaption(), $this->round_value->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->round_value->FormValue)) {
			ew_AddMessage($gsFormError, $this->round_value->FldErrMsg());
		}
		if (!$this->tot_process->FldIsDetailKey && !is_null($this->tot_process->FormValue) && $this->tot_process->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tot_process->FldCaption(), $this->tot_process->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tot_process->FormValue)) {
			ew_AddMessage($gsFormError, $this->tot_process->FldErrMsg());
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

			// process_id
			// process_name

			$this->process_name->SetDbValueDef($rsnew, $this->process_name->CurrentValue, "", $this->process_name->ReadOnly);

			// date1
			$this->date1->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date1->CurrentValue, 0), ew_CurrentDate(), $this->date1->ReadOnly);

			// date2
			$this->date2->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->date2->CurrentValue, 0), ew_CurrentDate(), $this->date2->ReadOnly);

			// payment_date
			$this->payment_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->payment_date->CurrentValue, 0), NULL, $this->payment_date->ReadOnly);

			// round_value
			$this->round_value->SetDbValueDef($rsnew, $this->round_value->CurrentValue, 0, $this->round_value->ReadOnly);

			// tot_process
			$this->tot_process->SetDbValueDef($rsnew, $this->tot_process->CurrentValue, 0, $this->tot_process->ReadOnly);

			// create_by
			$this->create_by->SetDbValueDef($rsnew, $this->create_by->CurrentValue, NULL, $this->create_by->ReadOnly);

			// check_by
			$this->check_by->SetDbValueDef($rsnew, $this->check_by->CurrentValue, NULL, $this->check_by->ReadOnly);

			// approve_by
			$this->approve_by->SetDbValueDef($rsnew, $this->approve_by->CurrentValue, NULL, $this->approve_by->ReadOnly);

			// keterangan
			$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, $this->keterangan->ReadOnly);

			// lastupdate_date
			$this->lastupdate_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0), ew_CurrentDate(), $this->lastupdate_date->ReadOnly);

			// lastupdate_user
			$this->lastupdate_user->SetDbValueDef($rsnew, $this->lastupdate_user->CurrentValue, "", $this->lastupdate_user->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_process_mlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_process_m_edit)) $z_pay_process_m_edit = new cz_pay_process_m_edit();

// Page init
$z_pay_process_m_edit->Page_Init();

// Page main
$z_pay_process_m_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_process_m_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fz_pay_process_medit = new ew_Form("fz_pay_process_medit", "edit");

// Validate form
fz_pay_process_medit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_process_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->process_id->FldCaption(), $z_pay_process_m->process_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_process_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->process_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_process_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->process_name->FldCaption(), $z_pay_process_m->process_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->date1->FldCaption(), $z_pay_process_m->date1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date1");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->date1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_date2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->date2->FldCaption(), $z_pay_process_m->date2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_date2");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->date2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_payment_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->payment_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_round_value");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->round_value->FldCaption(), $z_pay_process_m->round_value->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_round_value");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->round_value->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tot_process");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->tot_process->FldCaption(), $z_pay_process_m->tot_process->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tot_process");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->tot_process->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->lastupdate_date->FldCaption(), $z_pay_process_m->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_m->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_m->lastupdate_user->FldCaption(), $z_pay_process_m->lastupdate_user->ReqErrMsg)) ?>");

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
fz_pay_process_medit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_process_medit.ValidateRequired = true;
<?php } else { ?>
fz_pay_process_medit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$z_pay_process_m_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $z_pay_process_m_edit->ShowPageHeader(); ?>
<?php
$z_pay_process_m_edit->ShowMessage();
?>
<form name="fz_pay_process_medit" id="fz_pay_process_medit" class="<?php echo $z_pay_process_m_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_process_m_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_process_m_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_process_m">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($z_pay_process_m_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($z_pay_process_m->process_id->Visible) { // process_id ?>
	<div id="r_process_id" class="form-group">
		<label id="elh_z_pay_process_m_process_id" for="x_process_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->process_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->process_id->CellAttributes() ?>>
<span id="el_z_pay_process_m_process_id">
<span<?php echo $z_pay_process_m->process_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_process_m->process_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_process_m" data-field="x_process_id" name="x_process_id" id="x_process_id" value="<?php echo ew_HtmlEncode($z_pay_process_m->process_id->CurrentValue) ?>">
<?php echo $z_pay_process_m->process_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->process_name->Visible) { // process_name ?>
	<div id="r_process_name" class="form-group">
		<label id="elh_z_pay_process_m_process_name" for="x_process_name" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->process_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->process_name->CellAttributes() ?>>
<span id="el_z_pay_process_m_process_name">
<input type="text" data-table="z_pay_process_m" data-field="x_process_name" name="x_process_name" id="x_process_name" size="30" maxlength="250" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->process_name->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->process_name->EditValue ?>"<?php echo $z_pay_process_m->process_name->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->process_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->date1->Visible) { // date1 ?>
	<div id="r_date1" class="form-group">
		<label id="elh_z_pay_process_m_date1" for="x_date1" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->date1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->date1->CellAttributes() ?>>
<span id="el_z_pay_process_m_date1">
<input type="text" data-table="z_pay_process_m" data-field="x_date1" name="x_date1" id="x_date1" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->date1->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->date1->EditValue ?>"<?php echo $z_pay_process_m->date1->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->date1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->date2->Visible) { // date2 ?>
	<div id="r_date2" class="form-group">
		<label id="elh_z_pay_process_m_date2" for="x_date2" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->date2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->date2->CellAttributes() ?>>
<span id="el_z_pay_process_m_date2">
<input type="text" data-table="z_pay_process_m" data-field="x_date2" name="x_date2" id="x_date2" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->date2->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->date2->EditValue ?>"<?php echo $z_pay_process_m->date2->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->date2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->payment_date->Visible) { // payment_date ?>
	<div id="r_payment_date" class="form-group">
		<label id="elh_z_pay_process_m_payment_date" for="x_payment_date" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->payment_date->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->payment_date->CellAttributes() ?>>
<span id="el_z_pay_process_m_payment_date">
<input type="text" data-table="z_pay_process_m" data-field="x_payment_date" name="x_payment_date" id="x_payment_date" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->payment_date->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->payment_date->EditValue ?>"<?php echo $z_pay_process_m->payment_date->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->payment_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->round_value->Visible) { // round_value ?>
	<div id="r_round_value" class="form-group">
		<label id="elh_z_pay_process_m_round_value" for="x_round_value" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->round_value->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->round_value->CellAttributes() ?>>
<span id="el_z_pay_process_m_round_value">
<input type="text" data-table="z_pay_process_m" data-field="x_round_value" name="x_round_value" id="x_round_value" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->round_value->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->round_value->EditValue ?>"<?php echo $z_pay_process_m->round_value->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->round_value->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->tot_process->Visible) { // tot_process ?>
	<div id="r_tot_process" class="form-group">
		<label id="elh_z_pay_process_m_tot_process" for="x_tot_process" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->tot_process->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->tot_process->CellAttributes() ?>>
<span id="el_z_pay_process_m_tot_process">
<input type="text" data-table="z_pay_process_m" data-field="x_tot_process" name="x_tot_process" id="x_tot_process" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->tot_process->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->tot_process->EditValue ?>"<?php echo $z_pay_process_m->tot_process->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->tot_process->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->create_by->Visible) { // create_by ?>
	<div id="r_create_by" class="form-group">
		<label id="elh_z_pay_process_m_create_by" for="x_create_by" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->create_by->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->create_by->CellAttributes() ?>>
<span id="el_z_pay_process_m_create_by">
<input type="text" data-table="z_pay_process_m" data-field="x_create_by" name="x_create_by" id="x_create_by" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->create_by->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->create_by->EditValue ?>"<?php echo $z_pay_process_m->create_by->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->create_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->check_by->Visible) { // check_by ?>
	<div id="r_check_by" class="form-group">
		<label id="elh_z_pay_process_m_check_by" for="x_check_by" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->check_by->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->check_by->CellAttributes() ?>>
<span id="el_z_pay_process_m_check_by">
<input type="text" data-table="z_pay_process_m" data-field="x_check_by" name="x_check_by" id="x_check_by" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->check_by->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->check_by->EditValue ?>"<?php echo $z_pay_process_m->check_by->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->check_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->approve_by->Visible) { // approve_by ?>
	<div id="r_approve_by" class="form-group">
		<label id="elh_z_pay_process_m_approve_by" for="x_approve_by" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->approve_by->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->approve_by->CellAttributes() ?>>
<span id="el_z_pay_process_m_approve_by">
<input type="text" data-table="z_pay_process_m" data-field="x_approve_by" name="x_approve_by" id="x_approve_by" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->approve_by->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->approve_by->EditValue ?>"<?php echo $z_pay_process_m->approve_by->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->approve_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_z_pay_process_m_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->keterangan->CellAttributes() ?>>
<span id="el_z_pay_process_m_keterangan">
<input type="text" data-table="z_pay_process_m" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->keterangan->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->keterangan->EditValue ?>"<?php echo $z_pay_process_m->keterangan->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_z_pay_process_m_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->lastupdate_date->CellAttributes() ?>>
<span id="el_z_pay_process_m_lastupdate_date">
<input type="text" data-table="z_pay_process_m" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->lastupdate_date->EditValue ?>"<?php echo $z_pay_process_m->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_m->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_z_pay_process_m_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_m->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_m->lastupdate_user->CellAttributes() ?>>
<span id="el_z_pay_process_m_lastupdate_user">
<input type="text" data-table="z_pay_process_m" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($z_pay_process_m->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_m->lastupdate_user->EditValue ?>"<?php echo $z_pay_process_m->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_m->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$z_pay_process_m_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_process_m_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fz_pay_process_medit.Init();
</script>
<?php
$z_pay_process_m_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_process_m_edit->Page_Terminate();
?>
