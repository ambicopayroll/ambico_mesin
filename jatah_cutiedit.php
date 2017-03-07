<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "jatah_cutiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$jatah_cuti_edit = NULL; // Initialize page object first

class cjatah_cuti_edit extends cjatah_cuti {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'jatah_cuti';

	// Page object name
	var $PageObjName = 'jatah_cuti_edit';

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

		// Table object (jatah_cuti)
		if (!isset($GLOBALS["jatah_cuti"]) || get_class($GLOBALS["jatah_cuti"]) == "cjatah_cuti") {
			$GLOBALS["jatah_cuti"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jatah_cuti"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jatah_cuti', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("jatah_cutilist.php"));
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
		$this->pegawai_id->SetVisibility();
		$this->jatah_c_mulai->SetVisibility();
		$this->jatah_c_akhir->SetVisibility();
		$this->jatah_c_jml->SetVisibility();
		$this->jatah_c_hak_jml->SetVisibility();
		$this->jatah_c_ambil_jml->SetVisibility();
		$this->jatah_c_utang_jml->SetVisibility();

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
		global $EW_EXPORT, $jatah_cuti;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jatah_cuti);
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
		if (@$_GET["pegawai_id"] <> "") {
			$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
		}
		if (@$_GET["jatah_c_mulai"] <> "") {
			$this->jatah_c_mulai->setQueryStringValue($_GET["jatah_c_mulai"]);
		}
		if (@$_GET["jatah_c_akhir"] <> "") {
			$this->jatah_c_akhir->setQueryStringValue($_GET["jatah_c_akhir"]);
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->pegawai_id->CurrentValue == "") {
			$this->Page_Terminate("jatah_cutilist.php"); // Invalid key, return to list
		}
		if ($this->jatah_c_mulai->CurrentValue == "") {
			$this->Page_Terminate("jatah_cutilist.php"); // Invalid key, return to list
		}
		if ($this->jatah_c_akhir->CurrentValue == "") {
			$this->Page_Terminate("jatah_cutilist.php"); // Invalid key, return to list
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
					$this->Page_Terminate("jatah_cutilist.php"); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "jatah_cutilist.php")
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
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->jatah_c_mulai->FldIsDetailKey) {
			$this->jatah_c_mulai->setFormValue($objForm->GetValue("x_jatah_c_mulai"));
			$this->jatah_c_mulai->CurrentValue = ew_UnFormatDateTime($this->jatah_c_mulai->CurrentValue, 0);
		}
		if (!$this->jatah_c_akhir->FldIsDetailKey) {
			$this->jatah_c_akhir->setFormValue($objForm->GetValue("x_jatah_c_akhir"));
			$this->jatah_c_akhir->CurrentValue = ew_UnFormatDateTime($this->jatah_c_akhir->CurrentValue, 0);
		}
		if (!$this->jatah_c_jml->FldIsDetailKey) {
			$this->jatah_c_jml->setFormValue($objForm->GetValue("x_jatah_c_jml"));
		}
		if (!$this->jatah_c_hak_jml->FldIsDetailKey) {
			$this->jatah_c_hak_jml->setFormValue($objForm->GetValue("x_jatah_c_hak_jml"));
		}
		if (!$this->jatah_c_ambil_jml->FldIsDetailKey) {
			$this->jatah_c_ambil_jml->setFormValue($objForm->GetValue("x_jatah_c_ambil_jml"));
		}
		if (!$this->jatah_c_utang_jml->FldIsDetailKey) {
			$this->jatah_c_utang_jml->setFormValue($objForm->GetValue("x_jatah_c_utang_jml"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->jatah_c_mulai->CurrentValue = $this->jatah_c_mulai->FormValue;
		$this->jatah_c_mulai->CurrentValue = ew_UnFormatDateTime($this->jatah_c_mulai->CurrentValue, 0);
		$this->jatah_c_akhir->CurrentValue = $this->jatah_c_akhir->FormValue;
		$this->jatah_c_akhir->CurrentValue = ew_UnFormatDateTime($this->jatah_c_akhir->CurrentValue, 0);
		$this->jatah_c_jml->CurrentValue = $this->jatah_c_jml->FormValue;
		$this->jatah_c_hak_jml->CurrentValue = $this->jatah_c_hak_jml->FormValue;
		$this->jatah_c_ambil_jml->CurrentValue = $this->jatah_c_ambil_jml->FormValue;
		$this->jatah_c_utang_jml->CurrentValue = $this->jatah_c_utang_jml->FormValue;
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
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->jatah_c_mulai->setDbValue($rs->fields('jatah_c_mulai'));
		$this->jatah_c_akhir->setDbValue($rs->fields('jatah_c_akhir'));
		$this->jatah_c_jml->setDbValue($rs->fields('jatah_c_jml'));
		$this->jatah_c_hak_jml->setDbValue($rs->fields('jatah_c_hak_jml'));
		$this->jatah_c_ambil_jml->setDbValue($rs->fields('jatah_c_ambil_jml'));
		$this->jatah_c_utang_jml->setDbValue($rs->fields('jatah_c_utang_jml'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->jatah_c_mulai->DbValue = $row['jatah_c_mulai'];
		$this->jatah_c_akhir->DbValue = $row['jatah_c_akhir'];
		$this->jatah_c_jml->DbValue = $row['jatah_c_jml'];
		$this->jatah_c_hak_jml->DbValue = $row['jatah_c_hak_jml'];
		$this->jatah_c_ambil_jml->DbValue = $row['jatah_c_ambil_jml'];
		$this->jatah_c_utang_jml->DbValue = $row['jatah_c_utang_jml'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// jatah_c_mulai
		// jatah_c_akhir
		// jatah_c_jml
		// jatah_c_hak_jml
		// jatah_c_ambil_jml
		// jatah_c_utang_jml

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// jatah_c_mulai
		$this->jatah_c_mulai->ViewValue = $this->jatah_c_mulai->CurrentValue;
		$this->jatah_c_mulai->ViewValue = ew_FormatDateTime($this->jatah_c_mulai->ViewValue, 0);
		$this->jatah_c_mulai->ViewCustomAttributes = "";

		// jatah_c_akhir
		$this->jatah_c_akhir->ViewValue = $this->jatah_c_akhir->CurrentValue;
		$this->jatah_c_akhir->ViewValue = ew_FormatDateTime($this->jatah_c_akhir->ViewValue, 0);
		$this->jatah_c_akhir->ViewCustomAttributes = "";

		// jatah_c_jml
		$this->jatah_c_jml->ViewValue = $this->jatah_c_jml->CurrentValue;
		$this->jatah_c_jml->ViewCustomAttributes = "";

		// jatah_c_hak_jml
		$this->jatah_c_hak_jml->ViewValue = $this->jatah_c_hak_jml->CurrentValue;
		$this->jatah_c_hak_jml->ViewCustomAttributes = "";

		// jatah_c_ambil_jml
		$this->jatah_c_ambil_jml->ViewValue = $this->jatah_c_ambil_jml->CurrentValue;
		$this->jatah_c_ambil_jml->ViewCustomAttributes = "";

		// jatah_c_utang_jml
		$this->jatah_c_utang_jml->ViewValue = $this->jatah_c_utang_jml->CurrentValue;
		$this->jatah_c_utang_jml->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// jatah_c_mulai
			$this->jatah_c_mulai->LinkCustomAttributes = "";
			$this->jatah_c_mulai->HrefValue = "";
			$this->jatah_c_mulai->TooltipValue = "";

			// jatah_c_akhir
			$this->jatah_c_akhir->LinkCustomAttributes = "";
			$this->jatah_c_akhir->HrefValue = "";
			$this->jatah_c_akhir->TooltipValue = "";

			// jatah_c_jml
			$this->jatah_c_jml->LinkCustomAttributes = "";
			$this->jatah_c_jml->HrefValue = "";
			$this->jatah_c_jml->TooltipValue = "";

			// jatah_c_hak_jml
			$this->jatah_c_hak_jml->LinkCustomAttributes = "";
			$this->jatah_c_hak_jml->HrefValue = "";
			$this->jatah_c_hak_jml->TooltipValue = "";

			// jatah_c_ambil_jml
			$this->jatah_c_ambil_jml->LinkCustomAttributes = "";
			$this->jatah_c_ambil_jml->HrefValue = "";
			$this->jatah_c_ambil_jml->TooltipValue = "";

			// jatah_c_utang_jml
			$this->jatah_c_utang_jml->LinkCustomAttributes = "";
			$this->jatah_c_utang_jml->HrefValue = "";
			$this->jatah_c_utang_jml->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
			$this->pegawai_id->ViewCustomAttributes = "";

			// jatah_c_mulai
			$this->jatah_c_mulai->EditAttrs["class"] = "form-control";
			$this->jatah_c_mulai->EditCustomAttributes = "";
			$this->jatah_c_mulai->EditValue = $this->jatah_c_mulai->CurrentValue;
			$this->jatah_c_mulai->EditValue = ew_FormatDateTime($this->jatah_c_mulai->EditValue, 0);
			$this->jatah_c_mulai->ViewCustomAttributes = "";

			// jatah_c_akhir
			$this->jatah_c_akhir->EditAttrs["class"] = "form-control";
			$this->jatah_c_akhir->EditCustomAttributes = "";
			$this->jatah_c_akhir->EditValue = $this->jatah_c_akhir->CurrentValue;
			$this->jatah_c_akhir->EditValue = ew_FormatDateTime($this->jatah_c_akhir->EditValue, 0);
			$this->jatah_c_akhir->ViewCustomAttributes = "";

			// jatah_c_jml
			$this->jatah_c_jml->EditAttrs["class"] = "form-control";
			$this->jatah_c_jml->EditCustomAttributes = "";
			$this->jatah_c_jml->EditValue = ew_HtmlEncode($this->jatah_c_jml->CurrentValue);
			$this->jatah_c_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_jml->FldCaption());

			// jatah_c_hak_jml
			$this->jatah_c_hak_jml->EditAttrs["class"] = "form-control";
			$this->jatah_c_hak_jml->EditCustomAttributes = "";
			$this->jatah_c_hak_jml->EditValue = ew_HtmlEncode($this->jatah_c_hak_jml->CurrentValue);
			$this->jatah_c_hak_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_hak_jml->FldCaption());

			// jatah_c_ambil_jml
			$this->jatah_c_ambil_jml->EditAttrs["class"] = "form-control";
			$this->jatah_c_ambil_jml->EditCustomAttributes = "";
			$this->jatah_c_ambil_jml->EditValue = ew_HtmlEncode($this->jatah_c_ambil_jml->CurrentValue);
			$this->jatah_c_ambil_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_ambil_jml->FldCaption());

			// jatah_c_utang_jml
			$this->jatah_c_utang_jml->EditAttrs["class"] = "form-control";
			$this->jatah_c_utang_jml->EditCustomAttributes = "";
			$this->jatah_c_utang_jml->EditValue = ew_HtmlEncode($this->jatah_c_utang_jml->CurrentValue);
			$this->jatah_c_utang_jml->PlaceHolder = ew_RemoveHtml($this->jatah_c_utang_jml->FldCaption());

			// Edit refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// jatah_c_mulai
			$this->jatah_c_mulai->LinkCustomAttributes = "";
			$this->jatah_c_mulai->HrefValue = "";

			// jatah_c_akhir
			$this->jatah_c_akhir->LinkCustomAttributes = "";
			$this->jatah_c_akhir->HrefValue = "";

			// jatah_c_jml
			$this->jatah_c_jml->LinkCustomAttributes = "";
			$this->jatah_c_jml->HrefValue = "";

			// jatah_c_hak_jml
			$this->jatah_c_hak_jml->LinkCustomAttributes = "";
			$this->jatah_c_hak_jml->HrefValue = "";

			// jatah_c_ambil_jml
			$this->jatah_c_ambil_jml->LinkCustomAttributes = "";
			$this->jatah_c_ambil_jml->HrefValue = "";

			// jatah_c_utang_jml
			$this->jatah_c_utang_jml->LinkCustomAttributes = "";
			$this->jatah_c_utang_jml->HrefValue = "";
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
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->jatah_c_mulai->FldIsDetailKey && !is_null($this->jatah_c_mulai->FormValue) && $this->jatah_c_mulai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jatah_c_mulai->FldCaption(), $this->jatah_c_mulai->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->jatah_c_mulai->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatah_c_mulai->FldErrMsg());
		}
		if (!$this->jatah_c_akhir->FldIsDetailKey && !is_null($this->jatah_c_akhir->FormValue) && $this->jatah_c_akhir->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jatah_c_akhir->FldCaption(), $this->jatah_c_akhir->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->jatah_c_akhir->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatah_c_akhir->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jatah_c_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatah_c_jml->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jatah_c_hak_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatah_c_hak_jml->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jatah_c_ambil_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatah_c_ambil_jml->FldErrMsg());
		}
		if (!ew_CheckInteger($this->jatah_c_utang_jml->FormValue)) {
			ew_AddMessage($gsFormError, $this->jatah_c_utang_jml->FldErrMsg());
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

			// pegawai_id
			// jatah_c_mulai
			// jatah_c_akhir
			// jatah_c_jml

			$this->jatah_c_jml->SetDbValueDef($rsnew, $this->jatah_c_jml->CurrentValue, NULL, $this->jatah_c_jml->ReadOnly);

			// jatah_c_hak_jml
			$this->jatah_c_hak_jml->SetDbValueDef($rsnew, $this->jatah_c_hak_jml->CurrentValue, NULL, $this->jatah_c_hak_jml->ReadOnly);

			// jatah_c_ambil_jml
			$this->jatah_c_ambil_jml->SetDbValueDef($rsnew, $this->jatah_c_ambil_jml->CurrentValue, NULL, $this->jatah_c_ambil_jml->ReadOnly);

			// jatah_c_utang_jml
			$this->jatah_c_utang_jml->SetDbValueDef($rsnew, $this->jatah_c_utang_jml->CurrentValue, NULL, $this->jatah_c_utang_jml->ReadOnly);

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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jatah_cutilist.php"), "", $this->TableVar, TRUE);
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
if (!isset($jatah_cuti_edit)) $jatah_cuti_edit = new cjatah_cuti_edit();

// Page init
$jatah_cuti_edit->Page_Init();

// Page main
$jatah_cuti_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jatah_cuti_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fjatah_cutiedit = new ew_Form("fjatah_cutiedit", "edit");

// Validate form
fjatah_cutiedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jatah_cuti->pegawai_id->FldCaption(), $jatah_cuti->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_mulai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jatah_cuti->jatah_c_mulai->FldCaption(), $jatah_cuti->jatah_c_mulai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_mulai");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->jatah_c_mulai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_akhir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $jatah_cuti->jatah_c_akhir->FldCaption(), $jatah_cuti->jatah_c_akhir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_akhir");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->jatah_c_akhir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_jml");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->jatah_c_jml->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_hak_jml");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->jatah_c_hak_jml->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_ambil_jml");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->jatah_c_ambil_jml->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jatah_c_utang_jml");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($jatah_cuti->jatah_c_utang_jml->FldErrMsg()) ?>");

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
fjatah_cutiedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjatah_cutiedit.ValidateRequired = true;
<?php } else { ?>
fjatah_cutiedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$jatah_cuti_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $jatah_cuti_edit->ShowPageHeader(); ?>
<?php
$jatah_cuti_edit->ShowMessage();
?>
<form name="fjatah_cutiedit" id="fjatah_cutiedit" class="<?php echo $jatah_cuti_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jatah_cuti_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jatah_cuti_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jatah_cuti">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($jatah_cuti_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($jatah_cuti->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_jatah_cuti_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->pegawai_id->CellAttributes() ?>>
<span id="el_jatah_cuti_pegawai_id">
<span<?php echo $jatah_cuti->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jatah_cuti->pegawai_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jatah_cuti" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo ew_HtmlEncode($jatah_cuti->pegawai_id->CurrentValue) ?>">
<?php echo $jatah_cuti->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_mulai->Visible) { // jatah_c_mulai ?>
	<div id="r_jatah_c_mulai" class="form-group">
		<label id="elh_jatah_cuti_jatah_c_mulai" for="x_jatah_c_mulai" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->jatah_c_mulai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->jatah_c_mulai->CellAttributes() ?>>
<span id="el_jatah_cuti_jatah_c_mulai">
<span<?php echo $jatah_cuti->jatah_c_mulai->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jatah_cuti->jatah_c_mulai->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jatah_cuti" data-field="x_jatah_c_mulai" name="x_jatah_c_mulai" id="x_jatah_c_mulai" value="<?php echo ew_HtmlEncode($jatah_cuti->jatah_c_mulai->CurrentValue) ?>">
<?php echo $jatah_cuti->jatah_c_mulai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_akhir->Visible) { // jatah_c_akhir ?>
	<div id="r_jatah_c_akhir" class="form-group">
		<label id="elh_jatah_cuti_jatah_c_akhir" for="x_jatah_c_akhir" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->jatah_c_akhir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->jatah_c_akhir->CellAttributes() ?>>
<span id="el_jatah_cuti_jatah_c_akhir">
<span<?php echo $jatah_cuti->jatah_c_akhir->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $jatah_cuti->jatah_c_akhir->EditValue ?></p></span>
</span>
<input type="hidden" data-table="jatah_cuti" data-field="x_jatah_c_akhir" name="x_jatah_c_akhir" id="x_jatah_c_akhir" value="<?php echo ew_HtmlEncode($jatah_cuti->jatah_c_akhir->CurrentValue) ?>">
<?php echo $jatah_cuti->jatah_c_akhir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_jml->Visible) { // jatah_c_jml ?>
	<div id="r_jatah_c_jml" class="form-group">
		<label id="elh_jatah_cuti_jatah_c_jml" for="x_jatah_c_jml" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->jatah_c_jml->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->jatah_c_jml->CellAttributes() ?>>
<span id="el_jatah_cuti_jatah_c_jml">
<input type="text" data-table="jatah_cuti" data-field="x_jatah_c_jml" name="x_jatah_c_jml" id="x_jatah_c_jml" size="30" placeholder="<?php echo ew_HtmlEncode($jatah_cuti->jatah_c_jml->getPlaceHolder()) ?>" value="<?php echo $jatah_cuti->jatah_c_jml->EditValue ?>"<?php echo $jatah_cuti->jatah_c_jml->EditAttributes() ?>>
</span>
<?php echo $jatah_cuti->jatah_c_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_hak_jml->Visible) { // jatah_c_hak_jml ?>
	<div id="r_jatah_c_hak_jml" class="form-group">
		<label id="elh_jatah_cuti_jatah_c_hak_jml" for="x_jatah_c_hak_jml" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->jatah_c_hak_jml->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->jatah_c_hak_jml->CellAttributes() ?>>
<span id="el_jatah_cuti_jatah_c_hak_jml">
<input type="text" data-table="jatah_cuti" data-field="x_jatah_c_hak_jml" name="x_jatah_c_hak_jml" id="x_jatah_c_hak_jml" size="30" placeholder="<?php echo ew_HtmlEncode($jatah_cuti->jatah_c_hak_jml->getPlaceHolder()) ?>" value="<?php echo $jatah_cuti->jatah_c_hak_jml->EditValue ?>"<?php echo $jatah_cuti->jatah_c_hak_jml->EditAttributes() ?>>
</span>
<?php echo $jatah_cuti->jatah_c_hak_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_ambil_jml->Visible) { // jatah_c_ambil_jml ?>
	<div id="r_jatah_c_ambil_jml" class="form-group">
		<label id="elh_jatah_cuti_jatah_c_ambil_jml" for="x_jatah_c_ambil_jml" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->jatah_c_ambil_jml->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->jatah_c_ambil_jml->CellAttributes() ?>>
<span id="el_jatah_cuti_jatah_c_ambil_jml">
<input type="text" data-table="jatah_cuti" data-field="x_jatah_c_ambil_jml" name="x_jatah_c_ambil_jml" id="x_jatah_c_ambil_jml" size="30" placeholder="<?php echo ew_HtmlEncode($jatah_cuti->jatah_c_ambil_jml->getPlaceHolder()) ?>" value="<?php echo $jatah_cuti->jatah_c_ambil_jml->EditValue ?>"<?php echo $jatah_cuti->jatah_c_ambil_jml->EditAttributes() ?>>
</span>
<?php echo $jatah_cuti->jatah_c_ambil_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_utang_jml->Visible) { // jatah_c_utang_jml ?>
	<div id="r_jatah_c_utang_jml" class="form-group">
		<label id="elh_jatah_cuti_jatah_c_utang_jml" for="x_jatah_c_utang_jml" class="col-sm-2 control-label ewLabel"><?php echo $jatah_cuti->jatah_c_utang_jml->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $jatah_cuti->jatah_c_utang_jml->CellAttributes() ?>>
<span id="el_jatah_cuti_jatah_c_utang_jml">
<input type="text" data-table="jatah_cuti" data-field="x_jatah_c_utang_jml" name="x_jatah_c_utang_jml" id="x_jatah_c_utang_jml" size="30" placeholder="<?php echo ew_HtmlEncode($jatah_cuti->jatah_c_utang_jml->getPlaceHolder()) ?>" value="<?php echo $jatah_cuti->jatah_c_utang_jml->EditValue ?>"<?php echo $jatah_cuti->jatah_c_utang_jml->EditAttributes() ?>>
</span>
<?php echo $jatah_cuti->jatah_c_utang_jml->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$jatah_cuti_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jatah_cuti_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fjatah_cutiedit.Init();
</script>
<?php
$jatah_cuti_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jatah_cuti_edit->Page_Terminate();
?>
