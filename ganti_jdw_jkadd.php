<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "ganti_jdw_jkinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$ganti_jdw_jk_add = NULL; // Initialize page object first

class cganti_jdw_jk_add extends cganti_jdw_jk {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'ganti_jdw_jk';

	// Page object name
	var $PageObjName = 'ganti_jdw_jk_add';

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

		// Table object (ganti_jdw_jk)
		if (!isset($GLOBALS["ganti_jdw_jk"]) || get_class($GLOBALS["ganti_jdw_jk"]) == "cganti_jdw_jk") {
			$GLOBALS["ganti_jdw_jk"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["ganti_jdw_jk"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'ganti_jdw_jk', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("ganti_jdw_jklist.php"));
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
		$this->jdw_kerja_m_id1->SetVisibility();
		$this->jdw_kerja_m_id2->SetVisibility();
		$this->tgl_awal->SetVisibility();
		$this->tgl_akhir->SetVisibility();
		$this->keterangan->SetVisibility();

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
		global $EW_EXPORT, $ganti_jdw_jk;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($ganti_jdw_jk);
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
			if (@$_GET["ganti_jdw_id"] != "") {
				$this->ganti_jdw_id->setQueryStringValue($_GET["ganti_jdw_id"]);
				$this->setKey("ganti_jdw_id", $this->ganti_jdw_id->CurrentValue); // Set up key
			} else {
				$this->setKey("ganti_jdw_id", ""); // Clear key
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
					$this->Page_Terminate("ganti_jdw_jklist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "ganti_jdw_jklist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "ganti_jdw_jkview.php")
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
		$this->ganti_jdw_id->CurrentValue = NULL;
		$this->ganti_jdw_id->OldValue = $this->ganti_jdw_id->CurrentValue;
		$this->jdw_kerja_m_id1->CurrentValue = NULL;
		$this->jdw_kerja_m_id1->OldValue = $this->jdw_kerja_m_id1->CurrentValue;
		$this->jdw_kerja_m_id2->CurrentValue = NULL;
		$this->jdw_kerja_m_id2->OldValue = $this->jdw_kerja_m_id2->CurrentValue;
		$this->tgl_awal->CurrentValue = NULL;
		$this->tgl_awal->OldValue = $this->tgl_awal->CurrentValue;
		$this->tgl_akhir->CurrentValue = NULL;
		$this->tgl_akhir->OldValue = $this->tgl_akhir->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->ganti_jdw_id->FldIsDetailKey) {
			$this->ganti_jdw_id->setFormValue($objForm->GetValue("x_ganti_jdw_id"));
		}
		if (!$this->jdw_kerja_m_id1->FldIsDetailKey) {
			$this->jdw_kerja_m_id1->setFormValue($objForm->GetValue("x_jdw_kerja_m_id1"));
		}
		if (!$this->jdw_kerja_m_id2->FldIsDetailKey) {
			$this->jdw_kerja_m_id2->setFormValue($objForm->GetValue("x_jdw_kerja_m_id2"));
		}
		if (!$this->tgl_awal->FldIsDetailKey) {
			$this->tgl_awal->setFormValue($objForm->GetValue("x_tgl_awal"));
			$this->tgl_awal->CurrentValue = ew_UnFormatDateTime($this->tgl_awal->CurrentValue, 0);
		}
		if (!$this->tgl_akhir->FldIsDetailKey) {
			$this->tgl_akhir->setFormValue($objForm->GetValue("x_tgl_akhir"));
			$this->tgl_akhir->CurrentValue = ew_UnFormatDateTime($this->tgl_akhir->CurrentValue, 0);
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->ganti_jdw_id->CurrentValue = $this->ganti_jdw_id->FormValue;
		$this->jdw_kerja_m_id1->CurrentValue = $this->jdw_kerja_m_id1->FormValue;
		$this->jdw_kerja_m_id2->CurrentValue = $this->jdw_kerja_m_id2->FormValue;
		$this->tgl_awal->CurrentValue = $this->tgl_awal->FormValue;
		$this->tgl_awal->CurrentValue = ew_UnFormatDateTime($this->tgl_awal->CurrentValue, 0);
		$this->tgl_akhir->CurrentValue = $this->tgl_akhir->FormValue;
		$this->tgl_akhir->CurrentValue = ew_UnFormatDateTime($this->tgl_akhir->CurrentValue, 0);
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
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
		$this->jdw_kerja_m_id1->setDbValue($rs->fields('jdw_kerja_m_id1'));
		$this->jdw_kerja_m_id2->setDbValue($rs->fields('jdw_kerja_m_id2'));
		$this->tgl_awal->setDbValue($rs->fields('tgl_awal'));
		$this->tgl_akhir->setDbValue($rs->fields('tgl_akhir'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ganti_jdw_id->DbValue = $row['ganti_jdw_id'];
		$this->jdw_kerja_m_id1->DbValue = $row['jdw_kerja_m_id1'];
		$this->jdw_kerja_m_id2->DbValue = $row['jdw_kerja_m_id2'];
		$this->tgl_awal->DbValue = $row['tgl_awal'];
		$this->tgl_akhir->DbValue = $row['tgl_akhir'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("ganti_jdw_id")) <> "")
			$this->ganti_jdw_id->CurrentValue = $this->getKey("ganti_jdw_id"); // ganti_jdw_id
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
		// ganti_jdw_id
		// jdw_kerja_m_id1
		// jdw_kerja_m_id2
		// tgl_awal
		// tgl_akhir
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ganti_jdw_id
		$this->ganti_jdw_id->ViewValue = $this->ganti_jdw_id->CurrentValue;
		$this->ganti_jdw_id->ViewCustomAttributes = "";

		// jdw_kerja_m_id1
		$this->jdw_kerja_m_id1->ViewValue = $this->jdw_kerja_m_id1->CurrentValue;
		$this->jdw_kerja_m_id1->ViewCustomAttributes = "";

		// jdw_kerja_m_id2
		$this->jdw_kerja_m_id2->ViewValue = $this->jdw_kerja_m_id2->CurrentValue;
		$this->jdw_kerja_m_id2->ViewCustomAttributes = "";

		// tgl_awal
		$this->tgl_awal->ViewValue = $this->tgl_awal->CurrentValue;
		$this->tgl_awal->ViewValue = ew_FormatDateTime($this->tgl_awal->ViewValue, 0);
		$this->tgl_awal->ViewCustomAttributes = "";

		// tgl_akhir
		$this->tgl_akhir->ViewValue = $this->tgl_akhir->CurrentValue;
		$this->tgl_akhir->ViewValue = ew_FormatDateTime($this->tgl_akhir->ViewValue, 0);
		$this->tgl_akhir->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

			// ganti_jdw_id
			$this->ganti_jdw_id->LinkCustomAttributes = "";
			$this->ganti_jdw_id->HrefValue = "";
			$this->ganti_jdw_id->TooltipValue = "";

			// jdw_kerja_m_id1
			$this->jdw_kerja_m_id1->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id1->HrefValue = "";
			$this->jdw_kerja_m_id1->TooltipValue = "";

			// jdw_kerja_m_id2
			$this->jdw_kerja_m_id2->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id2->HrefValue = "";
			$this->jdw_kerja_m_id2->TooltipValue = "";

			// tgl_awal
			$this->tgl_awal->LinkCustomAttributes = "";
			$this->tgl_awal->HrefValue = "";
			$this->tgl_awal->TooltipValue = "";

			// tgl_akhir
			$this->tgl_akhir->LinkCustomAttributes = "";
			$this->tgl_akhir->HrefValue = "";
			$this->tgl_akhir->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// ganti_jdw_id
			$this->ganti_jdw_id->EditAttrs["class"] = "form-control";
			$this->ganti_jdw_id->EditCustomAttributes = "";
			$this->ganti_jdw_id->EditValue = ew_HtmlEncode($this->ganti_jdw_id->CurrentValue);
			$this->ganti_jdw_id->PlaceHolder = ew_RemoveHtml($this->ganti_jdw_id->FldCaption());

			// jdw_kerja_m_id1
			$this->jdw_kerja_m_id1->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_id1->EditCustomAttributes = "";
			$this->jdw_kerja_m_id1->EditValue = ew_HtmlEncode($this->jdw_kerja_m_id1->CurrentValue);
			$this->jdw_kerja_m_id1->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_id1->FldCaption());

			// jdw_kerja_m_id2
			$this->jdw_kerja_m_id2->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_id2->EditCustomAttributes = "";
			$this->jdw_kerja_m_id2->EditValue = ew_HtmlEncode($this->jdw_kerja_m_id2->CurrentValue);
			$this->jdw_kerja_m_id2->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_id2->FldCaption());

			// tgl_awal
			$this->tgl_awal->EditAttrs["class"] = "form-control";
			$this->tgl_awal->EditCustomAttributes = "";
			$this->tgl_awal->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_awal->CurrentValue, 8));
			$this->tgl_awal->PlaceHolder = ew_RemoveHtml($this->tgl_awal->FldCaption());

			// tgl_akhir
			$this->tgl_akhir->EditAttrs["class"] = "form-control";
			$this->tgl_akhir->EditCustomAttributes = "";
			$this->tgl_akhir->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_akhir->CurrentValue, 8));
			$this->tgl_akhir->PlaceHolder = ew_RemoveHtml($this->tgl_akhir->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// Add refer script
			// ganti_jdw_id

			$this->ganti_jdw_id->LinkCustomAttributes = "";
			$this->ganti_jdw_id->HrefValue = "";

			// jdw_kerja_m_id1
			$this->jdw_kerja_m_id1->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id1->HrefValue = "";

			// jdw_kerja_m_id2
			$this->jdw_kerja_m_id2->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id2->HrefValue = "";

			// tgl_awal
			$this->tgl_awal->LinkCustomAttributes = "";
			$this->tgl_awal->HrefValue = "";

			// tgl_akhir
			$this->tgl_akhir->LinkCustomAttributes = "";
			$this->tgl_akhir->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
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
		if (!$this->jdw_kerja_m_id1->FldIsDetailKey && !is_null($this->jdw_kerja_m_id1->FormValue) && $this->jdw_kerja_m_id1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jdw_kerja_m_id1->FldCaption(), $this->jdw_kerja_m_id1->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_id1->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_id1->FldErrMsg());
		}
		if (!$this->jdw_kerja_m_id2->FldIsDetailKey && !is_null($this->jdw_kerja_m_id2->FormValue) && $this->jdw_kerja_m_id2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jdw_kerja_m_id2->FldCaption(), $this->jdw_kerja_m_id2->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_id2->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_id2->FldErrMsg());
		}
		if (!$this->tgl_awal->FldIsDetailKey && !is_null($this->tgl_awal->FormValue) && $this->tgl_awal->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_awal->FldCaption(), $this->tgl_awal->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_awal->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_awal->FldErrMsg());
		}
		if (!$this->tgl_akhir->FldIsDetailKey && !is_null($this->tgl_akhir->FormValue) && $this->tgl_akhir->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_akhir->FldCaption(), $this->tgl_akhir->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_akhir->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_akhir->FldErrMsg());
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
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

		// ganti_jdw_id
		$this->ganti_jdw_id->SetDbValueDef($rsnew, $this->ganti_jdw_id->CurrentValue, 0, FALSE);

		// jdw_kerja_m_id1
		$this->jdw_kerja_m_id1->SetDbValueDef($rsnew, $this->jdw_kerja_m_id1->CurrentValue, 0, FALSE);

		// jdw_kerja_m_id2
		$this->jdw_kerja_m_id2->SetDbValueDef($rsnew, $this->jdw_kerja_m_id2->CurrentValue, 0, FALSE);

		// tgl_awal
		$this->tgl_awal->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_awal->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// tgl_akhir
		$this->tgl_akhir->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_akhir->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['ganti_jdw_id']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("ganti_jdw_jklist.php"), "", $this->TableVar, TRUE);
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
if (!isset($ganti_jdw_jk_add)) $ganti_jdw_jk_add = new cganti_jdw_jk_add();

// Page init
$ganti_jdw_jk_add->Page_Init();

// Page main
$ganti_jdw_jk_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$ganti_jdw_jk_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fganti_jdw_jkadd = new ew_Form("fganti_jdw_jkadd", "add");

// Validate form
fganti_jdw_jkadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_jk->ganti_jdw_id->FldCaption(), $ganti_jdw_jk->ganti_jdw_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ganti_jdw_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_jk->ganti_jdw_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_jk->jdw_kerja_m_id1->FldCaption(), $ganti_jdw_jk->jdw_kerja_m_id1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id1");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_jk->jdw_kerja_m_id1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_jk->jdw_kerja_m_id2->FldCaption(), $ganti_jdw_jk->jdw_kerja_m_id2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id2");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_jk->jdw_kerja_m_id2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_awal");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_jk->tgl_awal->FldCaption(), $ganti_jdw_jk->tgl_awal->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_awal");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_jk->tgl_awal->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_akhir");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_jk->tgl_akhir->FldCaption(), $ganti_jdw_jk->tgl_akhir->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_akhir");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($ganti_jdw_jk->tgl_akhir->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $ganti_jdw_jk->keterangan->FldCaption(), $ganti_jdw_jk->keterangan->ReqErrMsg)) ?>");

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
fganti_jdw_jkadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fganti_jdw_jkadd.ValidateRequired = true;
<?php } else { ?>
fganti_jdw_jkadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$ganti_jdw_jk_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $ganti_jdw_jk_add->ShowPageHeader(); ?>
<?php
$ganti_jdw_jk_add->ShowMessage();
?>
<form name="fganti_jdw_jkadd" id="fganti_jdw_jkadd" class="<?php echo $ganti_jdw_jk_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($ganti_jdw_jk_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $ganti_jdw_jk_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="ganti_jdw_jk">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($ganti_jdw_jk_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($ganti_jdw_jk->ganti_jdw_id->Visible) { // ganti_jdw_id ?>
	<div id="r_ganti_jdw_id" class="form-group">
		<label id="elh_ganti_jdw_jk_ganti_jdw_id" for="x_ganti_jdw_id" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_jk->ganti_jdw_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_jk->ganti_jdw_id->CellAttributes() ?>>
<span id="el_ganti_jdw_jk_ganti_jdw_id">
<input type="text" data-table="ganti_jdw_jk" data-field="x_ganti_jdw_id" name="x_ganti_jdw_id" id="x_ganti_jdw_id" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jdw_jk->ganti_jdw_id->getPlaceHolder()) ?>" value="<?php echo $ganti_jdw_jk->ganti_jdw_id->EditValue ?>"<?php echo $ganti_jdw_jk->ganti_jdw_id->EditAttributes() ?>>
</span>
<?php echo $ganti_jdw_jk->ganti_jdw_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_jk->jdw_kerja_m_id1->Visible) { // jdw_kerja_m_id1 ?>
	<div id="r_jdw_kerja_m_id1" class="form-group">
		<label id="elh_ganti_jdw_jk_jdw_kerja_m_id1" for="x_jdw_kerja_m_id1" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_jk->jdw_kerja_m_id1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->CellAttributes() ?>>
<span id="el_ganti_jdw_jk_jdw_kerja_m_id1">
<input type="text" data-table="ganti_jdw_jk" data-field="x_jdw_kerja_m_id1" name="x_jdw_kerja_m_id1" id="x_jdw_kerja_m_id1" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jdw_jk->jdw_kerja_m_id1->getPlaceHolder()) ?>" value="<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->EditValue ?>"<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->EditAttributes() ?>>
</span>
<?php echo $ganti_jdw_jk->jdw_kerja_m_id1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_jk->jdw_kerja_m_id2->Visible) { // jdw_kerja_m_id2 ?>
	<div id="r_jdw_kerja_m_id2" class="form-group">
		<label id="elh_ganti_jdw_jk_jdw_kerja_m_id2" for="x_jdw_kerja_m_id2" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_jk->jdw_kerja_m_id2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->CellAttributes() ?>>
<span id="el_ganti_jdw_jk_jdw_kerja_m_id2">
<input type="text" data-table="ganti_jdw_jk" data-field="x_jdw_kerja_m_id2" name="x_jdw_kerja_m_id2" id="x_jdw_kerja_m_id2" size="30" placeholder="<?php echo ew_HtmlEncode($ganti_jdw_jk->jdw_kerja_m_id2->getPlaceHolder()) ?>" value="<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->EditValue ?>"<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->EditAttributes() ?>>
</span>
<?php echo $ganti_jdw_jk->jdw_kerja_m_id2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_jk->tgl_awal->Visible) { // tgl_awal ?>
	<div id="r_tgl_awal" class="form-group">
		<label id="elh_ganti_jdw_jk_tgl_awal" for="x_tgl_awal" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_jk->tgl_awal->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_jk->tgl_awal->CellAttributes() ?>>
<span id="el_ganti_jdw_jk_tgl_awal">
<input type="text" data-table="ganti_jdw_jk" data-field="x_tgl_awal" name="x_tgl_awal" id="x_tgl_awal" placeholder="<?php echo ew_HtmlEncode($ganti_jdw_jk->tgl_awal->getPlaceHolder()) ?>" value="<?php echo $ganti_jdw_jk->tgl_awal->EditValue ?>"<?php echo $ganti_jdw_jk->tgl_awal->EditAttributes() ?>>
</span>
<?php echo $ganti_jdw_jk->tgl_awal->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_jk->tgl_akhir->Visible) { // tgl_akhir ?>
	<div id="r_tgl_akhir" class="form-group">
		<label id="elh_ganti_jdw_jk_tgl_akhir" for="x_tgl_akhir" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_jk->tgl_akhir->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_jk->tgl_akhir->CellAttributes() ?>>
<span id="el_ganti_jdw_jk_tgl_akhir">
<input type="text" data-table="ganti_jdw_jk" data-field="x_tgl_akhir" name="x_tgl_akhir" id="x_tgl_akhir" placeholder="<?php echo ew_HtmlEncode($ganti_jdw_jk->tgl_akhir->getPlaceHolder()) ?>" value="<?php echo $ganti_jdw_jk->tgl_akhir->EditValue ?>"<?php echo $ganti_jdw_jk->tgl_akhir->EditAttributes() ?>>
</span>
<?php echo $ganti_jdw_jk->tgl_akhir->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($ganti_jdw_jk->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_ganti_jdw_jk_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $ganti_jdw_jk->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $ganti_jdw_jk->keterangan->CellAttributes() ?>>
<span id="el_ganti_jdw_jk_keterangan">
<input type="text" data-table="ganti_jdw_jk" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($ganti_jdw_jk->keterangan->getPlaceHolder()) ?>" value="<?php echo $ganti_jdw_jk->keterangan->EditValue ?>"<?php echo $ganti_jdw_jk->keterangan->EditAttributes() ?>>
</span>
<?php echo $ganti_jdw_jk->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$ganti_jdw_jk_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $ganti_jdw_jk_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fganti_jdw_jkadd.Init();
</script>
<?php
$ganti_jdw_jk_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$ganti_jdw_jk_add->Page_Terminate();
?>
