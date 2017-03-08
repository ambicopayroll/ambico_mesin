<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "jdw_kerja_minfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$jdw_kerja_m_edit = NULL; // Initialize page object first

class cjdw_kerja_m_edit extends cjdw_kerja_m {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'jdw_kerja_m';

	// Page object name
	var $PageObjName = 'jdw_kerja_m_edit';

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

		// Table object (jdw_kerja_m)
		if (!isset($GLOBALS["jdw_kerja_m"]) || get_class($GLOBALS["jdw_kerja_m"]) == "cjdw_kerja_m") {
			$GLOBALS["jdw_kerja_m"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jdw_kerja_m"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jdw_kerja_m', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("jdw_kerja_mlist.php"));
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
		$this->jdw_kerja_m_id->SetVisibility();
		$this->jdw_kerja_m_kode->SetVisibility();
		$this->jdw_kerja_m_name->SetVisibility();
		$this->jdw_kerja_m_keterangan->SetVisibility();
		$this->jdw_kerja_m_periode->SetVisibility();
		$this->jdw_kerja_m_mulai->SetVisibility();
		$this->jdw_kerja_m_type->SetVisibility();
		$this->use_sama->SetVisibility();

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
		global $EW_EXPORT, $jdw_kerja_m;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jdw_kerja_m);
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
		if (@$_GET["jdw_kerja_m_id"] <> "") {
			$this->jdw_kerja_m_id->setQueryStringValue($_GET["jdw_kerja_m_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->jdw_kerja_m_id->CurrentValue == "") {
			$this->Page_Terminate("jdw_kerja_mlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("jdw_kerja_mlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "jdw_kerja_mlist.php")
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
		if (!$this->jdw_kerja_m_id->FldIsDetailKey) {
			$this->jdw_kerja_m_id->setFormValue($objForm->GetValue("x_jdw_kerja_m_id"));
		}
		if (!$this->jdw_kerja_m_kode->FldIsDetailKey) {
			$this->jdw_kerja_m_kode->setFormValue($objForm->GetValue("x_jdw_kerja_m_kode"));
		}
		if (!$this->jdw_kerja_m_name->FldIsDetailKey) {
			$this->jdw_kerja_m_name->setFormValue($objForm->GetValue("x_jdw_kerja_m_name"));
		}
		if (!$this->jdw_kerja_m_keterangan->FldIsDetailKey) {
			$this->jdw_kerja_m_keterangan->setFormValue($objForm->GetValue("x_jdw_kerja_m_keterangan"));
		}
		if (!$this->jdw_kerja_m_periode->FldIsDetailKey) {
			$this->jdw_kerja_m_periode->setFormValue($objForm->GetValue("x_jdw_kerja_m_periode"));
		}
		if (!$this->jdw_kerja_m_mulai->FldIsDetailKey) {
			$this->jdw_kerja_m_mulai->setFormValue($objForm->GetValue("x_jdw_kerja_m_mulai"));
			$this->jdw_kerja_m_mulai->CurrentValue = ew_UnFormatDateTime($this->jdw_kerja_m_mulai->CurrentValue, 0);
		}
		if (!$this->jdw_kerja_m_type->FldIsDetailKey) {
			$this->jdw_kerja_m_type->setFormValue($objForm->GetValue("x_jdw_kerja_m_type"));
		}
		if (!$this->use_sama->FldIsDetailKey) {
			$this->use_sama->setFormValue($objForm->GetValue("x_use_sama"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->jdw_kerja_m_id->CurrentValue = $this->jdw_kerja_m_id->FormValue;
		$this->jdw_kerja_m_kode->CurrentValue = $this->jdw_kerja_m_kode->FormValue;
		$this->jdw_kerja_m_name->CurrentValue = $this->jdw_kerja_m_name->FormValue;
		$this->jdw_kerja_m_keterangan->CurrentValue = $this->jdw_kerja_m_keterangan->FormValue;
		$this->jdw_kerja_m_periode->CurrentValue = $this->jdw_kerja_m_periode->FormValue;
		$this->jdw_kerja_m_mulai->CurrentValue = $this->jdw_kerja_m_mulai->FormValue;
		$this->jdw_kerja_m_mulai->CurrentValue = ew_UnFormatDateTime($this->jdw_kerja_m_mulai->CurrentValue, 0);
		$this->jdw_kerja_m_type->CurrentValue = $this->jdw_kerja_m_type->FormValue;
		$this->use_sama->CurrentValue = $this->use_sama->FormValue;
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
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->jdw_kerja_m_kode->setDbValue($rs->fields('jdw_kerja_m_kode'));
		$this->jdw_kerja_m_name->setDbValue($rs->fields('jdw_kerja_m_name'));
		$this->jdw_kerja_m_keterangan->setDbValue($rs->fields('jdw_kerja_m_keterangan'));
		$this->jdw_kerja_m_periode->setDbValue($rs->fields('jdw_kerja_m_periode'));
		$this->jdw_kerja_m_mulai->setDbValue($rs->fields('jdw_kerja_m_mulai'));
		$this->jdw_kerja_m_type->setDbValue($rs->fields('jdw_kerja_m_type'));
		$this->use_sama->setDbValue($rs->fields('use_sama'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->jdw_kerja_m_id->DbValue = $row['jdw_kerja_m_id'];
		$this->jdw_kerja_m_kode->DbValue = $row['jdw_kerja_m_kode'];
		$this->jdw_kerja_m_name->DbValue = $row['jdw_kerja_m_name'];
		$this->jdw_kerja_m_keterangan->DbValue = $row['jdw_kerja_m_keterangan'];
		$this->jdw_kerja_m_periode->DbValue = $row['jdw_kerja_m_periode'];
		$this->jdw_kerja_m_mulai->DbValue = $row['jdw_kerja_m_mulai'];
		$this->jdw_kerja_m_type->DbValue = $row['jdw_kerja_m_type'];
		$this->use_sama->DbValue = $row['use_sama'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// jdw_kerja_m_id
		// jdw_kerja_m_kode
		// jdw_kerja_m_name
		// jdw_kerja_m_keterangan
		// jdw_kerja_m_periode
		// jdw_kerja_m_mulai
		// jdw_kerja_m_type
		// use_sama

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// jdw_kerja_m_kode
		$this->jdw_kerja_m_kode->ViewValue = $this->jdw_kerja_m_kode->CurrentValue;
		$this->jdw_kerja_m_kode->ViewCustomAttributes = "";

		// jdw_kerja_m_name
		$this->jdw_kerja_m_name->ViewValue = $this->jdw_kerja_m_name->CurrentValue;
		$this->jdw_kerja_m_name->ViewCustomAttributes = "";

		// jdw_kerja_m_keterangan
		$this->jdw_kerja_m_keterangan->ViewValue = $this->jdw_kerja_m_keterangan->CurrentValue;
		$this->jdw_kerja_m_keterangan->ViewCustomAttributes = "";

		// jdw_kerja_m_periode
		$this->jdw_kerja_m_periode->ViewValue = $this->jdw_kerja_m_periode->CurrentValue;
		$this->jdw_kerja_m_periode->ViewCustomAttributes = "";

		// jdw_kerja_m_mulai
		$this->jdw_kerja_m_mulai->ViewValue = $this->jdw_kerja_m_mulai->CurrentValue;
		$this->jdw_kerja_m_mulai->ViewValue = ew_FormatDateTime($this->jdw_kerja_m_mulai->ViewValue, 0);
		$this->jdw_kerja_m_mulai->ViewCustomAttributes = "";

		// jdw_kerja_m_type
		$this->jdw_kerja_m_type->ViewValue = $this->jdw_kerja_m_type->CurrentValue;
		$this->jdw_kerja_m_type->ViewCustomAttributes = "";

		// use_sama
		$this->use_sama->ViewValue = $this->use_sama->CurrentValue;
		$this->use_sama->ViewCustomAttributes = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";
			$this->jdw_kerja_m_id->TooltipValue = "";

			// jdw_kerja_m_kode
			$this->jdw_kerja_m_kode->LinkCustomAttributes = "";
			$this->jdw_kerja_m_kode->HrefValue = "";
			$this->jdw_kerja_m_kode->TooltipValue = "";

			// jdw_kerja_m_name
			$this->jdw_kerja_m_name->LinkCustomAttributes = "";
			$this->jdw_kerja_m_name->HrefValue = "";
			$this->jdw_kerja_m_name->TooltipValue = "";

			// jdw_kerja_m_keterangan
			$this->jdw_kerja_m_keterangan->LinkCustomAttributes = "";
			$this->jdw_kerja_m_keterangan->HrefValue = "";
			$this->jdw_kerja_m_keterangan->TooltipValue = "";

			// jdw_kerja_m_periode
			$this->jdw_kerja_m_periode->LinkCustomAttributes = "";
			$this->jdw_kerja_m_periode->HrefValue = "";
			$this->jdw_kerja_m_periode->TooltipValue = "";

			// jdw_kerja_m_mulai
			$this->jdw_kerja_m_mulai->LinkCustomAttributes = "";
			$this->jdw_kerja_m_mulai->HrefValue = "";
			$this->jdw_kerja_m_mulai->TooltipValue = "";

			// jdw_kerja_m_type
			$this->jdw_kerja_m_type->LinkCustomAttributes = "";
			$this->jdw_kerja_m_type->HrefValue = "";
			$this->jdw_kerja_m_type->TooltipValue = "";

			// use_sama
			$this->use_sama->LinkCustomAttributes = "";
			$this->use_sama->HrefValue = "";
			$this->use_sama->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_id->EditCustomAttributes = "";
			$this->jdw_kerja_m_id->EditValue = $this->jdw_kerja_m_id->CurrentValue;
			$this->jdw_kerja_m_id->ViewCustomAttributes = "";

			// jdw_kerja_m_kode
			$this->jdw_kerja_m_kode->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_kode->EditCustomAttributes = "";
			$this->jdw_kerja_m_kode->EditValue = ew_HtmlEncode($this->jdw_kerja_m_kode->CurrentValue);
			$this->jdw_kerja_m_kode->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_kode->FldCaption());

			// jdw_kerja_m_name
			$this->jdw_kerja_m_name->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_name->EditCustomAttributes = "";
			$this->jdw_kerja_m_name->EditValue = ew_HtmlEncode($this->jdw_kerja_m_name->CurrentValue);
			$this->jdw_kerja_m_name->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_name->FldCaption());

			// jdw_kerja_m_keterangan
			$this->jdw_kerja_m_keterangan->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_keterangan->EditCustomAttributes = "";
			$this->jdw_kerja_m_keterangan->EditValue = ew_HtmlEncode($this->jdw_kerja_m_keterangan->CurrentValue);
			$this->jdw_kerja_m_keterangan->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_keterangan->FldCaption());

			// jdw_kerja_m_periode
			$this->jdw_kerja_m_periode->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_periode->EditCustomAttributes = "";
			$this->jdw_kerja_m_periode->EditValue = ew_HtmlEncode($this->jdw_kerja_m_periode->CurrentValue);
			$this->jdw_kerja_m_periode->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_periode->FldCaption());

			// jdw_kerja_m_mulai
			$this->jdw_kerja_m_mulai->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_mulai->EditCustomAttributes = "";
			$this->jdw_kerja_m_mulai->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->jdw_kerja_m_mulai->CurrentValue, 8));
			$this->jdw_kerja_m_mulai->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_mulai->FldCaption());

			// jdw_kerja_m_type
			$this->jdw_kerja_m_type->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_type->EditCustomAttributes = "";
			$this->jdw_kerja_m_type->EditValue = ew_HtmlEncode($this->jdw_kerja_m_type->CurrentValue);
			$this->jdw_kerja_m_type->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_type->FldCaption());

			// use_sama
			$this->use_sama->EditAttrs["class"] = "form-control";
			$this->use_sama->EditCustomAttributes = "";
			$this->use_sama->EditValue = ew_HtmlEncode($this->use_sama->CurrentValue);
			$this->use_sama->PlaceHolder = ew_RemoveHtml($this->use_sama->FldCaption());

			// Edit refer script
			// jdw_kerja_m_id

			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";

			// jdw_kerja_m_kode
			$this->jdw_kerja_m_kode->LinkCustomAttributes = "";
			$this->jdw_kerja_m_kode->HrefValue = "";

			// jdw_kerja_m_name
			$this->jdw_kerja_m_name->LinkCustomAttributes = "";
			$this->jdw_kerja_m_name->HrefValue = "";

			// jdw_kerja_m_keterangan
			$this->jdw_kerja_m_keterangan->LinkCustomAttributes = "";
			$this->jdw_kerja_m_keterangan->HrefValue = "";

			// jdw_kerja_m_periode
			$this->jdw_kerja_m_periode->LinkCustomAttributes = "";
			$this->jdw_kerja_m_periode->HrefValue = "";

			// jdw_kerja_m_mulai
			$this->jdw_kerja_m_mulai->LinkCustomAttributes = "";
			$this->jdw_kerja_m_mulai->HrefValue = "";

			// jdw_kerja_m_type
			$this->jdw_kerja_m_type->LinkCustomAttributes = "";
			$this->jdw_kerja_m_type->HrefValue = "";

			// use_sama
			$this->use_sama->LinkCustomAttributes = "";
			$this->use_sama->HrefValue = "";
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
		if (!$this->jdw_kerja_m_id->FldIsDetailKey && !is_null($this->jdw_kerja_m_id->FormValue) && $this->jdw_kerja_m_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jdw_kerja_m_id->FldCaption(), $this->jdw_kerja_m_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_id->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_periode->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_periode->FldErrMsg());
		}
		if (!ew_CheckDateDef($this->jdw_kerja_m_mulai->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_mulai->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_type->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_type->FldErrMsg());
		}
		if (!ew_CheckInteger($this->use_sama->FormValue)) {
			ew_AddMessage($gsFormError, $this->use_sama->FldErrMsg());
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

			// jdw_kerja_m_id
			// jdw_kerja_m_kode

			$this->jdw_kerja_m_kode->SetDbValueDef($rsnew, $this->jdw_kerja_m_kode->CurrentValue, NULL, $this->jdw_kerja_m_kode->ReadOnly);

			// jdw_kerja_m_name
			$this->jdw_kerja_m_name->SetDbValueDef($rsnew, $this->jdw_kerja_m_name->CurrentValue, NULL, $this->jdw_kerja_m_name->ReadOnly);

			// jdw_kerja_m_keterangan
			$this->jdw_kerja_m_keterangan->SetDbValueDef($rsnew, $this->jdw_kerja_m_keterangan->CurrentValue, NULL, $this->jdw_kerja_m_keterangan->ReadOnly);

			// jdw_kerja_m_periode
			$this->jdw_kerja_m_periode->SetDbValueDef($rsnew, $this->jdw_kerja_m_periode->CurrentValue, NULL, $this->jdw_kerja_m_periode->ReadOnly);

			// jdw_kerja_m_mulai
			$this->jdw_kerja_m_mulai->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->jdw_kerja_m_mulai->CurrentValue, 0), NULL, $this->jdw_kerja_m_mulai->ReadOnly);

			// jdw_kerja_m_type
			$this->jdw_kerja_m_type->SetDbValueDef($rsnew, $this->jdw_kerja_m_type->CurrentValue, NULL, $this->jdw_kerja_m_type->ReadOnly);

			// use_sama
			$this->use_sama->SetDbValueDef($rsnew, $this->use_sama->CurrentValue, NULL, $this->use_sama->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jdw_kerja_mlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($jdw_kerja_m_edit)) $jdw_kerja_m_edit = new cjdw_kerja_m_edit();

// Page init
$jdw_kerja_m_edit->Page_Init();

// Page main
$jdw_kerja_m_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jdw_kerja_m_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fjdw_kerja_medit = new ew_Form("fjdw_kerja_medit", "edit");

// Validate form
fjdw_kerja_medit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jdw_kerja_m->jdw_kerja_m_id->FldCaption(), $jdw_kerja_m->jdw_kerja_m_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jdw_kerja_m->jdw_kerja_m_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_periode");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jdw_kerja_m->jdw_kerja_m_periode->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_mulai");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jdw_kerja_m->jdw_kerja_m_mulai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_type");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jdw_kerja_m->jdw_kerja_m_type->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_use_sama");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jdw_kerja_m->use_sama->FldErrMsg()) ?>");

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
fjdw_kerja_medit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjdw_kerja_medit.ValidateRequired = true;
<?php } else { ?>
fjdw_kerja_medit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$jdw_kerja_m_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $jdw_kerja_m_edit->ShowPageHeader(); ?>
<?php
$jdw_kerja_m_edit->ShowMessage();
?>
<form name="fjdw_kerja_medit" id="fjdw_kerja_medit" class="<?php echo $jdw_kerja_m_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jdw_kerja_m_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jdw_kerja_m_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jdw_kerja_m">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($jdw_kerja_m_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($jdw_kerja_m->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
	<div id="r_jdw_kerja_m_id" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_id" for="x_jdw_kerja_m_id" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_id">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jdw_kerja_m->jdw_kerja_m_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_id" name="x_jdw_kerja_m_id" id="x_jdw_kerja_m_id" value="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_id->CurrentValue) ?>">
<?php echo $jdw_kerja_m->jdw_kerja_m_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_kode->Visible) { // jdw_kerja_m_kode ?>
	<div id="r_jdw_kerja_m_kode" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_kode" for="x_jdw_kerja_m_kode" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_kode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_kode->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_kode">
<input type="text" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_kode" name="x_jdw_kerja_m_kode" id="x_jdw_kerja_m_kode" size="30" maxlength="5" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_kode->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->jdw_kerja_m_kode->EditValue ?>"<?php echo $jdw_kerja_m->jdw_kerja_m_kode->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->jdw_kerja_m_kode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_name->Visible) { // jdw_kerja_m_name ?>
	<div id="r_jdw_kerja_m_name" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_name" for="x_jdw_kerja_m_name" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_name->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_name->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_name">
<input type="text" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_name" name="x_jdw_kerja_m_name" id="x_jdw_kerja_m_name" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_name->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->jdw_kerja_m_name->EditValue ?>"<?php echo $jdw_kerja_m->jdw_kerja_m_name->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->jdw_kerja_m_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_keterangan->Visible) { // jdw_kerja_m_keterangan ?>
	<div id="r_jdw_kerja_m_keterangan" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_keterangan" for="x_jdw_kerja_m_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_keterangan">
<input type="text" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_keterangan" name="x_jdw_kerja_m_keterangan" id="x_jdw_kerja_m_keterangan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_keterangan->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->EditValue ?>"<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_periode->Visible) { // jdw_kerja_m_periode ?>
	<div id="r_jdw_kerja_m_periode" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_periode" for="x_jdw_kerja_m_periode" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_periode->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_periode->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_periode">
<input type="text" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_periode" name="x_jdw_kerja_m_periode" id="x_jdw_kerja_m_periode" size="30" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_periode->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->jdw_kerja_m_periode->EditValue ?>"<?php echo $jdw_kerja_m->jdw_kerja_m_periode->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->jdw_kerja_m_periode->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_mulai->Visible) { // jdw_kerja_m_mulai ?>
	<div id="r_jdw_kerja_m_mulai" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_mulai" for="x_jdw_kerja_m_mulai" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_mulai->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_mulai">
<input type="text" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_mulai" name="x_jdw_kerja_m_mulai" id="x_jdw_kerja_m_mulai" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_mulai->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->EditValue ?>"<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_type->Visible) { // jdw_kerja_m_type ?>
	<div id="r_jdw_kerja_m_type" class="form-group">
		<label id="elh_jdw_kerja_m_jdw_kerja_m_type" for="x_jdw_kerja_m_type" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->jdw_kerja_m_type->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->jdw_kerja_m_type->CellAttributes() ?>>
<span id="el_jdw_kerja_m_jdw_kerja_m_type">
<input type="text" data-table="jdw_kerja_m" data-field="x_jdw_kerja_m_type" name="x_jdw_kerja_m_type" id="x_jdw_kerja_m_type" size="30" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->jdw_kerja_m_type->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->jdw_kerja_m_type->EditValue ?>"<?php echo $jdw_kerja_m->jdw_kerja_m_type->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->jdw_kerja_m_type->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jdw_kerja_m->use_sama->Visible) { // use_sama ?>
	<div id="r_use_sama" class="form-group">
		<label id="elh_jdw_kerja_m_use_sama" for="x_use_sama" class="col-sm-2 control-label ewLabel"><?php echo $jdw_kerja_m->use_sama->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jdw_kerja_m->use_sama->CellAttributes() ?>>
<span id="el_jdw_kerja_m_use_sama">
<input type="text" data-table="jdw_kerja_m" data-field="x_use_sama" name="x_use_sama" id="x_use_sama" size="30" placeholder="<?php echo ew_HtmlEncode($jdw_kerja_m->use_sama->getPlaceHolder()) ?>" value="<?php echo $jdw_kerja_m->use_sama->EditValue ?>"<?php echo $jdw_kerja_m->use_sama->EditAttributes() ?>>
</span>
<?php echo $jdw_kerja_m->use_sama->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$jdw_kerja_m_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jdw_kerja_m_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fjdw_kerja_medit.Init();
</script>
<?php
$jdw_kerja_m_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jdw_kerja_m_edit->Page_Terminate();
?>
