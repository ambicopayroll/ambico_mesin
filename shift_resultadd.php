<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "shift_resultinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$shift_result_add = NULL; // Initialize page object first

class cshift_result_add extends cshift_result {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'shift_result';

	// Page object name
	var $PageObjName = 'shift_result_add';

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

		// Table object (shift_result)
		if (!isset($GLOBALS["shift_result"]) || get_class($GLOBALS["shift_result"]) == "cshift_result") {
			$GLOBALS["shift_result"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["shift_result"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'shift_result', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("shift_resultlist.php"));
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
		$this->tgl_shift->SetVisibility();
		$this->khusus_lembur->SetVisibility();
		$this->khusus_extra->SetVisibility();
		$this->temp_id_auto->SetVisibility();
		$this->jdw_kerja_m_id->SetVisibility();
		$this->jk_id->SetVisibility();
		$this->jns_dok->SetVisibility();
		$this->izin_jenis_id->SetVisibility();
		$this->cuti_n_id->SetVisibility();
		$this->libur_umum->SetVisibility();
		$this->libur_rutin->SetVisibility();
		$this->jk_ot->SetVisibility();
		$this->scan_in->SetVisibility();
		$this->att_id_in->SetVisibility();
		$this->late_permission->SetVisibility();
		$this->late_minute->SetVisibility();
		$this->late->SetVisibility();
		$this->break_out->SetVisibility();
		$this->att_id_break1->SetVisibility();
		$this->break_in->SetVisibility();
		$this->att_id_break2->SetVisibility();
		$this->break_minute->SetVisibility();
		$this->break->SetVisibility();
		$this->break_ot_minute->SetVisibility();
		$this->break_ot->SetVisibility();
		$this->early_permission->SetVisibility();
		$this->early_minute->SetVisibility();
		$this->early->SetVisibility();
		$this->scan_out->SetVisibility();
		$this->att_id_out->SetVisibility();
		$this->durasi_minute->SetVisibility();
		$this->durasi->SetVisibility();
		$this->durasi_eot_minute->SetVisibility();
		$this->jk_count_as->SetVisibility();
		$this->status_jk->SetVisibility();
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
		global $EW_EXPORT, $shift_result;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($shift_result);
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
			if (@$_GET["tgl_shift"] != "") {
				$this->tgl_shift->setQueryStringValue($_GET["tgl_shift"]);
				$this->setKey("tgl_shift", $this->tgl_shift->CurrentValue); // Set up key
			} else {
				$this->setKey("tgl_shift", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["khusus_lembur"] != "") {
				$this->khusus_lembur->setQueryStringValue($_GET["khusus_lembur"]);
				$this->setKey("khusus_lembur", $this->khusus_lembur->CurrentValue); // Set up key
			} else {
				$this->setKey("khusus_lembur", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["khusus_extra"] != "") {
				$this->khusus_extra->setQueryStringValue($_GET["khusus_extra"]);
				$this->setKey("khusus_extra", $this->khusus_extra->CurrentValue); // Set up key
			} else {
				$this->setKey("khusus_extra", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["temp_id_auto"] != "") {
				$this->temp_id_auto->setQueryStringValue($_GET["temp_id_auto"]);
				$this->setKey("temp_id_auto", $this->temp_id_auto->CurrentValue); // Set up key
			} else {
				$this->setKey("temp_id_auto", ""); // Clear key
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
					$this->Page_Terminate("shift_resultlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "shift_resultlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "shift_resultview.php")
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
		$this->tgl_shift->CurrentValue = NULL;
		$this->tgl_shift->OldValue = $this->tgl_shift->CurrentValue;
		$this->khusus_lembur->CurrentValue = 0;
		$this->khusus_extra->CurrentValue = 0;
		$this->temp_id_auto->CurrentValue = 0;
		$this->jdw_kerja_m_id->CurrentValue = 0;
		$this->jk_id->CurrentValue = 0;
		$this->jns_dok->CurrentValue = 0;
		$this->izin_jenis_id->CurrentValue = 0;
		$this->cuti_n_id->CurrentValue = 0;
		$this->libur_umum->CurrentValue = 0;
		$this->libur_rutin->CurrentValue = 0;
		$this->jk_ot->CurrentValue = 0;
		$this->scan_in->CurrentValue = NULL;
		$this->scan_in->OldValue = $this->scan_in->CurrentValue;
		$this->att_id_in->CurrentValue = "0";
		$this->late_permission->CurrentValue = 0;
		$this->late_minute->CurrentValue = 0;
		$this->late->CurrentValue = 0;
		$this->break_out->CurrentValue = NULL;
		$this->break_out->OldValue = $this->break_out->CurrentValue;
		$this->att_id_break1->CurrentValue = "0";
		$this->break_in->CurrentValue = NULL;
		$this->break_in->OldValue = $this->break_in->CurrentValue;
		$this->att_id_break2->CurrentValue = "0";
		$this->break_minute->CurrentValue = 0;
		$this->break->CurrentValue = 0;
		$this->break_ot_minute->CurrentValue = 0;
		$this->break_ot->CurrentValue = 0;
		$this->early_permission->CurrentValue = 0;
		$this->early_minute->CurrentValue = 0;
		$this->early->CurrentValue = 0;
		$this->scan_out->CurrentValue = NULL;
		$this->scan_out->OldValue = $this->scan_out->CurrentValue;
		$this->att_id_out->CurrentValue = "0";
		$this->durasi_minute->CurrentValue = 0;
		$this->durasi->CurrentValue = 0;
		$this->durasi_eot_minute->CurrentValue = 0;
		$this->jk_count_as->CurrentValue = 0;
		$this->status_jk->CurrentValue = 0;
		$this->keterangan->CurrentValue = NULL;
		$this->keterangan->OldValue = $this->keterangan->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->tgl_shift->FldIsDetailKey) {
			$this->tgl_shift->setFormValue($objForm->GetValue("x_tgl_shift"));
			$this->tgl_shift->CurrentValue = ew_UnFormatDateTime($this->tgl_shift->CurrentValue, 0);
		}
		if (!$this->khusus_lembur->FldIsDetailKey) {
			$this->khusus_lembur->setFormValue($objForm->GetValue("x_khusus_lembur"));
		}
		if (!$this->khusus_extra->FldIsDetailKey) {
			$this->khusus_extra->setFormValue($objForm->GetValue("x_khusus_extra"));
		}
		if (!$this->temp_id_auto->FldIsDetailKey) {
			$this->temp_id_auto->setFormValue($objForm->GetValue("x_temp_id_auto"));
		}
		if (!$this->jdw_kerja_m_id->FldIsDetailKey) {
			$this->jdw_kerja_m_id->setFormValue($objForm->GetValue("x_jdw_kerja_m_id"));
		}
		if (!$this->jk_id->FldIsDetailKey) {
			$this->jk_id->setFormValue($objForm->GetValue("x_jk_id"));
		}
		if (!$this->jns_dok->FldIsDetailKey) {
			$this->jns_dok->setFormValue($objForm->GetValue("x_jns_dok"));
		}
		if (!$this->izin_jenis_id->FldIsDetailKey) {
			$this->izin_jenis_id->setFormValue($objForm->GetValue("x_izin_jenis_id"));
		}
		if (!$this->cuti_n_id->FldIsDetailKey) {
			$this->cuti_n_id->setFormValue($objForm->GetValue("x_cuti_n_id"));
		}
		if (!$this->libur_umum->FldIsDetailKey) {
			$this->libur_umum->setFormValue($objForm->GetValue("x_libur_umum"));
		}
		if (!$this->libur_rutin->FldIsDetailKey) {
			$this->libur_rutin->setFormValue($objForm->GetValue("x_libur_rutin"));
		}
		if (!$this->jk_ot->FldIsDetailKey) {
			$this->jk_ot->setFormValue($objForm->GetValue("x_jk_ot"));
		}
		if (!$this->scan_in->FldIsDetailKey) {
			$this->scan_in->setFormValue($objForm->GetValue("x_scan_in"));
			$this->scan_in->CurrentValue = ew_UnFormatDateTime($this->scan_in->CurrentValue, 0);
		}
		if (!$this->att_id_in->FldIsDetailKey) {
			$this->att_id_in->setFormValue($objForm->GetValue("x_att_id_in"));
		}
		if (!$this->late_permission->FldIsDetailKey) {
			$this->late_permission->setFormValue($objForm->GetValue("x_late_permission"));
		}
		if (!$this->late_minute->FldIsDetailKey) {
			$this->late_minute->setFormValue($objForm->GetValue("x_late_minute"));
		}
		if (!$this->late->FldIsDetailKey) {
			$this->late->setFormValue($objForm->GetValue("x_late"));
		}
		if (!$this->break_out->FldIsDetailKey) {
			$this->break_out->setFormValue($objForm->GetValue("x_break_out"));
			$this->break_out->CurrentValue = ew_UnFormatDateTime($this->break_out->CurrentValue, 0);
		}
		if (!$this->att_id_break1->FldIsDetailKey) {
			$this->att_id_break1->setFormValue($objForm->GetValue("x_att_id_break1"));
		}
		if (!$this->break_in->FldIsDetailKey) {
			$this->break_in->setFormValue($objForm->GetValue("x_break_in"));
			$this->break_in->CurrentValue = ew_UnFormatDateTime($this->break_in->CurrentValue, 0);
		}
		if (!$this->att_id_break2->FldIsDetailKey) {
			$this->att_id_break2->setFormValue($objForm->GetValue("x_att_id_break2"));
		}
		if (!$this->break_minute->FldIsDetailKey) {
			$this->break_minute->setFormValue($objForm->GetValue("x_break_minute"));
		}
		if (!$this->break->FldIsDetailKey) {
			$this->break->setFormValue($objForm->GetValue("x_break"));
		}
		if (!$this->break_ot_minute->FldIsDetailKey) {
			$this->break_ot_minute->setFormValue($objForm->GetValue("x_break_ot_minute"));
		}
		if (!$this->break_ot->FldIsDetailKey) {
			$this->break_ot->setFormValue($objForm->GetValue("x_break_ot"));
		}
		if (!$this->early_permission->FldIsDetailKey) {
			$this->early_permission->setFormValue($objForm->GetValue("x_early_permission"));
		}
		if (!$this->early_minute->FldIsDetailKey) {
			$this->early_minute->setFormValue($objForm->GetValue("x_early_minute"));
		}
		if (!$this->early->FldIsDetailKey) {
			$this->early->setFormValue($objForm->GetValue("x_early"));
		}
		if (!$this->scan_out->FldIsDetailKey) {
			$this->scan_out->setFormValue($objForm->GetValue("x_scan_out"));
			$this->scan_out->CurrentValue = ew_UnFormatDateTime($this->scan_out->CurrentValue, 0);
		}
		if (!$this->att_id_out->FldIsDetailKey) {
			$this->att_id_out->setFormValue($objForm->GetValue("x_att_id_out"));
		}
		if (!$this->durasi_minute->FldIsDetailKey) {
			$this->durasi_minute->setFormValue($objForm->GetValue("x_durasi_minute"));
		}
		if (!$this->durasi->FldIsDetailKey) {
			$this->durasi->setFormValue($objForm->GetValue("x_durasi"));
		}
		if (!$this->durasi_eot_minute->FldIsDetailKey) {
			$this->durasi_eot_minute->setFormValue($objForm->GetValue("x_durasi_eot_minute"));
		}
		if (!$this->jk_count_as->FldIsDetailKey) {
			$this->jk_count_as->setFormValue($objForm->GetValue("x_jk_count_as"));
		}
		if (!$this->status_jk->FldIsDetailKey) {
			$this->status_jk->setFormValue($objForm->GetValue("x_status_jk"));
		}
		if (!$this->keterangan->FldIsDetailKey) {
			$this->keterangan->setFormValue($objForm->GetValue("x_keterangan"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->tgl_shift->CurrentValue = $this->tgl_shift->FormValue;
		$this->tgl_shift->CurrentValue = ew_UnFormatDateTime($this->tgl_shift->CurrentValue, 0);
		$this->khusus_lembur->CurrentValue = $this->khusus_lembur->FormValue;
		$this->khusus_extra->CurrentValue = $this->khusus_extra->FormValue;
		$this->temp_id_auto->CurrentValue = $this->temp_id_auto->FormValue;
		$this->jdw_kerja_m_id->CurrentValue = $this->jdw_kerja_m_id->FormValue;
		$this->jk_id->CurrentValue = $this->jk_id->FormValue;
		$this->jns_dok->CurrentValue = $this->jns_dok->FormValue;
		$this->izin_jenis_id->CurrentValue = $this->izin_jenis_id->FormValue;
		$this->cuti_n_id->CurrentValue = $this->cuti_n_id->FormValue;
		$this->libur_umum->CurrentValue = $this->libur_umum->FormValue;
		$this->libur_rutin->CurrentValue = $this->libur_rutin->FormValue;
		$this->jk_ot->CurrentValue = $this->jk_ot->FormValue;
		$this->scan_in->CurrentValue = $this->scan_in->FormValue;
		$this->scan_in->CurrentValue = ew_UnFormatDateTime($this->scan_in->CurrentValue, 0);
		$this->att_id_in->CurrentValue = $this->att_id_in->FormValue;
		$this->late_permission->CurrentValue = $this->late_permission->FormValue;
		$this->late_minute->CurrentValue = $this->late_minute->FormValue;
		$this->late->CurrentValue = $this->late->FormValue;
		$this->break_out->CurrentValue = $this->break_out->FormValue;
		$this->break_out->CurrentValue = ew_UnFormatDateTime($this->break_out->CurrentValue, 0);
		$this->att_id_break1->CurrentValue = $this->att_id_break1->FormValue;
		$this->break_in->CurrentValue = $this->break_in->FormValue;
		$this->break_in->CurrentValue = ew_UnFormatDateTime($this->break_in->CurrentValue, 0);
		$this->att_id_break2->CurrentValue = $this->att_id_break2->FormValue;
		$this->break_minute->CurrentValue = $this->break_minute->FormValue;
		$this->break->CurrentValue = $this->break->FormValue;
		$this->break_ot_minute->CurrentValue = $this->break_ot_minute->FormValue;
		$this->break_ot->CurrentValue = $this->break_ot->FormValue;
		$this->early_permission->CurrentValue = $this->early_permission->FormValue;
		$this->early_minute->CurrentValue = $this->early_minute->FormValue;
		$this->early->CurrentValue = $this->early->FormValue;
		$this->scan_out->CurrentValue = $this->scan_out->FormValue;
		$this->scan_out->CurrentValue = ew_UnFormatDateTime($this->scan_out->CurrentValue, 0);
		$this->att_id_out->CurrentValue = $this->att_id_out->FormValue;
		$this->durasi_minute->CurrentValue = $this->durasi_minute->FormValue;
		$this->durasi->CurrentValue = $this->durasi->FormValue;
		$this->durasi_eot_minute->CurrentValue = $this->durasi_eot_minute->FormValue;
		$this->jk_count_as->CurrentValue = $this->jk_count_as->FormValue;
		$this->status_jk->CurrentValue = $this->status_jk->FormValue;
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
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->tgl_shift->setDbValue($rs->fields('tgl_shift'));
		$this->khusus_lembur->setDbValue($rs->fields('khusus_lembur'));
		$this->khusus_extra->setDbValue($rs->fields('khusus_extra'));
		$this->temp_id_auto->setDbValue($rs->fields('temp_id_auto'));
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->jk_id->setDbValue($rs->fields('jk_id'));
		$this->jns_dok->setDbValue($rs->fields('jns_dok'));
		$this->izin_jenis_id->setDbValue($rs->fields('izin_jenis_id'));
		$this->cuti_n_id->setDbValue($rs->fields('cuti_n_id'));
		$this->libur_umum->setDbValue($rs->fields('libur_umum'));
		$this->libur_rutin->setDbValue($rs->fields('libur_rutin'));
		$this->jk_ot->setDbValue($rs->fields('jk_ot'));
		$this->scan_in->setDbValue($rs->fields('scan_in'));
		$this->att_id_in->setDbValue($rs->fields('att_id_in'));
		$this->late_permission->setDbValue($rs->fields('late_permission'));
		$this->late_minute->setDbValue($rs->fields('late_minute'));
		$this->late->setDbValue($rs->fields('late'));
		$this->break_out->setDbValue($rs->fields('break_out'));
		$this->att_id_break1->setDbValue($rs->fields('att_id_break1'));
		$this->break_in->setDbValue($rs->fields('break_in'));
		$this->att_id_break2->setDbValue($rs->fields('att_id_break2'));
		$this->break_minute->setDbValue($rs->fields('break_minute'));
		$this->break->setDbValue($rs->fields('break'));
		$this->break_ot_minute->setDbValue($rs->fields('break_ot_minute'));
		$this->break_ot->setDbValue($rs->fields('break_ot'));
		$this->early_permission->setDbValue($rs->fields('early_permission'));
		$this->early_minute->setDbValue($rs->fields('early_minute'));
		$this->early->setDbValue($rs->fields('early'));
		$this->scan_out->setDbValue($rs->fields('scan_out'));
		$this->att_id_out->setDbValue($rs->fields('att_id_out'));
		$this->durasi_minute->setDbValue($rs->fields('durasi_minute'));
		$this->durasi->setDbValue($rs->fields('durasi'));
		$this->durasi_eot_minute->setDbValue($rs->fields('durasi_eot_minute'));
		$this->jk_count_as->setDbValue($rs->fields('jk_count_as'));
		$this->status_jk->setDbValue($rs->fields('status_jk'));
		$this->keterangan->setDbValue($rs->fields('keterangan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->tgl_shift->DbValue = $row['tgl_shift'];
		$this->khusus_lembur->DbValue = $row['khusus_lembur'];
		$this->khusus_extra->DbValue = $row['khusus_extra'];
		$this->temp_id_auto->DbValue = $row['temp_id_auto'];
		$this->jdw_kerja_m_id->DbValue = $row['jdw_kerja_m_id'];
		$this->jk_id->DbValue = $row['jk_id'];
		$this->jns_dok->DbValue = $row['jns_dok'];
		$this->izin_jenis_id->DbValue = $row['izin_jenis_id'];
		$this->cuti_n_id->DbValue = $row['cuti_n_id'];
		$this->libur_umum->DbValue = $row['libur_umum'];
		$this->libur_rutin->DbValue = $row['libur_rutin'];
		$this->jk_ot->DbValue = $row['jk_ot'];
		$this->scan_in->DbValue = $row['scan_in'];
		$this->att_id_in->DbValue = $row['att_id_in'];
		$this->late_permission->DbValue = $row['late_permission'];
		$this->late_minute->DbValue = $row['late_minute'];
		$this->late->DbValue = $row['late'];
		$this->break_out->DbValue = $row['break_out'];
		$this->att_id_break1->DbValue = $row['att_id_break1'];
		$this->break_in->DbValue = $row['break_in'];
		$this->att_id_break2->DbValue = $row['att_id_break2'];
		$this->break_minute->DbValue = $row['break_minute'];
		$this->break->DbValue = $row['break'];
		$this->break_ot_minute->DbValue = $row['break_ot_minute'];
		$this->break_ot->DbValue = $row['break_ot'];
		$this->early_permission->DbValue = $row['early_permission'];
		$this->early_minute->DbValue = $row['early_minute'];
		$this->early->DbValue = $row['early'];
		$this->scan_out->DbValue = $row['scan_out'];
		$this->att_id_out->DbValue = $row['att_id_out'];
		$this->durasi_minute->DbValue = $row['durasi_minute'];
		$this->durasi->DbValue = $row['durasi'];
		$this->durasi_eot_minute->DbValue = $row['durasi_eot_minute'];
		$this->jk_count_as->DbValue = $row['jk_count_as'];
		$this->status_jk->DbValue = $row['status_jk'];
		$this->keterangan->DbValue = $row['keterangan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("tgl_shift")) <> "")
			$this->tgl_shift->CurrentValue = $this->getKey("tgl_shift"); // tgl_shift
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("khusus_lembur")) <> "")
			$this->khusus_lembur->CurrentValue = $this->getKey("khusus_lembur"); // khusus_lembur
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("khusus_extra")) <> "")
			$this->khusus_extra->CurrentValue = $this->getKey("khusus_extra"); // khusus_extra
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("temp_id_auto")) <> "")
			$this->temp_id_auto->CurrentValue = $this->getKey("temp_id_auto"); // temp_id_auto
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

		if ($this->late->FormValue == $this->late->CurrentValue && is_numeric(ew_StrToFloat($this->late->CurrentValue)))
			$this->late->CurrentValue = ew_StrToFloat($this->late->CurrentValue);

		// Convert decimal values if posted back
		if ($this->break->FormValue == $this->break->CurrentValue && is_numeric(ew_StrToFloat($this->break->CurrentValue)))
			$this->break->CurrentValue = ew_StrToFloat($this->break->CurrentValue);

		// Convert decimal values if posted back
		if ($this->break_ot->FormValue == $this->break_ot->CurrentValue && is_numeric(ew_StrToFloat($this->break_ot->CurrentValue)))
			$this->break_ot->CurrentValue = ew_StrToFloat($this->break_ot->CurrentValue);

		// Convert decimal values if posted back
		if ($this->early->FormValue == $this->early->CurrentValue && is_numeric(ew_StrToFloat($this->early->CurrentValue)))
			$this->early->CurrentValue = ew_StrToFloat($this->early->CurrentValue);

		// Convert decimal values if posted back
		if ($this->durasi->FormValue == $this->durasi->CurrentValue && is_numeric(ew_StrToFloat($this->durasi->CurrentValue)))
			$this->durasi->CurrentValue = ew_StrToFloat($this->durasi->CurrentValue);

		// Convert decimal values if posted back
		if ($this->jk_count_as->FormValue == $this->jk_count_as->CurrentValue && is_numeric(ew_StrToFloat($this->jk_count_as->CurrentValue)))
			$this->jk_count_as->CurrentValue = ew_StrToFloat($this->jk_count_as->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// tgl_shift
		// khusus_lembur
		// khusus_extra
		// temp_id_auto
		// jdw_kerja_m_id
		// jk_id
		// jns_dok
		// izin_jenis_id
		// cuti_n_id
		// libur_umum
		// libur_rutin
		// jk_ot
		// scan_in
		// att_id_in
		// late_permission
		// late_minute
		// late
		// break_out
		// att_id_break1
		// break_in
		// att_id_break2
		// break_minute
		// break
		// break_ot_minute
		// break_ot
		// early_permission
		// early_minute
		// early
		// scan_out
		// att_id_out
		// durasi_minute
		// durasi
		// durasi_eot_minute
		// jk_count_as
		// status_jk
		// keterangan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// tgl_shift
		$this->tgl_shift->ViewValue = $this->tgl_shift->CurrentValue;
		$this->tgl_shift->ViewValue = ew_FormatDateTime($this->tgl_shift->ViewValue, 0);
		$this->tgl_shift->ViewCustomAttributes = "";

		// khusus_lembur
		$this->khusus_lembur->ViewValue = $this->khusus_lembur->CurrentValue;
		$this->khusus_lembur->ViewCustomAttributes = "";

		// khusus_extra
		$this->khusus_extra->ViewValue = $this->khusus_extra->CurrentValue;
		$this->khusus_extra->ViewCustomAttributes = "";

		// temp_id_auto
		$this->temp_id_auto->ViewValue = $this->temp_id_auto->CurrentValue;
		$this->temp_id_auto->ViewCustomAttributes = "";

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// jk_id
		$this->jk_id->ViewValue = $this->jk_id->CurrentValue;
		$this->jk_id->ViewCustomAttributes = "";

		// jns_dok
		$this->jns_dok->ViewValue = $this->jns_dok->CurrentValue;
		$this->jns_dok->ViewCustomAttributes = "";

		// izin_jenis_id
		$this->izin_jenis_id->ViewValue = $this->izin_jenis_id->CurrentValue;
		$this->izin_jenis_id->ViewCustomAttributes = "";

		// cuti_n_id
		$this->cuti_n_id->ViewValue = $this->cuti_n_id->CurrentValue;
		$this->cuti_n_id->ViewCustomAttributes = "";

		// libur_umum
		$this->libur_umum->ViewValue = $this->libur_umum->CurrentValue;
		$this->libur_umum->ViewCustomAttributes = "";

		// libur_rutin
		$this->libur_rutin->ViewValue = $this->libur_rutin->CurrentValue;
		$this->libur_rutin->ViewCustomAttributes = "";

		// jk_ot
		$this->jk_ot->ViewValue = $this->jk_ot->CurrentValue;
		$this->jk_ot->ViewCustomAttributes = "";

		// scan_in
		$this->scan_in->ViewValue = $this->scan_in->CurrentValue;
		$this->scan_in->ViewValue = ew_FormatDateTime($this->scan_in->ViewValue, 0);
		$this->scan_in->ViewCustomAttributes = "";

		// att_id_in
		$this->att_id_in->ViewValue = $this->att_id_in->CurrentValue;
		$this->att_id_in->ViewCustomAttributes = "";

		// late_permission
		$this->late_permission->ViewValue = $this->late_permission->CurrentValue;
		$this->late_permission->ViewCustomAttributes = "";

		// late_minute
		$this->late_minute->ViewValue = $this->late_minute->CurrentValue;
		$this->late_minute->ViewCustomAttributes = "";

		// late
		$this->late->ViewValue = $this->late->CurrentValue;
		$this->late->ViewCustomAttributes = "";

		// break_out
		$this->break_out->ViewValue = $this->break_out->CurrentValue;
		$this->break_out->ViewValue = ew_FormatDateTime($this->break_out->ViewValue, 0);
		$this->break_out->ViewCustomAttributes = "";

		// att_id_break1
		$this->att_id_break1->ViewValue = $this->att_id_break1->CurrentValue;
		$this->att_id_break1->ViewCustomAttributes = "";

		// break_in
		$this->break_in->ViewValue = $this->break_in->CurrentValue;
		$this->break_in->ViewValue = ew_FormatDateTime($this->break_in->ViewValue, 0);
		$this->break_in->ViewCustomAttributes = "";

		// att_id_break2
		$this->att_id_break2->ViewValue = $this->att_id_break2->CurrentValue;
		$this->att_id_break2->ViewCustomAttributes = "";

		// break_minute
		$this->break_minute->ViewValue = $this->break_minute->CurrentValue;
		$this->break_minute->ViewCustomAttributes = "";

		// break
		$this->break->ViewValue = $this->break->CurrentValue;
		$this->break->ViewCustomAttributes = "";

		// break_ot_minute
		$this->break_ot_minute->ViewValue = $this->break_ot_minute->CurrentValue;
		$this->break_ot_minute->ViewCustomAttributes = "";

		// break_ot
		$this->break_ot->ViewValue = $this->break_ot->CurrentValue;
		$this->break_ot->ViewCustomAttributes = "";

		// early_permission
		$this->early_permission->ViewValue = $this->early_permission->CurrentValue;
		$this->early_permission->ViewCustomAttributes = "";

		// early_minute
		$this->early_minute->ViewValue = $this->early_minute->CurrentValue;
		$this->early_minute->ViewCustomAttributes = "";

		// early
		$this->early->ViewValue = $this->early->CurrentValue;
		$this->early->ViewCustomAttributes = "";

		// scan_out
		$this->scan_out->ViewValue = $this->scan_out->CurrentValue;
		$this->scan_out->ViewValue = ew_FormatDateTime($this->scan_out->ViewValue, 0);
		$this->scan_out->ViewCustomAttributes = "";

		// att_id_out
		$this->att_id_out->ViewValue = $this->att_id_out->CurrentValue;
		$this->att_id_out->ViewCustomAttributes = "";

		// durasi_minute
		$this->durasi_minute->ViewValue = $this->durasi_minute->CurrentValue;
		$this->durasi_minute->ViewCustomAttributes = "";

		// durasi
		$this->durasi->ViewValue = $this->durasi->CurrentValue;
		$this->durasi->ViewCustomAttributes = "";

		// durasi_eot_minute
		$this->durasi_eot_minute->ViewValue = $this->durasi_eot_minute->CurrentValue;
		$this->durasi_eot_minute->ViewCustomAttributes = "";

		// jk_count_as
		$this->jk_count_as->ViewValue = $this->jk_count_as->CurrentValue;
		$this->jk_count_as->ViewCustomAttributes = "";

		// status_jk
		$this->status_jk->ViewValue = $this->status_jk->CurrentValue;
		$this->status_jk->ViewCustomAttributes = "";

		// keterangan
		$this->keterangan->ViewValue = $this->keterangan->CurrentValue;
		$this->keterangan->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// tgl_shift
			$this->tgl_shift->LinkCustomAttributes = "";
			$this->tgl_shift->HrefValue = "";
			$this->tgl_shift->TooltipValue = "";

			// khusus_lembur
			$this->khusus_lembur->LinkCustomAttributes = "";
			$this->khusus_lembur->HrefValue = "";
			$this->khusus_lembur->TooltipValue = "";

			// khusus_extra
			$this->khusus_extra->LinkCustomAttributes = "";
			$this->khusus_extra->HrefValue = "";
			$this->khusus_extra->TooltipValue = "";

			// temp_id_auto
			$this->temp_id_auto->LinkCustomAttributes = "";
			$this->temp_id_auto->HrefValue = "";
			$this->temp_id_auto->TooltipValue = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";
			$this->jdw_kerja_m_id->TooltipValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";
			$this->jk_id->TooltipValue = "";

			// jns_dok
			$this->jns_dok->LinkCustomAttributes = "";
			$this->jns_dok->HrefValue = "";
			$this->jns_dok->TooltipValue = "";

			// izin_jenis_id
			$this->izin_jenis_id->LinkCustomAttributes = "";
			$this->izin_jenis_id->HrefValue = "";
			$this->izin_jenis_id->TooltipValue = "";

			// cuti_n_id
			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";
			$this->cuti_n_id->TooltipValue = "";

			// libur_umum
			$this->libur_umum->LinkCustomAttributes = "";
			$this->libur_umum->HrefValue = "";
			$this->libur_umum->TooltipValue = "";

			// libur_rutin
			$this->libur_rutin->LinkCustomAttributes = "";
			$this->libur_rutin->HrefValue = "";
			$this->libur_rutin->TooltipValue = "";

			// jk_ot
			$this->jk_ot->LinkCustomAttributes = "";
			$this->jk_ot->HrefValue = "";
			$this->jk_ot->TooltipValue = "";

			// scan_in
			$this->scan_in->LinkCustomAttributes = "";
			$this->scan_in->HrefValue = "";
			$this->scan_in->TooltipValue = "";

			// att_id_in
			$this->att_id_in->LinkCustomAttributes = "";
			$this->att_id_in->HrefValue = "";
			$this->att_id_in->TooltipValue = "";

			// late_permission
			$this->late_permission->LinkCustomAttributes = "";
			$this->late_permission->HrefValue = "";
			$this->late_permission->TooltipValue = "";

			// late_minute
			$this->late_minute->LinkCustomAttributes = "";
			$this->late_minute->HrefValue = "";
			$this->late_minute->TooltipValue = "";

			// late
			$this->late->LinkCustomAttributes = "";
			$this->late->HrefValue = "";
			$this->late->TooltipValue = "";

			// break_out
			$this->break_out->LinkCustomAttributes = "";
			$this->break_out->HrefValue = "";
			$this->break_out->TooltipValue = "";

			// att_id_break1
			$this->att_id_break1->LinkCustomAttributes = "";
			$this->att_id_break1->HrefValue = "";
			$this->att_id_break1->TooltipValue = "";

			// break_in
			$this->break_in->LinkCustomAttributes = "";
			$this->break_in->HrefValue = "";
			$this->break_in->TooltipValue = "";

			// att_id_break2
			$this->att_id_break2->LinkCustomAttributes = "";
			$this->att_id_break2->HrefValue = "";
			$this->att_id_break2->TooltipValue = "";

			// break_minute
			$this->break_minute->LinkCustomAttributes = "";
			$this->break_minute->HrefValue = "";
			$this->break_minute->TooltipValue = "";

			// break
			$this->break->LinkCustomAttributes = "";
			$this->break->HrefValue = "";
			$this->break->TooltipValue = "";

			// break_ot_minute
			$this->break_ot_minute->LinkCustomAttributes = "";
			$this->break_ot_minute->HrefValue = "";
			$this->break_ot_minute->TooltipValue = "";

			// break_ot
			$this->break_ot->LinkCustomAttributes = "";
			$this->break_ot->HrefValue = "";
			$this->break_ot->TooltipValue = "";

			// early_permission
			$this->early_permission->LinkCustomAttributes = "";
			$this->early_permission->HrefValue = "";
			$this->early_permission->TooltipValue = "";

			// early_minute
			$this->early_minute->LinkCustomAttributes = "";
			$this->early_minute->HrefValue = "";
			$this->early_minute->TooltipValue = "";

			// early
			$this->early->LinkCustomAttributes = "";
			$this->early->HrefValue = "";
			$this->early->TooltipValue = "";

			// scan_out
			$this->scan_out->LinkCustomAttributes = "";
			$this->scan_out->HrefValue = "";
			$this->scan_out->TooltipValue = "";

			// att_id_out
			$this->att_id_out->LinkCustomAttributes = "";
			$this->att_id_out->HrefValue = "";
			$this->att_id_out->TooltipValue = "";

			// durasi_minute
			$this->durasi_minute->LinkCustomAttributes = "";
			$this->durasi_minute->HrefValue = "";
			$this->durasi_minute->TooltipValue = "";

			// durasi
			$this->durasi->LinkCustomAttributes = "";
			$this->durasi->HrefValue = "";
			$this->durasi->TooltipValue = "";

			// durasi_eot_minute
			$this->durasi_eot_minute->LinkCustomAttributes = "";
			$this->durasi_eot_minute->HrefValue = "";
			$this->durasi_eot_minute->TooltipValue = "";

			// jk_count_as
			$this->jk_count_as->LinkCustomAttributes = "";
			$this->jk_count_as->HrefValue = "";
			$this->jk_count_as->TooltipValue = "";

			// status_jk
			$this->status_jk->LinkCustomAttributes = "";
			$this->status_jk->HrefValue = "";
			$this->status_jk->TooltipValue = "";

			// keterangan
			$this->keterangan->LinkCustomAttributes = "";
			$this->keterangan->HrefValue = "";
			$this->keterangan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = ew_HtmlEncode($this->pegawai_id->CurrentValue);
			$this->pegawai_id->PlaceHolder = ew_RemoveHtml($this->pegawai_id->FldCaption());

			// tgl_shift
			$this->tgl_shift->EditAttrs["class"] = "form-control";
			$this->tgl_shift->EditCustomAttributes = "";
			$this->tgl_shift->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->tgl_shift->CurrentValue, 8));
			$this->tgl_shift->PlaceHolder = ew_RemoveHtml($this->tgl_shift->FldCaption());

			// khusus_lembur
			$this->khusus_lembur->EditAttrs["class"] = "form-control";
			$this->khusus_lembur->EditCustomAttributes = "";
			$this->khusus_lembur->EditValue = ew_HtmlEncode($this->khusus_lembur->CurrentValue);
			$this->khusus_lembur->PlaceHolder = ew_RemoveHtml($this->khusus_lembur->FldCaption());

			// khusus_extra
			$this->khusus_extra->EditAttrs["class"] = "form-control";
			$this->khusus_extra->EditCustomAttributes = "";
			$this->khusus_extra->EditValue = ew_HtmlEncode($this->khusus_extra->CurrentValue);
			$this->khusus_extra->PlaceHolder = ew_RemoveHtml($this->khusus_extra->FldCaption());

			// temp_id_auto
			$this->temp_id_auto->EditAttrs["class"] = "form-control";
			$this->temp_id_auto->EditCustomAttributes = "";
			$this->temp_id_auto->EditValue = ew_HtmlEncode($this->temp_id_auto->CurrentValue);
			$this->temp_id_auto->PlaceHolder = ew_RemoveHtml($this->temp_id_auto->FldCaption());

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->EditAttrs["class"] = "form-control";
			$this->jdw_kerja_m_id->EditCustomAttributes = "";
			$this->jdw_kerja_m_id->EditValue = ew_HtmlEncode($this->jdw_kerja_m_id->CurrentValue);
			$this->jdw_kerja_m_id->PlaceHolder = ew_RemoveHtml($this->jdw_kerja_m_id->FldCaption());

			// jk_id
			$this->jk_id->EditAttrs["class"] = "form-control";
			$this->jk_id->EditCustomAttributes = "";
			$this->jk_id->EditValue = ew_HtmlEncode($this->jk_id->CurrentValue);
			$this->jk_id->PlaceHolder = ew_RemoveHtml($this->jk_id->FldCaption());

			// jns_dok
			$this->jns_dok->EditAttrs["class"] = "form-control";
			$this->jns_dok->EditCustomAttributes = "";
			$this->jns_dok->EditValue = ew_HtmlEncode($this->jns_dok->CurrentValue);
			$this->jns_dok->PlaceHolder = ew_RemoveHtml($this->jns_dok->FldCaption());

			// izin_jenis_id
			$this->izin_jenis_id->EditAttrs["class"] = "form-control";
			$this->izin_jenis_id->EditCustomAttributes = "";
			$this->izin_jenis_id->EditValue = ew_HtmlEncode($this->izin_jenis_id->CurrentValue);
			$this->izin_jenis_id->PlaceHolder = ew_RemoveHtml($this->izin_jenis_id->FldCaption());

			// cuti_n_id
			$this->cuti_n_id->EditAttrs["class"] = "form-control";
			$this->cuti_n_id->EditCustomAttributes = "";
			$this->cuti_n_id->EditValue = ew_HtmlEncode($this->cuti_n_id->CurrentValue);
			$this->cuti_n_id->PlaceHolder = ew_RemoveHtml($this->cuti_n_id->FldCaption());

			// libur_umum
			$this->libur_umum->EditAttrs["class"] = "form-control";
			$this->libur_umum->EditCustomAttributes = "";
			$this->libur_umum->EditValue = ew_HtmlEncode($this->libur_umum->CurrentValue);
			$this->libur_umum->PlaceHolder = ew_RemoveHtml($this->libur_umum->FldCaption());

			// libur_rutin
			$this->libur_rutin->EditAttrs["class"] = "form-control";
			$this->libur_rutin->EditCustomAttributes = "";
			$this->libur_rutin->EditValue = ew_HtmlEncode($this->libur_rutin->CurrentValue);
			$this->libur_rutin->PlaceHolder = ew_RemoveHtml($this->libur_rutin->FldCaption());

			// jk_ot
			$this->jk_ot->EditAttrs["class"] = "form-control";
			$this->jk_ot->EditCustomAttributes = "";
			$this->jk_ot->EditValue = ew_HtmlEncode($this->jk_ot->CurrentValue);
			$this->jk_ot->PlaceHolder = ew_RemoveHtml($this->jk_ot->FldCaption());

			// scan_in
			$this->scan_in->EditAttrs["class"] = "form-control";
			$this->scan_in->EditCustomAttributes = "";
			$this->scan_in->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->scan_in->CurrentValue, 8));
			$this->scan_in->PlaceHolder = ew_RemoveHtml($this->scan_in->FldCaption());

			// att_id_in
			$this->att_id_in->EditAttrs["class"] = "form-control";
			$this->att_id_in->EditCustomAttributes = "";
			$this->att_id_in->EditValue = ew_HtmlEncode($this->att_id_in->CurrentValue);
			$this->att_id_in->PlaceHolder = ew_RemoveHtml($this->att_id_in->FldCaption());

			// late_permission
			$this->late_permission->EditAttrs["class"] = "form-control";
			$this->late_permission->EditCustomAttributes = "";
			$this->late_permission->EditValue = ew_HtmlEncode($this->late_permission->CurrentValue);
			$this->late_permission->PlaceHolder = ew_RemoveHtml($this->late_permission->FldCaption());

			// late_minute
			$this->late_minute->EditAttrs["class"] = "form-control";
			$this->late_minute->EditCustomAttributes = "";
			$this->late_minute->EditValue = ew_HtmlEncode($this->late_minute->CurrentValue);
			$this->late_minute->PlaceHolder = ew_RemoveHtml($this->late_minute->FldCaption());

			// late
			$this->late->EditAttrs["class"] = "form-control";
			$this->late->EditCustomAttributes = "";
			$this->late->EditValue = ew_HtmlEncode($this->late->CurrentValue);
			$this->late->PlaceHolder = ew_RemoveHtml($this->late->FldCaption());
			if (strval($this->late->EditValue) <> "" && is_numeric($this->late->EditValue)) $this->late->EditValue = ew_FormatNumber($this->late->EditValue, -2, -1, -2, 0);

			// break_out
			$this->break_out->EditAttrs["class"] = "form-control";
			$this->break_out->EditCustomAttributes = "";
			$this->break_out->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->break_out->CurrentValue, 8));
			$this->break_out->PlaceHolder = ew_RemoveHtml($this->break_out->FldCaption());

			// att_id_break1
			$this->att_id_break1->EditAttrs["class"] = "form-control";
			$this->att_id_break1->EditCustomAttributes = "";
			$this->att_id_break1->EditValue = ew_HtmlEncode($this->att_id_break1->CurrentValue);
			$this->att_id_break1->PlaceHolder = ew_RemoveHtml($this->att_id_break1->FldCaption());

			// break_in
			$this->break_in->EditAttrs["class"] = "form-control";
			$this->break_in->EditCustomAttributes = "";
			$this->break_in->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->break_in->CurrentValue, 8));
			$this->break_in->PlaceHolder = ew_RemoveHtml($this->break_in->FldCaption());

			// att_id_break2
			$this->att_id_break2->EditAttrs["class"] = "form-control";
			$this->att_id_break2->EditCustomAttributes = "";
			$this->att_id_break2->EditValue = ew_HtmlEncode($this->att_id_break2->CurrentValue);
			$this->att_id_break2->PlaceHolder = ew_RemoveHtml($this->att_id_break2->FldCaption());

			// break_minute
			$this->break_minute->EditAttrs["class"] = "form-control";
			$this->break_minute->EditCustomAttributes = "";
			$this->break_minute->EditValue = ew_HtmlEncode($this->break_minute->CurrentValue);
			$this->break_minute->PlaceHolder = ew_RemoveHtml($this->break_minute->FldCaption());

			// break
			$this->break->EditAttrs["class"] = "form-control";
			$this->break->EditCustomAttributes = "";
			$this->break->EditValue = ew_HtmlEncode($this->break->CurrentValue);
			$this->break->PlaceHolder = ew_RemoveHtml($this->break->FldCaption());
			if (strval($this->break->EditValue) <> "" && is_numeric($this->break->EditValue)) $this->break->EditValue = ew_FormatNumber($this->break->EditValue, -2, -1, -2, 0);

			// break_ot_minute
			$this->break_ot_minute->EditAttrs["class"] = "form-control";
			$this->break_ot_minute->EditCustomAttributes = "";
			$this->break_ot_minute->EditValue = ew_HtmlEncode($this->break_ot_minute->CurrentValue);
			$this->break_ot_minute->PlaceHolder = ew_RemoveHtml($this->break_ot_minute->FldCaption());

			// break_ot
			$this->break_ot->EditAttrs["class"] = "form-control";
			$this->break_ot->EditCustomAttributes = "";
			$this->break_ot->EditValue = ew_HtmlEncode($this->break_ot->CurrentValue);
			$this->break_ot->PlaceHolder = ew_RemoveHtml($this->break_ot->FldCaption());
			if (strval($this->break_ot->EditValue) <> "" && is_numeric($this->break_ot->EditValue)) $this->break_ot->EditValue = ew_FormatNumber($this->break_ot->EditValue, -2, -1, -2, 0);

			// early_permission
			$this->early_permission->EditAttrs["class"] = "form-control";
			$this->early_permission->EditCustomAttributes = "";
			$this->early_permission->EditValue = ew_HtmlEncode($this->early_permission->CurrentValue);
			$this->early_permission->PlaceHolder = ew_RemoveHtml($this->early_permission->FldCaption());

			// early_minute
			$this->early_minute->EditAttrs["class"] = "form-control";
			$this->early_minute->EditCustomAttributes = "";
			$this->early_minute->EditValue = ew_HtmlEncode($this->early_minute->CurrentValue);
			$this->early_minute->PlaceHolder = ew_RemoveHtml($this->early_minute->FldCaption());

			// early
			$this->early->EditAttrs["class"] = "form-control";
			$this->early->EditCustomAttributes = "";
			$this->early->EditValue = ew_HtmlEncode($this->early->CurrentValue);
			$this->early->PlaceHolder = ew_RemoveHtml($this->early->FldCaption());
			if (strval($this->early->EditValue) <> "" && is_numeric($this->early->EditValue)) $this->early->EditValue = ew_FormatNumber($this->early->EditValue, -2, -1, -2, 0);

			// scan_out
			$this->scan_out->EditAttrs["class"] = "form-control";
			$this->scan_out->EditCustomAttributes = "";
			$this->scan_out->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->scan_out->CurrentValue, 8));
			$this->scan_out->PlaceHolder = ew_RemoveHtml($this->scan_out->FldCaption());

			// att_id_out
			$this->att_id_out->EditAttrs["class"] = "form-control";
			$this->att_id_out->EditCustomAttributes = "";
			$this->att_id_out->EditValue = ew_HtmlEncode($this->att_id_out->CurrentValue);
			$this->att_id_out->PlaceHolder = ew_RemoveHtml($this->att_id_out->FldCaption());

			// durasi_minute
			$this->durasi_minute->EditAttrs["class"] = "form-control";
			$this->durasi_minute->EditCustomAttributes = "";
			$this->durasi_minute->EditValue = ew_HtmlEncode($this->durasi_minute->CurrentValue);
			$this->durasi_minute->PlaceHolder = ew_RemoveHtml($this->durasi_minute->FldCaption());

			// durasi
			$this->durasi->EditAttrs["class"] = "form-control";
			$this->durasi->EditCustomAttributes = "";
			$this->durasi->EditValue = ew_HtmlEncode($this->durasi->CurrentValue);
			$this->durasi->PlaceHolder = ew_RemoveHtml($this->durasi->FldCaption());
			if (strval($this->durasi->EditValue) <> "" && is_numeric($this->durasi->EditValue)) $this->durasi->EditValue = ew_FormatNumber($this->durasi->EditValue, -2, -1, -2, 0);

			// durasi_eot_minute
			$this->durasi_eot_minute->EditAttrs["class"] = "form-control";
			$this->durasi_eot_minute->EditCustomAttributes = "";
			$this->durasi_eot_minute->EditValue = ew_HtmlEncode($this->durasi_eot_minute->CurrentValue);
			$this->durasi_eot_minute->PlaceHolder = ew_RemoveHtml($this->durasi_eot_minute->FldCaption());

			// jk_count_as
			$this->jk_count_as->EditAttrs["class"] = "form-control";
			$this->jk_count_as->EditCustomAttributes = "";
			$this->jk_count_as->EditValue = ew_HtmlEncode($this->jk_count_as->CurrentValue);
			$this->jk_count_as->PlaceHolder = ew_RemoveHtml($this->jk_count_as->FldCaption());
			if (strval($this->jk_count_as->EditValue) <> "" && is_numeric($this->jk_count_as->EditValue)) $this->jk_count_as->EditValue = ew_FormatNumber($this->jk_count_as->EditValue, -2, -1, -2, 0);

			// status_jk
			$this->status_jk->EditAttrs["class"] = "form-control";
			$this->status_jk->EditCustomAttributes = "";
			$this->status_jk->EditValue = ew_HtmlEncode($this->status_jk->CurrentValue);
			$this->status_jk->PlaceHolder = ew_RemoveHtml($this->status_jk->FldCaption());

			// keterangan
			$this->keterangan->EditAttrs["class"] = "form-control";
			$this->keterangan->EditCustomAttributes = "";
			$this->keterangan->EditValue = ew_HtmlEncode($this->keterangan->CurrentValue);
			$this->keterangan->PlaceHolder = ew_RemoveHtml($this->keterangan->FldCaption());

			// Add refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// tgl_shift
			$this->tgl_shift->LinkCustomAttributes = "";
			$this->tgl_shift->HrefValue = "";

			// khusus_lembur
			$this->khusus_lembur->LinkCustomAttributes = "";
			$this->khusus_lembur->HrefValue = "";

			// khusus_extra
			$this->khusus_extra->LinkCustomAttributes = "";
			$this->khusus_extra->HrefValue = "";

			// temp_id_auto
			$this->temp_id_auto->LinkCustomAttributes = "";
			$this->temp_id_auto->HrefValue = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";

			// jk_id
			$this->jk_id->LinkCustomAttributes = "";
			$this->jk_id->HrefValue = "";

			// jns_dok
			$this->jns_dok->LinkCustomAttributes = "";
			$this->jns_dok->HrefValue = "";

			// izin_jenis_id
			$this->izin_jenis_id->LinkCustomAttributes = "";
			$this->izin_jenis_id->HrefValue = "";

			// cuti_n_id
			$this->cuti_n_id->LinkCustomAttributes = "";
			$this->cuti_n_id->HrefValue = "";

			// libur_umum
			$this->libur_umum->LinkCustomAttributes = "";
			$this->libur_umum->HrefValue = "";

			// libur_rutin
			$this->libur_rutin->LinkCustomAttributes = "";
			$this->libur_rutin->HrefValue = "";

			// jk_ot
			$this->jk_ot->LinkCustomAttributes = "";
			$this->jk_ot->HrefValue = "";

			// scan_in
			$this->scan_in->LinkCustomAttributes = "";
			$this->scan_in->HrefValue = "";

			// att_id_in
			$this->att_id_in->LinkCustomAttributes = "";
			$this->att_id_in->HrefValue = "";

			// late_permission
			$this->late_permission->LinkCustomAttributes = "";
			$this->late_permission->HrefValue = "";

			// late_minute
			$this->late_minute->LinkCustomAttributes = "";
			$this->late_minute->HrefValue = "";

			// late
			$this->late->LinkCustomAttributes = "";
			$this->late->HrefValue = "";

			// break_out
			$this->break_out->LinkCustomAttributes = "";
			$this->break_out->HrefValue = "";

			// att_id_break1
			$this->att_id_break1->LinkCustomAttributes = "";
			$this->att_id_break1->HrefValue = "";

			// break_in
			$this->break_in->LinkCustomAttributes = "";
			$this->break_in->HrefValue = "";

			// att_id_break2
			$this->att_id_break2->LinkCustomAttributes = "";
			$this->att_id_break2->HrefValue = "";

			// break_minute
			$this->break_minute->LinkCustomAttributes = "";
			$this->break_minute->HrefValue = "";

			// break
			$this->break->LinkCustomAttributes = "";
			$this->break->HrefValue = "";

			// break_ot_minute
			$this->break_ot_minute->LinkCustomAttributes = "";
			$this->break_ot_minute->HrefValue = "";

			// break_ot
			$this->break_ot->LinkCustomAttributes = "";
			$this->break_ot->HrefValue = "";

			// early_permission
			$this->early_permission->LinkCustomAttributes = "";
			$this->early_permission->HrefValue = "";

			// early_minute
			$this->early_minute->LinkCustomAttributes = "";
			$this->early_minute->HrefValue = "";

			// early
			$this->early->LinkCustomAttributes = "";
			$this->early->HrefValue = "";

			// scan_out
			$this->scan_out->LinkCustomAttributes = "";
			$this->scan_out->HrefValue = "";

			// att_id_out
			$this->att_id_out->LinkCustomAttributes = "";
			$this->att_id_out->HrefValue = "";

			// durasi_minute
			$this->durasi_minute->LinkCustomAttributes = "";
			$this->durasi_minute->HrefValue = "";

			// durasi
			$this->durasi->LinkCustomAttributes = "";
			$this->durasi->HrefValue = "";

			// durasi_eot_minute
			$this->durasi_eot_minute->LinkCustomAttributes = "";
			$this->durasi_eot_minute->HrefValue = "";

			// jk_count_as
			$this->jk_count_as->LinkCustomAttributes = "";
			$this->jk_count_as->HrefValue = "";

			// status_jk
			$this->status_jk->LinkCustomAttributes = "";
			$this->status_jk->HrefValue = "";

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
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->tgl_shift->FldIsDetailKey && !is_null($this->tgl_shift->FormValue) && $this->tgl_shift->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->tgl_shift->FldCaption(), $this->tgl_shift->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->tgl_shift->FormValue)) {
			ew_AddMessage($gsFormError, $this->tgl_shift->FldErrMsg());
		}
		if (!$this->khusus_lembur->FldIsDetailKey && !is_null($this->khusus_lembur->FormValue) && $this->khusus_lembur->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->khusus_lembur->FldCaption(), $this->khusus_lembur->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->khusus_lembur->FormValue)) {
			ew_AddMessage($gsFormError, $this->khusus_lembur->FldErrMsg());
		}
		if (!$this->khusus_extra->FldIsDetailKey && !is_null($this->khusus_extra->FormValue) && $this->khusus_extra->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->khusus_extra->FldCaption(), $this->khusus_extra->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->khusus_extra->FormValue)) {
			ew_AddMessage($gsFormError, $this->khusus_extra->FldErrMsg());
		}
		if (!$this->temp_id_auto->FldIsDetailKey && !is_null($this->temp_id_auto->FormValue) && $this->temp_id_auto->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->temp_id_auto->FldCaption(), $this->temp_id_auto->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->temp_id_auto->FormValue)) {
			ew_AddMessage($gsFormError, $this->temp_id_auto->FldErrMsg());
		}
		if (!$this->jdw_kerja_m_id->FldIsDetailKey && !is_null($this->jdw_kerja_m_id->FormValue) && $this->jdw_kerja_m_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jdw_kerja_m_id->FldCaption(), $this->jdw_kerja_m_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jdw_kerja_m_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jdw_kerja_m_id->FldErrMsg());
		}
		if (!$this->jk_id->FldIsDetailKey && !is_null($this->jk_id->FormValue) && $this->jk_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_id->FldCaption(), $this->jk_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_id->FldErrMsg());
		}
		if (!$this->jns_dok->FldIsDetailKey && !is_null($this->jns_dok->FormValue) && $this->jns_dok->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jns_dok->FldCaption(), $this->jns_dok->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jns_dok->FormValue)) {
			ew_AddMessage($gsFormError, $this->jns_dok->FldErrMsg());
		}
		if (!$this->izin_jenis_id->FldIsDetailKey && !is_null($this->izin_jenis_id->FormValue) && $this->izin_jenis_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->izin_jenis_id->FldCaption(), $this->izin_jenis_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->izin_jenis_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->izin_jenis_id->FldErrMsg());
		}
		if (!$this->cuti_n_id->FldIsDetailKey && !is_null($this->cuti_n_id->FormValue) && $this->cuti_n_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->cuti_n_id->FldCaption(), $this->cuti_n_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->cuti_n_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->cuti_n_id->FldErrMsg());
		}
		if (!$this->libur_umum->FldIsDetailKey && !is_null($this->libur_umum->FormValue) && $this->libur_umum->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->libur_umum->FldCaption(), $this->libur_umum->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->libur_umum->FormValue)) {
			ew_AddMessage($gsFormError, $this->libur_umum->FldErrMsg());
		}
		if (!$this->libur_rutin->FldIsDetailKey && !is_null($this->libur_rutin->FormValue) && $this->libur_rutin->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->libur_rutin->FldCaption(), $this->libur_rutin->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->libur_rutin->FormValue)) {
			ew_AddMessage($gsFormError, $this->libur_rutin->FldErrMsg());
		}
		if (!$this->jk_ot->FldIsDetailKey && !is_null($this->jk_ot->FormValue) && $this->jk_ot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_ot->FldCaption(), $this->jk_ot->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jk_ot->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_ot->FldErrMsg());
		}
		if (!$this->scan_in->FldIsDetailKey && !is_null($this->scan_in->FormValue) && $this->scan_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scan_in->FldCaption(), $this->scan_in->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->scan_in->FormValue)) {
			ew_AddMessage($gsFormError, $this->scan_in->FldErrMsg());
		}
		if (!$this->att_id_in->FldIsDetailKey && !is_null($this->att_id_in->FormValue) && $this->att_id_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->att_id_in->FldCaption(), $this->att_id_in->ReqErrMsg));
		}
		if (!$this->late_permission->FldIsDetailKey && !is_null($this->late_permission->FormValue) && $this->late_permission->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->late_permission->FldCaption(), $this->late_permission->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->late_permission->FormValue)) {
			ew_AddMessage($gsFormError, $this->late_permission->FldErrMsg());
		}
		if (!$this->late_minute->FldIsDetailKey && !is_null($this->late_minute->FormValue) && $this->late_minute->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->late_minute->FldCaption(), $this->late_minute->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->late_minute->FormValue)) {
			ew_AddMessage($gsFormError, $this->late_minute->FldErrMsg());
		}
		if (!$this->late->FldIsDetailKey && !is_null($this->late->FormValue) && $this->late->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->late->FldCaption(), $this->late->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->late->FormValue)) {
			ew_AddMessage($gsFormError, $this->late->FldErrMsg());
		}
		if (!$this->break_out->FldIsDetailKey && !is_null($this->break_out->FormValue) && $this->break_out->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->break_out->FldCaption(), $this->break_out->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->break_out->FormValue)) {
			ew_AddMessage($gsFormError, $this->break_out->FldErrMsg());
		}
		if (!$this->att_id_break1->FldIsDetailKey && !is_null($this->att_id_break1->FormValue) && $this->att_id_break1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->att_id_break1->FldCaption(), $this->att_id_break1->ReqErrMsg));
		}
		if (!$this->break_in->FldIsDetailKey && !is_null($this->break_in->FormValue) && $this->break_in->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->break_in->FldCaption(), $this->break_in->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->break_in->FormValue)) {
			ew_AddMessage($gsFormError, $this->break_in->FldErrMsg());
		}
		if (!$this->att_id_break2->FldIsDetailKey && !is_null($this->att_id_break2->FormValue) && $this->att_id_break2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->att_id_break2->FldCaption(), $this->att_id_break2->ReqErrMsg));
		}
		if (!$this->break_minute->FldIsDetailKey && !is_null($this->break_minute->FormValue) && $this->break_minute->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->break_minute->FldCaption(), $this->break_minute->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->break_minute->FormValue)) {
			ew_AddMessage($gsFormError, $this->break_minute->FldErrMsg());
		}
		if (!$this->break->FldIsDetailKey && !is_null($this->break->FormValue) && $this->break->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->break->FldCaption(), $this->break->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->break->FormValue)) {
			ew_AddMessage($gsFormError, $this->break->FldErrMsg());
		}
		if (!$this->break_ot_minute->FldIsDetailKey && !is_null($this->break_ot_minute->FormValue) && $this->break_ot_minute->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->break_ot_minute->FldCaption(), $this->break_ot_minute->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->break_ot_minute->FormValue)) {
			ew_AddMessage($gsFormError, $this->break_ot_minute->FldErrMsg());
		}
		if (!$this->break_ot->FldIsDetailKey && !is_null($this->break_ot->FormValue) && $this->break_ot->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->break_ot->FldCaption(), $this->break_ot->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->break_ot->FormValue)) {
			ew_AddMessage($gsFormError, $this->break_ot->FldErrMsg());
		}
		if (!$this->early_permission->FldIsDetailKey && !is_null($this->early_permission->FormValue) && $this->early_permission->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->early_permission->FldCaption(), $this->early_permission->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->early_permission->FormValue)) {
			ew_AddMessage($gsFormError, $this->early_permission->FldErrMsg());
		}
		if (!$this->early_minute->FldIsDetailKey && !is_null($this->early_minute->FormValue) && $this->early_minute->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->early_minute->FldCaption(), $this->early_minute->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->early_minute->FormValue)) {
			ew_AddMessage($gsFormError, $this->early_minute->FldErrMsg());
		}
		if (!$this->early->FldIsDetailKey && !is_null($this->early->FormValue) && $this->early->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->early->FldCaption(), $this->early->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->early->FormValue)) {
			ew_AddMessage($gsFormError, $this->early->FldErrMsg());
		}
		if (!$this->scan_out->FldIsDetailKey && !is_null($this->scan_out->FormValue) && $this->scan_out->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->scan_out->FldCaption(), $this->scan_out->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->scan_out->FormValue)) {
			ew_AddMessage($gsFormError, $this->scan_out->FldErrMsg());
		}
		if (!$this->att_id_out->FldIsDetailKey && !is_null($this->att_id_out->FormValue) && $this->att_id_out->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->att_id_out->FldCaption(), $this->att_id_out->ReqErrMsg));
		}
		if (!$this->durasi_minute->FldIsDetailKey && !is_null($this->durasi_minute->FormValue) && $this->durasi_minute->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->durasi_minute->FldCaption(), $this->durasi_minute->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->durasi_minute->FormValue)) {
			ew_AddMessage($gsFormError, $this->durasi_minute->FldErrMsg());
		}
		if (!$this->durasi->FldIsDetailKey && !is_null($this->durasi->FormValue) && $this->durasi->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->durasi->FldCaption(), $this->durasi->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->durasi->FormValue)) {
			ew_AddMessage($gsFormError, $this->durasi->FldErrMsg());
		}
		if (!$this->durasi_eot_minute->FldIsDetailKey && !is_null($this->durasi_eot_minute->FormValue) && $this->durasi_eot_minute->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->durasi_eot_minute->FldCaption(), $this->durasi_eot_minute->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->durasi_eot_minute->FormValue)) {
			ew_AddMessage($gsFormError, $this->durasi_eot_minute->FldErrMsg());
		}
		if (!$this->jk_count_as->FldIsDetailKey && !is_null($this->jk_count_as->FormValue) && $this->jk_count_as->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jk_count_as->FldCaption(), $this->jk_count_as->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->jk_count_as->FormValue)) {
			ew_AddMessage($gsFormError, $this->jk_count_as->FldErrMsg());
		}
		if (!$this->status_jk->FldIsDetailKey && !is_null($this->status_jk->FormValue) && $this->status_jk->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->status_jk->FldCaption(), $this->status_jk->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->status_jk->FormValue)) {
			ew_AddMessage($gsFormError, $this->status_jk->FldErrMsg());
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

		// tgl_shift
		$this->tgl_shift->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->tgl_shift->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// khusus_lembur
		$this->khusus_lembur->SetDbValueDef($rsnew, $this->khusus_lembur->CurrentValue, 0, strval($this->khusus_lembur->CurrentValue) == "");

		// khusus_extra
		$this->khusus_extra->SetDbValueDef($rsnew, $this->khusus_extra->CurrentValue, 0, strval($this->khusus_extra->CurrentValue) == "");

		// temp_id_auto
		$this->temp_id_auto->SetDbValueDef($rsnew, $this->temp_id_auto->CurrentValue, 0, strval($this->temp_id_auto->CurrentValue) == "");

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->SetDbValueDef($rsnew, $this->jdw_kerja_m_id->CurrentValue, 0, strval($this->jdw_kerja_m_id->CurrentValue) == "");

		// jk_id
		$this->jk_id->SetDbValueDef($rsnew, $this->jk_id->CurrentValue, 0, strval($this->jk_id->CurrentValue) == "");

		// jns_dok
		$this->jns_dok->SetDbValueDef($rsnew, $this->jns_dok->CurrentValue, 0, strval($this->jns_dok->CurrentValue) == "");

		// izin_jenis_id
		$this->izin_jenis_id->SetDbValueDef($rsnew, $this->izin_jenis_id->CurrentValue, 0, strval($this->izin_jenis_id->CurrentValue) == "");

		// cuti_n_id
		$this->cuti_n_id->SetDbValueDef($rsnew, $this->cuti_n_id->CurrentValue, 0, strval($this->cuti_n_id->CurrentValue) == "");

		// libur_umum
		$this->libur_umum->SetDbValueDef($rsnew, $this->libur_umum->CurrentValue, 0, strval($this->libur_umum->CurrentValue) == "");

		// libur_rutin
		$this->libur_rutin->SetDbValueDef($rsnew, $this->libur_rutin->CurrentValue, 0, strval($this->libur_rutin->CurrentValue) == "");

		// jk_ot
		$this->jk_ot->SetDbValueDef($rsnew, $this->jk_ot->CurrentValue, 0, strval($this->jk_ot->CurrentValue) == "");

		// scan_in
		$this->scan_in->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->scan_in->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// att_id_in
		$this->att_id_in->SetDbValueDef($rsnew, $this->att_id_in->CurrentValue, "", strval($this->att_id_in->CurrentValue) == "");

		// late_permission
		$this->late_permission->SetDbValueDef($rsnew, $this->late_permission->CurrentValue, 0, strval($this->late_permission->CurrentValue) == "");

		// late_minute
		$this->late_minute->SetDbValueDef($rsnew, $this->late_minute->CurrentValue, 0, strval($this->late_minute->CurrentValue) == "");

		// late
		$this->late->SetDbValueDef($rsnew, $this->late->CurrentValue, 0, strval($this->late->CurrentValue) == "");

		// break_out
		$this->break_out->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->break_out->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// att_id_break1
		$this->att_id_break1->SetDbValueDef($rsnew, $this->att_id_break1->CurrentValue, "", strval($this->att_id_break1->CurrentValue) == "");

		// break_in
		$this->break_in->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->break_in->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// att_id_break2
		$this->att_id_break2->SetDbValueDef($rsnew, $this->att_id_break2->CurrentValue, "", strval($this->att_id_break2->CurrentValue) == "");

		// break_minute
		$this->break_minute->SetDbValueDef($rsnew, $this->break_minute->CurrentValue, 0, strval($this->break_minute->CurrentValue) == "");

		// break
		$this->break->SetDbValueDef($rsnew, $this->break->CurrentValue, 0, strval($this->break->CurrentValue) == "");

		// break_ot_minute
		$this->break_ot_minute->SetDbValueDef($rsnew, $this->break_ot_minute->CurrentValue, 0, strval($this->break_ot_minute->CurrentValue) == "");

		// break_ot
		$this->break_ot->SetDbValueDef($rsnew, $this->break_ot->CurrentValue, 0, strval($this->break_ot->CurrentValue) == "");

		// early_permission
		$this->early_permission->SetDbValueDef($rsnew, $this->early_permission->CurrentValue, 0, strval($this->early_permission->CurrentValue) == "");

		// early_minute
		$this->early_minute->SetDbValueDef($rsnew, $this->early_minute->CurrentValue, 0, strval($this->early_minute->CurrentValue) == "");

		// early
		$this->early->SetDbValueDef($rsnew, $this->early->CurrentValue, 0, strval($this->early->CurrentValue) == "");

		// scan_out
		$this->scan_out->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->scan_out->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// att_id_out
		$this->att_id_out->SetDbValueDef($rsnew, $this->att_id_out->CurrentValue, "", strval($this->att_id_out->CurrentValue) == "");

		// durasi_minute
		$this->durasi_minute->SetDbValueDef($rsnew, $this->durasi_minute->CurrentValue, 0, strval($this->durasi_minute->CurrentValue) == "");

		// durasi
		$this->durasi->SetDbValueDef($rsnew, $this->durasi->CurrentValue, 0, strval($this->durasi->CurrentValue) == "");

		// durasi_eot_minute
		$this->durasi_eot_minute->SetDbValueDef($rsnew, $this->durasi_eot_minute->CurrentValue, 0, strval($this->durasi_eot_minute->CurrentValue) == "");

		// jk_count_as
		$this->jk_count_as->SetDbValueDef($rsnew, $this->jk_count_as->CurrentValue, 0, strval($this->jk_count_as->CurrentValue) == "");

		// status_jk
		$this->status_jk->SetDbValueDef($rsnew, $this->status_jk->CurrentValue, 0, strval($this->status_jk->CurrentValue) == "");

		// keterangan
		$this->keterangan->SetDbValueDef($rsnew, $this->keterangan->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pegawai_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['tgl_shift']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['khusus_lembur']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['khusus_extra']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['temp_id_auto']) == "") {
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shift_resultlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($shift_result_add)) $shift_result_add = new cshift_result_add();

// Page init
$shift_result_add->Page_Init();

// Page main
$shift_result_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shift_result_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fshift_resultadd = new ew_Form("fshift_resultadd", "add");

// Validate form
fshift_resultadd.Validate = function() {
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
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->pegawai_id->FldCaption(), $shift_result->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_tgl_shift");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->tgl_shift->FldCaption(), $shift_result->tgl_shift->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_tgl_shift");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->tgl_shift->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_khusus_lembur");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->khusus_lembur->FldCaption(), $shift_result->khusus_lembur->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_khusus_lembur");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->khusus_lembur->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_khusus_extra");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->khusus_extra->FldCaption(), $shift_result->khusus_extra->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_khusus_extra");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->khusus_extra->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_temp_id_auto");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->temp_id_auto->FldCaption(), $shift_result->temp_id_auto->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_temp_id_auto");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->temp_id_auto->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->jdw_kerja_m_id->FldCaption(), $shift_result->jdw_kerja_m_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jdw_kerja_m_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->jdw_kerja_m_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->jk_id->FldCaption(), $shift_result->jk_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->jk_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jns_dok");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->jns_dok->FldCaption(), $shift_result->jns_dok->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jns_dok");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->jns_dok->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_izin_jenis_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->izin_jenis_id->FldCaption(), $shift_result->izin_jenis_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_izin_jenis_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->izin_jenis_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->cuti_n_id->FldCaption(), $shift_result->cuti_n_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_cuti_n_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->cuti_n_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_libur_umum");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->libur_umum->FldCaption(), $shift_result->libur_umum->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_libur_umum");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->libur_umum->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_libur_rutin");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->libur_rutin->FldCaption(), $shift_result->libur_rutin->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_libur_rutin");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->libur_rutin->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_ot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->jk_ot->FldCaption(), $shift_result->jk_ot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_ot");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->jk_ot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_scan_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->scan_in->FldCaption(), $shift_result->scan_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scan_in");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->scan_in->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_att_id_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->att_id_in->FldCaption(), $shift_result->att_id_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_late_permission");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->late_permission->FldCaption(), $shift_result->late_permission->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_late_permission");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->late_permission->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_late_minute");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->late_minute->FldCaption(), $shift_result->late_minute->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_late_minute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->late_minute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_late");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->late->FldCaption(), $shift_result->late->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_late");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->late->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_break_out");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->break_out->FldCaption(), $shift_result->break_out->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_out");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->break_out->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_att_id_break1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->att_id_break1->FldCaption(), $shift_result->att_id_break1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_in");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->break_in->FldCaption(), $shift_result->break_in->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_in");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->break_in->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_att_id_break2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->att_id_break2->FldCaption(), $shift_result->att_id_break2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_minute");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->break_minute->FldCaption(), $shift_result->break_minute->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_minute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->break_minute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_break");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->break->FldCaption(), $shift_result->break->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->break->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_break_ot_minute");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->break_ot_minute->FldCaption(), $shift_result->break_ot_minute->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_ot_minute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->break_ot_minute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_break_ot");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->break_ot->FldCaption(), $shift_result->break_ot->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_break_ot");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->break_ot->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_early_permission");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->early_permission->FldCaption(), $shift_result->early_permission->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_early_permission");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->early_permission->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_early_minute");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->early_minute->FldCaption(), $shift_result->early_minute->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_early_minute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->early_minute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_early");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->early->FldCaption(), $shift_result->early->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_early");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->early->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_scan_out");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->scan_out->FldCaption(), $shift_result->scan_out->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_scan_out");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->scan_out->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_att_id_out");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->att_id_out->FldCaption(), $shift_result->att_id_out->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_durasi_minute");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->durasi_minute->FldCaption(), $shift_result->durasi_minute->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_durasi_minute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->durasi_minute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_durasi");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->durasi->FldCaption(), $shift_result->durasi->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_durasi");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->durasi->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_durasi_eot_minute");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->durasi_eot_minute->FldCaption(), $shift_result->durasi_eot_minute->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_durasi_eot_minute");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->durasi_eot_minute->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jk_count_as");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->jk_count_as->FldCaption(), $shift_result->jk_count_as->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jk_count_as");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->jk_count_as->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_status_jk");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $shift_result->status_jk->FldCaption(), $shift_result->status_jk->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_status_jk");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($shift_result->status_jk->FldErrMsg()) ?>");

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
fshift_resultadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshift_resultadd.ValidateRequired = true;
<?php } else { ?>
fshift_resultadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$shift_result_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $shift_result_add->ShowPageHeader(); ?>
<?php
$shift_result_add->ShowMessage();
?>
<form name="fshift_resultadd" id="fshift_resultadd" class="<?php echo $shift_result_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shift_result_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shift_result_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shift_result">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($shift_result_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<div>
<?php if ($shift_result->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_shift_result_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->pegawai_id->CellAttributes() ?>>
<span id="el_shift_result_pegawai_id">
<input type="text" data-table="shift_result" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $shift_result->pegawai_id->EditValue ?>"<?php echo $shift_result->pegawai_id->EditAttributes() ?>>
</span>
<?php echo $shift_result->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->tgl_shift->Visible) { // tgl_shift ?>
	<div id="r_tgl_shift" class="form-group">
		<label id="elh_shift_result_tgl_shift" for="x_tgl_shift" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->tgl_shift->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->tgl_shift->CellAttributes() ?>>
<span id="el_shift_result_tgl_shift">
<input type="text" data-table="shift_result" data-field="x_tgl_shift" name="x_tgl_shift" id="x_tgl_shift" placeholder="<?php echo ew_HtmlEncode($shift_result->tgl_shift->getPlaceHolder()) ?>" value="<?php echo $shift_result->tgl_shift->EditValue ?>"<?php echo $shift_result->tgl_shift->EditAttributes() ?>>
</span>
<?php echo $shift_result->tgl_shift->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->khusus_lembur->Visible) { // khusus_lembur ?>
	<div id="r_khusus_lembur" class="form-group">
		<label id="elh_shift_result_khusus_lembur" for="x_khusus_lembur" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->khusus_lembur->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->khusus_lembur->CellAttributes() ?>>
<span id="el_shift_result_khusus_lembur">
<input type="text" data-table="shift_result" data-field="x_khusus_lembur" name="x_khusus_lembur" id="x_khusus_lembur" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->khusus_lembur->getPlaceHolder()) ?>" value="<?php echo $shift_result->khusus_lembur->EditValue ?>"<?php echo $shift_result->khusus_lembur->EditAttributes() ?>>
</span>
<?php echo $shift_result->khusus_lembur->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->khusus_extra->Visible) { // khusus_extra ?>
	<div id="r_khusus_extra" class="form-group">
		<label id="elh_shift_result_khusus_extra" for="x_khusus_extra" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->khusus_extra->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->khusus_extra->CellAttributes() ?>>
<span id="el_shift_result_khusus_extra">
<input type="text" data-table="shift_result" data-field="x_khusus_extra" name="x_khusus_extra" id="x_khusus_extra" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->khusus_extra->getPlaceHolder()) ?>" value="<?php echo $shift_result->khusus_extra->EditValue ?>"<?php echo $shift_result->khusus_extra->EditAttributes() ?>>
</span>
<?php echo $shift_result->khusus_extra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->temp_id_auto->Visible) { // temp_id_auto ?>
	<div id="r_temp_id_auto" class="form-group">
		<label id="elh_shift_result_temp_id_auto" for="x_temp_id_auto" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->temp_id_auto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->temp_id_auto->CellAttributes() ?>>
<span id="el_shift_result_temp_id_auto">
<input type="text" data-table="shift_result" data-field="x_temp_id_auto" name="x_temp_id_auto" id="x_temp_id_auto" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->temp_id_auto->getPlaceHolder()) ?>" value="<?php echo $shift_result->temp_id_auto->EditValue ?>"<?php echo $shift_result->temp_id_auto->EditAttributes() ?>>
</span>
<?php echo $shift_result->temp_id_auto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
	<div id="r_jdw_kerja_m_id" class="form-group">
		<label id="elh_shift_result_jdw_kerja_m_id" for="x_jdw_kerja_m_id" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->jdw_kerja_m_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el_shift_result_jdw_kerja_m_id">
<input type="text" data-table="shift_result" data-field="x_jdw_kerja_m_id" name="x_jdw_kerja_m_id" id="x_jdw_kerja_m_id" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->jdw_kerja_m_id->getPlaceHolder()) ?>" value="<?php echo $shift_result->jdw_kerja_m_id->EditValue ?>"<?php echo $shift_result->jdw_kerja_m_id->EditAttributes() ?>>
</span>
<?php echo $shift_result->jdw_kerja_m_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->jk_id->Visible) { // jk_id ?>
	<div id="r_jk_id" class="form-group">
		<label id="elh_shift_result_jk_id" for="x_jk_id" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->jk_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->jk_id->CellAttributes() ?>>
<span id="el_shift_result_jk_id">
<input type="text" data-table="shift_result" data-field="x_jk_id" name="x_jk_id" id="x_jk_id" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->jk_id->getPlaceHolder()) ?>" value="<?php echo $shift_result->jk_id->EditValue ?>"<?php echo $shift_result->jk_id->EditAttributes() ?>>
</span>
<?php echo $shift_result->jk_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->jns_dok->Visible) { // jns_dok ?>
	<div id="r_jns_dok" class="form-group">
		<label id="elh_shift_result_jns_dok" for="x_jns_dok" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->jns_dok->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->jns_dok->CellAttributes() ?>>
<span id="el_shift_result_jns_dok">
<input type="text" data-table="shift_result" data-field="x_jns_dok" name="x_jns_dok" id="x_jns_dok" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->jns_dok->getPlaceHolder()) ?>" value="<?php echo $shift_result->jns_dok->EditValue ?>"<?php echo $shift_result->jns_dok->EditAttributes() ?>>
</span>
<?php echo $shift_result->jns_dok->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->izin_jenis_id->Visible) { // izin_jenis_id ?>
	<div id="r_izin_jenis_id" class="form-group">
		<label id="elh_shift_result_izin_jenis_id" for="x_izin_jenis_id" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->izin_jenis_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->izin_jenis_id->CellAttributes() ?>>
<span id="el_shift_result_izin_jenis_id">
<input type="text" data-table="shift_result" data-field="x_izin_jenis_id" name="x_izin_jenis_id" id="x_izin_jenis_id" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->izin_jenis_id->getPlaceHolder()) ?>" value="<?php echo $shift_result->izin_jenis_id->EditValue ?>"<?php echo $shift_result->izin_jenis_id->EditAttributes() ?>>
</span>
<?php echo $shift_result->izin_jenis_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->cuti_n_id->Visible) { // cuti_n_id ?>
	<div id="r_cuti_n_id" class="form-group">
		<label id="elh_shift_result_cuti_n_id" for="x_cuti_n_id" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->cuti_n_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->cuti_n_id->CellAttributes() ?>>
<span id="el_shift_result_cuti_n_id">
<input type="text" data-table="shift_result" data-field="x_cuti_n_id" name="x_cuti_n_id" id="x_cuti_n_id" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->cuti_n_id->getPlaceHolder()) ?>" value="<?php echo $shift_result->cuti_n_id->EditValue ?>"<?php echo $shift_result->cuti_n_id->EditAttributes() ?>>
</span>
<?php echo $shift_result->cuti_n_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->libur_umum->Visible) { // libur_umum ?>
	<div id="r_libur_umum" class="form-group">
		<label id="elh_shift_result_libur_umum" for="x_libur_umum" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->libur_umum->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->libur_umum->CellAttributes() ?>>
<span id="el_shift_result_libur_umum">
<input type="text" data-table="shift_result" data-field="x_libur_umum" name="x_libur_umum" id="x_libur_umum" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->libur_umum->getPlaceHolder()) ?>" value="<?php echo $shift_result->libur_umum->EditValue ?>"<?php echo $shift_result->libur_umum->EditAttributes() ?>>
</span>
<?php echo $shift_result->libur_umum->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->libur_rutin->Visible) { // libur_rutin ?>
	<div id="r_libur_rutin" class="form-group">
		<label id="elh_shift_result_libur_rutin" for="x_libur_rutin" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->libur_rutin->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->libur_rutin->CellAttributes() ?>>
<span id="el_shift_result_libur_rutin">
<input type="text" data-table="shift_result" data-field="x_libur_rutin" name="x_libur_rutin" id="x_libur_rutin" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->libur_rutin->getPlaceHolder()) ?>" value="<?php echo $shift_result->libur_rutin->EditValue ?>"<?php echo $shift_result->libur_rutin->EditAttributes() ?>>
</span>
<?php echo $shift_result->libur_rutin->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->jk_ot->Visible) { // jk_ot ?>
	<div id="r_jk_ot" class="form-group">
		<label id="elh_shift_result_jk_ot" for="x_jk_ot" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->jk_ot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->jk_ot->CellAttributes() ?>>
<span id="el_shift_result_jk_ot">
<input type="text" data-table="shift_result" data-field="x_jk_ot" name="x_jk_ot" id="x_jk_ot" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->jk_ot->getPlaceHolder()) ?>" value="<?php echo $shift_result->jk_ot->EditValue ?>"<?php echo $shift_result->jk_ot->EditAttributes() ?>>
</span>
<?php echo $shift_result->jk_ot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->scan_in->Visible) { // scan_in ?>
	<div id="r_scan_in" class="form-group">
		<label id="elh_shift_result_scan_in" for="x_scan_in" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->scan_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->scan_in->CellAttributes() ?>>
<span id="el_shift_result_scan_in">
<input type="text" data-table="shift_result" data-field="x_scan_in" name="x_scan_in" id="x_scan_in" placeholder="<?php echo ew_HtmlEncode($shift_result->scan_in->getPlaceHolder()) ?>" value="<?php echo $shift_result->scan_in->EditValue ?>"<?php echo $shift_result->scan_in->EditAttributes() ?>>
</span>
<?php echo $shift_result->scan_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->att_id_in->Visible) { // att_id_in ?>
	<div id="r_att_id_in" class="form-group">
		<label id="elh_shift_result_att_id_in" for="x_att_id_in" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->att_id_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->att_id_in->CellAttributes() ?>>
<span id="el_shift_result_att_id_in">
<input type="text" data-table="shift_result" data-field="x_att_id_in" name="x_att_id_in" id="x_att_id_in" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($shift_result->att_id_in->getPlaceHolder()) ?>" value="<?php echo $shift_result->att_id_in->EditValue ?>"<?php echo $shift_result->att_id_in->EditAttributes() ?>>
</span>
<?php echo $shift_result->att_id_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->late_permission->Visible) { // late_permission ?>
	<div id="r_late_permission" class="form-group">
		<label id="elh_shift_result_late_permission" for="x_late_permission" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->late_permission->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->late_permission->CellAttributes() ?>>
<span id="el_shift_result_late_permission">
<input type="text" data-table="shift_result" data-field="x_late_permission" name="x_late_permission" id="x_late_permission" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->late_permission->getPlaceHolder()) ?>" value="<?php echo $shift_result->late_permission->EditValue ?>"<?php echo $shift_result->late_permission->EditAttributes() ?>>
</span>
<?php echo $shift_result->late_permission->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->late_minute->Visible) { // late_minute ?>
	<div id="r_late_minute" class="form-group">
		<label id="elh_shift_result_late_minute" for="x_late_minute" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->late_minute->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->late_minute->CellAttributes() ?>>
<span id="el_shift_result_late_minute">
<input type="text" data-table="shift_result" data-field="x_late_minute" name="x_late_minute" id="x_late_minute" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->late_minute->getPlaceHolder()) ?>" value="<?php echo $shift_result->late_minute->EditValue ?>"<?php echo $shift_result->late_minute->EditAttributes() ?>>
</span>
<?php echo $shift_result->late_minute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->late->Visible) { // late ?>
	<div id="r_late" class="form-group">
		<label id="elh_shift_result_late" for="x_late" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->late->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->late->CellAttributes() ?>>
<span id="el_shift_result_late">
<input type="text" data-table="shift_result" data-field="x_late" name="x_late" id="x_late" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->late->getPlaceHolder()) ?>" value="<?php echo $shift_result->late->EditValue ?>"<?php echo $shift_result->late->EditAttributes() ?>>
</span>
<?php echo $shift_result->late->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->break_out->Visible) { // break_out ?>
	<div id="r_break_out" class="form-group">
		<label id="elh_shift_result_break_out" for="x_break_out" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->break_out->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->break_out->CellAttributes() ?>>
<span id="el_shift_result_break_out">
<input type="text" data-table="shift_result" data-field="x_break_out" name="x_break_out" id="x_break_out" placeholder="<?php echo ew_HtmlEncode($shift_result->break_out->getPlaceHolder()) ?>" value="<?php echo $shift_result->break_out->EditValue ?>"<?php echo $shift_result->break_out->EditAttributes() ?>>
</span>
<?php echo $shift_result->break_out->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->att_id_break1->Visible) { // att_id_break1 ?>
	<div id="r_att_id_break1" class="form-group">
		<label id="elh_shift_result_att_id_break1" for="x_att_id_break1" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->att_id_break1->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->att_id_break1->CellAttributes() ?>>
<span id="el_shift_result_att_id_break1">
<input type="text" data-table="shift_result" data-field="x_att_id_break1" name="x_att_id_break1" id="x_att_id_break1" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($shift_result->att_id_break1->getPlaceHolder()) ?>" value="<?php echo $shift_result->att_id_break1->EditValue ?>"<?php echo $shift_result->att_id_break1->EditAttributes() ?>>
</span>
<?php echo $shift_result->att_id_break1->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->break_in->Visible) { // break_in ?>
	<div id="r_break_in" class="form-group">
		<label id="elh_shift_result_break_in" for="x_break_in" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->break_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->break_in->CellAttributes() ?>>
<span id="el_shift_result_break_in">
<input type="text" data-table="shift_result" data-field="x_break_in" name="x_break_in" id="x_break_in" placeholder="<?php echo ew_HtmlEncode($shift_result->break_in->getPlaceHolder()) ?>" value="<?php echo $shift_result->break_in->EditValue ?>"<?php echo $shift_result->break_in->EditAttributes() ?>>
</span>
<?php echo $shift_result->break_in->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->att_id_break2->Visible) { // att_id_break2 ?>
	<div id="r_att_id_break2" class="form-group">
		<label id="elh_shift_result_att_id_break2" for="x_att_id_break2" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->att_id_break2->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->att_id_break2->CellAttributes() ?>>
<span id="el_shift_result_att_id_break2">
<input type="text" data-table="shift_result" data-field="x_att_id_break2" name="x_att_id_break2" id="x_att_id_break2" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($shift_result->att_id_break2->getPlaceHolder()) ?>" value="<?php echo $shift_result->att_id_break2->EditValue ?>"<?php echo $shift_result->att_id_break2->EditAttributes() ?>>
</span>
<?php echo $shift_result->att_id_break2->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->break_minute->Visible) { // break_minute ?>
	<div id="r_break_minute" class="form-group">
		<label id="elh_shift_result_break_minute" for="x_break_minute" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->break_minute->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->break_minute->CellAttributes() ?>>
<span id="el_shift_result_break_minute">
<input type="text" data-table="shift_result" data-field="x_break_minute" name="x_break_minute" id="x_break_minute" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->break_minute->getPlaceHolder()) ?>" value="<?php echo $shift_result->break_minute->EditValue ?>"<?php echo $shift_result->break_minute->EditAttributes() ?>>
</span>
<?php echo $shift_result->break_minute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->break->Visible) { // break ?>
	<div id="r_break" class="form-group">
		<label id="elh_shift_result_break" for="x_break" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->break->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->break->CellAttributes() ?>>
<span id="el_shift_result_break">
<input type="text" data-table="shift_result" data-field="x_break" name="x_break" id="x_break" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->break->getPlaceHolder()) ?>" value="<?php echo $shift_result->break->EditValue ?>"<?php echo $shift_result->break->EditAttributes() ?>>
</span>
<?php echo $shift_result->break->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->break_ot_minute->Visible) { // break_ot_minute ?>
	<div id="r_break_ot_minute" class="form-group">
		<label id="elh_shift_result_break_ot_minute" for="x_break_ot_minute" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->break_ot_minute->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->break_ot_minute->CellAttributes() ?>>
<span id="el_shift_result_break_ot_minute">
<input type="text" data-table="shift_result" data-field="x_break_ot_minute" name="x_break_ot_minute" id="x_break_ot_minute" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->break_ot_minute->getPlaceHolder()) ?>" value="<?php echo $shift_result->break_ot_minute->EditValue ?>"<?php echo $shift_result->break_ot_minute->EditAttributes() ?>>
</span>
<?php echo $shift_result->break_ot_minute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->break_ot->Visible) { // break_ot ?>
	<div id="r_break_ot" class="form-group">
		<label id="elh_shift_result_break_ot" for="x_break_ot" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->break_ot->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->break_ot->CellAttributes() ?>>
<span id="el_shift_result_break_ot">
<input type="text" data-table="shift_result" data-field="x_break_ot" name="x_break_ot" id="x_break_ot" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->break_ot->getPlaceHolder()) ?>" value="<?php echo $shift_result->break_ot->EditValue ?>"<?php echo $shift_result->break_ot->EditAttributes() ?>>
</span>
<?php echo $shift_result->break_ot->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->early_permission->Visible) { // early_permission ?>
	<div id="r_early_permission" class="form-group">
		<label id="elh_shift_result_early_permission" for="x_early_permission" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->early_permission->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->early_permission->CellAttributes() ?>>
<span id="el_shift_result_early_permission">
<input type="text" data-table="shift_result" data-field="x_early_permission" name="x_early_permission" id="x_early_permission" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->early_permission->getPlaceHolder()) ?>" value="<?php echo $shift_result->early_permission->EditValue ?>"<?php echo $shift_result->early_permission->EditAttributes() ?>>
</span>
<?php echo $shift_result->early_permission->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->early_minute->Visible) { // early_minute ?>
	<div id="r_early_minute" class="form-group">
		<label id="elh_shift_result_early_minute" for="x_early_minute" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->early_minute->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->early_minute->CellAttributes() ?>>
<span id="el_shift_result_early_minute">
<input type="text" data-table="shift_result" data-field="x_early_minute" name="x_early_minute" id="x_early_minute" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->early_minute->getPlaceHolder()) ?>" value="<?php echo $shift_result->early_minute->EditValue ?>"<?php echo $shift_result->early_minute->EditAttributes() ?>>
</span>
<?php echo $shift_result->early_minute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->early->Visible) { // early ?>
	<div id="r_early" class="form-group">
		<label id="elh_shift_result_early" for="x_early" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->early->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->early->CellAttributes() ?>>
<span id="el_shift_result_early">
<input type="text" data-table="shift_result" data-field="x_early" name="x_early" id="x_early" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->early->getPlaceHolder()) ?>" value="<?php echo $shift_result->early->EditValue ?>"<?php echo $shift_result->early->EditAttributes() ?>>
</span>
<?php echo $shift_result->early->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->scan_out->Visible) { // scan_out ?>
	<div id="r_scan_out" class="form-group">
		<label id="elh_shift_result_scan_out" for="x_scan_out" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->scan_out->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->scan_out->CellAttributes() ?>>
<span id="el_shift_result_scan_out">
<input type="text" data-table="shift_result" data-field="x_scan_out" name="x_scan_out" id="x_scan_out" placeholder="<?php echo ew_HtmlEncode($shift_result->scan_out->getPlaceHolder()) ?>" value="<?php echo $shift_result->scan_out->EditValue ?>"<?php echo $shift_result->scan_out->EditAttributes() ?>>
</span>
<?php echo $shift_result->scan_out->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->att_id_out->Visible) { // att_id_out ?>
	<div id="r_att_id_out" class="form-group">
		<label id="elh_shift_result_att_id_out" for="x_att_id_out" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->att_id_out->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->att_id_out->CellAttributes() ?>>
<span id="el_shift_result_att_id_out">
<input type="text" data-table="shift_result" data-field="x_att_id_out" name="x_att_id_out" id="x_att_id_out" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($shift_result->att_id_out->getPlaceHolder()) ?>" value="<?php echo $shift_result->att_id_out->EditValue ?>"<?php echo $shift_result->att_id_out->EditAttributes() ?>>
</span>
<?php echo $shift_result->att_id_out->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->durasi_minute->Visible) { // durasi_minute ?>
	<div id="r_durasi_minute" class="form-group">
		<label id="elh_shift_result_durasi_minute" for="x_durasi_minute" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->durasi_minute->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->durasi_minute->CellAttributes() ?>>
<span id="el_shift_result_durasi_minute">
<input type="text" data-table="shift_result" data-field="x_durasi_minute" name="x_durasi_minute" id="x_durasi_minute" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->durasi_minute->getPlaceHolder()) ?>" value="<?php echo $shift_result->durasi_minute->EditValue ?>"<?php echo $shift_result->durasi_minute->EditAttributes() ?>>
</span>
<?php echo $shift_result->durasi_minute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->durasi->Visible) { // durasi ?>
	<div id="r_durasi" class="form-group">
		<label id="elh_shift_result_durasi" for="x_durasi" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->durasi->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->durasi->CellAttributes() ?>>
<span id="el_shift_result_durasi">
<input type="text" data-table="shift_result" data-field="x_durasi" name="x_durasi" id="x_durasi" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->durasi->getPlaceHolder()) ?>" value="<?php echo $shift_result->durasi->EditValue ?>"<?php echo $shift_result->durasi->EditAttributes() ?>>
</span>
<?php echo $shift_result->durasi->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->durasi_eot_minute->Visible) { // durasi_eot_minute ?>
	<div id="r_durasi_eot_minute" class="form-group">
		<label id="elh_shift_result_durasi_eot_minute" for="x_durasi_eot_minute" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->durasi_eot_minute->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->durasi_eot_minute->CellAttributes() ?>>
<span id="el_shift_result_durasi_eot_minute">
<input type="text" data-table="shift_result" data-field="x_durasi_eot_minute" name="x_durasi_eot_minute" id="x_durasi_eot_minute" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->durasi_eot_minute->getPlaceHolder()) ?>" value="<?php echo $shift_result->durasi_eot_minute->EditValue ?>"<?php echo $shift_result->durasi_eot_minute->EditAttributes() ?>>
</span>
<?php echo $shift_result->durasi_eot_minute->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->jk_count_as->Visible) { // jk_count_as ?>
	<div id="r_jk_count_as" class="form-group">
		<label id="elh_shift_result_jk_count_as" for="x_jk_count_as" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->jk_count_as->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->jk_count_as->CellAttributes() ?>>
<span id="el_shift_result_jk_count_as">
<input type="text" data-table="shift_result" data-field="x_jk_count_as" name="x_jk_count_as" id="x_jk_count_as" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->jk_count_as->getPlaceHolder()) ?>" value="<?php echo $shift_result->jk_count_as->EditValue ?>"<?php echo $shift_result->jk_count_as->EditAttributes() ?>>
</span>
<?php echo $shift_result->jk_count_as->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->status_jk->Visible) { // status_jk ?>
	<div id="r_status_jk" class="form-group">
		<label id="elh_shift_result_status_jk" for="x_status_jk" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->status_jk->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->status_jk->CellAttributes() ?>>
<span id="el_shift_result_status_jk">
<input type="text" data-table="shift_result" data-field="x_status_jk" name="x_status_jk" id="x_status_jk" size="30" placeholder="<?php echo ew_HtmlEncode($shift_result->status_jk->getPlaceHolder()) ?>" value="<?php echo $shift_result->status_jk->EditValue ?>"<?php echo $shift_result->status_jk->EditAttributes() ?>>
</span>
<?php echo $shift_result->status_jk->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($shift_result->keterangan->Visible) { // keterangan ?>
	<div id="r_keterangan" class="form-group">
		<label id="elh_shift_result_keterangan" for="x_keterangan" class="col-sm-2 control-label ewLabel"><?php echo $shift_result->keterangan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $shift_result->keterangan->CellAttributes() ?>>
<span id="el_shift_result_keterangan">
<textarea data-table="shift_result" data-field="x_keterangan" name="x_keterangan" id="x_keterangan" cols="35" rows="4" placeholder="<?php echo ew_HtmlEncode($shift_result->keterangan->getPlaceHolder()) ?>"<?php echo $shift_result->keterangan->EditAttributes() ?>><?php echo $shift_result->keterangan->EditValue ?></textarea>
</span>
<?php echo $shift_result->keterangan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$shift_result_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $shift_result_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fshift_resultadd.Init();
</script>
<?php
$shift_result_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$shift_result_add->Page_Terminate();
?>
