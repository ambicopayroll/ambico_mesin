<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "z_pay_process_sdinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$z_pay_process_sd_add = NULL; // Initialize page object first

class cz_pay_process_sd_add extends cz_pay_process_sd {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'z_pay_process_sd';

	// Page object name
	var $PageObjName = 'z_pay_process_sd_add';

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

		// Table object (z_pay_process_sd)
		if (!isset($GLOBALS["z_pay_process_sd"]) || get_class($GLOBALS["z_pay_process_sd"]) == "cz_pay_process_sd") {
			$GLOBALS["z_pay_process_sd"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["z_pay_process_sd"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'z_pay_process_sd', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("z_pay_process_sdlist.php"));
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
		$this->no_urut_ref->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->com_id->SetVisibility();
		$this->kondisi->SetVisibility();
		$this->rumus->SetVisibility();
		$this->subtot_payroll->SetVisibility();
		$this->jml_faktor->SetVisibility();

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
		global $EW_EXPORT, $z_pay_process_sd;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($z_pay_process_sd);
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
			if (@$_GET["process_id"] != "") {
				$this->process_id->setQueryStringValue($_GET["process_id"]);
				$this->setKey("process_id", $this->process_id->CurrentValue); // Set up key
			} else {
				$this->setKey("process_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["no_urut"] != "") {
				$this->no_urut->setQueryStringValue($_GET["no_urut"]);
				$this->setKey("no_urut", $this->no_urut->CurrentValue); // Set up key
			} else {
				$this->setKey("no_urut", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["no_urut_ref"] != "") {
				$this->no_urut_ref->setQueryStringValue($_GET["no_urut_ref"]);
				$this->setKey("no_urut_ref", $this->no_urut_ref->CurrentValue); // Set up key
			} else {
				$this->setKey("no_urut_ref", ""); // Clear key
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
					$this->Page_Terminate("z_pay_process_sdlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "z_pay_process_sdlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "z_pay_process_sdview.php")
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
		$this->process_id->CurrentValue = NULL;
		$this->process_id->OldValue = $this->process_id->CurrentValue;
		$this->no_urut->CurrentValue = NULL;
		$this->no_urut->OldValue = $this->no_urut->CurrentValue;
		$this->no_urut_ref->CurrentValue = NULL;
		$this->no_urut_ref->OldValue = $this->no_urut_ref->CurrentValue;
		$this->emp_id_auto->CurrentValue = NULL;
		$this->emp_id_auto->OldValue = $this->emp_id_auto->CurrentValue;
		$this->com_id->CurrentValue = NULL;
		$this->com_id->OldValue = $this->com_id->CurrentValue;
		$this->kondisi->CurrentValue = "0";
		$this->rumus->CurrentValue = "0";
		$this->subtot_payroll->CurrentValue = NULL;
		$this->subtot_payroll->OldValue = $this->subtot_payroll->CurrentValue;
		$this->jml_faktor->CurrentValue = 0;
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
		if (!$this->no_urut_ref->FldIsDetailKey) {
			$this->no_urut_ref->setFormValue($objForm->GetValue("x_no_urut_ref"));
		}
		if (!$this->emp_id_auto->FldIsDetailKey) {
			$this->emp_id_auto->setFormValue($objForm->GetValue("x_emp_id_auto"));
		}
		if (!$this->com_id->FldIsDetailKey) {
			$this->com_id->setFormValue($objForm->GetValue("x_com_id"));
		}
		if (!$this->kondisi->FldIsDetailKey) {
			$this->kondisi->setFormValue($objForm->GetValue("x_kondisi"));
		}
		if (!$this->rumus->FldIsDetailKey) {
			$this->rumus->setFormValue($objForm->GetValue("x_rumus"));
		}
		if (!$this->subtot_payroll->FldIsDetailKey) {
			$this->subtot_payroll->setFormValue($objForm->GetValue("x_subtot_payroll"));
		}
		if (!$this->jml_faktor->FldIsDetailKey) {
			$this->jml_faktor->setFormValue($objForm->GetValue("x_jml_faktor"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->process_id->CurrentValue = $this->process_id->FormValue;
		$this->no_urut->CurrentValue = $this->no_urut->FormValue;
		$this->no_urut_ref->CurrentValue = $this->no_urut_ref->FormValue;
		$this->emp_id_auto->CurrentValue = $this->emp_id_auto->FormValue;
		$this->com_id->CurrentValue = $this->com_id->FormValue;
		$this->kondisi->CurrentValue = $this->kondisi->FormValue;
		$this->rumus->CurrentValue = $this->rumus->FormValue;
		$this->subtot_payroll->CurrentValue = $this->subtot_payroll->FormValue;
		$this->jml_faktor->CurrentValue = $this->jml_faktor->FormValue;
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
		$this->no_urut_ref->setDbValue($rs->fields('no_urut_ref'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->com_id->setDbValue($rs->fields('com_id'));
		$this->kondisi->setDbValue($rs->fields('kondisi'));
		$this->rumus->setDbValue($rs->fields('rumus'));
		$this->subtot_payroll->setDbValue($rs->fields('subtot_payroll'));
		$this->jml_faktor->setDbValue($rs->fields('jml_faktor'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->process_id->DbValue = $row['process_id'];
		$this->no_urut->DbValue = $row['no_urut'];
		$this->no_urut_ref->DbValue = $row['no_urut_ref'];
		$this->emp_id_auto->DbValue = $row['emp_id_auto'];
		$this->com_id->DbValue = $row['com_id'];
		$this->kondisi->DbValue = $row['kondisi'];
		$this->rumus->DbValue = $row['rumus'];
		$this->subtot_payroll->DbValue = $row['subtot_payroll'];
		$this->jml_faktor->DbValue = $row['jml_faktor'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("process_id")) <> "")
			$this->process_id->CurrentValue = $this->getKey("process_id"); // process_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("no_urut")) <> "")
			$this->no_urut->CurrentValue = $this->getKey("no_urut"); // no_urut
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("no_urut_ref")) <> "")
			$this->no_urut_ref->CurrentValue = $this->getKey("no_urut_ref"); // no_urut_ref
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
		// Convert decimal values if posted back

		if ($this->subtot_payroll->FormValue == $this->subtot_payroll->CurrentValue && is_numeric(ew_StrToFloat($this->subtot_payroll->CurrentValue)))
			$this->subtot_payroll->CurrentValue = ew_StrToFloat($this->subtot_payroll->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jml_faktor->FormValue == $this->jml_faktor->CurrentValue && is_numeric(ew_StrToFloat($this->jml_faktor->CurrentValue)))
			$this->jml_faktor->CurrentValue = ew_StrToFloat($this->jml_faktor->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// process_id
		// no_urut
		// no_urut_ref
		// emp_id_auto
		// com_id
		// kondisi
		// rumus
		// subtot_payroll
		// jml_faktor

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// process_id
		$this->process_id->ViewValue = $this->process_id->CurrentValue;
		$this->process_id->ViewCustomAttributes = "";

		// no_urut
		$this->no_urut->ViewValue = $this->no_urut->CurrentValue;
		$this->no_urut->ViewCustomAttributes = "";

		// no_urut_ref
		$this->no_urut_ref->ViewValue = $this->no_urut_ref->CurrentValue;
		$this->no_urut_ref->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// com_id
		$this->com_id->ViewValue = $this->com_id->CurrentValue;
		$this->com_id->ViewCustomAttributes = "";

		// kondisi
		$this->kondisi->ViewValue = $this->kondisi->CurrentValue;
		$this->kondisi->ViewCustomAttributes = "";

		// rumus
		$this->rumus->ViewValue = $this->rumus->CurrentValue;
		$this->rumus->ViewCustomAttributes = "";

		// subtot_payroll
		$this->subtot_payroll->ViewValue = $this->subtot_payroll->CurrentValue;
		$this->subtot_payroll->ViewCustomAttributes = "";

		// jml_faktor
		$this->jml_faktor->ViewValue = $this->jml_faktor->CurrentValue;
		$this->jml_faktor->ViewCustomAttributes = "";

			// process_id
			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";
			$this->process_id->TooltipValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";
			$this->no_urut->TooltipValue = "";

			// no_urut_ref
			$this->no_urut_ref->LinkCustomAttributes = "";
			$this->no_urut_ref->HrefValue = "";
			$this->no_urut_ref->TooltipValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";
			$this->emp_id_auto->TooltipValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";
			$this->com_id->TooltipValue = "";

			// kondisi
			$this->kondisi->LinkCustomAttributes = "";
			$this->kondisi->HrefValue = "";
			$this->kondisi->TooltipValue = "";

			// rumus
			$this->rumus->LinkCustomAttributes = "";
			$this->rumus->HrefValue = "";
			$this->rumus->TooltipValue = "";

			// subtot_payroll
			$this->subtot_payroll->LinkCustomAttributes = "";
			$this->subtot_payroll->HrefValue = "";
			$this->subtot_payroll->TooltipValue = "";

			// jml_faktor
			$this->jml_faktor->LinkCustomAttributes = "";
			$this->jml_faktor->HrefValue = "";
			$this->jml_faktor->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// process_id
			$this->process_id->EditAttrs["class"] = "form-control";
			$this->process_id->EditCustomAttributes = "";
			$this->process_id->EditValue = ew_HtmlEncode($this->process_id->CurrentValue);
			$this->process_id->PlaceHolder = ew_RemoveHtml($this->process_id->FldCaption());

			// no_urut
			$this->no_urut->EditAttrs["class"] = "form-control";
			$this->no_urut->EditCustomAttributes = "";
			$this->no_urut->EditValue = ew_HtmlEncode($this->no_urut->CurrentValue);
			$this->no_urut->PlaceHolder = ew_RemoveHtml($this->no_urut->FldCaption());

			// no_urut_ref
			$this->no_urut_ref->EditAttrs["class"] = "form-control";
			$this->no_urut_ref->EditCustomAttributes = "";
			$this->no_urut_ref->EditValue = ew_HtmlEncode($this->no_urut_ref->CurrentValue);
			$this->no_urut_ref->PlaceHolder = ew_RemoveHtml($this->no_urut_ref->FldCaption());

			// emp_id_auto
			$this->emp_id_auto->EditAttrs["class"] = "form-control";
			$this->emp_id_auto->EditCustomAttributes = "";
			$this->emp_id_auto->EditValue = ew_HtmlEncode($this->emp_id_auto->CurrentValue);
			$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

			// com_id
			$this->com_id->EditAttrs["class"] = "form-control";
			$this->com_id->EditCustomAttributes = "";
			$this->com_id->EditValue = ew_HtmlEncode($this->com_id->CurrentValue);
			$this->com_id->PlaceHolder = ew_RemoveHtml($this->com_id->FldCaption());

			// kondisi
			$this->kondisi->EditAttrs["class"] = "form-control";
			$this->kondisi->EditCustomAttributes = "";
			$this->kondisi->EditValue = ew_HtmlEncode($this->kondisi->CurrentValue);
			$this->kondisi->PlaceHolder = ew_RemoveHtml($this->kondisi->FldCaption());

			// rumus
			$this->rumus->EditAttrs["class"] = "form-control";
			$this->rumus->EditCustomAttributes = "";
			$this->rumus->EditValue = ew_HtmlEncode($this->rumus->CurrentValue);
			$this->rumus->PlaceHolder = ew_RemoveHtml($this->rumus->FldCaption());

			// subtot_payroll
			$this->subtot_payroll->EditAttrs["class"] = "form-control";
			$this->subtot_payroll->EditCustomAttributes = "";
			$this->subtot_payroll->EditValue = ew_HtmlEncode($this->subtot_payroll->CurrentValue);
			$this->subtot_payroll->PlaceHolder = ew_RemoveHtml($this->subtot_payroll->FldCaption());
			if (strval($this->subtot_payroll->EditValue) <> "" && is_numeric($this->subtot_payroll->EditValue)) $this->subtot_payroll->EditValue = ew_FormatNumber($this->subtot_payroll->EditValue, -2, -1, -2, 0);

			// jml_faktor
			$this->jml_faktor->EditAttrs["class"] = "form-control";
			$this->jml_faktor->EditCustomAttributes = "";
			$this->jml_faktor->EditValue = ew_HtmlEncode($this->jml_faktor->CurrentValue);
			$this->jml_faktor->PlaceHolder = ew_RemoveHtml($this->jml_faktor->FldCaption());
			if (strval($this->jml_faktor->EditValue) <> "" && is_numeric($this->jml_faktor->EditValue)) $this->jml_faktor->EditValue = ew_FormatNumber($this->jml_faktor->EditValue, -2, -1, -2, 0);

			// Add refer script
			// process_id

			$this->process_id->LinkCustomAttributes = "";
			$this->process_id->HrefValue = "";

			// no_urut
			$this->no_urut->LinkCustomAttributes = "";
			$this->no_urut->HrefValue = "";

			// no_urut_ref
			$this->no_urut_ref->LinkCustomAttributes = "";
			$this->no_urut_ref->HrefValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";

			// com_id
			$this->com_id->LinkCustomAttributes = "";
			$this->com_id->HrefValue = "";

			// kondisi
			$this->kondisi->LinkCustomAttributes = "";
			$this->kondisi->HrefValue = "";

			// rumus
			$this->rumus->LinkCustomAttributes = "";
			$this->rumus->HrefValue = "";

			// subtot_payroll
			$this->subtot_payroll->LinkCustomAttributes = "";
			$this->subtot_payroll->HrefValue = "";

			// jml_faktor
			$this->jml_faktor->LinkCustomAttributes = "";
			$this->jml_faktor->HrefValue = "";
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
		if (!$this->no_urut_ref->FldIsDetailKey && !is_null($this->no_urut_ref->FormValue) && $this->no_urut_ref->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_urut_ref->FldCaption(), $this->no_urut_ref->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->no_urut_ref->FormValue)) {
			ew_AddMessage($gsFormError, $this->no_urut_ref->FldErrMsg());
		}
		if (!$this->emp_id_auto->FldIsDetailKey && !is_null($this->emp_id_auto->FormValue) && $this->emp_id_auto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->emp_id_auto->FldCaption(), $this->emp_id_auto->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->emp_id_auto->FormValue)) {
			ew_AddMessage($gsFormError, $this->emp_id_auto->FldErrMsg());
		}
		if (!$this->com_id->FldIsDetailKey && !is_null($this->com_id->FormValue) && $this->com_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->com_id->FldCaption(), $this->com_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->com_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->com_id->FldErrMsg());
		}
		if (!$this->kondisi->FldIsDetailKey && !is_null($this->kondisi->FormValue) && $this->kondisi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->kondisi->FldCaption(), $this->kondisi->ReqErrMsg));
		}
		if (!$this->rumus->FldIsDetailKey && !is_null($this->rumus->FormValue) && $this->rumus->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->rumus->FldCaption(), $this->rumus->ReqErrMsg));
		}
		if (!$this->subtot_payroll->FldIsDetailKey && !is_null($this->subtot_payroll->FormValue) && $this->subtot_payroll->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->subtot_payroll->FldCaption(), $this->subtot_payroll->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->subtot_payroll->FormValue)) {
			ew_AddMessage($gsFormError, $this->subtot_payroll->FldErrMsg());
		}
		if (!$this->jml_faktor->FldIsDetailKey && !is_null($this->jml_faktor->FormValue) && $this->jml_faktor->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jml_faktor->FldCaption(), $this->jml_faktor->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->jml_faktor->FormValue)) {
			ew_AddMessage($gsFormError, $this->jml_faktor->FldErrMsg());
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

		// process_id
		$this->process_id->SetDbValueDef($rsnew, $this->process_id->CurrentValue, 0, FALSE);

		// no_urut
		$this->no_urut->SetDbValueDef($rsnew, $this->no_urut->CurrentValue, 0, FALSE);

		// no_urut_ref
		$this->no_urut_ref->SetDbValueDef($rsnew, $this->no_urut_ref->CurrentValue, 0, FALSE);

		// emp_id_auto
		$this->emp_id_auto->SetDbValueDef($rsnew, $this->emp_id_auto->CurrentValue, 0, FALSE);

		// com_id
		$this->com_id->SetDbValueDef($rsnew, $this->com_id->CurrentValue, 0, FALSE);

		// kondisi
		$this->kondisi->SetDbValueDef($rsnew, $this->kondisi->CurrentValue, "", strval($this->kondisi->CurrentValue) == "");

		// rumus
		$this->rumus->SetDbValueDef($rsnew, $this->rumus->CurrentValue, "", strval($this->rumus->CurrentValue) == "");

		// subtot_payroll
		$this->subtot_payroll->SetDbValueDef($rsnew, $this->subtot_payroll->CurrentValue, 0, FALSE);

		// jml_faktor
		$this->jml_faktor->SetDbValueDef($rsnew, $this->jml_faktor->CurrentValue, 0, strval($this->jml_faktor->CurrentValue) == "");

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['process_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['no_urut']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['no_urut_ref']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("z_pay_process_sdlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($z_pay_process_sd_add)) $z_pay_process_sd_add = new cz_pay_process_sd_add();

// Page init
$z_pay_process_sd_add->Page_Init();

// Page main
$z_pay_process_sd_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$z_pay_process_sd_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fz_pay_process_sdadd = new ew_Form("fz_pay_process_sdadd", "add");

// Validate form
fz_pay_process_sdadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->process_id->FldCaption(), $z_pay_process_sd->process_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_process_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->process_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->no_urut->FldCaption(), $z_pay_process_sd->no_urut->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_urut");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->no_urut->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_urut_ref");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->no_urut_ref->FldCaption(), $z_pay_process_sd->no_urut_ref->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_no_urut_ref");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->no_urut_ref->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->emp_id_auto->FldCaption(), $z_pay_process_sd->emp_id_auto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->emp_id_auto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->com_id->FldCaption(), $z_pay_process_sd->com_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_com_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->com_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kondisi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->kondisi->FldCaption(), $z_pay_process_sd->kondisi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_rumus");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->rumus->FldCaption(), $z_pay_process_sd->rumus->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subtot_payroll");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->subtot_payroll->FldCaption(), $z_pay_process_sd->subtot_payroll->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_subtot_payroll");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->subtot_payroll->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jml_faktor");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $z_pay_process_sd->jml_faktor->FldCaption(), $z_pay_process_sd->jml_faktor->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jml_faktor");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($z_pay_process_sd->jml_faktor->FldErrMsg()) ?>");

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
fz_pay_process_sdadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fz_pay_process_sdadd.ValidateRequired = true;
<?php } else { ?>
fz_pay_process_sdadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$z_pay_process_sd_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $z_pay_process_sd_add->ShowPageHeader(); ?>
<?php
$z_pay_process_sd_add->ShowMessage();
?>
<form name="fz_pay_process_sdadd" id="fz_pay_process_sdadd" class="<?php echo $z_pay_process_sd_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($z_pay_process_sd_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $z_pay_process_sd_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="z_pay_process_sd">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($z_pay_process_sd_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($z_pay_process_sd->process_id->Visible) { // process_id ?>
	<div id="r_process_id" class="form-group">
		<label id="elh_z_pay_process_sd_process_id" for="x_process_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->process_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->process_id->CellAttributes() ?>>
<span id="el_z_pay_process_sd_process_id">
<input type="text" data-table="z_pay_process_sd" data-field="x_process_id" name="x_process_id" id="x_process_id" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->process_id->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->process_id->EditValue ?>"<?php echo $z_pay_process_sd->process_id->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->process_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->no_urut->Visible) { // no_urut ?>
	<div id="r_no_urut" class="form-group">
		<label id="elh_z_pay_process_sd_no_urut" for="x_no_urut" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->no_urut->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->no_urut->CellAttributes() ?>>
<span id="el_z_pay_process_sd_no_urut">
<input type="text" data-table="z_pay_process_sd" data-field="x_no_urut" name="x_no_urut" id="x_no_urut" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->no_urut->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->no_urut->EditValue ?>"<?php echo $z_pay_process_sd->no_urut->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->no_urut->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->no_urut_ref->Visible) { // no_urut_ref ?>
	<div id="r_no_urut_ref" class="form-group">
		<label id="elh_z_pay_process_sd_no_urut_ref" for="x_no_urut_ref" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->no_urut_ref->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->no_urut_ref->CellAttributes() ?>>
<span id="el_z_pay_process_sd_no_urut_ref">
<input type="text" data-table="z_pay_process_sd" data-field="x_no_urut_ref" name="x_no_urut_ref" id="x_no_urut_ref" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->no_urut_ref->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->no_urut_ref->EditValue ?>"<?php echo $z_pay_process_sd->no_urut_ref->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->no_urut_ref->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->emp_id_auto->Visible) { // emp_id_auto ?>
	<div id="r_emp_id_auto" class="form-group">
		<label id="elh_z_pay_process_sd_emp_id_auto" for="x_emp_id_auto" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->emp_id_auto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->emp_id_auto->CellAttributes() ?>>
<span id="el_z_pay_process_sd_emp_id_auto">
<input type="text" data-table="z_pay_process_sd" data-field="x_emp_id_auto" name="x_emp_id_auto" id="x_emp_id_auto" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->emp_id_auto->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->emp_id_auto->EditValue ?>"<?php echo $z_pay_process_sd->emp_id_auto->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->emp_id_auto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->com_id->Visible) { // com_id ?>
	<div id="r_com_id" class="form-group">
		<label id="elh_z_pay_process_sd_com_id" for="x_com_id" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->com_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->com_id->CellAttributes() ?>>
<span id="el_z_pay_process_sd_com_id">
<input type="text" data-table="z_pay_process_sd" data-field="x_com_id" name="x_com_id" id="x_com_id" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->com_id->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->com_id->EditValue ?>"<?php echo $z_pay_process_sd->com_id->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->com_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->kondisi->Visible) { // kondisi ?>
	<div id="r_kondisi" class="form-group">
		<label id="elh_z_pay_process_sd_kondisi" for="x_kondisi" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->kondisi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->kondisi->CellAttributes() ?>>
<span id="el_z_pay_process_sd_kondisi">
<input type="text" data-table="z_pay_process_sd" data-field="x_kondisi" name="x_kondisi" id="x_kondisi" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->kondisi->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->kondisi->EditValue ?>"<?php echo $z_pay_process_sd->kondisi->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->kondisi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->rumus->Visible) { // rumus ?>
	<div id="r_rumus" class="form-group">
		<label id="elh_z_pay_process_sd_rumus" for="x_rumus" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->rumus->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->rumus->CellAttributes() ?>>
<span id="el_z_pay_process_sd_rumus">
<input type="text" data-table="z_pay_process_sd" data-field="x_rumus" name="x_rumus" id="x_rumus" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->rumus->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->rumus->EditValue ?>"<?php echo $z_pay_process_sd->rumus->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->rumus->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->subtot_payroll->Visible) { // subtot_payroll ?>
	<div id="r_subtot_payroll" class="form-group">
		<label id="elh_z_pay_process_sd_subtot_payroll" for="x_subtot_payroll" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->subtot_payroll->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->subtot_payroll->CellAttributes() ?>>
<span id="el_z_pay_process_sd_subtot_payroll">
<input type="text" data-table="z_pay_process_sd" data-field="x_subtot_payroll" name="x_subtot_payroll" id="x_subtot_payroll" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->subtot_payroll->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->subtot_payroll->EditValue ?>"<?php echo $z_pay_process_sd->subtot_payroll->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->subtot_payroll->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($z_pay_process_sd->jml_faktor->Visible) { // jml_faktor ?>
	<div id="r_jml_faktor" class="form-group">
		<label id="elh_z_pay_process_sd_jml_faktor" for="x_jml_faktor" class="col-sm-2 control-label ewLabel"><?php echo $z_pay_process_sd->jml_faktor->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $z_pay_process_sd->jml_faktor->CellAttributes() ?>>
<span id="el_z_pay_process_sd_jml_faktor">
<input type="text" data-table="z_pay_process_sd" data-field="x_jml_faktor" name="x_jml_faktor" id="x_jml_faktor" size="30" placeholder="<?php echo ew_HtmlEncode($z_pay_process_sd->jml_faktor->getPlaceHolder()) ?>" value="<?php echo $z_pay_process_sd->jml_faktor->EditValue ?>"<?php echo $z_pay_process_sd->jml_faktor->EditAttributes() ?>>
</span>
<?php echo $z_pay_process_sd->jml_faktor->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$z_pay_process_sd_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $z_pay_process_sd_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fz_pay_process_sdadd.Init();
</script>
<?php
$z_pay_process_sd_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$z_pay_process_sd_add->Page_Terminate();
?>
