<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "zx_kredit_minfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$zx_kredit_m_add = NULL; // Initialize page object first

class czx_kredit_m_add extends czx_kredit_m {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'zx_kredit_m';

	// Page object name
	var $PageObjName = 'zx_kredit_m_add';

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

		// Table object (zx_kredit_m)
		if (!isset($GLOBALS["zx_kredit_m"]) || get_class($GLOBALS["zx_kredit_m"]) == "czx_kredit_m") {
			$GLOBALS["zx_kredit_m"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zx_kredit_m"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'zx_kredit_m', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("zx_kredit_mlist.php"));
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
		$this->id_kredit->SetVisibility();
		$this->no_kredit->SetVisibility();
		$this->tgl_kredit->SetVisibility();
		$this->emp_id_auto->SetVisibility();
		$this->krd_id->SetVisibility();
		$this->cara_hitung->SetVisibility();
		$this->tot_kredit->SetVisibility();
		$this->saldo_aw->SetVisibility();
		$this->suku_bunga->SetVisibility();
		$this->periode_bulan->SetVisibility();
		$this->angs_pokok->SetVisibility();
		$this->angs_pertama->SetVisibility();
		$this->tot_debet->SetVisibility();
		$this->tot_angs_pokok->SetVisibility();
		$this->tot_bunga->SetVisibility();
		$this->def_pembulatan->SetVisibility();
		$this->jumlah_piutang->SetVisibility();
		$this->approv_by->SetVisibility();
		$this->keterangan->SetVisibility();
		$this->status->SetVisibility();
		$this->status_lunas->SetVisibility();
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
		global $EW_EXPORT, $zx_kredit_m;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zx_kredit_m);
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
			if (@$_GET["id_kredit"] != "") {
				$this->id_kredit->setQueryStringValue($_GET["id_kredit"]);
				$this->setKey("id_kredit", $this->id_kredit->CurrentValue); // Set up key
			} else {
				$this->setKey("id_kredit", ""); // Clear key
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
					$this->Page_Terminate("zx_kredit_mlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "zx_kredit_mlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "zx_kredit_mview.php")
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
		$this->id_kredit->CurrentValue = NULL;
		$this->id_kredit->OldValue = $this->id_kredit->CurrentValue;
		$this->no_kredit->CurrentValue = NULL;
		$this->no_kredit->OldValue = $this->no_kredit->CurrentValue;
		$this->tgl_kredit->CurrentValue = NULL;
		$this->tgl_kredit->OldValue = $this->tgl_kredit->CurrentValue;
		$this->emp_id_auto->CurrentValue = NULL;
		$this->emp_id_auto->OldValue = $this->emp_id_auto->CurrentValue;
		$this->krd_id->CurrentValue = NULL;
		$this->krd_id->OldValue = $this->krd_id->CurrentValue;
		$this->cara_hitung->CurrentValue = 0;
		$this->tot_kredit->CurrentValue = NULL;
		$this->tot_kredit->OldValue = $this->tot_kredit->CurrentValue;
		$this->saldo_aw->CurrentValue = NULL;
		$this->saldo_aw->OldValue = $this->saldo_aw->CurrentValue;
		$this->suku_bunga->CurrentValue = NULL;
		$this->suku_bunga->OldValue = $this->suku_bunga->CurrentValue;
		$this->periode_bulan->CurrentValue = NULL;
		$this->periode_bulan->OldValue = $this->periode_bulan->CurrentValue;
		$this->angs_pokok->CurrentValue = NULL;
		$this->angs_pokok->OldValue = $this->angs_pokok->CurrentValue;
		$this->angs_pertama->CurrentValue = NULL;
		$this->angs_pertama->OldValue = $this->angs_pertama->CurrentValue;
		$this->tot_debet->CurrentValue = NULL;
		$this->tot_debet->OldValue = $this->tot_debet->CurrentValue;
		$this->tot_angs_pokok->CurrentValue = NULL;
		$this->tot_angs_pokok->OldValue = $this->tot_angs_pokok->CurrentValue;
		$this->tot_bunga->CurrentValue = NULL;
		$this->tot_bunga->OldValue = $this->tot_bunga->CurrentValue;
		$this->def_pembulatan->CurrentValue = NULL;
		$this->def_pembulatan->OldValue = $this->def_pembulatan->CurrentValue;
		$this->jumlah_piutang->CurrentValue = NULL;
		$this->jumlah_piutang->OldValue = $this->jumlah_piutang->CurrentValue;
		$this->approv_by->CurrentValue = NULL;
		$this->approv_by->OldValue = $this->approv_by->CurrentValue;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
		$this->status->CurrentValue = 0;
		$this->status_lunas->CurrentValue = 0;
		$this->lastupdate_date->CurrentValue = NULL;
		$this->lastupdate_date->OldValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_user->CurrentValue = NULL;
		$this->lastupdate_user->OldValue = $this->lastupdate_user->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->id_kredit->FldIsDetailKey) {
			$this->id_kredit->setFormValue($objForm->GetValue("x_id_kredit"));
		}
		if (!$this->no_kredit->FldIsDetailKey) {
			$this->no_kredit->setFormValue($objForm->GetValue("x_no_kredit"));
		}
		if (!$this->tgl_kredit->FldIsDetailKey) {
			$this->tgl_kredit->setFormValue($objForm->GetValue("x_tgl_kredit"));
			$this->tgl_kredit->CurrentValue = ew_UnFormatDateTime($this->tgl_kredit->CurrentValue, 0);
		}
		if (!$this->emp_id_auto->FldIsDetailKey) {
			$this->emp_id_auto->setFormValue($objForm->GetValue("x_emp_id_auto"));
		}
		if (!$this->krd_id->FldIsDetailKey) {
			$this->krd_id->setFormValue($objForm->GetValue("x_krd_id"));
		}
		if (!$this->cara_hitung->FldIsDetailKey) {
			$this->cara_hitung->setFormValue($objForm->GetValue("x_cara_hitung"));
		}
		if (!$this->tot_kredit->FldIsDetailKey) {
			$this->tot_kredit->setFormValue($objForm->GetValue("x_tot_kredit"));
		}
		if (!$this->saldo_aw->FldIsDetailKey) {
			$this->saldo_aw->setFormValue($objForm->GetValue("x_saldo_aw"));
		}
		if (!$this->suku_bunga->FldIsDetailKey) {
			$this->suku_bunga->setFormValue($objForm->GetValue("x_suku_bunga"));
		}
		if (!$this->periode_bulan->FldIsDetailKey) {
			$this->periode_bulan->setFormValue($objForm->GetValue("x_periode_bulan"));
		}
		if (!$this->angs_pokok->FldIsDetailKey) {
			$this->angs_pokok->setFormValue($objForm->GetValue("x_angs_pokok"));
		}
		if (!$this->angs_pertama->FldIsDetailKey) {
			$this->angs_pertama->setFormValue($objForm->GetValue("x_angs_pertama"));
			$this->angs_pertama->CurrentValue = ew_UnFormatDateTime($this->angs_pertama->CurrentValue, 0);
		}
		if (!$this->tot_debet->FldIsDetailKey) {
			$this->tot_debet->setFormValue($objForm->GetValue("x_tot_debet"));
		}
		if (!$this->tot_angs_pokok->FldIsDetailKey) {
			$this->tot_angs_pokok->setFormValue($objForm->GetValue("x_tot_angs_pokok"));
		}
		if (!$this->tot_bunga->FldIsDetailKey) {
			$this->tot_bunga->setFormValue($objForm->GetValue("x_tot_bunga"));
		}
		if (!$this->def_pembulatan->FldIsDetailKey) {
			$this->def_pembulatan->setFormValue($objForm->GetValue("x_def_pembulatan"));
		}
		if (!$this->jumlah_piutang->FldIsDetailKey) {
			$this->jumlah_piutang->setFormValue($objForm->GetValue("x_jumlah_piutang"));
		}
		if (!$this->approv_by->FldIsDetailKey) {
			$this->approv_by->setFormValue($objForm->GetValue("x_approv_by"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
		if (!$this->status->FldIsDetailKey) {
			$this->status->setFormValue($objForm->GetValue("x_status"));
		}
		if (!$this->status_lunas->FldIsDetailKey) {
			$this->status_lunas->setFormValue($objForm->GetValue("x_status_lunas"));
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
		$this->LoadOldRecord();
		$this->id_kredit->CurrentValue = $this->id_kredit->FormValue;
		$this->no_kredit->CurrentValue = $this->no_kredit->FormValue;
		$this->tgl_kredit->CurrentValue = $this->tgl_kredit->FormValue;
		$this->tgl_kredit->CurrentValue = ew_UnFormatDateTime($this->tgl_kredit->CurrentValue, 0);
		$this->emp_id_auto->CurrentValue = $this->emp_id_auto->FormValue;
		$this->krd_id->CurrentValue = $this->krd_id->FormValue;
		$this->cara_hitung->CurrentValue = $this->cara_hitung->FormValue;
		$this->tot_kredit->CurrentValue = $this->tot_kredit->FormValue;
		$this->saldo_aw->CurrentValue = $this->saldo_aw->FormValue;
		$this->suku_bunga->CurrentValue = $this->suku_bunga->FormValue;
		$this->periode_bulan->CurrentValue = $this->periode_bulan->FormValue;
		$this->angs_pokok->CurrentValue = $this->angs_pokok->FormValue;
		$this->angs_pertama->CurrentValue = $this->angs_pertama->FormValue;
		$this->angs_pertama->CurrentValue = ew_UnFormatDateTime($this->angs_pertama->CurrentValue, 0);
		$this->tot_debet->CurrentValue = $this->tot_debet->FormValue;
		$this->tot_angs_pokok->CurrentValue = $this->tot_angs_pokok->FormValue;
		$this->tot_bunga->CurrentValue = $this->tot_bunga->FormValue;
		$this->def_pembulatan->CurrentValue = $this->def_pembulatan->FormValue;
		$this->jumlah_piutang->CurrentValue = $this->jumlah_piutang->FormValue;
		$this->approv_by->CurrentValue = $this->approv_by->FormValue;
		$this->keterangan->CurrentValue = $this->keterangan->FormValue;
		$this->status->CurrentValue = $this->status->FormValue;
		$this->status_lunas->CurrentValue = $this->status_lunas->FormValue;
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
		$this->id_kredit->setDbValue($rs->fields('id_kredit'));
		$this->no_kredit->setDbValue($rs->fields('no_kredit'));
		$this->tgl_kredit->setDbValue($rs->fields('tgl_kredit'));
		$this->emp_id_auto->setDbValue($rs->fields('emp_id_auto'));
		$this->krd_id->setDbValue($rs->fields('krd_id'));
		$this->cara_hitung->setDbValue($rs->fields('cara_hitung'));
		$this->tot_kredit->setDbValue($rs->fields('tot_kredit'));
		$this->saldo_aw->setDbValue($rs->fields('saldo_aw'));
		$this->suku_bunga->setDbValue($rs->fields('suku_bunga'));
		$this->periode_bulan->setDbValue($rs->fields('periode_bulan'));
		$this->angs_pokok->setDbValue($rs->fields('angs_pokok'));
		$this->angs_pertama->setDbValue($rs->fields('angs_pertama'));
		$this->tot_debet->setDbValue($rs->fields('tot_debet'));
		$this->tot_angs_pokok->setDbValue($rs->fields('tot_angs_pokok'));
		$this->tot_bunga->setDbValue($rs->fields('tot_bunga'));
		$this->def_pembulatan->setDbValue($rs->fields('def_pembulatan'));
		$this->jumlah_piutang->setDbValue($rs->fields('jumlah_piutang'));
		$this->approv_by->setDbValue($rs->fields('approv_by'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
		$this->status->setDbValue($rs->fields('status'));
		$this->status_lunas->setDbValue($rs->fields('status_lunas'));
		$this->lastupdate_date->setDbValue($rs->fields('lastupdate_date'));
		$this->lastupdate_user->setDbValue($rs->fields('lastupdate_user'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id_kredit->DbValue = $row['id_kredit'];
		$this->no_kredit->DbValue = $row['no_kredit'];
		$this->tgl_kredit->DbValue = $row['tgl_kredit'];
		$this->emp_id_auto->DbValue = $row['emp_id_auto'];
		$this->krd_id->DbValue = $row['krd_id'];
		$this->cara_hitung->DbValue = $row['cara_hitung'];
		$this->tot_kredit->DbValue = $row['tot_kredit'];
		$this->saldo_aw->DbValue = $row['saldo_aw'];
		$this->suku_bunga->DbValue = $row['suku_bunga'];
		$this->periode_bulan->DbValue = $row['periode_bulan'];
		$this->angs_pokok->DbValue = $row['angs_pokok'];
		$this->angs_pertama->DbValue = $row['angs_pertama'];
		$this->tot_debet->DbValue = $row['tot_debet'];
		$this->tot_angs_pokok->DbValue = $row['tot_angs_pokok'];
		$this->tot_bunga->DbValue = $row['tot_bunga'];
		$this->def_pembulatan->DbValue = $row['def_pembulatan'];
		$this->jumlah_piutang->DbValue = $row['jumlah_piutang'];
		$this->approv_by->DbValue = $row['approv_by'];
		$this->keterangan->DbValue = $row['keterangan'];
		$this->status->DbValue = $row['status'];
		$this->status_lunas->DbValue = $row['status_lunas'];
		$this->lastupdate_date->DbValue = $row['lastupdate_date'];
		$this->lastupdate_user->DbValue = $row['lastupdate_user'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_kredit")) <> "")
			$this->id_kredit->CurrentValue = $this->getKey("id_kredit"); // id_kredit
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

		if ($this->tot_kredit->FormValue == $this->tot_kredit->CurrentValue && is_numeric(ew_StrToFloat($this->tot_kredit->CurrentValue)))
			$this->tot_kredit->CurrentValue = ew_StrToFloat($this->tot_kredit->CurrentValue);

		// Convert decimal values if posted back
		if ($this->saldo_aw->FormValue == $this->saldo_aw->CurrentValue && is_numeric(ew_StrToFloat($this->saldo_aw->CurrentValue)))
			$this->saldo_aw->CurrentValue = ew_StrToFloat($this->saldo_aw->CurrentValue);

		// Convert decimal values if posted back
		if ($this->suku_bunga->FormValue == $this->suku_bunga->CurrentValue && is_numeric(ew_StrToFloat($this->suku_bunga->CurrentValue)))
			$this->suku_bunga->CurrentValue = ew_StrToFloat($this->suku_bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->angs_pokok->FormValue == $this->angs_pokok->CurrentValue && is_numeric(ew_StrToFloat($this->angs_pokok->CurrentValue)))
			$this->angs_pokok->CurrentValue = ew_StrToFloat($this->angs_pokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_debet->FormValue == $this->tot_debet->CurrentValue && is_numeric(ew_StrToFloat($this->tot_debet->CurrentValue)))
			$this->tot_debet->CurrentValue = ew_StrToFloat($this->tot_debet->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_angs_pokok->FormValue == $this->tot_angs_pokok->CurrentValue && is_numeric(ew_StrToFloat($this->tot_angs_pokok->CurrentValue)))
			$this->tot_angs_pokok->CurrentValue = ew_StrToFloat($this->tot_angs_pokok->CurrentValue);

		// Convert decimal values if posted back
		if ($this->tot_bunga->FormValue == $this->tot_bunga->CurrentValue && is_numeric(ew_StrToFloat($this->tot_bunga->CurrentValue)))
			$this->tot_bunga->CurrentValue = ew_StrToFloat($this->tot_bunga->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jumlah_piutang->FormValue == $this->jumlah_piutang->CurrentValue && is_numeric(ew_StrToFloat($this->jumlah_piutang->CurrentValue)))
			$this->jumlah_piutang->CurrentValue = ew_StrToFloat($this->jumlah_piutang->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// id_kredit
		// no_kredit
		// tgl_kredit
		// emp_id_auto
		// krd_id
		// cara_hitung
		// tot_kredit
		// saldo_aw
		// suku_bunga
		// periode_bulan
		// angs_pokok
		// angs_pertama
		// tot_debet
		// tot_angs_pokok
		// tot_bunga
		// def_pembulatan
		// jumlah_piutang
		// approv_by
		// keterangan
		// status
		// status_lunas
		// lastupdate_date
		// lastupdate_user

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// id_kredit
		$this->id_kredit->ViewValue = $this->id_kredit->CurrentValue;
		$this->id_kredit->ViewCustomAttributes = "";

		// no_kredit
		$this->no_kredit->ViewValue = $this->no_kredit->CurrentValue;
		$this->no_kredit->ViewCustomAttributes = "";

		// tgl_kredit
		$this->tgl_kredit->ViewValue = $this->tgl_kredit->CurrentValue;
		$this->tgl_kredit->ViewValue = ew_FormatDateTime($this->tgl_kredit->ViewValue, 0);
		$this->tgl_kredit->ViewCustomAttributes = "";

		// emp_id_auto
		$this->emp_id_auto->ViewValue = $this->emp_id_auto->CurrentValue;
		$this->emp_id_auto->ViewCustomAttributes = "";

		// krd_id
		$this->krd_id->ViewValue = $this->krd_id->CurrentValue;
		$this->krd_id->ViewCustomAttributes = "";

		// cara_hitung
		$this->cara_hitung->ViewValue = $this->cara_hitung->CurrentValue;
		$this->cara_hitung->ViewCustomAttributes = "";

		// tot_kredit
		$this->tot_kredit->ViewValue = $this->tot_kredit->CurrentValue;
		$this->tot_kredit->ViewCustomAttributes = "";

		// saldo_aw
		$this->saldo_aw->ViewValue = $this->saldo_aw->CurrentValue;
		$this->saldo_aw->ViewCustomAttributes = "";

		// suku_bunga
		$this->suku_bunga->ViewValue = $this->suku_bunga->CurrentValue;
		$this->suku_bunga->ViewCustomAttributes = "";

		// periode_bulan
		$this->periode_bulan->ViewValue = $this->periode_bulan->CurrentValue;
		$this->periode_bulan->ViewCustomAttributes = "";

		// angs_pokok
		$this->angs_pokok->ViewValue = $this->angs_pokok->CurrentValue;
		$this->angs_pokok->ViewCustomAttributes = "";

		// angs_pertama
		$this->angs_pertama->ViewValue = $this->angs_pertama->CurrentValue;
		$this->angs_pertama->ViewValue = ew_FormatDateTime($this->angs_pertama->ViewValue, 0);
		$this->angs_pertama->ViewCustomAttributes = "";

		// tot_debet
		$this->tot_debet->ViewValue = $this->tot_debet->CurrentValue;
		$this->tot_debet->ViewCustomAttributes = "";

		// tot_angs_pokok
		$this->tot_angs_pokok->ViewValue = $this->tot_angs_pokok->CurrentValue;
		$this->tot_angs_pokok->ViewCustomAttributes = "";

		// tot_bunga
		$this->tot_bunga->ViewValue = $this->tot_bunga->CurrentValue;
		$this->tot_bunga->ViewCustomAttributes = "";

		// def_pembulatan
		$this->def_pembulatan->ViewValue = $this->def_pembulatan->CurrentValue;
		$this->def_pembulatan->ViewCustomAttributes = "";

		// jumlah_piutang
		$this->jumlah_piutang->ViewValue = $this->jumlah_piutang->CurrentValue;
		$this->jumlah_piutang->ViewCustomAttributes = "";

		// approv_by
		$this->approv_by->ViewValue = $this->approv_by->CurrentValue;
		$this->approv_by->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

		// status
		$this->status->ViewValue = $this->status->CurrentValue;
		$this->status->ViewCustomAttributes = "";

		// status_lunas
		$this->status_lunas->ViewValue = $this->status_lunas->CurrentValue;
		$this->status_lunas->ViewCustomAttributes = "";

		// lastupdate_date
		$this->lastupdate_date->ViewValue = $this->lastupdate_date->CurrentValue;
		$this->lastupdate_date->ViewValue = ew_FormatDateTime($this->lastupdate_date->ViewValue, 0);
		$this->lastupdate_date->ViewCustomAttributes = "";

		// lastupdate_user
		$this->lastupdate_user->ViewValue = $this->lastupdate_user->CurrentValue;
		$this->lastupdate_user->ViewCustomAttributes = "";

			// id_kredit
			$this->id_kredit->LinkCustomAttributes = "";
			$this->id_kredit->HrefValue = "";
			$this->id_kredit->TooltipValue = "";

			// no_kredit
			$this->no_kredit->LinkCustomAttributes = "";
			$this->no_kredit->HrefValue = "";
			$this->no_kredit->TooltipValue = "";

			// tgl_kredit
			$this->tgl_kredit->LinkCustomAttributes = "";
			$this->tgl_kredit->HrefValue = "";
			$this->tgl_kredit->TooltipValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";
			$this->emp_id_auto->TooltipValue = "";

			// krd_id
			$this->krd_id->LinkCustomAttributes = "";
			$this->krd_id->HrefValue = "";
			$this->krd_id->TooltipValue = "";

			// cara_hitung
			$this->cara_hitung->LinkCustomAttributes = "";
			$this->cara_hitung->HrefValue = "";
			$this->cara_hitung->TooltipValue = "";

			// tot_kredit
			$this->tot_kredit->LinkCustomAttributes = "";
			$this->tot_kredit->HrefValue = "";
			$this->tot_kredit->TooltipValue = "";

			// saldo_aw
			$this->saldo_aw->LinkCustomAttributes = "";
			$this->saldo_aw->HrefValue = "";
			$this->saldo_aw->TooltipValue = "";

			// suku_bunga
			$this->suku_bunga->LinkCustomAttributes = "";
			$this->suku_bunga->HrefValue = "";
			$this->suku_bunga->TooltipValue = "";

			// periode_bulan
			$this->periode_bulan->LinkCustomAttributes = "";
			$this->periode_bulan->HrefValue = "";
			$this->periode_bulan->TooltipValue = "";

			// angs_pokok
			$this->angs_pokok->LinkCustomAttributes = "";
			$this->angs_pokok->HrefValue = "";
			$this->angs_pokok->TooltipValue = "";

			// angs_pertama
			$this->angs_pertama->LinkCustomAttributes = "";
			$this->angs_pertama->HrefValue = "";
			$this->angs_pertama->TooltipValue = "";

			// tot_debet
			$this->tot_debet->LinkCustomAttributes = "";
			$this->tot_debet->HrefValue = "";
			$this->tot_debet->TooltipValue = "";

			// tot_angs_pokok
			$this->tot_angs_pokok->LinkCustomAttributes = "";
			$this->tot_angs_pokok->HrefValue = "";
			$this->tot_angs_pokok->TooltipValue = "";

			// tot_bunga
			$this->tot_bunga->LinkCustomAttributes = "";
			$this->tot_bunga->HrefValue = "";
			$this->tot_bunga->TooltipValue = "";

			// def_pembulatan
			$this->def_pembulatan->LinkCustomAttributes = "";
			$this->def_pembulatan->HrefValue = "";
			$this->def_pembulatan->TooltipValue = "";

			// jumlah_piutang
			$this->jumlah_piutang->LinkCustomAttributes = "";
			$this->jumlah_piutang->HrefValue = "";
			$this->jumlah_piutang->TooltipValue = "";

			// approv_by
			$this->approv_by->LinkCustomAttributes = "";
			$this->approv_by->HrefValue = "";
			$this->approv_by->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// status_lunas
			$this->status_lunas->LinkCustomAttributes = "";
			$this->status_lunas->HrefValue = "";
			$this->status_lunas->TooltipValue = "";

			// lastupdate_date
			$this->lastupdate_date->LinkCustomAttributes = "";
			$this->lastupdate_date->HrefValue = "";
			$this->lastupdate_date->TooltipValue = "";

			// lastupdate_user
			$this->lastupdate_user->LinkCustomAttributes = "";
			$this->lastupdate_user->HrefValue = "";
			$this->lastupdate_user->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id_kredit
			$this->id_kredit->EditAttrs["class"] = "form-control";
			$this->id_kredit->EditCustomAttributes = "";
			$this->id_kredit->EditValue = ew_HtmlEncode($this->id_kredit->CurrentValue);
			$this->id_kredit->PlaceHolder = ew_RemoveHtml($this->id_kredit->FldCaption());

			// no_kredit
			$this->no_kredit->EditAttrs["class"] = "form-control";
			$this->no_kredit->EditCustomAttributes = "";
			$this->no_kredit->EditValue = ew_HtmlEncode($this->no_kredit->CurrentValue);
			$this->no_kredit->PlaceHolder = ew_RemoveHtml($this->no_kredit->FldCaption());

			// tgl_kredit
			$this->tgl_kredit->EditAttrs["class"] = "form-control";
			$this->tgl_kredit->EditCustomAttributes = "";
			$this->tgl_kredit->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_kredit->CurrentValue, 8));
			$this->tgl_kredit->PlaceHolder = ew_RemoveHtml($this->tgl_kredit->FldCaption());

			// emp_id_auto
			$this->emp_id_auto->EditAttrs["class"] = "form-control";
			$this->emp_id_auto->EditCustomAttributes = "";
			$this->emp_id_auto->EditValue = ew_HtmlEncode($this->emp_id_auto->CurrentValue);
			$this->emp_id_auto->PlaceHolder = ew_RemoveHtml($this->emp_id_auto->FldCaption());

			// krd_id
			$this->krd_id->EditAttrs["class"] = "form-control";
			$this->krd_id->EditCustomAttributes = "";
			$this->krd_id->EditValue = ew_HtmlEncode($this->krd_id->CurrentValue);
			$this->krd_id->PlaceHolder = ew_RemoveHtml($this->krd_id->FldCaption());

			// cara_hitung
			$this->cara_hitung->EditAttrs["class"] = "form-control";
			$this->cara_hitung->EditCustomAttributes = "";
			$this->cara_hitung->EditValue = ew_HtmlEncode($this->cara_hitung->CurrentValue);
			$this->cara_hitung->PlaceHolder = ew_RemoveHtml($this->cara_hitung->FldCaption());

			// tot_kredit
			$this->tot_kredit->EditAttrs["class"] = "form-control";
			$this->tot_kredit->EditCustomAttributes = "";
			$this->tot_kredit->EditValue = ew_HtmlEncode($this->tot_kredit->CurrentValue);
			$this->tot_kredit->PlaceHolder = ew_RemoveHtml($this->tot_kredit->FldCaption());
			if (strval($this->tot_kredit->EditValue) <> "" && is_numeric($this->tot_kredit->EditValue)) $this->tot_kredit->EditValue = ew_FormatNumber($this->tot_kredit->EditValue, -2, -1, -2, 0);

			// saldo_aw
			$this->saldo_aw->EditAttrs["class"] = "form-control";
			$this->saldo_aw->EditCustomAttributes = "";
			$this->saldo_aw->EditValue = ew_HtmlEncode($this->saldo_aw->CurrentValue);
			$this->saldo_aw->PlaceHolder = ew_RemoveHtml($this->saldo_aw->FldCaption());
			if (strval($this->saldo_aw->EditValue) <> "" && is_numeric($this->saldo_aw->EditValue)) $this->saldo_aw->EditValue = ew_FormatNumber($this->saldo_aw->EditValue, -2, -1, -2, 0);

			// suku_bunga
			$this->suku_bunga->EditAttrs["class"] = "form-control";
			$this->suku_bunga->EditCustomAttributes = "";
			$this->suku_bunga->EditValue = ew_HtmlEncode($this->suku_bunga->CurrentValue);
			$this->suku_bunga->PlaceHolder = ew_RemoveHtml($this->suku_bunga->FldCaption());
			if (strval($this->suku_bunga->EditValue) <> "" && is_numeric($this->suku_bunga->EditValue)) $this->suku_bunga->EditValue = ew_FormatNumber($this->suku_bunga->EditValue, -2, -1, -2, 0);

			// periode_bulan
			$this->periode_bulan->EditAttrs["class"] = "form-control";
			$this->periode_bulan->EditCustomAttributes = "";
			$this->periode_bulan->EditValue = ew_HtmlEncode($this->periode_bulan->CurrentValue);
			$this->periode_bulan->PlaceHolder = ew_RemoveHtml($this->periode_bulan->FldCaption());

			// angs_pokok
			$this->angs_pokok->EditAttrs["class"] = "form-control";
			$this->angs_pokok->EditCustomAttributes = "";
			$this->angs_pokok->EditValue = ew_HtmlEncode($this->angs_pokok->CurrentValue);
			$this->angs_pokok->PlaceHolder = ew_RemoveHtml($this->angs_pokok->FldCaption());
			if (strval($this->angs_pokok->EditValue) <> "" && is_numeric($this->angs_pokok->EditValue)) $this->angs_pokok->EditValue = ew_FormatNumber($this->angs_pokok->EditValue, -2, -1, -2, 0);

			// angs_pertama
			$this->angs_pertama->EditAttrs["class"] = "form-control";
			$this->angs_pertama->EditCustomAttributes = "";
			$this->angs_pertama->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->angs_pertama->CurrentValue, 8));
			$this->angs_pertama->PlaceHolder = ew_RemoveHtml($this->angs_pertama->FldCaption());

			// tot_debet
			$this->tot_debet->EditAttrs["class"] = "form-control";
			$this->tot_debet->EditCustomAttributes = "";
			$this->tot_debet->EditValue = ew_HtmlEncode($this->tot_debet->CurrentValue);
			$this->tot_debet->PlaceHolder = ew_RemoveHtml($this->tot_debet->FldCaption());
			if (strval($this->tot_debet->EditValue) <> "" && is_numeric($this->tot_debet->EditValue)) $this->tot_debet->EditValue = ew_FormatNumber($this->tot_debet->EditValue, -2, -1, -2, 0);

			// tot_angs_pokok
			$this->tot_angs_pokok->EditAttrs["class"] = "form-control";
			$this->tot_angs_pokok->EditCustomAttributes = "";
			$this->tot_angs_pokok->EditValue = ew_HtmlEncode($this->tot_angs_pokok->CurrentValue);
			$this->tot_angs_pokok->PlaceHolder = ew_RemoveHtml($this->tot_angs_pokok->FldCaption());
			if (strval($this->tot_angs_pokok->EditValue) <> "" && is_numeric($this->tot_angs_pokok->EditValue)) $this->tot_angs_pokok->EditValue = ew_FormatNumber($this->tot_angs_pokok->EditValue, -2, -1, -2, 0);

			// tot_bunga
			$this->tot_bunga->EditAttrs["class"] = "form-control";
			$this->tot_bunga->EditCustomAttributes = "";
			$this->tot_bunga->EditValue = ew_HtmlEncode($this->tot_bunga->CurrentValue);
			$this->tot_bunga->PlaceHolder = ew_RemoveHtml($this->tot_bunga->FldCaption());
			if (strval($this->tot_bunga->EditValue) <> "" && is_numeric($this->tot_bunga->EditValue)) $this->tot_bunga->EditValue = ew_FormatNumber($this->tot_bunga->EditValue, -2, -1, -2, 0);

			// def_pembulatan
			$this->def_pembulatan->EditAttrs["class"] = "form-control";
			$this->def_pembulatan->EditCustomAttributes = "";
			$this->def_pembulatan->EditValue = ew_HtmlEncode($this->def_pembulatan->CurrentValue);
			$this->def_pembulatan->PlaceHolder = ew_RemoveHtml($this->def_pembulatan->FldCaption());

			// jumlah_piutang
			$this->jumlah_piutang->EditAttrs["class"] = "form-control";
			$this->jumlah_piutang->EditCustomAttributes = "";
			$this->jumlah_piutang->EditValue = ew_HtmlEncode($this->jumlah_piutang->CurrentValue);
			$this->jumlah_piutang->PlaceHolder = ew_RemoveHtml($this->jumlah_piutang->FldCaption());
			if (strval($this->jumlah_piutang->EditValue) <> "" && is_numeric($this->jumlah_piutang->EditValue)) $this->jumlah_piutang->EditValue = ew_FormatNumber($this->jumlah_piutang->EditValue, -2, -1, -2, 0);

			// approv_by
			$this->approv_by->EditAttrs["class"] = "form-control";
			$this->approv_by->EditCustomAttributes = "";
			$this->approv_by->EditValue = ew_HtmlEncode($this->approv_by->CurrentValue);
			$this->approv_by->PlaceHolder = ew_RemoveHtml($this->approv_by->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$this->status->EditValue = ew_HtmlEncode($this->status->CurrentValue);
			$this->status->PlaceHolder = ew_RemoveHtml($this->status->FldCaption());

			// status_lunas
			$this->status_lunas->EditAttrs["class"] = "form-control";
			$this->status_lunas->EditCustomAttributes = "";
			$this->status_lunas->EditValue = ew_HtmlEncode($this->status_lunas->CurrentValue);
			$this->status_lunas->PlaceHolder = ew_RemoveHtml($this->status_lunas->FldCaption());

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

			// Add refer script
			// id_kredit

			$this->id_kredit->LinkCustomAttributes = "";
			$this->id_kredit->HrefValue = "";

			// no_kredit
			$this->no_kredit->LinkCustomAttributes = "";
			$this->no_kredit->HrefValue = "";

			// tgl_kredit
			$this->tgl_kredit->LinkCustomAttributes = "";
			$this->tgl_kredit->HrefValue = "";

			// emp_id_auto
			$this->emp_id_auto->LinkCustomAttributes = "";
			$this->emp_id_auto->HrefValue = "";

			// krd_id
			$this->krd_id->LinkCustomAttributes = "";
			$this->krd_id->HrefValue = "";

			// cara_hitung
			$this->cara_hitung->LinkCustomAttributes = "";
			$this->cara_hitung->HrefValue = "";

			// tot_kredit
			$this->tot_kredit->LinkCustomAttributes = "";
			$this->tot_kredit->HrefValue = "";

			// saldo_aw
			$this->saldo_aw->LinkCustomAttributes = "";
			$this->saldo_aw->HrefValue = "";

			// suku_bunga
			$this->suku_bunga->LinkCustomAttributes = "";
			$this->suku_bunga->HrefValue = "";

			// periode_bulan
			$this->periode_bulan->LinkCustomAttributes = "";
			$this->periode_bulan->HrefValue = "";

			// angs_pokok
			$this->angs_pokok->LinkCustomAttributes = "";
			$this->angs_pokok->HrefValue = "";

			// angs_pertama
			$this->angs_pertama->LinkCustomAttributes = "";
			$this->angs_pertama->HrefValue = "";

			// tot_debet
			$this->tot_debet->LinkCustomAttributes = "";
			$this->tot_debet->HrefValue = "";

			// tot_angs_pokok
			$this->tot_angs_pokok->LinkCustomAttributes = "";
			$this->tot_angs_pokok->HrefValue = "";

			// tot_bunga
			$this->tot_bunga->LinkCustomAttributes = "";
			$this->tot_bunga->HrefValue = "";

			// def_pembulatan
			$this->def_pembulatan->LinkCustomAttributes = "";
			$this->def_pembulatan->HrefValue = "";

			// jumlah_piutang
			$this->jumlah_piutang->LinkCustomAttributes = "";
			$this->jumlah_piutang->HrefValue = "";

			// approv_by
			$this->approv_by->LinkCustomAttributes = "";
			$this->approv_by->HrefValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";

			// status_lunas
			$this->status_lunas->LinkCustomAttributes = "";
			$this->status_lunas->HrefValue = "";

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
		if (!$this->id_kredit->FldIsDetailKey && !is_null($this->id_kredit->FormValue) && $this->id_kredit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->id_kredit->FldCaption(), $this->id_kredit->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->id_kredit->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_kredit->FldErrMsg());
		}
		if (!$this->no_kredit->FldIsDetailKey && !is_null($this->no_kredit->FormValue) && $this->no_kredit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->no_kredit->FldCaption(), $this->no_kredit->ReqErrMsg));
		}
		if (!$this->tgl_kredit->FldIsDetailKey && !is_null($this->tgl_kredit->FormValue) && $this->tgl_kredit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_kredit->FldCaption(), $this->tgl_kredit->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_kredit->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_kredit->FldErrMsg());
		}
		if (!$this->emp_id_auto->FldIsDetailKey && !is_null($this->emp_id_auto->FormValue) && $this->emp_id_auto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->emp_id_auto->FldCaption(), $this->emp_id_auto->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->emp_id_auto->FormValue)) {
			ew_AddMessage($gsFormError, $this->emp_id_auto->FldErrMsg());
		}
		if (!$this->krd_id->FldIsDetailKey && !is_null($this->krd_id->FormValue) && $this->krd_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->krd_id->FldCaption(), $this->krd_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->krd_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->krd_id->FldErrMsg());
		}
		if (!$this->cara_hitung->FldIsDetailKey && !is_null($this->cara_hitung->FormValue) && $this->cara_hitung->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cara_hitung->FldCaption(), $this->cara_hitung->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cara_hitung->FormValue)) {
			ew_AddMessage($gsFormError, $this->cara_hitung->FldErrMsg());
		}
		if (!$this->tot_kredit->FldIsDetailKey && !is_null($this->tot_kredit->FormValue) && $this->tot_kredit->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tot_kredit->FldCaption(), $this->tot_kredit->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tot_kredit->FormValue)) {
			ew_AddMessage($gsFormError, $this->tot_kredit->FldErrMsg());
		}
		if (!$this->saldo_aw->FldIsDetailKey && !is_null($this->saldo_aw->FormValue) && $this->saldo_aw->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->saldo_aw->FldCaption(), $this->saldo_aw->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->saldo_aw->FormValue)) {
			ew_AddMessage($gsFormError, $this->saldo_aw->FldErrMsg());
		}
		if (!$this->suku_bunga->FldIsDetailKey && !is_null($this->suku_bunga->FormValue) && $this->suku_bunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->suku_bunga->FldCaption(), $this->suku_bunga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->suku_bunga->FormValue)) {
			ew_AddMessage($gsFormError, $this->suku_bunga->FldErrMsg());
		}
		if (!$this->periode_bulan->FldIsDetailKey && !is_null($this->periode_bulan->FormValue) && $this->periode_bulan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->periode_bulan->FldCaption(), $this->periode_bulan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->periode_bulan->FormValue)) {
			ew_AddMessage($gsFormError, $this->periode_bulan->FldErrMsg());
		}
		if (!$this->angs_pokok->FldIsDetailKey && !is_null($this->angs_pokok->FormValue) && $this->angs_pokok->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angs_pokok->FldCaption(), $this->angs_pokok->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->angs_pokok->FormValue)) {
			ew_AddMessage($gsFormError, $this->angs_pokok->FldErrMsg());
		}
		if (!$this->angs_pertama->FldIsDetailKey && !is_null($this->angs_pertama->FormValue) && $this->angs_pertama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->angs_pertama->FldCaption(), $this->angs_pertama->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->angs_pertama->FormValue)) {
			ew_AddMessage($gsFormError, $this->angs_pertama->FldErrMsg());
		}
		if (!$this->tot_debet->FldIsDetailKey && !is_null($this->tot_debet->FormValue) && $this->tot_debet->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tot_debet->FldCaption(), $this->tot_debet->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tot_debet->FormValue)) {
			ew_AddMessage($gsFormError, $this->tot_debet->FldErrMsg());
		}
		if (!$this->tot_angs_pokok->FldIsDetailKey && !is_null($this->tot_angs_pokok->FormValue) && $this->tot_angs_pokok->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tot_angs_pokok->FldCaption(), $this->tot_angs_pokok->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tot_angs_pokok->FormValue)) {
			ew_AddMessage($gsFormError, $this->tot_angs_pokok->FldErrMsg());
		}
		if (!$this->tot_bunga->FldIsDetailKey && !is_null($this->tot_bunga->FormValue) && $this->tot_bunga->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tot_bunga->FldCaption(), $this->tot_bunga->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->tot_bunga->FormValue)) {
			ew_AddMessage($gsFormError, $this->tot_bunga->FldErrMsg());
		}
		if (!$this->def_pembulatan->FldIsDetailKey && !is_null($this->def_pembulatan->FormValue) && $this->def_pembulatan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->def_pembulatan->FldCaption(), $this->def_pembulatan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->def_pembulatan->FormValue)) {
			ew_AddMessage($gsFormError, $this->def_pembulatan->FldErrMsg());
		}
		if (!$this->jumlah_piutang->FldIsDetailKey && !is_null($this->jumlah_piutang->FormValue) && $this->jumlah_piutang->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jumlah_piutang->FldCaption(), $this->jumlah_piutang->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->jumlah_piutang->FormValue)) {
			ew_AddMessage($gsFormError, $this->jumlah_piutang->FldErrMsg());
		}
		if (!$this->approv_by->FldIsDetailKey && !is_null($this->approv_by->FormValue) && $this->approv_by->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->approv_by->FldCaption(), $this->approv_by->ReqErrMsg));
		}
		if (!$this->keterangan->FldIsDetailKey && !is_null($this->keterangan->FormValue) && $this->keterangan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->keterangan->FldCaption(), $this->keterangan->ReqErrMsg));
		}
		if (!$this->status->FldIsDetailKey && !is_null($this->status->FormValue) && $this->status->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status->FldCaption(), $this->status->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->status->FormValue)) {
			ew_AddMessage($gsFormError, $this->status->FldErrMsg());
		}
		if (!$this->status_lunas->FldIsDetailKey && !is_null($this->status_lunas->FormValue) && $this->status_lunas->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status_lunas->FldCaption(), $this->status_lunas->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->status_lunas->FormValue)) {
			ew_AddMessage($gsFormError, $this->status_lunas->FldErrMsg());
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

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// id_kredit
		$this->id_kredit->SetDbValueDef($rsnew, $this->id_kredit->CurrentValue, 0, FALSE);

		// no_kredit
		$this->no_kredit->SetDbValueDef($rsnew, $this->no_kredit->CurrentValue, "", FALSE);

		// tgl_kredit
		$this->tgl_kredit->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_kredit->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// emp_id_auto
		$this->emp_id_auto->SetDbValueDef($rsnew, $this->emp_id_auto->CurrentValue, 0, FALSE);

		// krd_id
		$this->krd_id->SetDbValueDef($rsnew, $this->krd_id->CurrentValue, 0, FALSE);

		// cara_hitung
		$this->cara_hitung->SetDbValueDef($rsnew, $this->cara_hitung->CurrentValue, 0, strval($this->cara_hitung->CurrentValue) == "");

		// tot_kredit
		$this->tot_kredit->SetDbValueDef($rsnew, $this->tot_kredit->CurrentValue, 0, FALSE);

		// saldo_aw
		$this->saldo_aw->SetDbValueDef($rsnew, $this->saldo_aw->CurrentValue, 0, FALSE);

		// suku_bunga
		$this->suku_bunga->SetDbValueDef($rsnew, $this->suku_bunga->CurrentValue, 0, FALSE);

		// periode_bulan
		$this->periode_bulan->SetDbValueDef($rsnew, $this->periode_bulan->CurrentValue, 0, FALSE);

		// angs_pokok
		$this->angs_pokok->SetDbValueDef($rsnew, $this->angs_pokok->CurrentValue, 0, FALSE);

		// angs_pertama
		$this->angs_pertama->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->angs_pertama->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// tot_debet
		$this->tot_debet->SetDbValueDef($rsnew, $this->tot_debet->CurrentValue, 0, FALSE);

		// tot_angs_pokok
		$this->tot_angs_pokok->SetDbValueDef($rsnew, $this->tot_angs_pokok->CurrentValue, 0, FALSE);

		// tot_bunga
		$this->tot_bunga->SetDbValueDef($rsnew, $this->tot_bunga->CurrentValue, 0, FALSE);

		// def_pembulatan
		$this->def_pembulatan->SetDbValueDef($rsnew, $this->def_pembulatan->CurrentValue, 0, FALSE);

		// jumlah_piutang
		$this->jumlah_piutang->SetDbValueDef($rsnew, $this->jumlah_piutang->CurrentValue, 0, FALSE);

		// approv_by
		$this->approv_by->SetDbValueDef($rsnew, $this->approv_by->CurrentValue, "", FALSE);

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, "", FALSE);

		// status
		$this->status->SetDbValueDef($rsnew, $this->status->CurrentValue, 0, strval($this->status->CurrentValue) == "");

		// status_lunas
		$this->status_lunas->SetDbValueDef($rsnew, $this->status_lunas->CurrentValue, 0, strval($this->status_lunas->CurrentValue) == "");

		// lastupdate_date
		$this->lastupdate_date->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->lastupdate_date->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// lastupdate_user
		$this->lastupdate_user->SetDbValueDef($rsnew, $this->lastupdate_user->CurrentValue, "", FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['id_kredit']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("zx_kredit_mlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($zx_kredit_m_add)) $zx_kredit_m_add = new czx_kredit_m_add();

// Page init
$zx_kredit_m_add->Page_Init();

// Page main
$zx_kredit_m_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_kredit_m_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fzx_kredit_madd = new ew_Form("fzx_kredit_madd", "add");

// Validate form
fzx_kredit_madd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_id_kredit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->id_kredit->FldCaption(), $zx_kredit_m->id_kredit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_id_kredit");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->id_kredit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_no_kredit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->no_kredit->FldCaption(), $zx_kredit_m->no_kredit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_kredit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->tgl_kredit->FldCaption(), $zx_kredit_m->tgl_kredit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_kredit");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->tgl_kredit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->emp_id_auto->FldCaption(), $zx_kredit_m->emp_id_auto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_emp_id_auto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->emp_id_auto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_krd_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->krd_id->FldCaption(), $zx_kredit_m->krd_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_krd_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->krd_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cara_hitung");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->cara_hitung->FldCaption(), $zx_kredit_m->cara_hitung->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cara_hitung");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->cara_hitung->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tot_kredit");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->tot_kredit->FldCaption(), $zx_kredit_m->tot_kredit->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tot_kredit");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->tot_kredit->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_saldo_aw");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->saldo_aw->FldCaption(), $zx_kredit_m->saldo_aw->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_saldo_aw");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->saldo_aw->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_suku_bunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->suku_bunga->FldCaption(), $zx_kredit_m->suku_bunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_suku_bunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->suku_bunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_periode_bulan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->periode_bulan->FldCaption(), $zx_kredit_m->periode_bulan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_periode_bulan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->periode_bulan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angs_pokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->angs_pokok->FldCaption(), $zx_kredit_m->angs_pokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angs_pokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->angs_pokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_angs_pertama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->angs_pertama->FldCaption(), $zx_kredit_m->angs_pertama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_angs_pertama");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->angs_pertama->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tot_debet");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->tot_debet->FldCaption(), $zx_kredit_m->tot_debet->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tot_debet");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->tot_debet->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tot_angs_pokok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->tot_angs_pokok->FldCaption(), $zx_kredit_m->tot_angs_pokok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tot_angs_pokok");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->tot_angs_pokok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tot_bunga");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->tot_bunga->FldCaption(), $zx_kredit_m->tot_bunga->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tot_bunga");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->tot_bunga->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_def_pembulatan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->def_pembulatan->FldCaption(), $zx_kredit_m->def_pembulatan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_def_pembulatan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->def_pembulatan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_piutang");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->jumlah_piutang->FldCaption(), $zx_kredit_m->jumlah_piutang->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jumlah_piutang");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->jumlah_piutang->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_approv_by");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->approv_by->FldCaption(), $zx_kredit_m->approv_by->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_keterangan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->keterangan->FldCaption(), $zx_kredit_m->keterangan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->status->FldCaption(), $zx_kredit_m->status->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->status->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status_lunas");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->status_lunas->FldCaption(), $zx_kredit_m->status_lunas->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status_lunas");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->status_lunas->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->lastupdate_date->FldCaption(), $zx_kredit_m->lastupdate_date->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_date");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zx_kredit_m->lastupdate_date->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_lastupdate_user");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zx_kredit_m->lastupdate_user->FldCaption(), $zx_kredit_m->lastupdate_user->ReqErrMsg)) ?>");

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
fzx_kredit_madd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_kredit_madd.ValidateRequired = true;
<?php } else { ?>
fzx_kredit_madd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$zx_kredit_m_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $zx_kredit_m_add->ShowPageHeader(); ?>
<?php
$zx_kredit_m_add->ShowMessage();
?>
<form name="fzx_kredit_madd" id="fzx_kredit_madd" class="<?php echo $zx_kredit_m_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_kredit_m_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_kredit_m_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_kredit_m">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($zx_kredit_m_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($zx_kredit_m->id_kredit->Visible) { // id_kredit ?>
	<div id="r_id_kredit" class="form-group">
		<label id="elh_zx_kredit_m_id_kredit" for="x_id_kredit" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->id_kredit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->id_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_id_kredit">
<input type="text" data-table="zx_kredit_m" data-field="x_id_kredit" name="x_id_kredit" id="x_id_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->id_kredit->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->id_kredit->EditValue ?>"<?php echo $zx_kredit_m->id_kredit->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->id_kredit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->no_kredit->Visible) { // no_kredit ?>
	<div id="r_no_kredit" class="form-group">
		<label id="elh_zx_kredit_m_no_kredit" for="x_no_kredit" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->no_kredit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->no_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_no_kredit">
<input type="text" data-table="zx_kredit_m" data-field="x_no_kredit" name="x_no_kredit" id="x_no_kredit" size="30" maxlength="100" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->no_kredit->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->no_kredit->EditValue ?>"<?php echo $zx_kredit_m->no_kredit->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->no_kredit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->tgl_kredit->Visible) { // tgl_kredit ?>
	<div id="r_tgl_kredit" class="form-group">
		<label id="elh_zx_kredit_m_tgl_kredit" for="x_tgl_kredit" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->tgl_kredit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->tgl_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_tgl_kredit">
<input type="text" data-table="zx_kredit_m" data-field="x_tgl_kredit" name="x_tgl_kredit" id="x_tgl_kredit" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->tgl_kredit->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->tgl_kredit->EditValue ?>"<?php echo $zx_kredit_m->tgl_kredit->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->tgl_kredit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->emp_id_auto->Visible) { // emp_id_auto ?>
	<div id="r_emp_id_auto" class="form-group">
		<label id="elh_zx_kredit_m_emp_id_auto" for="x_emp_id_auto" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->emp_id_auto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->emp_id_auto->CellAttributes() ?>>
<span id="el_zx_kredit_m_emp_id_auto">
<input type="text" data-table="zx_kredit_m" data-field="x_emp_id_auto" name="x_emp_id_auto" id="x_emp_id_auto" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->emp_id_auto->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->emp_id_auto->EditValue ?>"<?php echo $zx_kredit_m->emp_id_auto->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->emp_id_auto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->krd_id->Visible) { // krd_id ?>
	<div id="r_krd_id" class="form-group">
		<label id="elh_zx_kredit_m_krd_id" for="x_krd_id" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->krd_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->krd_id->CellAttributes() ?>>
<span id="el_zx_kredit_m_krd_id">
<input type="text" data-table="zx_kredit_m" data-field="x_krd_id" name="x_krd_id" id="x_krd_id" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->krd_id->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->krd_id->EditValue ?>"<?php echo $zx_kredit_m->krd_id->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->krd_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->cara_hitung->Visible) { // cara_hitung ?>
	<div id="r_cara_hitung" class="form-group">
		<label id="elh_zx_kredit_m_cara_hitung" for="x_cara_hitung" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->cara_hitung->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->cara_hitung->CellAttributes() ?>>
<span id="el_zx_kredit_m_cara_hitung">
<input type="text" data-table="zx_kredit_m" data-field="x_cara_hitung" name="x_cara_hitung" id="x_cara_hitung" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->cara_hitung->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->cara_hitung->EditValue ?>"<?php echo $zx_kredit_m->cara_hitung->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->cara_hitung->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->tot_kredit->Visible) { // tot_kredit ?>
	<div id="r_tot_kredit" class="form-group">
		<label id="elh_zx_kredit_m_tot_kredit" for="x_tot_kredit" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->tot_kredit->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->tot_kredit->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_kredit">
<input type="text" data-table="zx_kredit_m" data-field="x_tot_kredit" name="x_tot_kredit" id="x_tot_kredit" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->tot_kredit->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->tot_kredit->EditValue ?>"<?php echo $zx_kredit_m->tot_kredit->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->tot_kredit->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->saldo_aw->Visible) { // saldo_aw ?>
	<div id="r_saldo_aw" class="form-group">
		<label id="elh_zx_kredit_m_saldo_aw" for="x_saldo_aw" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->saldo_aw->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->saldo_aw->CellAttributes() ?>>
<span id="el_zx_kredit_m_saldo_aw">
<input type="text" data-table="zx_kredit_m" data-field="x_saldo_aw" name="x_saldo_aw" id="x_saldo_aw" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->saldo_aw->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->saldo_aw->EditValue ?>"<?php echo $zx_kredit_m->saldo_aw->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->saldo_aw->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->suku_bunga->Visible) { // suku_bunga ?>
	<div id="r_suku_bunga" class="form-group">
		<label id="elh_zx_kredit_m_suku_bunga" for="x_suku_bunga" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->suku_bunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->suku_bunga->CellAttributes() ?>>
<span id="el_zx_kredit_m_suku_bunga">
<input type="text" data-table="zx_kredit_m" data-field="x_suku_bunga" name="x_suku_bunga" id="x_suku_bunga" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->suku_bunga->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->suku_bunga->EditValue ?>"<?php echo $zx_kredit_m->suku_bunga->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->suku_bunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->periode_bulan->Visible) { // periode_bulan ?>
	<div id="r_periode_bulan" class="form-group">
		<label id="elh_zx_kredit_m_periode_bulan" for="x_periode_bulan" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->periode_bulan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->periode_bulan->CellAttributes() ?>>
<span id="el_zx_kredit_m_periode_bulan">
<input type="text" data-table="zx_kredit_m" data-field="x_periode_bulan" name="x_periode_bulan" id="x_periode_bulan" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->periode_bulan->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->periode_bulan->EditValue ?>"<?php echo $zx_kredit_m->periode_bulan->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->periode_bulan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->angs_pokok->Visible) { // angs_pokok ?>
	<div id="r_angs_pokok" class="form-group">
		<label id="elh_zx_kredit_m_angs_pokok" for="x_angs_pokok" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->angs_pokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->angs_pokok->CellAttributes() ?>>
<span id="el_zx_kredit_m_angs_pokok">
<input type="text" data-table="zx_kredit_m" data-field="x_angs_pokok" name="x_angs_pokok" id="x_angs_pokok" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->angs_pokok->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->angs_pokok->EditValue ?>"<?php echo $zx_kredit_m->angs_pokok->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->angs_pokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->angs_pertama->Visible) { // angs_pertama ?>
	<div id="r_angs_pertama" class="form-group">
		<label id="elh_zx_kredit_m_angs_pertama" for="x_angs_pertama" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->angs_pertama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->angs_pertama->CellAttributes() ?>>
<span id="el_zx_kredit_m_angs_pertama">
<input type="text" data-table="zx_kredit_m" data-field="x_angs_pertama" name="x_angs_pertama" id="x_angs_pertama" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->angs_pertama->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->angs_pertama->EditValue ?>"<?php echo $zx_kredit_m->angs_pertama->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->angs_pertama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->tot_debet->Visible) { // tot_debet ?>
	<div id="r_tot_debet" class="form-group">
		<label id="elh_zx_kredit_m_tot_debet" for="x_tot_debet" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->tot_debet->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->tot_debet->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_debet">
<input type="text" data-table="zx_kredit_m" data-field="x_tot_debet" name="x_tot_debet" id="x_tot_debet" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->tot_debet->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->tot_debet->EditValue ?>"<?php echo $zx_kredit_m->tot_debet->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->tot_debet->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->tot_angs_pokok->Visible) { // tot_angs_pokok ?>
	<div id="r_tot_angs_pokok" class="form-group">
		<label id="elh_zx_kredit_m_tot_angs_pokok" for="x_tot_angs_pokok" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->tot_angs_pokok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->tot_angs_pokok->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_angs_pokok">
<input type="text" data-table="zx_kredit_m" data-field="x_tot_angs_pokok" name="x_tot_angs_pokok" id="x_tot_angs_pokok" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->tot_angs_pokok->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->tot_angs_pokok->EditValue ?>"<?php echo $zx_kredit_m->tot_angs_pokok->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->tot_angs_pokok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->tot_bunga->Visible) { // tot_bunga ?>
	<div id="r_tot_bunga" class="form-group">
		<label id="elh_zx_kredit_m_tot_bunga" for="x_tot_bunga" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->tot_bunga->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->tot_bunga->CellAttributes() ?>>
<span id="el_zx_kredit_m_tot_bunga">
<input type="text" data-table="zx_kredit_m" data-field="x_tot_bunga" name="x_tot_bunga" id="x_tot_bunga" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->tot_bunga->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->tot_bunga->EditValue ?>"<?php echo $zx_kredit_m->tot_bunga->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->tot_bunga->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->def_pembulatan->Visible) { // def_pembulatan ?>
	<div id="r_def_pembulatan" class="form-group">
		<label id="elh_zx_kredit_m_def_pembulatan" for="x_def_pembulatan" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->def_pembulatan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->def_pembulatan->CellAttributes() ?>>
<span id="el_zx_kredit_m_def_pembulatan">
<input type="text" data-table="zx_kredit_m" data-field="x_def_pembulatan" name="x_def_pembulatan" id="x_def_pembulatan" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->def_pembulatan->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->def_pembulatan->EditValue ?>"<?php echo $zx_kredit_m->def_pembulatan->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->def_pembulatan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->jumlah_piutang->Visible) { // jumlah_piutang ?>
	<div id="r_jumlah_piutang" class="form-group">
		<label id="elh_zx_kredit_m_jumlah_piutang" for="x_jumlah_piutang" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->jumlah_piutang->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->jumlah_piutang->CellAttributes() ?>>
<span id="el_zx_kredit_m_jumlah_piutang">
<input type="text" data-table="zx_kredit_m" data-field="x_jumlah_piutang" name="x_jumlah_piutang" id="x_jumlah_piutang" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->jumlah_piutang->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->jumlah_piutang->EditValue ?>"<?php echo $zx_kredit_m->jumlah_piutang->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->jumlah_piutang->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->approv_by->Visible) { // approv_by ?>
	<div id="r_approv_by" class="form-group">
		<label id="elh_zx_kredit_m_approv_by" for="x_approv_by" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->approv_by->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->approv_by->CellAttributes() ?>>
<span id="el_zx_kredit_m_approv_by">
<input type="text" data-table="zx_kredit_m" data-field="x_approv_by" name="x_approv_by" id="x_approv_by" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->approv_by->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->approv_by->EditValue ?>"<?php echo $zx_kredit_m->approv_by->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->approv_by->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_zx_kredit_m_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->keterangan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->keterangan->CellAttributes() ?>>
<span id="el_zx_kredit_m_keterangan">
<textarea data-table="zx_kredit_m" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->keterangan->getPlaceHolder()) ?>"<?php echo $zx_kredit_m->keterangan->EditAttributes() ?>><?php echo $zx_kredit_m->keterangan->EditValue ?></textarea>
</span>
<?php echo $zx_kredit_m->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->status->Visible) { // status ?>
	<div id="r_status" class="form-group">
		<label id="elh_zx_kredit_m_status" for="x_status" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->status->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->status->CellAttributes() ?>>
<span id="el_zx_kredit_m_status">
<input type="text" data-table="zx_kredit_m" data-field="x_status" name="x_status" id="x_status" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->status->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->status->EditValue ?>"<?php echo $zx_kredit_m->status->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->status->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->status_lunas->Visible) { // status_lunas ?>
	<div id="r_status_lunas" class="form-group">
		<label id="elh_zx_kredit_m_status_lunas" for="x_status_lunas" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->status_lunas->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->status_lunas->CellAttributes() ?>>
<span id="el_zx_kredit_m_status_lunas">
<input type="text" data-table="zx_kredit_m" data-field="x_status_lunas" name="x_status_lunas" id="x_status_lunas" size="30" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->status_lunas->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->status_lunas->EditValue ?>"<?php echo $zx_kredit_m->status_lunas->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->status_lunas->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_date->Visible) { // lastupdate_date ?>
	<div id="r_lastupdate_date" class="form-group">
		<label id="elh_zx_kredit_m_lastupdate_date" for="x_lastupdate_date" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->lastupdate_date->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->lastupdate_date->CellAttributes() ?>>
<span id="el_zx_kredit_m_lastupdate_date">
<input type="text" data-table="zx_kredit_m" data-field="x_lastupdate_date" name="x_lastupdate_date" id="x_lastupdate_date" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->lastupdate_date->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->lastupdate_date->EditValue ?>"<?php echo $zx_kredit_m->lastupdate_date->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->lastupdate_date->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_user->Visible) { // lastupdate_user ?>
	<div id="r_lastupdate_user" class="form-group">
		<label id="elh_zx_kredit_m_lastupdate_user" for="x_lastupdate_user" class="col-sm-2 control-label ewLabel"><?php echo $zx_kredit_m->lastupdate_user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $zx_kredit_m->lastupdate_user->CellAttributes() ?>>
<span id="el_zx_kredit_m_lastupdate_user">
<input type="text" data-table="zx_kredit_m" data-field="x_lastupdate_user" name="x_lastupdate_user" id="x_lastupdate_user" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($zx_kredit_m->lastupdate_user->getPlaceHolder()) ?>" value="<?php echo $zx_kredit_m->lastupdate_user->EditValue ?>"<?php echo $zx_kredit_m->lastupdate_user->EditAttributes() ?>>
</span>
<?php echo $zx_kredit_m->lastupdate_user->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$zx_kredit_m_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $zx_kredit_m_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fzx_kredit_madd.Init();
</script>
<?php
$zx_kredit_m_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$zx_kredit_m_add->Page_Terminate();
?>
