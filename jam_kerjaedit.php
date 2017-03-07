<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "jam_kerjainfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$jam_kerja_edit = NULL; // Initialize page object first

class cjam_kerja_edit extends cjam_kerja {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'jam_kerja';

	// Page object name
	var $PageObjName = 'jam_kerja_edit';

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

		// Table object (jam_kerja)
		if (!isset($GLOBALS["jam_kerja"]) || get_class($GLOBALS["jam_kerja"]) == "cjam_kerja") {
			$GLOBALS["jam_kerja"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jam_kerja"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jam_kerja', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("jam_kerjalist.php"));
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
		$this->jk_id->SetVisibility();
		$this->jk_name->SetVisibility();
		$this->jk_kode->SetVisibility();
		$this->use_set->SetVisibility();
		$this->jk_bcin->SetVisibility();
		$this->jk_cin->SetVisibility();
		$this->jk_ecin->SetVisibility();
		$this->jk_tol_late->SetVisibility();
		$this->jk_use_ist->SetVisibility();
		$this->jk_ist1->SetVisibility();
		$this->jk_ist2->SetVisibility();
		$this->jk_tol_early->SetVisibility();
		$this->jk_bcout->SetVisibility();
		$this->jk_cout->SetVisibility();
		$this->jk_ecout->SetVisibility();
		$this->use_eot->SetVisibility();
		$this->min_eot->SetVisibility();
		$this->max_eot->SetVisibility();
		$this->reduce_eot->SetVisibility();
		$this->jk_durasi->SetVisibility();
		$this->jk_countas->SetVisibility();
		$this->jk_min_countas->SetVisibility();
		$this->jk_ket->SetVisibility();

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
		global $EW_EXPORT, $jam_kerja;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jam_kerja);
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
		if (@$_GET["jk_id"] <> "") {
			$this->jk_id->setQueryStringValue($_GET["jk_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->jk_id->CurrentValue == "") {
			$this->Page_Terminate("jam_kerjalist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("jam_kerjalist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "jam_kerjalist.php")
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
		if (!$this->jk_id->FldIsDetailKey) {
			$this->jk_id->setFormValue($objForm->GetValue("x_jk_id"));
		}
		if (!$this->jk_name->FldIsDetailKey) {
			$this->jk_name->setFormValue($objForm->GetValue("x_jk_name"));
		}
		if (!$this->jk_kode->FldIsDetailKey) {
			$this->jk_kode->setFormValue($objForm->GetValue("x_jk_kode"));
		}
		if (!$this->use_set->FldIsDetailKey) {
			$this->use_set->setFormValue($objForm->GetValue("x_use_set"));
		}
		if (!$this->jk_bcin->FldIsDetailKey) {
			$this->jk_bcin->setFormValue($objForm->GetValue("x_jk_bcin"));
			$this->jk_bcin->CurrentValue = ew_UnFormatDateTime($this->jk_bcin->CurrentValue, 0);
		}
		if (!$this->jk_cin->FldIsDetailKey) {
			$this->jk_cin->setFormValue($objForm->GetValue("x_jk_cin"));
		}
		if (!$this->jk_ecin->FldIsDetailKey) {
			$this->jk_ecin->setFormValue($objForm->GetValue("x_jk_ecin"));
		}
		if (!$this->jk_tol_late->FldIsDetailKey) {
			$this->jk_tol_late->setFormValue($objForm->GetValue("x_jk_tol_late"));
		}
		if (!$this->jk_use_ist->FldIsDetailKey) {
			$this->jk_use_ist->setFormValue($objForm->GetValue("x_jk_use_ist"));
		}
		if (!$this->jk_ist1->FldIsDetailKey) {
			$this->jk_ist1->setFormValue($objForm->GetValue("x_jk_ist1"));
			$this->jk_ist1->CurrentValue = ew_UnFormatDateTime($this->jk_ist1->CurrentValue, 0);
		}
		if (!$this->jk_ist2->FldIsDetailKey) {
			$this->jk_ist2->setFormValue($objForm->GetValue("x_jk_ist2"));
			$this->jk_ist2->CurrentValue = ew_UnFormatDateTime($this->jk_ist2->CurrentValue, 0);
		}
		if (!$this->jk_tol_early->FldIsDetailKey) {
			$this->jk_tol_early->setFormValue($objForm->GetValue("x_jk_tol_early"));
		}
		if (!$this->jk_bcout->FldIsDetailKey) {
			$this->jk_bcout->setFormValue($objForm->GetValue("x_jk_bcout"));
		}
		if (!$this->jk_cout->FldIsDetailKey) {
			$this->jk_cout->setFormValue($objForm->GetValue("x_jk_cout"));
		}
		if (!$this->jk_ecout->FldIsDetailKey) {
			$this->jk_ecout->setFormValue($objForm->GetValue("x_jk_ecout"));
			$this->jk_ecout->CurrentValue = ew_UnFormatDateTime($this->jk_ecout->CurrentValue, 0);
		}
		if (!$this->use_eot->FldIsDetailKey) {
			$this->use_eot->setFormValue($objForm->GetValue("x_use_eot"));
		}
		if (!$this->min_eot->FldIsDetailKey) {
			$this->min_eot->setFormValue($objForm->GetValue("x_min_eot"));
		}
		if (!$this->max_eot->FldIsDetailKey) {
			$this->max_eot->setFormValue($objForm->GetValue("x_max_eot"));
		}
		if (!$this->reduce_eot->FldIsDetailKey) {
			$this->reduce_eot->setFormValue($objForm->GetValue("x_reduce_eot"));
		}
		if (!$this->jk_durasi->FldIsDetailKey) {
			$this->jk_durasi->setFormValue($objForm->GetValue("x_jk_durasi"));
		}
		if (!$this->jk_countas->FldIsDetailKey) {
			$this->jk_countas->setFormValue($objForm->GetValue("x_jk_countas"));
		}
		if (!$this->jk_min_countas->FldIsDetailKey) {
			$this->jk_min_countas->setFormValue($objForm->GetValue("x_jk_min_countas"));
		}
		if (!$this->jk_ket->FldIsDetailKey) {
			$this->jk_ket->setFormValue($objForm->GetValue("x_jk_ket"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->jk_id->CurrentValue = $this->jk_id->FormValue;
		$this->jk_name->CurrentValue = $this->jk_name->FormValue;
		$this->jk_kode->CurrentValue = $this->jk_kode->FormValue;
		$this->use_set->CurrentValue = $this->use_set->FormValue;
		$this->jk_bcin->CurrentValue = $this->jk_bcin->FormValue;
		$this->jk_bcin->CurrentValue = ew_UnFormatDateTime($this->jk_bcin->CurrentValue, 0);
		$this->jk_cin->CurrentValue = $this->jk_cin->FormValue;
		$this->jk_ecin->CurrentValue = $this->jk_ecin->FormValue;
		$this->jk_tol_late->CurrentValue = $this->jk_tol_late->FormValue;
		$this->jk_use_ist->CurrentValue = $this->jk_use_ist->FormValue;
		$this->jk_ist1->CurrentValue = $this->jk_ist1->FormValue;
		$this->jk_ist1->CurrentValue = ew_UnFormatDateTime($this->jk_ist1->CurrentValue, 0);
		$this->jk_ist2->CurrentValue = $this->jk_ist2->FormValue;
		$this->jk_ist2->CurrentValue = ew_UnFormatDateTime($this->jk_ist2->CurrentValue, 0);
		$this->jk_tol_early->CurrentValue = $this->jk_tol_early->FormValue;
		$this->jk_bcout->CurrentValue = $this->jk_bcout->FormValue;
		$this->jk_cout->CurrentValue = $this->jk_cout->FormValue;
		$this->jk_ecout->CurrentValue = $this->jk_ecout->FormValue;
		$this->jk_ecout->CurrentValue = ew_UnFormatDateTime($this->jk_ecout->CurrentValue, 0);
		$this->use_eot->CurrentValue = $this->use_eot->FormValue;
		$this->min_eot->CurrentValue = $this->min_eot->FormValue;
		$this->max_eot->CurrentValue = $this->max_eot->FormValue;
		$this->reduce_eot->CurrentValue = $this->reduce_eot->FormValue;
		$this->jk_durasi->CurrentValue = $this->jk_durasi->FormValue;
		$this->jk_countas->CurrentValue = $this->jk_countas->FormValue;
		$this->jk_min_countas->CurrentValue = $this->jk_min_countas->FormValue;
		$this->jk_ket->CurrentValue = $this->jk_ket->FormValue;
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
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->jk_name->setDbValue($rs->fields('jk_name'));
		$this->jk_kode->setDbValue($rs->fields('jk_kode'));
		$this->use_set->setDbValue($rs->fields('use_set'));
		$this->jk_bcin->setDbValue($rs->fields('jk_bcin'));
		$this->jk_cin->setDbValue($rs->fields('jk_cin'));
		$this->jk_ecin->setDbValue($rs->fields('jk_ecin'));
		$this->jk_tol_late->setDbValue($rs->fields('jk_tol_late'));
		$this->jk_use_ist->setDbValue($rs->fields('jk_use_ist'));
		$this->jk_ist1->setDbValue($rs->fields('jk_ist1'));
		$this->jk_ist2->setDbValue($rs->fields('jk_ist2'));
		$this->jk_tol_early->setDbValue($rs->fields('jk_tol_early'));
		$this->jk_bcout->setDbValue($rs->fields('jk_bcout'));
		$this->jk_cout->setDbValue($rs->fields('jk_cout'));
		$this->jk_ecout->setDbValue($rs->fields('jk_ecout'));
		$this->use_eot->setDbValue($rs->fields('use_eot'));
		$this->min_eot->setDbValue($rs->fields('min_eot'));
		$this->max_eot->setDbValue($rs->fields('max_eot'));
		$this->reduce_eot->setDbValue($rs->fields('reduce_eot'));
		$this->jk_durasi->setDbValue($rs->fields('jk_durasi'));
		$this->jk_countas->setDbValue($rs->fields('jk_countas'));
		$this->jk_min_countas->setDbValue($rs->fields('jk_min_countas'));
		$this->jk_ket->setDbValue($rs->fields('jk_ket'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->jk_id->DbValue = $row['jk_id'];
		$this->jk_name->DbValue = $row['jk_name'];
		$this->jk_kode->DbValue = $row['jk_kode'];
		$this->use_set->DbValue = $row['use_set'];
		$this->jk_bcin->DbValue = $row['jk_bcin'];
		$this->jk_cin->DbValue = $row['jk_cin'];
		$this->jk_ecin->DbValue = $row['jk_ecin'];
		$this->jk_tol_late->DbValue = $row['jk_tol_late'];
		$this->jk_use_ist->DbValue = $row['jk_use_ist'];
		$this->jk_ist1->DbValue = $row['jk_ist1'];
		$this->jk_ist2->DbValue = $row['jk_ist2'];
		$this->jk_tol_early->DbValue = $row['jk_tol_early'];
		$this->jk_bcout->DbValue = $row['jk_bcout'];
		$this->jk_cout->DbValue = $row['jk_cout'];
		$this->jk_ecout->DbValue = $row['jk_ecout'];
		$this->use_eot->DbValue = $row['use_eot'];
		$this->min_eot->DbValue = $row['min_eot'];
		$this->max_eot->DbValue = $row['max_eot'];
		$this->reduce_eot->DbValue = $row['reduce_eot'];
		$this->jk_durasi->DbValue = $row['jk_durasi'];
		$this->jk_countas->DbValue = $row['jk_countas'];
		$this->jk_min_countas->DbValue = $row['jk_min_countas'];
		$this->jk_ket->DbValue = $row['jk_ket'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Convert decimal values if posted back

		if ($this->jk_countas->FormValue == $this->jk_countas->CurrentValue && is_numeric(ew_StrToFloat($this->jk_countas->CurrentValue)))
			$this->jk_countas->CurrentValue = ew_StrToFloat($this->jk_countas->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// jk_id
		// jk_name
		// jk_kode
		// use_set
		// jk_bcin
		// jk_cin
		// jk_ecin
		// jk_tol_late
		// jk_use_ist
		// jk_ist1
		// jk_ist2
		// jk_tol_early
		// jk_bcout
		// jk_cout
		// jk_ecout
		// use_eot
		// min_eot
		// max_eot
		// reduce_eot
		// jk_durasi
		// jk_countas
		// jk_min_countas
		// jk_ket

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jk_name
		$this->jk_name->ViewValue = $this->jk_name->CurrentValue;
		$this->jk_name->ViewCustomAttributes = "";

		// jk_kode
		$this->jk_kode->ViewValue = $this->jk_kode->CurrentValue;
		$this->jk_kode->ViewCustomAttributes = "";

		// use_set
		$this->use_set->ViewValue = $this->use_set->CurrentValue;
		$this->use_set->ViewCustomAttributes = "";

		// jk_bcin
		$this->jk_bcin->ViewValue = $this->jk_bcin->CurrentValue;
		$this->jk_bcin->ViewCustomAttributes = "";

		// jk_cin
		$this->jk_cin->ViewValue = $this->jk_cin->CurrentValue;
		$this->jk_cin->ViewCustomAttributes = "";

		// jk_ecin
		$this->jk_ecin->ViewValue = $this->jk_ecin->CurrentValue;
		$this->jk_ecin->ViewCustomAttributes = "";

		// jk_tol_late
		$this->jk_tol_late->ViewValue = $this->jk_tol_late->CurrentValue;
		$this->jk_tol_late->ViewCustomAttributes = "";

		// jk_use_ist
		$this->jk_use_ist->ViewValue = $this->jk_use_ist->CurrentValue;
		$this->jk_use_ist->ViewCustomAttributes = "";

		// jk_ist1
		$this->jk_ist1->ViewValue = $this->jk_ist1->CurrentValue;
		$this->jk_ist1->ViewCustomAttributes = "";

		// jk_ist2
		$this->jk_ist2->ViewValue = $this->jk_ist2->CurrentValue;
		$this->jk_ist2->ViewCustomAttributes = "";

		// jk_tol_early
		$this->jk_tol_early->ViewValue = $this->jk_tol_early->CurrentValue;
		$this->jk_tol_early->ViewCustomAttributes = "";

		// jk_bcout
		$this->jk_bcout->ViewValue = $this->jk_bcout->CurrentValue;
		$this->jk_bcout->ViewCustomAttributes = "";

		// jk_cout
		$this->jk_cout->ViewValue = $this->jk_cout->CurrentValue;
		$this->jk_cout->ViewCustomAttributes = "";

		// jk_ecout
		$this->jk_ecout->ViewValue = $this->jk_ecout->CurrentValue;
		$this->jk_ecout->ViewCustomAttributes = "";

		// use_eot
		$this->use_eot->ViewValue = $this->use_eot->CurrentValue;
		$this->use_eot->ViewCustomAttributes = "";

		// min_eot
		$this->min_eot->ViewValue = $this->min_eot->CurrentValue;
		$this->min_eot->ViewCustomAttributes = "";

		// max_eot
		$this->max_eot->ViewValue = $this->max_eot->CurrentValue;
		$this->max_eot->ViewCustomAttributes = "";

		// reduce_eot
		$this->reduce_eot->ViewValue = $this->reduce_eot->CurrentValue;
		$this->reduce_eot->ViewCustomAttributes = "";

		// jk_durasi
		$this->jk_durasi->ViewValue = $this->jk_durasi->CurrentValue;
		$this->jk_durasi->ViewCustomAttributes = "";

		// jk_countas
		$this->jk_countas->ViewValue = $this->jk_countas->CurrentValue;
		$this->jk_countas->ViewCustomAttributes = "";

		// jk_min_countas
		$this->jk_min_countas->ViewValue = $this->jk_min_countas->CurrentValue;
		$this->jk_min_countas->ViewCustomAttributes = "";

		// jk_ket
		$this->jk_ket->ViewValue = $this->jk_ket->CurrentValue;
		$this->jk_ket->ViewCustomAttributes = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";
			$this->jk_id->TooltipValue = "";

			// jk_name
			$this->jk_name->LinkCustomAttributes = "";
			$this->jk_name->HrefValue = "";
			$this->jk_name->TooltipValue = "";

			// jk_kode
			$this->jk_kode->LinkCustomAttributes = "";
			$this->jk_kode->HrefValue = "";
			$this->jk_kode->TooltipValue = "";

			// use_set
			$this->use_set->LinkCustomAttributes = "";
			$this->use_set->HrefValue = "";
			$this->use_set->TooltipValue = "";

			// jk_bcin
			$this->jk_bcin->LinkCustomAttributes = "";
			$this->jk_bcin->HrefValue = "";
			$this->jk_bcin->TooltipValue = "";

			// jk_cin
			$this->jk_cin->LinkCustomAttributes = "";
			$this->jk_cin->HrefValue = "";
			$this->jk_cin->TooltipValue = "";

			// jk_ecin
			$this->jk_ecin->LinkCustomAttributes = "";
			$this->jk_ecin->HrefValue = "";
			$this->jk_ecin->TooltipValue = "";

			// jk_tol_late
			$this->jk_tol_late->LinkCustomAttributes = "";
			$this->jk_tol_late->HrefValue = "";
			$this->jk_tol_late->TooltipValue = "";

			// jk_use_ist
			$this->jk_use_ist->LinkCustomAttributes = "";
			$this->jk_use_ist->HrefValue = "";
			$this->jk_use_ist->TooltipValue = "";

			// jk_ist1
			$this->jk_ist1->LinkCustomAttributes = "";
			$this->jk_ist1->HrefValue = "";
			$this->jk_ist1->TooltipValue = "";

			// jk_ist2
			$this->jk_ist2->LinkCustomAttributes = "";
			$this->jk_ist2->HrefValue = "";
			$this->jk_ist2->TooltipValue = "";

			// jk_tol_early
			$this->jk_tol_early->LinkCustomAttributes = "";
			$this->jk_tol_early->HrefValue = "";
			$this->jk_tol_early->TooltipValue = "";

			// jk_bcout
			$this->jk_bcout->LinkCustomAttributes = "";
			$this->jk_bcout->HrefValue = "";
			$this->jk_bcout->TooltipValue = "";

			// jk_cout
			$this->jk_cout->LinkCustomAttributes = "";
			$this->jk_cout->HrefValue = "";
			$this->jk_cout->TooltipValue = "";

			// jk_ecout
			$this->jk_ecout->LinkCustomAttributes = "";
			$this->jk_ecout->HrefValue = "";
			$this->jk_ecout->TooltipValue = "";

			// use_eot
			$this->use_eot->LinkCustomAttributes = "";
			$this->use_eot->HrefValue = "";
			$this->use_eot->TooltipValue = "";

			// min_eot
			$this->min_eot->LinkCustomAttributes = "";
			$this->min_eot->HrefValue = "";
			$this->min_eot->TooltipValue = "";

			// max_eot
			$this->max_eot->LinkCustomAttributes = "";
			$this->max_eot->HrefValue = "";
			$this->max_eot->TooltipValue = "";

			// reduce_eot
			$this->reduce_eot->LinkCustomAttributes = "";
			$this->reduce_eot->HrefValue = "";
			$this->reduce_eot->TooltipValue = "";

			// jk_durasi
			$this->jk_durasi->LinkCustomAttributes = "";
			$this->jk_durasi->HrefValue = "";
			$this->jk_durasi->TooltipValue = "";

			// jk_countas
			$this->jk_countas->LinkCustomAttributes = "";
			$this->jk_countas->HrefValue = "";
			$this->jk_countas->TooltipValue = "";

			// jk_min_countas
			$this->jk_min_countas->LinkCustomAttributes = "";
			$this->jk_min_countas->HrefValue = "";
			$this->jk_min_countas->TooltipValue = "";

			// jk_ket
			$this->jk_ket->LinkCustomAttributes = "";
			$this->jk_ket->HrefValue = "";
			$this->jk_ket->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// jk_id
			$this->jk_id->EditAttrs["class"] = "form-control";
			$this->jk_id->EditCustomAttributes = "";
			$this->jk_id->EditValue = $this->jk_id->CurrentValue;
			$this->jk_id->ViewCustomAttributes = "";

			// jk_name
			$this->jk_name->EditAttrs["class"] = "form-control";
			$this->jk_name->EditCustomAttributes = "";
			$this->jk_name->EditValue = ew_HtmlEncode($this->jk_name->CurrentValue);
			$this->jk_name->PlaceHolder = ew_RemoveHtml($this->jk_name->FldCaption());

			// jk_kode
			$this->jk_kode->EditAttrs["class"] = "form-control";
			$this->jk_kode->EditCustomAttributes = "";
			$this->jk_kode->EditValue = ew_HtmlEncode($this->jk_kode->CurrentValue);
			$this->jk_kode->PlaceHolder = ew_RemoveHtml($this->jk_kode->FldCaption());

			// use_set
			$this->use_set->EditAttrs["class"] = "form-control";
			$this->use_set->EditCustomAttributes = "";
			$this->use_set->EditValue = ew_HtmlEncode($this->use_set->CurrentValue);
			$this->use_set->PlaceHolder = ew_RemoveHtml($this->use_set->FldCaption());

			// jk_bcin
			$this->jk_bcin->EditAttrs["class"] = "form-control";
			$this->jk_bcin->EditCustomAttributes = "";
			$this->jk_bcin->EditValue = ew_HtmlEncode($this->jk_bcin->CurrentValue);
			$this->jk_bcin->PlaceHolder = ew_RemoveHtml($this->jk_bcin->FldCaption());

			// jk_cin
			$this->jk_cin->EditAttrs["class"] = "form-control";
			$this->jk_cin->EditCustomAttributes = "";
			$this->jk_cin->EditValue = ew_HtmlEncode($this->jk_cin->CurrentValue);
			$this->jk_cin->PlaceHolder = ew_RemoveHtml($this->jk_cin->FldCaption());

			// jk_ecin
			$this->jk_ecin->EditAttrs["class"] = "form-control";
			$this->jk_ecin->EditCustomAttributes = "";
			$this->jk_ecin->EditValue = ew_HtmlEncode($this->jk_ecin->CurrentValue);
			$this->jk_ecin->PlaceHolder = ew_RemoveHtml($this->jk_ecin->FldCaption());

			// jk_tol_late
			$this->jk_tol_late->EditAttrs["class"] = "form-control";
			$this->jk_tol_late->EditCustomAttributes = "";
			$this->jk_tol_late->EditValue = ew_HtmlEncode($this->jk_tol_late->CurrentValue);
			$this->jk_tol_late->PlaceHolder = ew_RemoveHtml($this->jk_tol_late->FldCaption());

			// jk_use_ist
			$this->jk_use_ist->EditAttrs["class"] = "form-control";
			$this->jk_use_ist->EditCustomAttributes = "";
			$this->jk_use_ist->EditValue = ew_HtmlEncode($this->jk_use_ist->CurrentValue);
			$this->jk_use_ist->PlaceHolder = ew_RemoveHtml($this->jk_use_ist->FldCaption());

			// jk_ist1
			$this->jk_ist1->EditAttrs["class"] = "form-control";
			$this->jk_ist1->EditCustomAttributes = "";
			$this->jk_ist1->EditValue = ew_HtmlEncode($this->jk_ist1->CurrentValue);
			$this->jk_ist1->PlaceHolder = ew_RemoveHtml($this->jk_ist1->FldCaption());

			// jk_ist2
			$this->jk_ist2->EditAttrs["class"] = "form-control";
			$this->jk_ist2->EditCustomAttributes = "";
			$this->jk_ist2->EditValue = ew_HtmlEncode($this->jk_ist2->CurrentValue);
			$this->jk_ist2->PlaceHolder = ew_RemoveHtml($this->jk_ist2->FldCaption());

			// jk_tol_early
			$this->jk_tol_early->EditAttrs["class"] = "form-control";
			$this->jk_tol_early->EditCustomAttributes = "";
			$this->jk_tol_early->EditValue = ew_HtmlEncode($this->jk_tol_early->CurrentValue);
			$this->jk_tol_early->PlaceHolder = ew_RemoveHtml($this->jk_tol_early->FldCaption());

			// jk_bcout
			$this->jk_bcout->EditAttrs["class"] = "form-control";
			$this->jk_bcout->EditCustomAttributes = "";
			$this->jk_bcout->EditValue = ew_HtmlEncode($this->jk_bcout->CurrentValue);
			$this->jk_bcout->PlaceHolder = ew_RemoveHtml($this->jk_bcout->FldCaption());

			// jk_cout
			$this->jk_cout->EditAttrs["class"] = "form-control";
			$this->jk_cout->EditCustomAttributes = "";
			$this->jk_cout->EditValue = ew_HtmlEncode($this->jk_cout->CurrentValue);
			$this->jk_cout->PlaceHolder = ew_RemoveHtml($this->jk_cout->FldCaption());

			// jk_ecout
			$this->jk_ecout->EditAttrs["class"] = "form-control";
			$this->jk_ecout->EditCustomAttributes = "";
			$this->jk_ecout->EditValue = ew_HtmlEncode($this->jk_ecout->CurrentValue);
			$this->jk_ecout->PlaceHolder = ew_RemoveHtml($this->jk_ecout->FldCaption());

			// use_eot
			$this->use_eot->EditAttrs["class"] = "form-control";
			$this->use_eot->EditCustomAttributes = "";
			$this->use_eot->EditValue = ew_HtmlEncode($this->use_eot->CurrentValue);
			$this->use_eot->PlaceHolder = ew_RemoveHtml($this->use_eot->FldCaption());

			// min_eot
			$this->min_eot->EditAttrs["class"] = "form-control";
			$this->min_eot->EditCustomAttributes = "";
			$this->min_eot->EditValue = ew_HtmlEncode($this->min_eot->CurrentValue);
			$this->min_eot->PlaceHolder = ew_RemoveHtml($this->min_eot->FldCaption());

			// max_eot
			$this->max_eot->EditAttrs["class"] = "form-control";
			$this->max_eot->EditCustomAttributes = "";
			$this->max_eot->EditValue = ew_HtmlEncode($this->max_eot->CurrentValue);
			$this->max_eot->PlaceHolder = ew_RemoveHtml($this->max_eot->FldCaption());

			// reduce_eot
			$this->reduce_eot->EditAttrs["class"] = "form-control";
			$this->reduce_eot->EditCustomAttributes = "";
			$this->reduce_eot->EditValue = ew_HtmlEncode($this->reduce_eot->CurrentValue);
			$this->reduce_eot->PlaceHolder = ew_RemoveHtml($this->reduce_eot->FldCaption());

			// jk_durasi
			$this->jk_durasi->EditAttrs["class"] = "form-control";
			$this->jk_durasi->EditCustomAttributes = "";
			$this->jk_durasi->EditValue = ew_HtmlEncode($this->jk_durasi->CurrentValue);
			$this->jk_durasi->PlaceHolder = ew_RemoveHtml($this->jk_durasi->FldCaption());

			// jk_countas
			$this->jk_countas->EditAttrs["class"] = "form-control";
			$this->jk_countas->EditCustomAttributes = "";
			$this->jk_countas->EditValue = ew_HtmlEncode($this->jk_countas->CurrentValue);
			$this->jk_countas->PlaceHolder = ew_RemoveHtml($this->jk_countas->FldCaption());
			if (strval($this->jk_countas->EditValue) <> "" && is_numeric($this->jk_countas->EditValue)) $this->jk_countas->EditValue = ew_FormatNumber($this->jk_countas->EditValue, -2, -1, -2, 0);

			// jk_min_countas
			$this->jk_min_countas->EditAttrs["class"] = "form-control";
			$this->jk_min_countas->EditCustomAttributes = "";
			$this->jk_min_countas->EditValue = ew_HtmlEncode($this->jk_min_countas->CurrentValue);
			$this->jk_min_countas->PlaceHolder = ew_RemoveHtml($this->jk_min_countas->FldCaption());

			// jk_ket
			$this->jk_ket->EditAttrs["class"] = "form-control";
			$this->jk_ket->EditCustomAttributes = "";
			$this->jk_ket->EditValue = ew_HtmlEncode($this->jk_ket->CurrentValue);
			$this->jk_ket->PlaceHolder = ew_RemoveHtml($this->jk_ket->FldCaption());

			// Edit refer script
			// jk_id

			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";

			// jk_name
			$this->jk_name->LinkCustomAttributes = "";
			$this->jk_name->HrefValue = "";

			// jk_kode
			$this->jk_kode->LinkCustomAttributes = "";
			$this->jk_kode->HrefValue = "";

			// use_set
			$this->use_set->LinkCustomAttributes = "";
			$this->use_set->HrefValue = "";

			// jk_bcin
			$this->jk_bcin->LinkCustomAttributes = "";
			$this->jk_bcin->HrefValue = "";

			// jk_cin
			$this->jk_cin->LinkCustomAttributes = "";
			$this->jk_cin->HrefValue = "";

			// jk_ecin
			$this->jk_ecin->LinkCustomAttributes = "";
			$this->jk_ecin->HrefValue = "";

			// jk_tol_late
			$this->jk_tol_late->LinkCustomAttributes = "";
			$this->jk_tol_late->HrefValue = "";

			// jk_use_ist
			$this->jk_use_ist->LinkCustomAttributes = "";
			$this->jk_use_ist->HrefValue = "";

			// jk_ist1
			$this->jk_ist1->LinkCustomAttributes = "";
			$this->jk_ist1->HrefValue = "";

			// jk_ist2
			$this->jk_ist2->LinkCustomAttributes = "";
			$this->jk_ist2->HrefValue = "";

			// jk_tol_early
			$this->jk_tol_early->LinkCustomAttributes = "";
			$this->jk_tol_early->HrefValue = "";

			// jk_bcout
			$this->jk_bcout->LinkCustomAttributes = "";
			$this->jk_bcout->HrefValue = "";

			// jk_cout
			$this->jk_cout->LinkCustomAttributes = "";
			$this->jk_cout->HrefValue = "";

			// jk_ecout
			$this->jk_ecout->LinkCustomAttributes = "";
			$this->jk_ecout->HrefValue = "";

			// use_eot
			$this->use_eot->LinkCustomAttributes = "";
			$this->use_eot->HrefValue = "";

			// min_eot
			$this->min_eot->LinkCustomAttributes = "";
			$this->min_eot->HrefValue = "";

			// max_eot
			$this->max_eot->LinkCustomAttributes = "";
			$this->max_eot->HrefValue = "";

			// reduce_eot
			$this->reduce_eot->LinkCustomAttributes = "";
			$this->reduce_eot->HrefValue = "";

			// jk_durasi
			$this->jk_durasi->LinkCustomAttributes = "";
			$this->jk_durasi->HrefValue = "";

			// jk_countas
			$this->jk_countas->LinkCustomAttributes = "";
			$this->jk_countas->HrefValue = "";

			// jk_min_countas
			$this->jk_min_countas->LinkCustomAttributes = "";
			$this->jk_min_countas->HrefValue = "";

			// jk_ket
			$this->jk_ket->LinkCustomAttributes = "";
			$this->jk_ket->HrefValue = "";
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
		if (!$this->jk_id->FldIsDetailKey && !is_null($this->jk_id->FormValue) && $this->jk_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_id->FldCaption(), $this->jk_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_id->FldErrMsg());
		}
		if (!$this->jk_name->FldIsDetailKey && !is_null($this->jk_name->FormValue) && $this->jk_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_name->FldCaption(), $this->jk_name->ReqErrMsg));
		}
		if (!$this->jk_kode->FldIsDetailKey && !is_null($this->jk_kode->FormValue) && $this->jk_kode->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_kode->FldCaption(), $this->jk_kode->ReqErrMsg));
		}
		if (!$this->use_set->FldIsDetailKey && !is_null($this->use_set->FormValue) && $this->use_set->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_set->FldCaption(), $this->use_set->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_set->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_set->FldErrMsg());
		}
		if (!$this->jk_bcin->FldIsDetailKey && !is_null($this->jk_bcin->FormValue) && $this->jk_bcin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_bcin->FldCaption(), $this->jk_bcin->ReqErrMsg));
		}
		if (!ew_CheckTime($this->jk_bcin->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_bcin->FldErrMsg());
		}
		if (!$this->jk_cin->FldIsDetailKey && !is_null($this->jk_cin->FormValue) && $this->jk_cin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_cin->FldCaption(), $this->jk_cin->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_cin->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_cin->FldErrMsg());
		}
		if (!$this->jk_ecin->FldIsDetailKey && !is_null($this->jk_ecin->FormValue) && $this->jk_ecin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_ecin->FldCaption(), $this->jk_ecin->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_ecin->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_ecin->FldErrMsg());
		}
		if (!$this->jk_tol_late->FldIsDetailKey && !is_null($this->jk_tol_late->FormValue) && $this->jk_tol_late->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_tol_late->FldCaption(), $this->jk_tol_late->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_tol_late->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_tol_late->FldErrMsg());
		}
		if (!$this->jk_use_ist->FldIsDetailKey && !is_null($this->jk_use_ist->FormValue) && $this->jk_use_ist->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_use_ist->FldCaption(), $this->jk_use_ist->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_use_ist->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_use_ist->FldErrMsg());
		}
		if (!$this->jk_ist1->FldIsDetailKey && !is_null($this->jk_ist1->FormValue) && $this->jk_ist1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_ist1->FldCaption(), $this->jk_ist1->ReqErrMsg));
		}
		if (!ew_CheckTime($this->jk_ist1->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_ist1->FldErrMsg());
		}
		if (!$this->jk_ist2->FldIsDetailKey && !is_null($this->jk_ist2->FormValue) && $this->jk_ist2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_ist2->FldCaption(), $this->jk_ist2->ReqErrMsg));
		}
		if (!ew_CheckTime($this->jk_ist2->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_ist2->FldErrMsg());
		}
		if (!$this->jk_tol_early->FldIsDetailKey && !is_null($this->jk_tol_early->FormValue) && $this->jk_tol_early->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_tol_early->FldCaption(), $this->jk_tol_early->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_tol_early->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_tol_early->FldErrMsg());
		}
		if (!$this->jk_bcout->FldIsDetailKey && !is_null($this->jk_bcout->FormValue) && $this->jk_bcout->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_bcout->FldCaption(), $this->jk_bcout->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_bcout->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_bcout->FldErrMsg());
		}
		if (!$this->jk_cout->FldIsDetailKey && !is_null($this->jk_cout->FormValue) && $this->jk_cout->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_cout->FldCaption(), $this->jk_cout->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_cout->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_cout->FldErrMsg());
		}
		if (!$this->jk_ecout->FldIsDetailKey && !is_null($this->jk_ecout->FormValue) && $this->jk_ecout->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_ecout->FldCaption(), $this->jk_ecout->ReqErrMsg));
		}
		if (!ew_CheckTime($this->jk_ecout->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_ecout->FldErrMsg());
		}
		if (!$this->use_eot->FldIsDetailKey && !is_null($this->use_eot->FormValue) && $this->use_eot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->use_eot->FldCaption(), $this->use_eot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->use_eot->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_eot->FldErrMsg());
		}
		if (!$this->min_eot->FldIsDetailKey && !is_null($this->min_eot->FormValue) && $this->min_eot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->min_eot->FldCaption(), $this->min_eot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->min_eot->FormValue)) {
			ew_AddMessage($gsFormError, $this->min_eot->FldErrMsg());
		}
		if (!$this->max_eot->FldIsDetailKey && !is_null($this->max_eot->FormValue) && $this->max_eot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->max_eot->FldCaption(), $this->max_eot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->max_eot->FormValue)) {
			ew_AddMessage($gsFormError, $this->max_eot->FldErrMsg());
		}
		if (!$this->reduce_eot->FldIsDetailKey && !is_null($this->reduce_eot->FormValue) && $this->reduce_eot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->reduce_eot->FldCaption(), $this->reduce_eot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->reduce_eot->FormValue)) {
			ew_AddMessage($gsFormError, $this->reduce_eot->FldErrMsg());
		}
		if (!$this->jk_durasi->FldIsDetailKey && !is_null($this->jk_durasi->FormValue) && $this->jk_durasi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_durasi->FldCaption(), $this->jk_durasi->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_durasi->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_durasi->FldErrMsg());
		}
		if (!$this->jk_countas->FldIsDetailKey && !is_null($this->jk_countas->FormValue) && $this->jk_countas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_countas->FldCaption(), $this->jk_countas->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->jk_countas->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_countas->FldErrMsg());
		}
		if (!$this->jk_min_countas->FldIsDetailKey && !is_null($this->jk_min_countas->FormValue) && $this->jk_min_countas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_min_countas->FldCaption(), $this->jk_min_countas->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_min_countas->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_min_countas->FldErrMsg());
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

			// jk_id
			// jk_name

			$this->jk_name->SetDbValueDef($rsnew, $this->jk_name->CurrentValue, "", $this->jk_name->ReadOnly);

			// jk_kode
			$this->jk_kode->SetDbValueDef($rsnew, $this->jk_kode->CurrentValue, "", $this->jk_kode->ReadOnly);

			// use_set
			$this->use_set->SetDbValueDef($rsnew, $this->use_set->CurrentValue, 0, $this->use_set->ReadOnly);

			// jk_bcin
			$this->jk_bcin->SetDbValueDef($rsnew, $this->jk_bcin->CurrentValue, ew_CurrentTime(), $this->jk_bcin->ReadOnly);

			// jk_cin
			$this->jk_cin->SetDbValueDef($rsnew, $this->jk_cin->CurrentValue, 0, $this->jk_cin->ReadOnly);

			// jk_ecin
			$this->jk_ecin->SetDbValueDef($rsnew, $this->jk_ecin->CurrentValue, 0, $this->jk_ecin->ReadOnly);

			// jk_tol_late
			$this->jk_tol_late->SetDbValueDef($rsnew, $this->jk_tol_late->CurrentValue, 0, $this->jk_tol_late->ReadOnly);

			// jk_use_ist
			$this->jk_use_ist->SetDbValueDef($rsnew, $this->jk_use_ist->CurrentValue, 0, $this->jk_use_ist->ReadOnly);

			// jk_ist1
			$this->jk_ist1->SetDbValueDef($rsnew, $this->jk_ist1->CurrentValue, ew_CurrentTime(), $this->jk_ist1->ReadOnly);

			// jk_ist2
			$this->jk_ist2->SetDbValueDef($rsnew, $this->jk_ist2->CurrentValue, ew_CurrentTime(), $this->jk_ist2->ReadOnly);

			// jk_tol_early
			$this->jk_tol_early->SetDbValueDef($rsnew, $this->jk_tol_early->CurrentValue, 0, $this->jk_tol_early->ReadOnly);

			// jk_bcout
			$this->jk_bcout->SetDbValueDef($rsnew, $this->jk_bcout->CurrentValue, 0, $this->jk_bcout->ReadOnly);

			// jk_cout
			$this->jk_cout->SetDbValueDef($rsnew, $this->jk_cout->CurrentValue, 0, $this->jk_cout->ReadOnly);

			// jk_ecout
			$this->jk_ecout->SetDbValueDef($rsnew, $this->jk_ecout->CurrentValue, ew_CurrentTime(), $this->jk_ecout->ReadOnly);

			// use_eot
			$this->use_eot->SetDbValueDef($rsnew, $this->use_eot->CurrentValue, 0, $this->use_eot->ReadOnly);

			// min_eot
			$this->min_eot->SetDbValueDef($rsnew, $this->min_eot->CurrentValue, 0, $this->min_eot->ReadOnly);

			// max_eot
			$this->max_eot->SetDbValueDef($rsnew, $this->max_eot->CurrentValue, 0, $this->max_eot->ReadOnly);

			// reduce_eot
			$this->reduce_eot->SetDbValueDef($rsnew, $this->reduce_eot->CurrentValue, 0, $this->reduce_eot->ReadOnly);

			// jk_durasi
			$this->jk_durasi->SetDbValueDef($rsnew, $this->jk_durasi->CurrentValue, 0, $this->jk_durasi->ReadOnly);

			// jk_countas
			$this->jk_countas->SetDbValueDef($rsnew, $this->jk_countas->CurrentValue, 0, $this->jk_countas->ReadOnly);

			// jk_min_countas
			$this->jk_min_countas->SetDbValueDef($rsnew, $this->jk_min_countas->CurrentValue, 0, $this->jk_min_countas->ReadOnly);

			// jk_ket
			$this->jk_ket->SetDbValueDef($rsnew, $this->jk_ket->CurrentValue, NULL, $this->jk_ket->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jam_kerjalist.php"), "", $this->TableVar, TRUE);
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
if (!isset($jam_kerja_edit)) $jam_kerja_edit = new cjam_kerja_edit();

// Page init
$jam_kerja_edit->Page_Init();

// Page main
$jam_kerja_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jam_kerja_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fjam_kerjaedit = new ew_Form("fjam_kerjaedit", "edit");

// Validate form
fjam_kerjaedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_id->FldCaption(), $jam_kerja->jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_name->FldCaption(), $jam_kerja->jk_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_kode");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_kode->FldCaption(), $jam_kerja->jk_kode->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_set");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->use_set->FldCaption(), $jam_kerja->use_set->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_set");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->use_set->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_bcin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_bcin->FldCaption(), $jam_kerja->jk_bcin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_bcin");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_bcin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_cin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_cin->FldCaption(), $jam_kerja->jk_cin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_cin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_cin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_ecin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_ecin->FldCaption(), $jam_kerja->jk_ecin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_ecin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_ecin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_tol_late");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_tol_late->FldCaption(), $jam_kerja->jk_tol_late->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_tol_late");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_tol_late->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_use_ist");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_use_ist->FldCaption(), $jam_kerja->jk_use_ist->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_use_ist");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_use_ist->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_ist1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_ist1->FldCaption(), $jam_kerja->jk_ist1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_ist1");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_ist1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_ist2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_ist2->FldCaption(), $jam_kerja->jk_ist2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_ist2");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_ist2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_tol_early");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_tol_early->FldCaption(), $jam_kerja->jk_tol_early->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_tol_early");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_tol_early->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_bcout");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_bcout->FldCaption(), $jam_kerja->jk_bcout->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_bcout");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_bcout->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_cout");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_cout->FldCaption(), $jam_kerja->jk_cout->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_cout");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_cout->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_ecout");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_ecout->FldCaption(), $jam_kerja->jk_ecout->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_ecout");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_ecout->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_eot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->use_eot->FldCaption(), $jam_kerja->use_eot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_use_eot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->use_eot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_min_eot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->min_eot->FldCaption(), $jam_kerja->min_eot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_min_eot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->min_eot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_max_eot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->max_eot->FldCaption(), $jam_kerja->max_eot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_max_eot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->max_eot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_reduce_eot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->reduce_eot->FldCaption(), $jam_kerja->reduce_eot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_reduce_eot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->reduce_eot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_durasi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_durasi->FldCaption(), $jam_kerja->jk_durasi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_durasi");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_durasi->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_countas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_countas->FldCaption(), $jam_kerja->jk_countas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_countas");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_countas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_min_countas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jam_kerja->jk_min_countas->FldCaption(), $jam_kerja->jk_min_countas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_min_countas");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jam_kerja->jk_min_countas->FldErrMsg()) ?>");

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
fjam_kerjaedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjam_kerjaedit.ValidateRequired = true;
<?php } else { ?>
fjam_kerjaedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$jam_kerja_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $jam_kerja_edit->ShowPageHeader(); ?>
<?php
$jam_kerja_edit->ShowMessage();
?>
<form name="fjam_kerjaedit" id="fjam_kerjaedit" class="<?php echo $jam_kerja_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jam_kerja_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jam_kerja_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jam_kerja">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($jam_kerja_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($jam_kerja->jk_id->Visible) { // jk_id ?>
	<div id="r_jk_id" class="form-group">
		<label id="elh_jam_kerja_jk_id" for="x_jk_id" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_id->CellAttributes() ?>>
<span id="el_jam_kerja_jk_id">
<span<?php echo $jam_kerja->jk_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jam_kerja->jk_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jam_kerja" data-field="x_jk_id" name="x_jk_id" id="x_jk_id" value="<?php echo ew_HtmlEncode($jam_kerja->jk_id->CurrentValue) ?>">
<?php echo $jam_kerja->jk_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_name->Visible) { // jk_name ?>
	<div id="r_jk_name" class="form-group">
		<label id="elh_jam_kerja_jk_name" for="x_jk_name" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_name->CellAttributes() ?>>
<span id="el_jam_kerja_jk_name">
<input type="text" data-table="jam_kerja" data-field="x_jk_name" name="x_jk_name" id="x_jk_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_name->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_name->EditValue ?>"<?php echo $jam_kerja->jk_name->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_kode->Visible) { // jk_kode ?>
	<div id="r_jk_kode" class="form-group">
		<label id="elh_jam_kerja_jk_kode" for="x_jk_kode" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_kode->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_kode->CellAttributes() ?>>
<span id="el_jam_kerja_jk_kode">
<input type="text" data-table="jam_kerja" data-field="x_jk_kode" name="x_jk_kode" id="x_jk_kode" size="30" maxlength="10" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_kode->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_kode->EditValue ?>"<?php echo $jam_kerja->jk_kode->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_kode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->use_set->Visible) { // use_set ?>
	<div id="r_use_set" class="form-group">
		<label id="elh_jam_kerja_use_set" for="x_use_set" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->use_set->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->use_set->CellAttributes() ?>>
<span id="el_jam_kerja_use_set">
<input type="text" data-table="jam_kerja" data-field="x_use_set" name="x_use_set" id="x_use_set" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->use_set->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->use_set->EditValue ?>"<?php echo $jam_kerja->use_set->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->use_set->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_bcin->Visible) { // jk_bcin ?>
	<div id="r_jk_bcin" class="form-group">
		<label id="elh_jam_kerja_jk_bcin" for="x_jk_bcin" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_bcin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_bcin->CellAttributes() ?>>
<span id="el_jam_kerja_jk_bcin">
<input type="text" data-table="jam_kerja" data-field="x_jk_bcin" name="x_jk_bcin" id="x_jk_bcin" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_bcin->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_bcin->EditValue ?>"<?php echo $jam_kerja->jk_bcin->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_bcin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_cin->Visible) { // jk_cin ?>
	<div id="r_jk_cin" class="form-group">
		<label id="elh_jam_kerja_jk_cin" for="x_jk_cin" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_cin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_cin->CellAttributes() ?>>
<span id="el_jam_kerja_jk_cin">
<input type="text" data-table="jam_kerja" data-field="x_jk_cin" name="x_jk_cin" id="x_jk_cin" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_cin->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_cin->EditValue ?>"<?php echo $jam_kerja->jk_cin->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_cin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_ecin->Visible) { // jk_ecin ?>
	<div id="r_jk_ecin" class="form-group">
		<label id="elh_jam_kerja_jk_ecin" for="x_jk_ecin" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_ecin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_ecin->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ecin">
<input type="text" data-table="jam_kerja" data-field="x_jk_ecin" name="x_jk_ecin" id="x_jk_ecin" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_ecin->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_ecin->EditValue ?>"<?php echo $jam_kerja->jk_ecin->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_ecin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_tol_late->Visible) { // jk_tol_late ?>
	<div id="r_jk_tol_late" class="form-group">
		<label id="elh_jam_kerja_jk_tol_late" for="x_jk_tol_late" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_tol_late->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_tol_late->CellAttributes() ?>>
<span id="el_jam_kerja_jk_tol_late">
<input type="text" data-table="jam_kerja" data-field="x_jk_tol_late" name="x_jk_tol_late" id="x_jk_tol_late" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_tol_late->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_tol_late->EditValue ?>"<?php echo $jam_kerja->jk_tol_late->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_tol_late->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_use_ist->Visible) { // jk_use_ist ?>
	<div id="r_jk_use_ist" class="form-group">
		<label id="elh_jam_kerja_jk_use_ist" for="x_jk_use_ist" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_use_ist->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_use_ist->CellAttributes() ?>>
<span id="el_jam_kerja_jk_use_ist">
<input type="text" data-table="jam_kerja" data-field="x_jk_use_ist" name="x_jk_use_ist" id="x_jk_use_ist" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_use_ist->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_use_ist->EditValue ?>"<?php echo $jam_kerja->jk_use_ist->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_use_ist->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_ist1->Visible) { // jk_ist1 ?>
	<div id="r_jk_ist1" class="form-group">
		<label id="elh_jam_kerja_jk_ist1" for="x_jk_ist1" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_ist1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_ist1->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ist1">
<input type="text" data-table="jam_kerja" data-field="x_jk_ist1" name="x_jk_ist1" id="x_jk_ist1" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_ist1->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_ist1->EditValue ?>"<?php echo $jam_kerja->jk_ist1->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_ist1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_ist2->Visible) { // jk_ist2 ?>
	<div id="r_jk_ist2" class="form-group">
		<label id="elh_jam_kerja_jk_ist2" for="x_jk_ist2" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_ist2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_ist2->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ist2">
<input type="text" data-table="jam_kerja" data-field="x_jk_ist2" name="x_jk_ist2" id="x_jk_ist2" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_ist2->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_ist2->EditValue ?>"<?php echo $jam_kerja->jk_ist2->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_ist2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_tol_early->Visible) { // jk_tol_early ?>
	<div id="r_jk_tol_early" class="form-group">
		<label id="elh_jam_kerja_jk_tol_early" for="x_jk_tol_early" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_tol_early->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_tol_early->CellAttributes() ?>>
<span id="el_jam_kerja_jk_tol_early">
<input type="text" data-table="jam_kerja" data-field="x_jk_tol_early" name="x_jk_tol_early" id="x_jk_tol_early" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_tol_early->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_tol_early->EditValue ?>"<?php echo $jam_kerja->jk_tol_early->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_tol_early->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_bcout->Visible) { // jk_bcout ?>
	<div id="r_jk_bcout" class="form-group">
		<label id="elh_jam_kerja_jk_bcout" for="x_jk_bcout" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_bcout->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_bcout->CellAttributes() ?>>
<span id="el_jam_kerja_jk_bcout">
<input type="text" data-table="jam_kerja" data-field="x_jk_bcout" name="x_jk_bcout" id="x_jk_bcout" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_bcout->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_bcout->EditValue ?>"<?php echo $jam_kerja->jk_bcout->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_bcout->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_cout->Visible) { // jk_cout ?>
	<div id="r_jk_cout" class="form-group">
		<label id="elh_jam_kerja_jk_cout" for="x_jk_cout" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_cout->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_cout->CellAttributes() ?>>
<span id="el_jam_kerja_jk_cout">
<input type="text" data-table="jam_kerja" data-field="x_jk_cout" name="x_jk_cout" id="x_jk_cout" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_cout->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_cout->EditValue ?>"<?php echo $jam_kerja->jk_cout->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_cout->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_ecout->Visible) { // jk_ecout ?>
	<div id="r_jk_ecout" class="form-group">
		<label id="elh_jam_kerja_jk_ecout" for="x_jk_ecout" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_ecout->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_ecout->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ecout">
<input type="text" data-table="jam_kerja" data-field="x_jk_ecout" name="x_jk_ecout" id="x_jk_ecout" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_ecout->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_ecout->EditValue ?>"<?php echo $jam_kerja->jk_ecout->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_ecout->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->use_eot->Visible) { // use_eot ?>
	<div id="r_use_eot" class="form-group">
		<label id="elh_jam_kerja_use_eot" for="x_use_eot" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->use_eot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->use_eot->CellAttributes() ?>>
<span id="el_jam_kerja_use_eot">
<input type="text" data-table="jam_kerja" data-field="x_use_eot" name="x_use_eot" id="x_use_eot" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->use_eot->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->use_eot->EditValue ?>"<?php echo $jam_kerja->use_eot->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->use_eot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->min_eot->Visible) { // min_eot ?>
	<div id="r_min_eot" class="form-group">
		<label id="elh_jam_kerja_min_eot" for="x_min_eot" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->min_eot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->min_eot->CellAttributes() ?>>
<span id="el_jam_kerja_min_eot">
<input type="text" data-table="jam_kerja" data-field="x_min_eot" name="x_min_eot" id="x_min_eot" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->min_eot->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->min_eot->EditValue ?>"<?php echo $jam_kerja->min_eot->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->min_eot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->max_eot->Visible) { // max_eot ?>
	<div id="r_max_eot" class="form-group">
		<label id="elh_jam_kerja_max_eot" for="x_max_eot" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->max_eot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->max_eot->CellAttributes() ?>>
<span id="el_jam_kerja_max_eot">
<input type="text" data-table="jam_kerja" data-field="x_max_eot" name="x_max_eot" id="x_max_eot" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->max_eot->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->max_eot->EditValue ?>"<?php echo $jam_kerja->max_eot->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->max_eot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->reduce_eot->Visible) { // reduce_eot ?>
	<div id="r_reduce_eot" class="form-group">
		<label id="elh_jam_kerja_reduce_eot" for="x_reduce_eot" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->reduce_eot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->reduce_eot->CellAttributes() ?>>
<span id="el_jam_kerja_reduce_eot">
<input type="text" data-table="jam_kerja" data-field="x_reduce_eot" name="x_reduce_eot" id="x_reduce_eot" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->reduce_eot->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->reduce_eot->EditValue ?>"<?php echo $jam_kerja->reduce_eot->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->reduce_eot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_durasi->Visible) { // jk_durasi ?>
	<div id="r_jk_durasi" class="form-group">
		<label id="elh_jam_kerja_jk_durasi" for="x_jk_durasi" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_durasi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_durasi->CellAttributes() ?>>
<span id="el_jam_kerja_jk_durasi">
<input type="text" data-table="jam_kerja" data-field="x_jk_durasi" name="x_jk_durasi" id="x_jk_durasi" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_durasi->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_durasi->EditValue ?>"<?php echo $jam_kerja->jk_durasi->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_durasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_countas->Visible) { // jk_countas ?>
	<div id="r_jk_countas" class="form-group">
		<label id="elh_jam_kerja_jk_countas" for="x_jk_countas" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_countas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_countas->CellAttributes() ?>>
<span id="el_jam_kerja_jk_countas">
<input type="text" data-table="jam_kerja" data-field="x_jk_countas" name="x_jk_countas" id="x_jk_countas" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_countas->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_countas->EditValue ?>"<?php echo $jam_kerja->jk_countas->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_countas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_min_countas->Visible) { // jk_min_countas ?>
	<div id="r_jk_min_countas" class="form-group">
		<label id="elh_jam_kerja_jk_min_countas" for="x_jk_min_countas" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_min_countas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_min_countas->CellAttributes() ?>>
<span id="el_jam_kerja_jk_min_countas">
<input type="text" data-table="jam_kerja" data-field="x_jk_min_countas" name="x_jk_min_countas" id="x_jk_min_countas" size="30" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_min_countas->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_min_countas->EditValue ?>"<?php echo $jam_kerja->jk_min_countas->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_min_countas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jam_kerja->jk_ket->Visible) { // jk_ket ?>
	<div id="r_jk_ket" class="form-group">
		<label id="elh_jam_kerja_jk_ket" for="x_jk_ket" class="col-sm-2 control-label ewLabel"><?php echo $jam_kerja->jk_ket->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jam_kerja->jk_ket->CellAttributes() ?>>
<span id="el_jam_kerja_jk_ket">
<input type="text" data-table="jam_kerja" data-field="x_jk_ket" name="x_jk_ket" id="x_jk_ket" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($jam_kerja->jk_ket->getPlaceHolder()) ?>" value="<?php echo $jam_kerja->jk_ket->EditValue ?>"<?php echo $jam_kerja->jk_ket->EditAttributes() ?>>
</span>
<?php echo $jam_kerja->jk_ket->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$jam_kerja_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jam_kerja_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fjam_kerjaedit.Init();
</script>
<?php
$jam_kerja_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jam_kerja_edit->Page_Terminate();
?>
