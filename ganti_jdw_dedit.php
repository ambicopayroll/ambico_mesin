<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "ganti_jdw_dinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$ganti_jdw_d_edit = NULL; // Initialize page object first

class cganti_jdw_d_edit extends cganti_jdw_d {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'ganti_jdw_d';

	// Page object name
	var $PageObjName = 'ganti_jdw_d_edit';

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

		// Table object (ganti_jdw_d)
		if (!isset($GLOBALS["ganti_jdw_d"]) || get_class($GLOBALS["ganti_jdw_d"]) == "cganti_jdw_d") {
			$GLOBALS["ganti_jdw_d"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ganti_jdw_d"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ganti_jdw_d', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("ganti_jdw_dlist.php"));
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
		$this->ganti_jdw_id->SetVisibility();
		$this->tgl_ganti_jdw->SetVisibility();
		$this->jns_ganti_jdw->SetVisibility();
		$this->jdw_kerja_m_id->SetVisibility();
		$this->pegawai_id->SetVisibility();

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
		global $EW_EXPORT, $ganti_jdw_d;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($ganti_jdw_d);
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
		if (@$_GET["ganti_jdw_id"] <> "") {
			$this->ganti_jdw_id->setQueryStringValue($_GET["ganti_jdw_id"]);
		}
		if (@$_GET["tgl_ganti_jdw"] <> "") {
			$this->tgl_ganti_jdw->setQueryStringValue($_GET["tgl_ganti_jdw"]);
		}
		if (@$_GET["jns_ganti_jdw"] <> "") {
			$this->jns_ganti_jdw->setQueryStringValue($_GET["jns_ganti_jdw"]);
		}
		if (@$_GET["jdw_kerja_m_id"] <> "") {
			$this->jdw_kerja_m_id->setQueryStringValue($_GET["jdw_kerja_m_id"]);
		}
		if (@$_GET["pegawai_id"] <> "") {
			$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->ganti_jdw_id->CurrentValue == "") {
			$this->Page_Terminate("ganti_jdw_dlist.php"); // Invalid key, return to list
		}
		if ($this->tgl_ganti_jdw->CurrentValue == "") {
			$this->Page_Terminate("ganti_jdw_dlist.php"); // Invalid key, return to list
		}
		if ($this->jns_ganti_jdw->CurrentValue == "") {
			$this->Page_Terminate("ganti_jdw_dlist.php"); // Invalid key, return to list
		}
		if ($this->jdw_kerja_m_id->CurrentValue == "") {
			$this->Page_Terminate("ganti_jdw_dlist.php"); // Invalid key, return to list
		}
		if ($this->pegawai_id->CurrentValue == "") {
			$this->Page_Terminate("ganti_jdw_dlist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("ganti_jdw_dlist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "ganti_jdw_dlist.php")
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
		if (!$this->ganti_jdw_id->FldIsDetailKey) {
			$this->ganti_jdw_id->setFormValue($objForm->GetValue("x_ganti_jdw_id"));
		}
		if (!$this->tgl_ganti_jdw->FldIsDetailKey) {
			$this->tgl_ganti_jdw->setFormValue($objForm->GetValue("x_tgl_ganti_jdw"));
			$this->tgl_ganti_jdw->CurrentValue = ew_UnFormatDateTime($this->tgl_ganti_jdw->CurrentValue, 0);
		}
		if (!$this->jns_ganti_jdw->FldIsDetailKey) {
			$this->jns_ganti_jdw->setFormValue($objForm->GetValue("x_jns_ganti_jdw"));
		}
		if (!$this->jdw_kerja_m_id->FldIsDetailKey) {
			$this->jdw_kerja_m_id->setFormValue($objForm->GetValue("x_jdw_kerja_m_id"));
		}
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->ganti_jdw_id->CurrentValue = $this->ganti_jdw_id->FormValue;
		$this->tgl_ganti_jdw->CurrentValue = $this->tgl_ganti_jdw->FormValue;
		$this->tgl_ganti_jdw->CurrentValue = ew_UnFormatDateTime($this->tgl_ganti_jdw->CurrentValue, 0);
		$this->jns_ganti_jdw->CurrentValue = $this->jns_ganti_jdw->FormValue;
		$this->jdw_kerja_m_id->CurrentValue = $this->jdw_kerja_m_id->FormValue;
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
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
		$this->ganti_jdw_id->setDbValue($rs->fields('ganti_jdw_id'));
		$this->tgl_ganti_jdw->setDbValue($rs->fields('tgl_ganti_jdw'));
		$this->jns_ganti_jdw->setDbValue($rs->fields('jns_ganti_jdw'));
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ganti_jdw_id->DbValue = $row['ganti_jdw_id'];
		$this->tgl_ganti_jdw->DbValue = $row['tgl_ganti_jdw'];
		$this->jns_ganti_jdw->DbValue = $row['jns_ganti_jdw'];
		$this->jdw_kerja_m_id->DbValue = $row['jdw_kerja_m_id'];
		$this->pegawai_id->DbValue = $row['pegawai_id'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ganti_jdw_id
		// tgl_ganti_jdw
		// jns_ganti_jdw
		// jdw_kerja_m_id
		// pegawai_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ganti_jdw_id
		$this->ganti_jdw_id->ViewValue = $this->ganti_jdw_id->CurrentValue;
		$this->ganti_jdw_id->ViewCustomAttributes = "";

		// tgl_ganti_jdw
		$this->tgl_ganti_jdw->ViewValue = $this->tgl_ganti_jdw->CurrentValue;
		$this->tgl_ganti_jdw->ViewValue = ew_FormatDateTime($this->tgl_ganti_jdw->ViewValue, 0);
		$this->tgl_ganti_jdw->ViewCustomAttributes = "";

		// jns_ganti_jdw
		$this->jns_ganti_jdw->ViewValue = $this->jns_ganti_jdw->CurrentValue;
		$this->jns_ganti_jdw->ViewCustomAttributes = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

			// ganti_jdw_id
			$this->ganti_jdw_id->LinkCustomAttributes = "";
			$this->ganti_jdw_id->HrefValue = "";
			$this->ganti_jdw_id->TooltipValue = "";

			// tgl_ganti_jdw
			$this->tgl_ganti_jdw->LinkCustomAttributes = "";
			$this->tgl_ganti_jdw->HrefValue = "";
			$this->tgl_ganti_jdw->TooltipValue = "";

			// jns_ganti_jdw
			$this->jns_ganti_jdw->LinkCustomAttributes = "";
			$this->jns_ganti_jdw->HrefValue = "";
			$this->jns_ganti_jdw->TooltipValue = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";
			$this->jdw_kerja_m_id->TooltipValue = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// ganti_jdw_id
			$this->ganti_jdw_id->EditAttrs["class"] = "form-control";
			$this->ganti_jdw_id->EditCustomAttributes = "";
			$this->ganti_jdw_id->EditValue = $this->ganti_jdw_id->CurrentValue;
			$this->ganti_jdw_id->ViewCustomAttributes = "";

			// tgl_ganti_jdw
			$this->tgl_ganti_jdw->EditAttrs["class"] = "form-control";
			$this->tgl_ganti_jdw->EditCustomAttributes = "";
			$this->tgl_ganti_jdw->EditValue = $this->tgl_ganti_jdw->CurrentValue;
			$this->tgl_ganti_jdw->EditValue = ew_FormatDateTime($this->tgl_ganti_jdw->EditValue, 0);
			$this->tgl_ganti_jdw->ViewCustomAttributes = "";

			// jns_ganti_jdw
			$this->jns_ganti_jdw->EditAttrs["class"] = "form-control";
			$this->jns_ganti_jdw->EditCustomAttributes = "";
			$this->jns_ganti_jdw->EditValue = $this->jns_ganti_jdw->CurrentValue;
			$this->jns_ganti_jdw->ViewCustomAttributes = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_id->EditCustomAttributes = "";
			$this->jdw_kerja_m_id->EditValue = $this->jdw_kerja_m_id->CurrentValue;
			$this->jdw_kerja_m_id->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
			$this->pegawai_id->ViewCustomAttributes = "";

			// Edit refer script
			// ganti_jdw_id

			$this->ganti_jdw_id->LinkCustomAttributes = "";
			$this->ganti_jdw_id->HrefValue = "";

			// tgl_ganti_jdw
			$this->tgl_ganti_jdw->LinkCustomAttributes = "";
			$this->tgl_ganti_jdw->HrefValue = "";

			// jns_ganti_jdw
			$this->jns_ganti_jdw->LinkCustomAttributes = "";
			$this->jns_ganti_jdw->HrefValue = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
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
		if (!$this->ganti_jdw_id->FldIsDetailKey && !is_null($this->ganti_jdw_id->FormValue) && $this->ganti_jdw_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ganti_jdw_id->FldCaption(), $this->ganti_jdw_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->ganti_jdw_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->ganti_jdw_id->FldErrMsg());
		}
		if (!$this->tgl_ganti_jdw->FldIsDetailKey && !is_null($this->tgl_ganti_jdw->FormValue) && $this->tgl_ganti_jdw->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_ganti_jdw->FldCaption(), $this->tgl_ganti_jdw->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_ganti_jdw->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_ganti_jdw->FldErrMsg());
		}
		if (!$this->jns_ganti_jdw->FldIsDetailKey && !is_null($this->jns_ganti_jdw->FormValue) && $this->jns_ganti_jdw->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jns_ganti_jdw->FldCaption(), $this->jns_ganti_jdw->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jns_ganti_jdw->FormValue)) {
			ew_AddMessage($gsFormError, $this->jns_ganti_jdw->FldErrMsg());
		}
		if (!$this->jdw_kerja_m_id->FldIsDetailKey && !is_null($this->jdw_kerja_m_id->FormValue) && $this->jdw_kerja_m_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jdw_kerja_m_id->FldCaption(), $this->jdw_kerja_m_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_id->FldErrMsg());
		}
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
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

			// ganti_jdw_id
			// tgl_ganti_jdw
			// jns_ganti_jdw
			// jdw_kerja_m_id
			// pegawai_id
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("ganti_jdw_dlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($ganti_jdw_d_edit)) $ganti_jdw_d_edit = new cganti_jdw_d_edit();

// Page init
$ganti_jdw_d_edit->Page_Init();

// Page main
$ganti_jdw_d_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ganti_jdw_d_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fganti_jdw_dedit = new ew_Form("fganti_jdw_dedit", "edit");

// Validate form
fganti_jdw_dedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_ganti_jdw_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_d->ganti_jdw_id->FldCaption(), $ganti_jdw_d->ganti_jdw_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ganti_jdw_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_d->ganti_jdw_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_ganti_jdw");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_d->tgl_ganti_jdw->FldCaption(), $ganti_jdw_d->tgl_ganti_jdw->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_ganti_jdw");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_d->tgl_ganti_jdw->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jns_ganti_jdw");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_d->jns_ganti_jdw->FldCaption(), $ganti_jdw_d->jns_ganti_jdw->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jns_ganti_jdw");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_d->jns_ganti_jdw->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_d->jdw_kerja_m_id->FldCaption(), $ganti_jdw_d->jdw_kerja_m_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_d->jdw_kerja_m_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_d->pegawai_id->FldCaption(), $ganti_jdw_d->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_d->pegawai_id->FldErrMsg()) ?>");

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
fganti_jdw_dedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fganti_jdw_dedit.ValidateRequired = true;
<?php } else { ?>
fganti_jdw_dedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$ganti_jdw_d_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $ganti_jdw_d_edit->ShowPageHeader(); ?>
<?php
$ganti_jdw_d_edit->ShowMessage();
?>
<form name="fganti_jdw_dedit" id="fganti_jdw_dedit" class="<?php echo $ganti_jdw_d_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($ganti_jdw_d_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $ganti_jdw_d_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="ganti_jdw_d">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($ganti_jdw_d_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($ganti_jdw_d->ganti_jdw_id->Visible) { // ganti_jdw_id ?>
	<div id="r_ganti_jdw_id" class="form-group">
		<label id="elh_ganti_jdw_d_ganti_jdw_id" for="x_ganti_jdw_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_d->ganti_jdw_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_d->ganti_jdw_id->CellAttributes() ?>>
<span id="el_ganti_jdw_d_ganti_jdw_id">
<span<?php echo $ganti_jdw_d->ganti_jdw_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ganti_jdw_d->ganti_jdw_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="ganti_jdw_d" data-field="x_ganti_jdw_id" name="x_ganti_jdw_id" id="x_ganti_jdw_id" value="<?php echo ew_HtmlEncode($ganti_jdw_d->ganti_jdw_id->CurrentValue) ?>">
<?php echo $ganti_jdw_d->ganti_jdw_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_d->tgl_ganti_jdw->Visible) { // tgl_ganti_jdw ?>
	<div id="r_tgl_ganti_jdw" class="form-group">
		<label id="elh_ganti_jdw_d_tgl_ganti_jdw" for="x_tgl_ganti_jdw" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_d->tgl_ganti_jdw->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_d->tgl_ganti_jdw->CellAttributes() ?>>
<span id="el_ganti_jdw_d_tgl_ganti_jdw">
<span<?php echo $ganti_jdw_d->tgl_ganti_jdw->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ganti_jdw_d->tgl_ganti_jdw->EditValue ?></p></span>
</span>
<input type="hidden" data-table="ganti_jdw_d" data-field="x_tgl_ganti_jdw" name="x_tgl_ganti_jdw" id="x_tgl_ganti_jdw" value="<?php echo ew_HtmlEncode($ganti_jdw_d->tgl_ganti_jdw->CurrentValue) ?>">
<?php echo $ganti_jdw_d->tgl_ganti_jdw->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_d->jns_ganti_jdw->Visible) { // jns_ganti_jdw ?>
	<div id="r_jns_ganti_jdw" class="form-group">
		<label id="elh_ganti_jdw_d_jns_ganti_jdw" for="x_jns_ganti_jdw" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_d->jns_ganti_jdw->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_d->jns_ganti_jdw->CellAttributes() ?>>
<span id="el_ganti_jdw_d_jns_ganti_jdw">
<span<?php echo $ganti_jdw_d->jns_ganti_jdw->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ganti_jdw_d->jns_ganti_jdw->EditValue ?></p></span>
</span>
<input type="hidden" data-table="ganti_jdw_d" data-field="x_jns_ganti_jdw" name="x_jns_ganti_jdw" id="x_jns_ganti_jdw" value="<?php echo ew_HtmlEncode($ganti_jdw_d->jns_ganti_jdw->CurrentValue) ?>">
<?php echo $ganti_jdw_d->jns_ganti_jdw->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_d->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
	<div id="r_jdw_kerja_m_id" class="form-group">
		<label id="elh_ganti_jdw_d_jdw_kerja_m_id" for="x_jdw_kerja_m_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_d->jdw_kerja_m_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_d->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el_ganti_jdw_d_jdw_kerja_m_id">
<span<?php echo $ganti_jdw_d->jdw_kerja_m_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ganti_jdw_d->jdw_kerja_m_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="ganti_jdw_d" data-field="x_jdw_kerja_m_id" name="x_jdw_kerja_m_id" id="x_jdw_kerja_m_id" value="<?php echo ew_HtmlEncode($ganti_jdw_d->jdw_kerja_m_id->CurrentValue) ?>">
<?php echo $ganti_jdw_d->jdw_kerja_m_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_d->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_ganti_jdw_d_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_d->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_d->pegawai_id->CellAttributes() ?>>
<span id="el_ganti_jdw_d_pegawai_id">
<span<?php echo $ganti_jdw_d->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $ganti_jdw_d->pegawai_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="ganti_jdw_d" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo ew_HtmlEncode($ganti_jdw_d->pegawai_id->CurrentValue) ?>">
<?php echo $ganti_jdw_d->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$ganti_jdw_d_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $ganti_jdw_d_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fganti_jdw_dedit.Init();
</script>
<?php
$ganti_jdw_d_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ganti_jdw_d_edit->Page_Terminate();
?>
