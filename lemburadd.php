<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "lemburinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$lembur_add = NULL; // Initialize page object first

class clembur_add extends clembur {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'lembur';

	// Page object name
	var $PageObjName = 'lembur_add';

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

		// Table object (lembur)
		if (!isset($GLOBALS["lembur"]) || get_class($GLOBALS["lembur"]) == "clembur") {
			$GLOBALS["lembur"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["lembur"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'lembur', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("lemburlist.php"));
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
		$this->lembur_tgl->SetVisibility();
		$this->lembur_mulai->SetVisibility();
		$this->lembur_selesai->SetVisibility();
		$this->lembur_urut->SetVisibility();
		$this->type_ot->SetVisibility();
		$this->lembur_durasi_min->SetVisibility();
		$this->lembur_durasi_max->SetVisibility();
		$this->lembur_keperluan->SetVisibility();

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
		global $EW_EXPORT, $lembur;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($lembur);
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
			if (@$_GET["pegawai_id"] != "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->setKey("pegawai_id", $this->pegawai_id->CurrentValue); // Set up key
			} else {
				$this->setKey("pegawai_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["lembur_tgl"] != "") {
				$this->lembur_tgl->setQueryStringValue($_GET["lembur_tgl"]);
				$this->setKey("lembur_tgl", $this->lembur_tgl->CurrentValue); // Set up key
			} else {
				$this->setKey("lembur_tgl", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["lembur_mulai"] != "") {
				$this->lembur_mulai->setQueryStringValue($_GET["lembur_mulai"]);
				$this->setKey("lembur_mulai", $this->lembur_mulai->CurrentValue); // Set up key
			} else {
				$this->setKey("lembur_mulai", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["lembur_selesai"] != "") {
				$this->lembur_selesai->setQueryStringValue($_GET["lembur_selesai"]);
				$this->setKey("lembur_selesai", $this->lembur_selesai->CurrentValue); // Set up key
			} else {
				$this->setKey("lembur_selesai", ""); // Clear key
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
					$this->Page_Terminate("lemburlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "lemburlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "lemburview.php")
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
		$this->pegawai_id->CurrentValue = 0;
		$this->lembur_tgl->CurrentValue = "0000-00-00";
		$this->lembur_mulai->CurrentValue = "00:00:00";
		$this->lembur_selesai->CurrentValue = "00:00:00";
		$this->lembur_urut->CurrentValue = NULL;
		$this->lembur_urut->OldValue = $this->lembur_urut->CurrentValue;
		$this->type_ot->CurrentValue = -1;
		$this->lembur_durasi_min->CurrentValue = 0;
		$this->lembur_durasi_max->CurrentValue = 0;
		$this->lembur_keperluan->CurrentValue = NULL;
		$this->lembur_keperluan->OldValue = $this->lembur_keperluan->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->lembur_tgl->FldIsDetailKey) {
			$this->lembur_tgl->setFormValue($objForm->GetValue("x_lembur_tgl"));
			$this->lembur_tgl->CurrentValue = ew_UnFormatDateTime($this->lembur_tgl->CurrentValue, 0);
		}
		if (!$this->lembur_mulai->FldIsDetailKey) {
			$this->lembur_mulai->setFormValue($objForm->GetValue("x_lembur_mulai"));
			$this->lembur_mulai->CurrentValue = ew_UnFormatDateTime($this->lembur_mulai->CurrentValue, 0);
		}
		if (!$this->lembur_selesai->FldIsDetailKey) {
			$this->lembur_selesai->setFormValue($objForm->GetValue("x_lembur_selesai"));
			$this->lembur_selesai->CurrentValue = ew_UnFormatDateTime($this->lembur_selesai->CurrentValue, 0);
		}
		if (!$this->lembur_urut->FldIsDetailKey) {
			$this->lembur_urut->setFormValue($objForm->GetValue("x_lembur_urut"));
		}
		if (!$this->type_ot->FldIsDetailKey) {
			$this->type_ot->setFormValue($objForm->GetValue("x_type_ot"));
		}
		if (!$this->lembur_durasi_min->FldIsDetailKey) {
			$this->lembur_durasi_min->setFormValue($objForm->GetValue("x_lembur_durasi_min"));
		}
		if (!$this->lembur_durasi_max->FldIsDetailKey) {
			$this->lembur_durasi_max->setFormValue($objForm->GetValue("x_lembur_durasi_max"));
		}
		if (!$this->lembur_keperluan->FldIsDetailKey) {
			$this->lembur_keperluan->setFormValue($objForm->GetValue("x_lembur_keperluan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->lembur_tgl->CurrentValue = $this->lembur_tgl->FormValue;
		$this->lembur_tgl->CurrentValue = ew_UnFormatDateTime($this->lembur_tgl->CurrentValue, 0);
		$this->lembur_mulai->CurrentValue = $this->lembur_mulai->FormValue;
		$this->lembur_mulai->CurrentValue = ew_UnFormatDateTime($this->lembur_mulai->CurrentValue, 0);
		$this->lembur_selesai->CurrentValue = $this->lembur_selesai->FormValue;
		$this->lembur_selesai->CurrentValue = ew_UnFormatDateTime($this->lembur_selesai->CurrentValue, 0);
		$this->lembur_urut->CurrentValue = $this->lembur_urut->FormValue;
		$this->type_ot->CurrentValue = $this->type_ot->FormValue;
		$this->lembur_durasi_min->CurrentValue = $this->lembur_durasi_min->FormValue;
		$this->lembur_durasi_max->CurrentValue = $this->lembur_durasi_max->FormValue;
		$this->lembur_keperluan->CurrentValue = $this->lembur_keperluan->FormValue;
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
		$this->lembur_tgl->setDbValue($rs->fields('lembur_tgl'));
		$this->lembur_mulai->setDbValue($rs->fields('lembur_mulai'));
		$this->lembur_selesai->setDbValue($rs->fields('lembur_selesai'));
		$this->lembur_urut->setDbValue($rs->fields('lembur_urut'));
		$this->type_ot->setDbValue($rs->fields('type_ot'));
		$this->lembur_durasi_min->setDbValue($rs->fields('lembur_durasi_min'));
		$this->lembur_durasi_max->setDbValue($rs->fields('lembur_durasi_max'));
		$this->lembur_keperluan->setDbValue($rs->fields('lembur_keperluan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->lembur_tgl->DbValue = $row['lembur_tgl'];
		$this->lembur_mulai->DbValue = $row['lembur_mulai'];
		$this->lembur_selesai->DbValue = $row['lembur_selesai'];
		$this->lembur_urut->DbValue = $row['lembur_urut'];
		$this->type_ot->DbValue = $row['type_ot'];
		$this->lembur_durasi_min->DbValue = $row['lembur_durasi_min'];
		$this->lembur_durasi_max->DbValue = $row['lembur_durasi_max'];
		$this->lembur_keperluan->DbValue = $row['lembur_keperluan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("lembur_tgl")) <> "")
			$this->lembur_tgl->CurrentValue = $this->getKey("lembur_tgl"); // lembur_tgl
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("lembur_mulai")) <> "")
			$this->lembur_mulai->CurrentValue = $this->getKey("lembur_mulai"); // lembur_mulai
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("lembur_selesai")) <> "")
			$this->lembur_selesai->CurrentValue = $this->getKey("lembur_selesai"); // lembur_selesai
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
		// pegawai_id
		// lembur_tgl
		// lembur_mulai
		// lembur_selesai
		// lembur_urut
		// type_ot
		// lembur_durasi_min
		// lembur_durasi_max
		// lembur_keperluan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// lembur_tgl
		$this->lembur_tgl->ViewValue = $this->lembur_tgl->CurrentValue;
		$this->lembur_tgl->ViewValue = ew_FormatDateTime($this->lembur_tgl->ViewValue, 0);
		$this->lembur_tgl->ViewCustomAttributes = "";

		// lembur_mulai
		$this->lembur_mulai->ViewValue = $this->lembur_mulai->CurrentValue;
		$this->lembur_mulai->ViewCustomAttributes = "";

		// lembur_selesai
		$this->lembur_selesai->ViewValue = $this->lembur_selesai->CurrentValue;
		$this->lembur_selesai->ViewCustomAttributes = "";

		// lembur_urut
		$this->lembur_urut->ViewValue = $this->lembur_urut->CurrentValue;
		$this->lembur_urut->ViewCustomAttributes = "";

		// type_ot
		$this->type_ot->ViewValue = $this->type_ot->CurrentValue;
		$this->type_ot->ViewCustomAttributes = "";

		// lembur_durasi_min
		$this->lembur_durasi_min->ViewValue = $this->lembur_durasi_min->CurrentValue;
		$this->lembur_durasi_min->ViewCustomAttributes = "";

		// lembur_durasi_max
		$this->lembur_durasi_max->ViewValue = $this->lembur_durasi_max->CurrentValue;
		$this->lembur_durasi_max->ViewCustomAttributes = "";

		// lembur_keperluan
		$this->lembur_keperluan->ViewValue = $this->lembur_keperluan->CurrentValue;
		$this->lembur_keperluan->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// lembur_tgl
			$this->lembur_tgl->LinkCustomAttributes = "";
			$this->lembur_tgl->HrefValue = "";
			$this->lembur_tgl->TooltipValue = "";

			// lembur_mulai
			$this->lembur_mulai->LinkCustomAttributes = "";
			$this->lembur_mulai->HrefValue = "";
			$this->lembur_mulai->TooltipValue = "";

			// lembur_selesai
			$this->lembur_selesai->LinkCustomAttributes = "";
			$this->lembur_selesai->HrefValue = "";
			$this->lembur_selesai->TooltipValue = "";

			// lembur_urut
			$this->lembur_urut->LinkCustomAttributes = "";
			$this->lembur_urut->HrefValue = "";
			$this->lembur_urut->TooltipValue = "";

			// type_ot
			$this->type_ot->LinkCustomAttributes = "";
			$this->type_ot->HrefValue = "";
			$this->type_ot->TooltipValue = "";

			// lembur_durasi_min
			$this->lembur_durasi_min->LinkCustomAttributes = "";
			$this->lembur_durasi_min->HrefValue = "";
			$this->lembur_durasi_min->TooltipValue = "";

			// lembur_durasi_max
			$this->lembur_durasi_max->LinkCustomAttributes = "";
			$this->lembur_durasi_max->HrefValue = "";
			$this->lembur_durasi_max->TooltipValue = "";

			// lembur_keperluan
			$this->lembur_keperluan->LinkCustomAttributes = "";
			$this->lembur_keperluan->HrefValue = "";
			$this->lembur_keperluan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = ew_HtmlEncode($this->pegawai_id->CurrentValue);
			$this->pegawai_id->PlaceHolder = ew_RemoveHtml($this->pegawai_id->FldCaption());

			// lembur_tgl
			$this->lembur_tgl->EditAttrs["class"] = "form-control";
			$this->lembur_tgl->EditCustomAttributes = "";
			$this->lembur_tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->lembur_tgl->CurrentValue, 8));
			$this->lembur_tgl->PlaceHolder = ew_RemoveHtml($this->lembur_tgl->FldCaption());

			// lembur_mulai
			$this->lembur_mulai->EditAttrs["class"] = "form-control";
			$this->lembur_mulai->EditCustomAttributes = "";
			$this->lembur_mulai->EditValue = ew_HtmlEncode($this->lembur_mulai->CurrentValue);
			$this->lembur_mulai->PlaceHolder = ew_RemoveHtml($this->lembur_mulai->FldCaption());

			// lembur_selesai
			$this->lembur_selesai->EditAttrs["class"] = "form-control";
			$this->lembur_selesai->EditCustomAttributes = "";
			$this->lembur_selesai->EditValue = ew_HtmlEncode($this->lembur_selesai->CurrentValue);
			$this->lembur_selesai->PlaceHolder = ew_RemoveHtml($this->lembur_selesai->FldCaption());

			// lembur_urut
			$this->lembur_urut->EditAttrs["class"] = "form-control";
			$this->lembur_urut->EditCustomAttributes = "";
			$this->lembur_urut->EditValue = ew_HtmlEncode($this->lembur_urut->CurrentValue);
			$this->lembur_urut->PlaceHolder = ew_RemoveHtml($this->lembur_urut->FldCaption());

			// type_ot
			$this->type_ot->EditAttrs["class"] = "form-control";
			$this->type_ot->EditCustomAttributes = "";
			$this->type_ot->EditValue = ew_HtmlEncode($this->type_ot->CurrentValue);
			$this->type_ot->PlaceHolder = ew_RemoveHtml($this->type_ot->FldCaption());

			// lembur_durasi_min
			$this->lembur_durasi_min->EditAttrs["class"] = "form-control";
			$this->lembur_durasi_min->EditCustomAttributes = "";
			$this->lembur_durasi_min->EditValue = ew_HtmlEncode($this->lembur_durasi_min->CurrentValue);
			$this->lembur_durasi_min->PlaceHolder = ew_RemoveHtml($this->lembur_durasi_min->FldCaption());

			// lembur_durasi_max
			$this->lembur_durasi_max->EditAttrs["class"] = "form-control";
			$this->lembur_durasi_max->EditCustomAttributes = "";
			$this->lembur_durasi_max->EditValue = ew_HtmlEncode($this->lembur_durasi_max->CurrentValue);
			$this->lembur_durasi_max->PlaceHolder = ew_RemoveHtml($this->lembur_durasi_max->FldCaption());

			// lembur_keperluan
			$this->lembur_keperluan->EditAttrs["class"] = "form-control";
			$this->lembur_keperluan->EditCustomAttributes = "";
			$this->lembur_keperluan->EditValue = ew_HtmlEncode($this->lembur_keperluan->CurrentValue);
			$this->lembur_keperluan->PlaceHolder = ew_RemoveHtml($this->lembur_keperluan->FldCaption());

			// Add refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// lembur_tgl
			$this->lembur_tgl->LinkCustomAttributes = "";
			$this->lembur_tgl->HrefValue = "";

			// lembur_mulai
			$this->lembur_mulai->LinkCustomAttributes = "";
			$this->lembur_mulai->HrefValue = "";

			// lembur_selesai
			$this->lembur_selesai->LinkCustomAttributes = "";
			$this->lembur_selesai->HrefValue = "";

			// lembur_urut
			$this->lembur_urut->LinkCustomAttributes = "";
			$this->lembur_urut->HrefValue = "";

			// type_ot
			$this->type_ot->LinkCustomAttributes = "";
			$this->type_ot->HrefValue = "";

			// lembur_durasi_min
			$this->lembur_durasi_min->LinkCustomAttributes = "";
			$this->lembur_durasi_min->HrefValue = "";

			// lembur_durasi_max
			$this->lembur_durasi_max->LinkCustomAttributes = "";
			$this->lembur_durasi_max->HrefValue = "";

			// lembur_keperluan
			$this->lembur_keperluan->LinkCustomAttributes = "";
			$this->lembur_keperluan->HrefValue = "";
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
		if (!$this->lembur_tgl->FldIsDetailKey && !is_null($this->lembur_tgl->FormValue) && $this->lembur_tgl->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lembur_tgl->FldCaption(), $this->lembur_tgl->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->lembur_tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur_tgl->FldErrMsg());
		}
		if (!$this->lembur_mulai->FldIsDetailKey && !is_null($this->lembur_mulai->FormValue) && $this->lembur_mulai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lembur_mulai->FldCaption(), $this->lembur_mulai->ReqErrMsg));
		}
		if (!ew_CheckTime($this->lembur_mulai->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur_mulai->FldErrMsg());
		}
		if (!$this->lembur_selesai->FldIsDetailKey && !is_null($this->lembur_selesai->FormValue) && $this->lembur_selesai->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lembur_selesai->FldCaption(), $this->lembur_selesai->ReqErrMsg));
		}
		if (!ew_CheckTime($this->lembur_selesai->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur_selesai->FldErrMsg());
		}
		if (!$this->lembur_urut->FldIsDetailKey && !is_null($this->lembur_urut->FormValue) && $this->lembur_urut->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->lembur_urut->FldCaption(), $this->lembur_urut->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->lembur_urut->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur_urut->FldErrMsg());
		}
		if (!$this->type_ot->FldIsDetailKey && !is_null($this->type_ot->FormValue) && $this->type_ot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->type_ot->FldCaption(), $this->type_ot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->type_ot->FormValue)) {
			ew_AddMessage($gsFormError, $this->type_ot->FldErrMsg());
		}
		if (!ew_CheckInteger($this->lembur_durasi_min->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur_durasi_min->FldErrMsg());
		}
		if (!ew_CheckInteger($this->lembur_durasi_max->FormValue)) {
			ew_AddMessage($gsFormError, $this->lembur_durasi_max->FldErrMsg());
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

		// pegawai_id
		$this->pegawai_id->SetDbValueDef($rsnew, $this->pegawai_id->CurrentValue, 0, strval($this->pegawai_id->CurrentValue) == "");

		// lembur_tgl
		$this->lembur_tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lembur_tgl->CurrentValue, 0), ew_CurrentDate(), strval($this->lembur_tgl->CurrentValue) == "");

		// lembur_mulai
		$this->lembur_mulai->SetDbValueDef($rsnew, $this->lembur_mulai->CurrentValue, ew_CurrentTime(), strval($this->lembur_mulai->CurrentValue) == "");

		// lembur_selesai
		$this->lembur_selesai->SetDbValueDef($rsnew, $this->lembur_selesai->CurrentValue, ew_CurrentTime(), strval($this->lembur_selesai->CurrentValue) == "");

		// lembur_urut
		$this->lembur_urut->SetDbValueDef($rsnew, $this->lembur_urut->CurrentValue, 0, FALSE);

		// type_ot
		$this->type_ot->SetDbValueDef($rsnew, $this->type_ot->CurrentValue, 0, strval($this->type_ot->CurrentValue) == "");

		// lembur_durasi_min
		$this->lembur_durasi_min->SetDbValueDef($rsnew, $this->lembur_durasi_min->CurrentValue, NULL, strval($this->lembur_durasi_min->CurrentValue) == "");

		// lembur_durasi_max
		$this->lembur_durasi_max->SetDbValueDef($rsnew, $this->lembur_durasi_max->CurrentValue, NULL, strval($this->lembur_durasi_max->CurrentValue) == "");

		// lembur_keperluan
		$this->lembur_keperluan->SetDbValueDef($rsnew, $this->lembur_keperluan->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pegawai_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['lembur_tgl']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['lembur_mulai']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['lembur_selesai']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("lemburlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($lembur_add)) $lembur_add = new clembur_add();

// Page init
$lembur_add->Page_Init();

// Page main
$lembur_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$lembur_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = flemburadd = new ew_Form("flemburadd", "add");

// Validate form
flemburadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lembur->pegawai_id->FldCaption(), $lembur->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur_tgl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lembur->lembur_tgl->FldCaption(), $lembur->lembur_tgl->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lembur_tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->lembur_tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur_mulai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lembur->lembur_mulai->FldCaption(), $lembur->lembur_mulai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lembur_mulai");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->lembur_mulai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur_selesai");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lembur->lembur_selesai->FldCaption(), $lembur->lembur_selesai->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lembur_selesai");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->lembur_selesai->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur_urut");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lembur->lembur_urut->FldCaption(), $lembur->lembur_urut->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lembur_urut");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->lembur_urut->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_type_ot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $lembur->type_ot->FldCaption(), $lembur->type_ot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_type_ot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->type_ot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur_durasi_min");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->lembur_durasi_min->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lembur_durasi_max");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($lembur->lembur_durasi_max->FldErrMsg()) ?>");

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
flemburadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flemburadd.ValidateRequired = true;
<?php } else { ?>
flemburadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$lembur_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $lembur_add->ShowPageHeader(); ?>
<?php
$lembur_add->ShowMessage();
?>
<form name="flemburadd" id="flemburadd" class="<?php echo $lembur_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($lembur_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $lembur_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="lembur">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($lembur_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($lembur->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_lembur_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $lembur->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->pegawai_id->CellAttributes() ?>>
<span id="el_lembur_pegawai_id">
<input type="text" data-table="lembur" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $lembur->pegawai_id->EditValue ?>"<?php echo $lembur->pegawai_id->EditAttributes() ?>>
</span>
<?php echo $lembur->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_tgl->Visible) { // lembur_tgl ?>
	<div id="r_lembur_tgl" class="form-group">
		<label id="elh_lembur_lembur_tgl" for="x_lembur_tgl" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_tgl->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_tgl->CellAttributes() ?>>
<span id="el_lembur_lembur_tgl">
<input type="text" data-table="lembur" data-field="x_lembur_tgl" name="x_lembur_tgl" id="x_lembur_tgl" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_tgl->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_tgl->EditValue ?>"<?php echo $lembur->lembur_tgl->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_mulai->Visible) { // lembur_mulai ?>
	<div id="r_lembur_mulai" class="form-group">
		<label id="elh_lembur_lembur_mulai" for="x_lembur_mulai" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_mulai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_mulai->CellAttributes() ?>>
<span id="el_lembur_lembur_mulai">
<input type="text" data-table="lembur" data-field="x_lembur_mulai" name="x_lembur_mulai" id="x_lembur_mulai" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_mulai->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_mulai->EditValue ?>"<?php echo $lembur->lembur_mulai->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_mulai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_selesai->Visible) { // lembur_selesai ?>
	<div id="r_lembur_selesai" class="form-group">
		<label id="elh_lembur_lembur_selesai" for="x_lembur_selesai" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_selesai->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_selesai->CellAttributes() ?>>
<span id="el_lembur_lembur_selesai">
<input type="text" data-table="lembur" data-field="x_lembur_selesai" name="x_lembur_selesai" id="x_lembur_selesai" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_selesai->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_selesai->EditValue ?>"<?php echo $lembur->lembur_selesai->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_selesai->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_urut->Visible) { // lembur_urut ?>
	<div id="r_lembur_urut" class="form-group">
		<label id="elh_lembur_lembur_urut" for="x_lembur_urut" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_urut->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_urut->CellAttributes() ?>>
<span id="el_lembur_lembur_urut">
<input type="text" data-table="lembur" data-field="x_lembur_urut" name="x_lembur_urut" id="x_lembur_urut" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_urut->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_urut->EditValue ?>"<?php echo $lembur->lembur_urut->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_urut->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->type_ot->Visible) { // type_ot ?>
	<div id="r_type_ot" class="form-group">
		<label id="elh_lembur_type_ot" for="x_type_ot" class="col-sm-2 control-label ewLabel"><?php echo $lembur->type_ot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->type_ot->CellAttributes() ?>>
<span id="el_lembur_type_ot">
<input type="text" data-table="lembur" data-field="x_type_ot" name="x_type_ot" id="x_type_ot" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->type_ot->getPlaceHolder()) ?>" value="<?php echo $lembur->type_ot->EditValue ?>"<?php echo $lembur->type_ot->EditAttributes() ?>>
</span>
<?php echo $lembur->type_ot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_durasi_min->Visible) { // lembur_durasi_min ?>
	<div id="r_lembur_durasi_min" class="form-group">
		<label id="elh_lembur_lembur_durasi_min" for="x_lembur_durasi_min" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_durasi_min->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_durasi_min->CellAttributes() ?>>
<span id="el_lembur_lembur_durasi_min">
<input type="text" data-table="lembur" data-field="x_lembur_durasi_min" name="x_lembur_durasi_min" id="x_lembur_durasi_min" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_durasi_min->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_durasi_min->EditValue ?>"<?php echo $lembur->lembur_durasi_min->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_durasi_min->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_durasi_max->Visible) { // lembur_durasi_max ?>
	<div id="r_lembur_durasi_max" class="form-group">
		<label id="elh_lembur_lembur_durasi_max" for="x_lembur_durasi_max" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_durasi_max->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_durasi_max->CellAttributes() ?>>
<span id="el_lembur_lembur_durasi_max">
<input type="text" data-table="lembur" data-field="x_lembur_durasi_max" name="x_lembur_durasi_max" id="x_lembur_durasi_max" size="30" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_durasi_max->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_durasi_max->EditValue ?>"<?php echo $lembur->lembur_durasi_max->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_durasi_max->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($lembur->lembur_keperluan->Visible) { // lembur_keperluan ?>
	<div id="r_lembur_keperluan" class="form-group">
		<label id="elh_lembur_lembur_keperluan" for="x_lembur_keperluan" class="col-sm-2 control-label ewLabel"><?php echo $lembur->lembur_keperluan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $lembur->lembur_keperluan->CellAttributes() ?>>
<span id="el_lembur_lembur_keperluan">
<input type="text" data-table="lembur" data-field="x_lembur_keperluan" name="x_lembur_keperluan" id="x_lembur_keperluan" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($lembur->lembur_keperluan->getPlaceHolder()) ?>" value="<?php echo $lembur->lembur_keperluan->EditValue ?>"<?php echo $lembur->lembur_keperluan->EditAttributes() ?>>
</span>
<?php echo $lembur->lembur_keperluan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$lembur_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $lembur_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
flemburadd.Init();
</script>
<?php
$lembur_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$lembur_add->Page_Terminate();
?>
