<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "izininfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$izin_add = NULL; // Initialize page object first

class cizin_add extends cizin {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'izin';

	// Page object name
	var $PageObjName = 'izin_add';

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

		// Table object (izin)
		if (!isset($GLOBALS["izin"]) || get_class($GLOBALS["izin"]) == "cizin") {
			$GLOBALS["izin"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["izin"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'izin', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("izinlist.php"));
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
		$this->izin_urutan->SetVisibility();
		$this->izin_tgl_pengajuan->SetVisibility();
		$this->izin_tgl->SetVisibility();
		$this->izin_jenis_id->SetVisibility();
		$this->izin_catatan->SetVisibility();
		$this->izin_status->SetVisibility();
		$this->izin_tinggal_t1->SetVisibility();
		$this->izin_tinggal_t2->SetVisibility();
		$this->cuti_n_id->SetVisibility();
		$this->izin_ket_lain->SetVisibility();
		$this->izin_noscan_time->SetVisibility();
		$this->kat_izin_id->SetVisibility();
		$this->ket_status->SetVisibility();

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
		global $EW_EXPORT, $izin;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($izin);
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
			if (@$_GET["izin_urutan"] != "") {
				$this->izin_urutan->setQueryStringValue($_GET["izin_urutan"]);
				$this->setKey("izin_urutan", $this->izin_urutan->CurrentValue); // Set up key
			} else {
				$this->setKey("izin_urutan", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["izin_tgl"] != "") {
				$this->izin_tgl->setQueryStringValue($_GET["izin_tgl"]);
				$this->setKey("izin_tgl", $this->izin_tgl->CurrentValue); // Set up key
			} else {
				$this->setKey("izin_tgl", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["izin_jenis_id"] != "") {
				$this->izin_jenis_id->setQueryStringValue($_GET["izin_jenis_id"]);
				$this->setKey("izin_jenis_id", $this->izin_jenis_id->CurrentValue); // Set up key
			} else {
				$this->setKey("izin_jenis_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["izin_status"] != "") {
				$this->izin_status->setQueryStringValue($_GET["izin_status"]);
				$this->setKey("izin_status", $this->izin_status->CurrentValue); // Set up key
			} else {
				$this->setKey("izin_status", ""); // Clear key
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
					$this->Page_Terminate("izinlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "izinlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "izinview.php")
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
		$this->izin_urutan->CurrentValue = 0;
		$this->izin_tgl_pengajuan->CurrentValue = NULL;
		$this->izin_tgl_pengajuan->OldValue = $this->izin_tgl_pengajuan->CurrentValue;
		$this->izin_tgl->CurrentValue = NULL;
		$this->izin_tgl->OldValue = $this->izin_tgl->CurrentValue;
		$this->izin_jenis_id->CurrentValue = 0;
		$this->izin_catatan->CurrentValue = NULL;
		$this->izin_catatan->OldValue = $this->izin_catatan->CurrentValue;
		$this->izin_status->CurrentValue = 1;
		$this->izin_tinggal_t1->CurrentValue = NULL;
		$this->izin_tinggal_t1->OldValue = $this->izin_tinggal_t1->CurrentValue;
		$this->izin_tinggal_t2->CurrentValue = NULL;
		$this->izin_tinggal_t2->OldValue = $this->izin_tinggal_t2->CurrentValue;
		$this->cuti_n_id->CurrentValue = 0;
		$this->izin_ket_lain->CurrentValue = NULL;
		$this->izin_ket_lain->OldValue = $this->izin_ket_lain->CurrentValue;
		$this->izin_noscan_time->CurrentValue = NULL;
		$this->izin_noscan_time->OldValue = $this->izin_noscan_time->CurrentValue;
		$this->kat_izin_id->CurrentValue = 0;
		$this->ket_status->CurrentValue = NULL;
		$this->ket_status->OldValue = $this->ket_status->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->izin_urutan->FldIsDetailKey) {
			$this->izin_urutan->setFormValue($objForm->GetValue("x_izin_urutan"));
		}
		if (!$this->izin_tgl_pengajuan->FldIsDetailKey) {
			$this->izin_tgl_pengajuan->setFormValue($objForm->GetValue("x_izin_tgl_pengajuan"));
			$this->izin_tgl_pengajuan->CurrentValue = ew_UnFormatDateTime($this->izin_tgl_pengajuan->CurrentValue, 0);
		}
		if (!$this->izin_tgl->FldIsDetailKey) {
			$this->izin_tgl->setFormValue($objForm->GetValue("x_izin_tgl"));
			$this->izin_tgl->CurrentValue = ew_UnFormatDateTime($this->izin_tgl->CurrentValue, 0);
		}
		if (!$this->izin_jenis_id->FldIsDetailKey) {
			$this->izin_jenis_id->setFormValue($objForm->GetValue("x_izin_jenis_id"));
		}
		if (!$this->izin_catatan->FldIsDetailKey) {
			$this->izin_catatan->setFormValue($objForm->GetValue("x_izin_catatan"));
		}
		if (!$this->izin_status->FldIsDetailKey) {
			$this->izin_status->setFormValue($objForm->GetValue("x_izin_status"));
		}
		if (!$this->izin_tinggal_t1->FldIsDetailKey) {
			$this->izin_tinggal_t1->setFormValue($objForm->GetValue("x_izin_tinggal_t1"));
			$this->izin_tinggal_t1->CurrentValue = ew_UnFormatDateTime($this->izin_tinggal_t1->CurrentValue, 0);
		}
		if (!$this->izin_tinggal_t2->FldIsDetailKey) {
			$this->izin_tinggal_t2->setFormValue($objForm->GetValue("x_izin_tinggal_t2"));
			$this->izin_tinggal_t2->CurrentValue = ew_UnFormatDateTime($this->izin_tinggal_t2->CurrentValue, 0);
		}
		if (!$this->cuti_n_id->FldIsDetailKey) {
			$this->cuti_n_id->setFormValue($objForm->GetValue("x_cuti_n_id"));
		}
		if (!$this->izin_ket_lain->FldIsDetailKey) {
			$this->izin_ket_lain->setFormValue($objForm->GetValue("x_izin_ket_lain"));
		}
		if (!$this->izin_noscan_time->FldIsDetailKey) {
			$this->izin_noscan_time->setFormValue($objForm->GetValue("x_izin_noscan_time"));
			$this->izin_noscan_time->CurrentValue = ew_UnFormatDateTime($this->izin_noscan_time->CurrentValue, 0);
		}
		if (!$this->kat_izin_id->FldIsDetailKey) {
			$this->kat_izin_id->setFormValue($objForm->GetValue("x_kat_izin_id"));
		}
		if (!$this->ket_status->FldIsDetailKey) {
			$this->ket_status->setFormValue($objForm->GetValue("x_ket_status"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->izin_urutan->CurrentValue = $this->izin_urutan->FormValue;
		$this->izin_tgl_pengajuan->CurrentValue = $this->izin_tgl_pengajuan->FormValue;
		$this->izin_tgl_pengajuan->CurrentValue = ew_UnFormatDateTime($this->izin_tgl_pengajuan->CurrentValue, 0);
		$this->izin_tgl->CurrentValue = $this->izin_tgl->FormValue;
		$this->izin_tgl->CurrentValue = ew_UnFormatDateTime($this->izin_tgl->CurrentValue, 0);
		$this->izin_jenis_id->CurrentValue = $this->izin_jenis_id->FormValue;
		$this->izin_catatan->CurrentValue = $this->izin_catatan->FormValue;
		$this->izin_status->CurrentValue = $this->izin_status->FormValue;
		$this->izin_tinggal_t1->CurrentValue = $this->izin_tinggal_t1->FormValue;
		$this->izin_tinggal_t1->CurrentValue = ew_UnFormatDateTime($this->izin_tinggal_t1->CurrentValue, 0);
		$this->izin_tinggal_t2->CurrentValue = $this->izin_tinggal_t2->FormValue;
		$this->izin_tinggal_t2->CurrentValue = ew_UnFormatDateTime($this->izin_tinggal_t2->CurrentValue, 0);
		$this->cuti_n_id->CurrentValue = $this->cuti_n_id->FormValue;
		$this->izin_ket_lain->CurrentValue = $this->izin_ket_lain->FormValue;
		$this->izin_noscan_time->CurrentValue = $this->izin_noscan_time->FormValue;
		$this->izin_noscan_time->CurrentValue = ew_UnFormatDateTime($this->izin_noscan_time->CurrentValue, 0);
		$this->kat_izin_id->CurrentValue = $this->kat_izin_id->FormValue;
		$this->ket_status->CurrentValue = $this->ket_status->FormValue;
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
		$this->izin_urutan->setDbValue($rs->fields('izin_urutan'));
		$this->izin_tgl_pengajuan->setDbValue($rs->fields('izin_tgl_pengajuan'));
		$this->izin_tgl->setDbValue($rs->fields('izin_tgl'));
		$this->izin_jenis_id->setDbValue($rs->fields('izin_jenis_id'));
		$this->izin_catatan->setDbValue($rs->fields('izin_catatan'));
		$this->izin_status->setDbValue($rs->fields('izin_status'));
		$this->izin_tinggal_t1->setDbValue($rs->fields('izin_tinggal_t1'));
		$this->izin_tinggal_t2->setDbValue($rs->fields('izin_tinggal_t2'));
		$this->cuti_n_id->setDbValue($rs->fields('cuti_n_id'));
		$this->izin_ket_lain->setDbValue($rs->fields('izin_ket_lain'));
		$this->izin_noscan_time->setDbValue($rs->fields('izin_noscan_time'));
		$this->kat_izin_id->setDbValue($rs->fields('kat_izin_id'));
		$this->ket_status->setDbValue($rs->fields('ket_status'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->izin_urutan->DbValue = $row['izin_urutan'];
		$this->izin_tgl_pengajuan->DbValue = $row['izin_tgl_pengajuan'];
		$this->izin_tgl->DbValue = $row['izin_tgl'];
		$this->izin_jenis_id->DbValue = $row['izin_jenis_id'];
		$this->izin_catatan->DbValue = $row['izin_catatan'];
		$this->izin_status->DbValue = $row['izin_status'];
		$this->izin_tinggal_t1->DbValue = $row['izin_tinggal_t1'];
		$this->izin_tinggal_t2->DbValue = $row['izin_tinggal_t2'];
		$this->cuti_n_id->DbValue = $row['cuti_n_id'];
		$this->izin_ket_lain->DbValue = $row['izin_ket_lain'];
		$this->izin_noscan_time->DbValue = $row['izin_noscan_time'];
		$this->kat_izin_id->DbValue = $row['kat_izin_id'];
		$this->ket_status->DbValue = $row['ket_status'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("izin_urutan")) <> "")
			$this->izin_urutan->CurrentValue = $this->getKey("izin_urutan"); // izin_urutan
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("izin_tgl")) <> "")
			$this->izin_tgl->CurrentValue = $this->getKey("izin_tgl"); // izin_tgl
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("izin_jenis_id")) <> "")
			$this->izin_jenis_id->CurrentValue = $this->getKey("izin_jenis_id"); // izin_jenis_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("izin_status")) <> "")
			$this->izin_status->CurrentValue = $this->getKey("izin_status"); // izin_status
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
		// izin_urutan
		// izin_tgl_pengajuan
		// izin_tgl
		// izin_jenis_id
		// izin_catatan
		// izin_status
		// izin_tinggal_t1
		// izin_tinggal_t2
		// cuti_n_id
		// izin_ket_lain
		// izin_noscan_time
		// kat_izin_id
		// ket_status

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// izin_urutan
		$this->izin_urutan->ViewValue = $this->izin_urutan->CurrentValue;
		$this->izin_urutan->ViewCustomAttributes = "";

		// izin_tgl_pengajuan
		$this->izin_tgl_pengajuan->ViewValue = $this->izin_tgl_pengajuan->CurrentValue;
		$this->izin_tgl_pengajuan->ViewValue = ew_FormatDateTime($this->izin_tgl_pengajuan->ViewValue, 0);
		$this->izin_tgl_pengajuan->ViewCustomAttributes = "";

		// izin_tgl
		$this->izin_tgl->ViewValue = $this->izin_tgl->CurrentValue;
		$this->izin_tgl->ViewValue = ew_FormatDateTime($this->izin_tgl->ViewValue, 0);
		$this->izin_tgl->ViewCustomAttributes = "";

		// izin_jenis_id
		$this->izin_jenis_id->ViewValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->ViewCustomAttributes = "";

		// izin_catatan
		$this->izin_catatan->ViewValue = $this->izin_catatan->CurrentValue;
		$this->izin_catatan->ViewCustomAttributes = "";

		// izin_status
		$this->izin_status->ViewValue = $this->izin_status->CurrentValue;
		$this->izin_status->ViewCustomAttributes = "";

		// izin_tinggal_t1
		$this->izin_tinggal_t1->ViewValue = $this->izin_tinggal_t1->CurrentValue;
		$this->izin_tinggal_t1->ViewCustomAttributes = "";

		// izin_tinggal_t2
		$this->izin_tinggal_t2->ViewValue = $this->izin_tinggal_t2->CurrentValue;
		$this->izin_tinggal_t2->ViewCustomAttributes = "";

		// cuti_n_id
		$this->cuti_n_id->ViewValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->ViewCustomAttributes = "";

		// izin_ket_lain
		$this->izin_ket_lain->ViewValue = $this->izin_ket_lain->CurrentValue;
		$this->izin_ket_lain->ViewCustomAttributes = "";

		// izin_noscan_time
		$this->izin_noscan_time->ViewValue = $this->izin_noscan_time->CurrentValue;
		$this->izin_noscan_time->ViewCustomAttributes = "";

		// kat_izin_id
		$this->kat_izin_id->ViewValue = $this->kat_izin_id->CurrentValue;
		$this->kat_izin_id->ViewCustomAttributes = "";

		// ket_status
		$this->ket_status->ViewValue = $this->ket_status->CurrentValue;
		$this->ket_status->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// izin_urutan
			$this->izin_urutan->LinkCustomAttributes = "";
			$this->izin_urutan->HrefValue = "";
			$this->izin_urutan->TooltipValue = "";

			// izin_tgl_pengajuan
			$this->izin_tgl_pengajuan->LinkCustomAttributes = "";
			$this->izin_tgl_pengajuan->HrefValue = "";
			$this->izin_tgl_pengajuan->TooltipValue = "";

			// izin_tgl
			$this->izin_tgl->LinkCustomAttributes = "";
			$this->izin_tgl->HrefValue = "";
			$this->izin_tgl->TooltipValue = "";

			// izin_jenis_id
			$this->izin_jenis_id->LinkCustomAttributes = "";
			$this->izin_jenis_id->HrefValue = "";
			$this->izin_jenis_id->TooltipValue = "";

			// izin_catatan
			$this->izin_catatan->LinkCustomAttributes = "";
			$this->izin_catatan->HrefValue = "";
			$this->izin_catatan->TooltipValue = "";

			// izin_status
			$this->izin_status->LinkCustomAttributes = "";
			$this->izin_status->HrefValue = "";
			$this->izin_status->TooltipValue = "";

			// izin_tinggal_t1
			$this->izin_tinggal_t1->LinkCustomAttributes = "";
			$this->izin_tinggal_t1->HrefValue = "";
			$this->izin_tinggal_t1->TooltipValue = "";

			// izin_tinggal_t2
			$this->izin_tinggal_t2->LinkCustomAttributes = "";
			$this->izin_tinggal_t2->HrefValue = "";
			$this->izin_tinggal_t2->TooltipValue = "";

			// cuti_n_id
			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";
			$this->cuti_n_id->TooltipValue = "";

			// izin_ket_lain
			$this->izin_ket_lain->LinkCustomAttributes = "";
			$this->izin_ket_lain->HrefValue = "";
			$this->izin_ket_lain->TooltipValue = "";

			// izin_noscan_time
			$this->izin_noscan_time->LinkCustomAttributes = "";
			$this->izin_noscan_time->HrefValue = "";
			$this->izin_noscan_time->TooltipValue = "";

			// kat_izin_id
			$this->kat_izin_id->LinkCustomAttributes = "";
			$this->kat_izin_id->HrefValue = "";
			$this->kat_izin_id->TooltipValue = "";

			// ket_status
			$this->ket_status->LinkCustomAttributes = "";
			$this->ket_status->HrefValue = "";
			$this->ket_status->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = ew_HtmlEncode($this->pegawai_id->CurrentValue);
			$this->pegawai_id->PlaceHolder = ew_RemoveHtml($this->pegawai_id->FldCaption());

			// izin_urutan
			$this->izin_urutan->EditAttrs["class"] = "form-control";
			$this->izin_urutan->EditCustomAttributes = "";
			$this->izin_urutan->EditValue = ew_HtmlEncode($this->izin_urutan->CurrentValue);
			$this->izin_urutan->PlaceHolder = ew_RemoveHtml($this->izin_urutan->FldCaption());

			// izin_tgl_pengajuan
			$this->izin_tgl_pengajuan->EditAttrs["class"] = "form-control";
			$this->izin_tgl_pengajuan->EditCustomAttributes = "";
			$this->izin_tgl_pengajuan->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->izin_tgl_pengajuan->CurrentValue, 8));
			$this->izin_tgl_pengajuan->PlaceHolder = ew_RemoveHtml($this->izin_tgl_pengajuan->FldCaption());

			// izin_tgl
			$this->izin_tgl->EditAttrs["class"] = "form-control";
			$this->izin_tgl->EditCustomAttributes = "";
			$this->izin_tgl->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->izin_tgl->CurrentValue, 8));
			$this->izin_tgl->PlaceHolder = ew_RemoveHtml($this->izin_tgl->FldCaption());

			// izin_jenis_id
			$this->izin_jenis_id->EditAttrs["class"] = "form-control";
			$this->izin_jenis_id->EditCustomAttributes = "";
			$this->izin_jenis_id->EditValue = ew_HtmlEncode($this->izin_jenis_id->CurrentValue);
			$this->izin_jenis_id->PlaceHolder = ew_RemoveHtml($this->izin_jenis_id->FldCaption());

			// izin_catatan
			$this->izin_catatan->EditAttrs["class"] = "form-control";
			$this->izin_catatan->EditCustomAttributes = "";
			$this->izin_catatan->EditValue = ew_HtmlEncode($this->izin_catatan->CurrentValue);
			$this->izin_catatan->PlaceHolder = ew_RemoveHtml($this->izin_catatan->FldCaption());

			// izin_status
			$this->izin_status->EditAttrs["class"] = "form-control";
			$this->izin_status->EditCustomAttributes = "";
			$this->izin_status->EditValue = ew_HtmlEncode($this->izin_status->CurrentValue);
			$this->izin_status->PlaceHolder = ew_RemoveHtml($this->izin_status->FldCaption());

			// izin_tinggal_t1
			$this->izin_tinggal_t1->EditAttrs["class"] = "form-control";
			$this->izin_tinggal_t1->EditCustomAttributes = "";
			$this->izin_tinggal_t1->EditValue = ew_HtmlEncode($this->izin_tinggal_t1->CurrentValue);
			$this->izin_tinggal_t1->PlaceHolder = ew_RemoveHtml($this->izin_tinggal_t1->FldCaption());

			// izin_tinggal_t2
			$this->izin_tinggal_t2->EditAttrs["class"] = "form-control";
			$this->izin_tinggal_t2->EditCustomAttributes = "";
			$this->izin_tinggal_t2->EditValue = ew_HtmlEncode($this->izin_tinggal_t2->CurrentValue);
			$this->izin_tinggal_t2->PlaceHolder = ew_RemoveHtml($this->izin_tinggal_t2->FldCaption());

			// cuti_n_id
			$this->cuti_n_id->EditAttrs["class"] = "form-control";
			$this->cuti_n_id->EditCustomAttributes = "";
			$this->cuti_n_id->EditValue = ew_HtmlEncode($this->cuti_n_id->CurrentValue);
			$this->cuti_n_id->PlaceHolder = ew_RemoveHtml($this->cuti_n_id->FldCaption());

			// izin_ket_lain
			$this->izin_ket_lain->EditAttrs["class"] = "form-control";
			$this->izin_ket_lain->EditCustomAttributes = "";
			$this->izin_ket_lain->EditValue = ew_HtmlEncode($this->izin_ket_lain->CurrentValue);
			$this->izin_ket_lain->PlaceHolder = ew_RemoveHtml($this->izin_ket_lain->FldCaption());

			// izin_noscan_time
			$this->izin_noscan_time->EditAttrs["class"] = "form-control";
			$this->izin_noscan_time->EditCustomAttributes = "";
			$this->izin_noscan_time->EditValue = ew_HtmlEncode($this->izin_noscan_time->CurrentValue);
			$this->izin_noscan_time->PlaceHolder = ew_RemoveHtml($this->izin_noscan_time->FldCaption());

			// kat_izin_id
			$this->kat_izin_id->EditAttrs["class"] = "form-control";
			$this->kat_izin_id->EditCustomAttributes = "";
			$this->kat_izin_id->EditValue = ew_HtmlEncode($this->kat_izin_id->CurrentValue);
			$this->kat_izin_id->PlaceHolder = ew_RemoveHtml($this->kat_izin_id->FldCaption());

			// ket_status
			$this->ket_status->EditAttrs["class"] = "form-control";
			$this->ket_status->EditCustomAttributes = "";
			$this->ket_status->EditValue = ew_HtmlEncode($this->ket_status->CurrentValue);
			$this->ket_status->PlaceHolder = ew_RemoveHtml($this->ket_status->FldCaption());

			// Add refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// izin_urutan
			$this->izin_urutan->LinkCustomAttributes = "";
			$this->izin_urutan->HrefValue = "";

			// izin_tgl_pengajuan
			$this->izin_tgl_pengajuan->LinkCustomAttributes = "";
			$this->izin_tgl_pengajuan->HrefValue = "";

			// izin_tgl
			$this->izin_tgl->LinkCustomAttributes = "";
			$this->izin_tgl->HrefValue = "";

			// izin_jenis_id
			$this->izin_jenis_id->LinkCustomAttributes = "";
			$this->izin_jenis_id->HrefValue = "";

			// izin_catatan
			$this->izin_catatan->LinkCustomAttributes = "";
			$this->izin_catatan->HrefValue = "";

			// izin_status
			$this->izin_status->LinkCustomAttributes = "";
			$this->izin_status->HrefValue = "";

			// izin_tinggal_t1
			$this->izin_tinggal_t1->LinkCustomAttributes = "";
			$this->izin_tinggal_t1->HrefValue = "";

			// izin_tinggal_t2
			$this->izin_tinggal_t2->LinkCustomAttributes = "";
			$this->izin_tinggal_t2->HrefValue = "";

			// cuti_n_id
			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";

			// izin_ket_lain
			$this->izin_ket_lain->LinkCustomAttributes = "";
			$this->izin_ket_lain->HrefValue = "";

			// izin_noscan_time
			$this->izin_noscan_time->LinkCustomAttributes = "";
			$this->izin_noscan_time->HrefValue = "";

			// kat_izin_id
			$this->kat_izin_id->LinkCustomAttributes = "";
			$this->kat_izin_id->HrefValue = "";

			// ket_status
			$this->ket_status->LinkCustomAttributes = "";
			$this->ket_status->HrefValue = "";
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
		if (!$this->izin_urutan->FldIsDetailKey && !is_null($this->izin_urutan->FormValue) && $this->izin_urutan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->izin_urutan->FldCaption(), $this->izin_urutan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->izin_urutan->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_urutan->FldErrMsg());
		}
		if (!$this->izin_tgl_pengajuan->FldIsDetailKey && !is_null($this->izin_tgl_pengajuan->FormValue) && $this->izin_tgl_pengajuan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->izin_tgl_pengajuan->FldCaption(), $this->izin_tgl_pengajuan->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->izin_tgl_pengajuan->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_tgl_pengajuan->FldErrMsg());
		}
		if (!$this->izin_tgl->FldIsDetailKey && !is_null($this->izin_tgl->FormValue) && $this->izin_tgl->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->izin_tgl->FldCaption(), $this->izin_tgl->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->izin_tgl->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_tgl->FldErrMsg());
		}
		if (!$this->izin_jenis_id->FldIsDetailKey && !is_null($this->izin_jenis_id->FormValue) && $this->izin_jenis_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->izin_jenis_id->FldCaption(), $this->izin_jenis_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->izin_jenis_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_jenis_id->FldErrMsg());
		}
		if (!$this->izin_status->FldIsDetailKey && !is_null($this->izin_status->FormValue) && $this->izin_status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->izin_status->FldCaption(), $this->izin_status->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->izin_status->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_status->FldErrMsg());
		}
		if (!ew_CheckTime($this->izin_tinggal_t1->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_tinggal_t1->FldErrMsg());
		}
		if (!ew_CheckTime($this->izin_tinggal_t2->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_tinggal_t2->FldErrMsg());
		}
		if (!ew_CheckInteger($this->cuti_n_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->cuti_n_id->FldErrMsg());
		}
		if (!ew_CheckTime($this->izin_noscan_time->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_noscan_time->FldErrMsg());
		}
		if (!ew_CheckInteger($this->kat_izin_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->kat_izin_id->FldErrMsg());
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

		// izin_urutan
		$this->izin_urutan->SetDbValueDef($rsnew, $this->izin_urutan->CurrentValue, 0, strval($this->izin_urutan->CurrentValue) == "");

		// izin_tgl_pengajuan
		$this->izin_tgl_pengajuan->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->izin_tgl_pengajuan->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// izin_tgl
		$this->izin_tgl->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->izin_tgl->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// izin_jenis_id
		$this->izin_jenis_id->SetDbValueDef($rsnew, $this->izin_jenis_id->CurrentValue, 0, strval($this->izin_jenis_id->CurrentValue) == "");

		// izin_catatan
		$this->izin_catatan->SetDbValueDef($rsnew, $this->izin_catatan->CurrentValue, NULL, FALSE);

		// izin_status
		$this->izin_status->SetDbValueDef($rsnew, $this->izin_status->CurrentValue, 0, strval($this->izin_status->CurrentValue) == "");

		// izin_tinggal_t1
		$this->izin_tinggal_t1->SetDbValueDef($rsnew, $this->izin_tinggal_t1->CurrentValue, NULL, FALSE);

		// izin_tinggal_t2
		$this->izin_tinggal_t2->SetDbValueDef($rsnew, $this->izin_tinggal_t2->CurrentValue, NULL, FALSE);

		// cuti_n_id
		$this->cuti_n_id->SetDbValueDef($rsnew, $this->cuti_n_id->CurrentValue, NULL, strval($this->cuti_n_id->CurrentValue) == "");

		// izin_ket_lain
		$this->izin_ket_lain->SetDbValueDef($rsnew, $this->izin_ket_lain->CurrentValue, NULL, FALSE);

		// izin_noscan_time
		$this->izin_noscan_time->SetDbValueDef($rsnew, $this->izin_noscan_time->CurrentValue, NULL, FALSE);

		// kat_izin_id
		$this->kat_izin_id->SetDbValueDef($rsnew, $this->kat_izin_id->CurrentValue, NULL, strval($this->kat_izin_id->CurrentValue) == "");

		// ket_status
		$this->ket_status->SetDbValueDef($rsnew, $this->ket_status->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pegawai_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['izin_urutan']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['izin_tgl']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['izin_jenis_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['izin_status']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("izinlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($izin_add)) $izin_add = new cizin_add();

// Page init
$izin_add->Page_Init();

// Page main
$izin_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$izin_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fizinadd = new ew_Form("fizinadd", "add");

// Validate form
fizinadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $izin->pegawai_id->FldCaption(), $izin->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_urutan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $izin->izin_urutan->FldCaption(), $izin->izin_urutan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_izin_urutan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_urutan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_tgl_pengajuan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $izin->izin_tgl_pengajuan->FldCaption(), $izin->izin_tgl_pengajuan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_izin_tgl_pengajuan");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_tgl_pengajuan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_tgl");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $izin->izin_tgl->FldCaption(), $izin->izin_tgl->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_izin_tgl");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_tgl->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_jenis_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $izin->izin_jenis_id->FldCaption(), $izin->izin_jenis_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_izin_jenis_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_jenis_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $izin->izin_status->FldCaption(), $izin->izin_status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_izin_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_tinggal_t1");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_tinggal_t1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_tinggal_t2");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_tinggal_t2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->cuti_n_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_noscan_time");
			if (elm && !ew_CheckTime(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->izin_noscan_time->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_kat_izin_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($izin->kat_izin_id->FldErrMsg()) ?>");

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
fizinadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fizinadd.ValidateRequired = true;
<?php } else { ?>
fizinadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$izin_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $izin_add->ShowPageHeader(); ?>
<?php
$izin_add->ShowMessage();
?>
<form name="fizinadd" id="fizinadd" class="<?php echo $izin_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($izin_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $izin_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="izin">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($izin_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($izin->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_izin_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $izin->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $izin->pegawai_id->CellAttributes() ?>>
<span id="el_izin_pegawai_id">
<input type="text" data-table="izin" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($izin->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $izin->pegawai_id->EditValue ?>"<?php echo $izin->pegawai_id->EditAttributes() ?>>
</span>
<?php echo $izin->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_urutan->Visible) { // izin_urutan ?>
	<div id="r_izin_urutan" class="form-group">
		<label id="elh_izin_izin_urutan" for="x_izin_urutan" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_urutan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_urutan->CellAttributes() ?>>
<span id="el_izin_izin_urutan">
<input type="text" data-table="izin" data-field="x_izin_urutan" name="x_izin_urutan" id="x_izin_urutan" size="30" placeholder="<?php echo ew_HtmlEncode($izin->izin_urutan->getPlaceHolder()) ?>" value="<?php echo $izin->izin_urutan->EditValue ?>"<?php echo $izin->izin_urutan->EditAttributes() ?>>
</span>
<?php echo $izin->izin_urutan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_tgl_pengajuan->Visible) { // izin_tgl_pengajuan ?>
	<div id="r_izin_tgl_pengajuan" class="form-group">
		<label id="elh_izin_izin_tgl_pengajuan" for="x_izin_tgl_pengajuan" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_tgl_pengajuan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_tgl_pengajuan->CellAttributes() ?>>
<span id="el_izin_izin_tgl_pengajuan">
<input type="text" data-table="izin" data-field="x_izin_tgl_pengajuan" name="x_izin_tgl_pengajuan" id="x_izin_tgl_pengajuan" placeholder="<?php echo ew_HtmlEncode($izin->izin_tgl_pengajuan->getPlaceHolder()) ?>" value="<?php echo $izin->izin_tgl_pengajuan->EditValue ?>"<?php echo $izin->izin_tgl_pengajuan->EditAttributes() ?>>
</span>
<?php echo $izin->izin_tgl_pengajuan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_tgl->Visible) { // izin_tgl ?>
	<div id="r_izin_tgl" class="form-group">
		<label id="elh_izin_izin_tgl" for="x_izin_tgl" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_tgl->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_tgl->CellAttributes() ?>>
<span id="el_izin_izin_tgl">
<input type="text" data-table="izin" data-field="x_izin_tgl" name="x_izin_tgl" id="x_izin_tgl" placeholder="<?php echo ew_HtmlEncode($izin->izin_tgl->getPlaceHolder()) ?>" value="<?php echo $izin->izin_tgl->EditValue ?>"<?php echo $izin->izin_tgl->EditAttributes() ?>>
</span>
<?php echo $izin->izin_tgl->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_jenis_id->Visible) { // izin_jenis_id ?>
	<div id="r_izin_jenis_id" class="form-group">
		<label id="elh_izin_izin_jenis_id" for="x_izin_jenis_id" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_jenis_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_jenis_id->CellAttributes() ?>>
<span id="el_izin_izin_jenis_id">
<input type="text" data-table="izin" data-field="x_izin_jenis_id" name="x_izin_jenis_id" id="x_izin_jenis_id" size="30" placeholder="<?php echo ew_HtmlEncode($izin->izin_jenis_id->getPlaceHolder()) ?>" value="<?php echo $izin->izin_jenis_id->EditValue ?>"<?php echo $izin->izin_jenis_id->EditAttributes() ?>>
</span>
<?php echo $izin->izin_jenis_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_catatan->Visible) { // izin_catatan ?>
	<div id="r_izin_catatan" class="form-group">
		<label id="elh_izin_izin_catatan" for="x_izin_catatan" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_catatan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_catatan->CellAttributes() ?>>
<span id="el_izin_izin_catatan">
<input type="text" data-table="izin" data-field="x_izin_catatan" name="x_izin_catatan" id="x_izin_catatan" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($izin->izin_catatan->getPlaceHolder()) ?>" value="<?php echo $izin->izin_catatan->EditValue ?>"<?php echo $izin->izin_catatan->EditAttributes() ?>>
</span>
<?php echo $izin->izin_catatan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_status->Visible) { // izin_status ?>
	<div id="r_izin_status" class="form-group">
		<label id="elh_izin_izin_status" for="x_izin_status" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_status->CellAttributes() ?>>
<span id="el_izin_izin_status">
<input type="text" data-table="izin" data-field="x_izin_status" name="x_izin_status" id="x_izin_status" size="30" placeholder="<?php echo ew_HtmlEncode($izin->izin_status->getPlaceHolder()) ?>" value="<?php echo $izin->izin_status->EditValue ?>"<?php echo $izin->izin_status->EditAttributes() ?>>
</span>
<?php echo $izin->izin_status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_tinggal_t1->Visible) { // izin_tinggal_t1 ?>
	<div id="r_izin_tinggal_t1" class="form-group">
		<label id="elh_izin_izin_tinggal_t1" for="x_izin_tinggal_t1" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_tinggal_t1->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_tinggal_t1->CellAttributes() ?>>
<span id="el_izin_izin_tinggal_t1">
<input type="text" data-table="izin" data-field="x_izin_tinggal_t1" name="x_izin_tinggal_t1" id="x_izin_tinggal_t1" size="30" placeholder="<?php echo ew_HtmlEncode($izin->izin_tinggal_t1->getPlaceHolder()) ?>" value="<?php echo $izin->izin_tinggal_t1->EditValue ?>"<?php echo $izin->izin_tinggal_t1->EditAttributes() ?>>
</span>
<?php echo $izin->izin_tinggal_t1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_tinggal_t2->Visible) { // izin_tinggal_t2 ?>
	<div id="r_izin_tinggal_t2" class="form-group">
		<label id="elh_izin_izin_tinggal_t2" for="x_izin_tinggal_t2" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_tinggal_t2->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_tinggal_t2->CellAttributes() ?>>
<span id="el_izin_izin_tinggal_t2">
<input type="text" data-table="izin" data-field="x_izin_tinggal_t2" name="x_izin_tinggal_t2" id="x_izin_tinggal_t2" size="30" placeholder="<?php echo ew_HtmlEncode($izin->izin_tinggal_t2->getPlaceHolder()) ?>" value="<?php echo $izin->izin_tinggal_t2->EditValue ?>"<?php echo $izin->izin_tinggal_t2->EditAttributes() ?>>
</span>
<?php echo $izin->izin_tinggal_t2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->cuti_n_id->Visible) { // cuti_n_id ?>
	<div id="r_cuti_n_id" class="form-group">
		<label id="elh_izin_cuti_n_id" for="x_cuti_n_id" class="col-sm-2 control-label ewLabel"><?php echo $izin->cuti_n_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->cuti_n_id->CellAttributes() ?>>
<span id="el_izin_cuti_n_id">
<input type="text" data-table="izin" data-field="x_cuti_n_id" name="x_cuti_n_id" id="x_cuti_n_id" size="30" placeholder="<?php echo ew_HtmlEncode($izin->cuti_n_id->getPlaceHolder()) ?>" value="<?php echo $izin->cuti_n_id->EditValue ?>"<?php echo $izin->cuti_n_id->EditAttributes() ?>>
</span>
<?php echo $izin->cuti_n_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_ket_lain->Visible) { // izin_ket_lain ?>
	<div id="r_izin_ket_lain" class="form-group">
		<label id="elh_izin_izin_ket_lain" for="x_izin_ket_lain" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_ket_lain->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_ket_lain->CellAttributes() ?>>
<span id="el_izin_izin_ket_lain">
<input type="text" data-table="izin" data-field="x_izin_ket_lain" name="x_izin_ket_lain" id="x_izin_ket_lain" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($izin->izin_ket_lain->getPlaceHolder()) ?>" value="<?php echo $izin->izin_ket_lain->EditValue ?>"<?php echo $izin->izin_ket_lain->EditAttributes() ?>>
</span>
<?php echo $izin->izin_ket_lain->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->izin_noscan_time->Visible) { // izin_noscan_time ?>
	<div id="r_izin_noscan_time" class="form-group">
		<label id="elh_izin_izin_noscan_time" for="x_izin_noscan_time" class="col-sm-2 control-label ewLabel"><?php echo $izin->izin_noscan_time->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->izin_noscan_time->CellAttributes() ?>>
<span id="el_izin_izin_noscan_time">
<input type="text" data-table="izin" data-field="x_izin_noscan_time" name="x_izin_noscan_time" id="x_izin_noscan_time" size="30" placeholder="<?php echo ew_HtmlEncode($izin->izin_noscan_time->getPlaceHolder()) ?>" value="<?php echo $izin->izin_noscan_time->EditValue ?>"<?php echo $izin->izin_noscan_time->EditAttributes() ?>>
</span>
<?php echo $izin->izin_noscan_time->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->kat_izin_id->Visible) { // kat_izin_id ?>
	<div id="r_kat_izin_id" class="form-group">
		<label id="elh_izin_kat_izin_id" for="x_kat_izin_id" class="col-sm-2 control-label ewLabel"><?php echo $izin->kat_izin_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->kat_izin_id->CellAttributes() ?>>
<span id="el_izin_kat_izin_id">
<input type="text" data-table="izin" data-field="x_kat_izin_id" name="x_kat_izin_id" id="x_kat_izin_id" size="30" placeholder="<?php echo ew_HtmlEncode($izin->kat_izin_id->getPlaceHolder()) ?>" value="<?php echo $izin->kat_izin_id->EditValue ?>"<?php echo $izin->kat_izin_id->EditAttributes() ?>>
</span>
<?php echo $izin->kat_izin_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($izin->ket_status->Visible) { // ket_status ?>
	<div id="r_ket_status" class="form-group">
		<label id="elh_izin_ket_status" for="x_ket_status" class="col-sm-2 control-label ewLabel"><?php echo $izin->ket_status->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $izin->ket_status->CellAttributes() ?>>
<span id="el_izin_ket_status">
<input type="text" data-table="izin" data-field="x_ket_status" name="x_ket_status" id="x_ket_status" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($izin->ket_status->getPlaceHolder()) ?>" value="<?php echo $izin->ket_status->EditValue ?>"<?php echo $izin->ket_status->EditAttributes() ?>>
</span>
<?php echo $izin->ket_status->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$izin_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $izin_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fizinadd.Init();
</script>
<?php
$izin_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$izin_add->Page_Terminate();
?>
