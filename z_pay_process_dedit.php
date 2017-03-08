<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_process_dinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_process_d_edit = NULL; // Initialize page object first

class cz_pay_process_d_edit extends cz_pay_process_d {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'z_pay_process_d';

	// Page object name
	var $PageObjName = 'z_pay_process_d_edit';

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

		// Table object (z_pay_process_d)
		if (!isset($GLOBALS["z_pay_process_d"]) || get_class($GLOBALS["z_pay_process_d"]) == "cz_pay_process_d") {
			$GLOBALS["z_pay_process_d"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_process_d"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_process_d', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_process_dlist.php"));
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
		$this->no_urut->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->tot_payroll->SetVisibility();

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
		global $EW_EXPORT, $z_pay_process_d;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_process_d);
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
		if (@$_GET["no_urut"] <> "") {
			$this->no_urut->setQueryStringValue($_GET["no_urut"]);
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
			$this->Page_Terminate("z_pay_process_dlist.php"); // Invalid key, return to list
		}
		if ($this->no_urut->CurrentValue == "") {
			$this->Page_Terminate("z_pay_process_dlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("z_pay_process_dlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "z_pay_process_dlist.php")
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
		if (!$this->no_urut->FldIsDetailKey) {
			$this->no_urut->setFormValue($objForm->GetValue("x_no_urut"));
		}
		if (!$this->emp_id_auto->FldIsDetailKey) {
			$this->emp_id_auto->setFormValue($objForm->GetValue("x_emp_id_auto"));
		}
		if (!$this->tot_payroll->FldIsDetailKey) {
			$this->tot_payroll->setFormValue($objForm->GetValue("x_tot_payroll"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->process_id->CurrentValue = $this->process_id->FormValue;
		$this->no_urut->CurrentValue = $this->no_urut->FormValue;
		$this->emp_id_auto->CurrentValue = $this->emp_id_auto->FormValue;
		$this->tot_payroll->CurrentValue = $this->tot_payroll->FormValue;
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
		$this->no_urut->setDbValue($rs->fields('no_urut'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->tot_payroll->setDbValue($rs->fields('tot_payroll'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->process_id->DbValue = $row['process_id'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->emp_id_auto->DbValue = $row['emp_id_auto'];
		$this->tot_payroll->DbValue = $row['tot_payroll'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->tot_payroll->FormValue == $this->tot_payroll->CurrentValue && is_numeric(ew_StrToFloat($this->tot_payroll->CurrentValue)))
			$this->tot_payroll->CurrentValue = ew_StrToFloat($this->tot_payroll->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// process_id
		// no_urut
		// emp_id_auto
		// tot_payroll

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// process_id
		$this->process_id->ViewValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// tot_payroll
		$this->tot_payroll->ViewValue = $this->tot_payroll->CurrentValue;
		$this->tot_payroll->ViewCustomAttributes = "";

			// process_id
			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";
			$this->process_id->TooltipValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";
			$this->no_urut->TooltipValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";
			$this->emp_id_auto->TooltipValue = "";

			// tot_payroll
			$this->tot_payroll->LinkCustomAttributes = "";
			$this->tot_payroll->HrefValue = "";
			$this->tot_payroll->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// process_id
			$this->process_id->EditAttrs["class"] = "form-control";
			$this->process_id->EditCustomAttributes = "";
			$this->process_id->EditValue = $this->process_id->CurrentValue;
			$this->process_id->ViewCustomAttributes = "";

			// no_urut
			$this->no_urut->EditAttrs["class"] = "form-control";
			$this->no_urut->EditCustomAttributes = "";
			$this->no_urut->EditValue = $this->no_urut->CurrentValue;
			$this->no_urut->ViewCustomAttributes = "";

			// emp_id_auto
			$this->emp_id_auto->EditAttrs["class"] = "form-control";
			$this->emp_id_auto->EditCustomAttributes = "";
			$this->emp_id_auto->EditValue = ew_HtmlEncode($this->emp_id_auto->CurrentValue);
			$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

			// tot_payroll
			$this->tot_payroll->EditAttrs["class"] = "form-control";
			$this->tot_payroll->EditCustomAttributes = "";
			$this->tot_payroll->EditValue = ew_HtmlEncode($this->tot_payroll->CurrentValue);
			$this->tot_payroll->PlaceHolder = ew_RemoveHtml($this->tot_payroll->FldCaption());
			if (strval($this->tot_payroll->EditValue) <> "" && is_numeric($this->tot_payroll->EditValue)) $this->tot_payroll->EditValue = ew_FormatNumber($this->tot_payroll->EditValue, -2, -1, -2, 0);

			// Edit refer script
			// process_id

			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";

			// tot_payroll
			$this->tot_payroll->LinkCustomAttributes = "";
			$this->tot_payroll->HrefValue = "";
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
		if (!$this->no_urut->FldIsDetailKey && !is_null($this->no_urut->FormValue) && $this->no_urut->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_urut->FldCaption(), $this->no_urut->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->no_urut->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_urut->FldErrMsg());
		}
		if (!$this->emp_id_auto->FldIsDetailKey && !is_null($this->emp_id_auto->FormValue) && $this->emp_id_auto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->emp_id_auto->FldCaption(), $this->emp_id_auto->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->emp_id_auto->FormValue)) {
			ew_AddMessage($gsFormError, $this->emp_id_auto->FldErrMsg());
		}
		if (!$this->tot_payroll->FldIsDetailKey && !is_null($this->tot_payroll->FormValue) && $this->tot_payroll->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tot_payroll->FldCaption(), $this->tot_payroll->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tot_payroll->FormValue)) {
			ew_AddMessage($gsFormError, $this->tot_payroll->FldErrMsg());
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
			// no_urut
			// emp_id_auto

			$this->emp_id_auto->SetDbValueDef($rsnew, $this->emp_id_auto->CurrentValue, 0, $this->emp_id_auto->ReadOnly);

			// tot_payroll
			$this->tot_payroll->SetDbValueDef($rsnew, $this->tot_payroll->CurrentValue, 0, $this->tot_payroll->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_process_dlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_process_d_edit)) $z_pay_process_d_edit = new cz_pay_process_d_edit();

// Page init
$z_pay_process_d_edit->Page_Init();

// Page main
$z_pay_process_d_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_process_d_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fz_pay_process_dedit = new ew_Form("fz_pay_process_dedit", "edit");

// Validate form
fz_pay_process_dedit.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_d->process_id->FldCaption(), $z_pay_process_d->process_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_process_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_d->process_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_d->no_urut->FldCaption(), $z_pay_process_d->no_urut->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_d->no_urut->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_d->emp_id_auto->FldCaption(), $z_pay_process_d->emp_id_auto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_d->emp_id_auto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tot_payroll");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_d->tot_payroll->FldCaption(), $z_pay_process_d->tot_payroll->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tot_payroll");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_d->tot_payroll->FldErrMsg()) ?>");

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
fz_pay_process_dedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_process_dedit.ValidateRequired = true;
<?php } else { ?>
fz_pay_process_dedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$z_pay_process_d_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $z_pay_process_d_edit->ShowPageHeader(); ?>
<?php
$z_pay_process_d_edit->ShowMessage();
?>
<form name="fz_pay_process_dedit" id="fz_pay_process_dedit" class="<?php echo $z_pay_process_d_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_process_d_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_process_d_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_process_d">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($z_pay_process_d_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($z_pay_process_d->process_id->Visible) { // process_id ?>
	<div id="r_process_id" class="form-group">
		<label id="elh_z_pay_process_d_process_id" for="x_process_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_d->process_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_d->process_id->CellAttributes() ?>>
<span id="el_z_pay_process_d_process_id">
<span<?php echo $z_pay_process_d->process_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_process_d->process_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_process_d" data-field="x_process_id" name="x_process_id" id="x_process_id" value="<?php echo ew_HtmlEncode($z_pay_process_d->process_id->CurrentValue) ?>">
<?php echo $z_pay_process_d->process_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_d->no_urut->Visible) { // no_urut ?>
	<div id="r_no_urut" class="form-group">
		<label id="elh_z_pay_process_d_no_urut" for="x_no_urut" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_d->no_urut->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_d->no_urut->CellAttributes() ?>>
<span id="el_z_pay_process_d_no_urut">
<span<?php echo $z_pay_process_d->no_urut->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $z_pay_process_d->no_urut->EditValue ?></p></span>
</span>
<input type="hidden" data-table="z_pay_process_d" data-field="x_no_urut" name="x_no_urut" id="x_no_urut" value="<?php echo ew_HtmlEncode($z_pay_process_d->no_urut->CurrentValue) ?>">
<?php echo $z_pay_process_d->no_urut->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_d->emp_id_auto->Visible) { // emp_id_auto ?>
	<div id="r_emp_id_auto" class="form-group">
		<label id="elh_z_pay_process_d_emp_id_auto" for="x_emp_id_auto" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_d->emp_id_auto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_d->emp_id_auto->CellAttributes() ?>>
<span id="el_z_pay_process_d_emp_id_auto">
<input type="text" data-table="z_pay_process_d" data-field="x_emp_id_auto" name="x_emp_id_auto" id="x_emp_id_auto" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_d->emp_id_auto->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_d->emp_id_auto->EditValue ?>"<?php echo $z_pay_process_d->emp_id_auto->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_d->emp_id_auto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_d->tot_payroll->Visible) { // tot_payroll ?>
	<div id="r_tot_payroll" class="form-group">
		<label id="elh_z_pay_process_d_tot_payroll" for="x_tot_payroll" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_d->tot_payroll->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_d->tot_payroll->CellAttributes() ?>>
<span id="el_z_pay_process_d_tot_payroll">
<input type="text" data-table="z_pay_process_d" data-field="x_tot_payroll" name="x_tot_payroll" id="x_tot_payroll" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_d->tot_payroll->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_d->tot_payroll->EditValue ?>"<?php echo $z_pay_process_d->tot_payroll->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_d->tot_payroll->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$z_pay_process_d_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_process_d_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fz_pay_process_dedit.Init();
</script>
<?php
$z_pay_process_d_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_process_d_edit->Page_Terminate();
?>
