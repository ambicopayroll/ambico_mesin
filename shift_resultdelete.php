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

$shift_result_delete = NULL; // Initialize page object first

class cshift_result_delete extends cshift_result {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'shift_result';

	// Page object name
	var $PageObjName = 'shift_result_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("shift_resultlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in shift_result class, shift_resultinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("shift_resultlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['pegawai_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['tgl_shift'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['khusus_lembur'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['khusus_extra'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['temp_id_auto'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("shift_resultlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($shift_result_delete)) $shift_result_delete = new cshift_result_delete();

// Page init
$shift_result_delete->Page_Init();

// Page main
$shift_result_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$shift_result_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fshift_resultdelete = new ew_Form("fshift_resultdelete", "delete");

// Form_CustomValidate event
fshift_resultdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fshift_resultdelete.ValidateRequired = true;
<?php } else { ?>
fshift_resultdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $shift_result_delete->ShowPageHeader(); ?>
<?php
$shift_result_delete->ShowMessage();
?>
<form name="fshift_resultdelete" id="fshift_resultdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($shift_result_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $shift_result_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="shift_result">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($shift_result_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $shift_result->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($shift_result->pegawai_id->Visible) { // pegawai_id ?>
		<th><span id="elh_shift_result_pegawai_id" class="shift_result_pegawai_id"><?php echo $shift_result->pegawai_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->tgl_shift->Visible) { // tgl_shift ?>
		<th><span id="elh_shift_result_tgl_shift" class="shift_result_tgl_shift"><?php echo $shift_result->tgl_shift->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->khusus_lembur->Visible) { // khusus_lembur ?>
		<th><span id="elh_shift_result_khusus_lembur" class="shift_result_khusus_lembur"><?php echo $shift_result->khusus_lembur->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->khusus_extra->Visible) { // khusus_extra ?>
		<th><span id="elh_shift_result_khusus_extra" class="shift_result_khusus_extra"><?php echo $shift_result->khusus_extra->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->temp_id_auto->Visible) { // temp_id_auto ?>
		<th><span id="elh_shift_result_temp_id_auto" class="shift_result_temp_id_auto"><?php echo $shift_result->temp_id_auto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
		<th><span id="elh_shift_result_jdw_kerja_m_id" class="shift_result_jdw_kerja_m_id"><?php echo $shift_result->jdw_kerja_m_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->jk_id->Visible) { // jk_id ?>
		<th><span id="elh_shift_result_jk_id" class="shift_result_jk_id"><?php echo $shift_result->jk_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->jns_dok->Visible) { // jns_dok ?>
		<th><span id="elh_shift_result_jns_dok" class="shift_result_jns_dok"><?php echo $shift_result->jns_dok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->izin_jenis_id->Visible) { // izin_jenis_id ?>
		<th><span id="elh_shift_result_izin_jenis_id" class="shift_result_izin_jenis_id"><?php echo $shift_result->izin_jenis_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->cuti_n_id->Visible) { // cuti_n_id ?>
		<th><span id="elh_shift_result_cuti_n_id" class="shift_result_cuti_n_id"><?php echo $shift_result->cuti_n_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->libur_umum->Visible) { // libur_umum ?>
		<th><span id="elh_shift_result_libur_umum" class="shift_result_libur_umum"><?php echo $shift_result->libur_umum->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->libur_rutin->Visible) { // libur_rutin ?>
		<th><span id="elh_shift_result_libur_rutin" class="shift_result_libur_rutin"><?php echo $shift_result->libur_rutin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->jk_ot->Visible) { // jk_ot ?>
		<th><span id="elh_shift_result_jk_ot" class="shift_result_jk_ot"><?php echo $shift_result->jk_ot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->scan_in->Visible) { // scan_in ?>
		<th><span id="elh_shift_result_scan_in" class="shift_result_scan_in"><?php echo $shift_result->scan_in->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->att_id_in->Visible) { // att_id_in ?>
		<th><span id="elh_shift_result_att_id_in" class="shift_result_att_id_in"><?php echo $shift_result->att_id_in->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->late_permission->Visible) { // late_permission ?>
		<th><span id="elh_shift_result_late_permission" class="shift_result_late_permission"><?php echo $shift_result->late_permission->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->late_minute->Visible) { // late_minute ?>
		<th><span id="elh_shift_result_late_minute" class="shift_result_late_minute"><?php echo $shift_result->late_minute->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->late->Visible) { // late ?>
		<th><span id="elh_shift_result_late" class="shift_result_late"><?php echo $shift_result->late->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->break_out->Visible) { // break_out ?>
		<th><span id="elh_shift_result_break_out" class="shift_result_break_out"><?php echo $shift_result->break_out->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->att_id_break1->Visible) { // att_id_break1 ?>
		<th><span id="elh_shift_result_att_id_break1" class="shift_result_att_id_break1"><?php echo $shift_result->att_id_break1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->break_in->Visible) { // break_in ?>
		<th><span id="elh_shift_result_break_in" class="shift_result_break_in"><?php echo $shift_result->break_in->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->att_id_break2->Visible) { // att_id_break2 ?>
		<th><span id="elh_shift_result_att_id_break2" class="shift_result_att_id_break2"><?php echo $shift_result->att_id_break2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->break_minute->Visible) { // break_minute ?>
		<th><span id="elh_shift_result_break_minute" class="shift_result_break_minute"><?php echo $shift_result->break_minute->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->break->Visible) { // break ?>
		<th><span id="elh_shift_result_break" class="shift_result_break"><?php echo $shift_result->break->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->break_ot_minute->Visible) { // break_ot_minute ?>
		<th><span id="elh_shift_result_break_ot_minute" class="shift_result_break_ot_minute"><?php echo $shift_result->break_ot_minute->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->break_ot->Visible) { // break_ot ?>
		<th><span id="elh_shift_result_break_ot" class="shift_result_break_ot"><?php echo $shift_result->break_ot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->early_permission->Visible) { // early_permission ?>
		<th><span id="elh_shift_result_early_permission" class="shift_result_early_permission"><?php echo $shift_result->early_permission->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->early_minute->Visible) { // early_minute ?>
		<th><span id="elh_shift_result_early_minute" class="shift_result_early_minute"><?php echo $shift_result->early_minute->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->early->Visible) { // early ?>
		<th><span id="elh_shift_result_early" class="shift_result_early"><?php echo $shift_result->early->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->scan_out->Visible) { // scan_out ?>
		<th><span id="elh_shift_result_scan_out" class="shift_result_scan_out"><?php echo $shift_result->scan_out->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->att_id_out->Visible) { // att_id_out ?>
		<th><span id="elh_shift_result_att_id_out" class="shift_result_att_id_out"><?php echo $shift_result->att_id_out->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->durasi_minute->Visible) { // durasi_minute ?>
		<th><span id="elh_shift_result_durasi_minute" class="shift_result_durasi_minute"><?php echo $shift_result->durasi_minute->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->durasi->Visible) { // durasi ?>
		<th><span id="elh_shift_result_durasi" class="shift_result_durasi"><?php echo $shift_result->durasi->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->durasi_eot_minute->Visible) { // durasi_eot_minute ?>
		<th><span id="elh_shift_result_durasi_eot_minute" class="shift_result_durasi_eot_minute"><?php echo $shift_result->durasi_eot_minute->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->jk_count_as->Visible) { // jk_count_as ?>
		<th><span id="elh_shift_result_jk_count_as" class="shift_result_jk_count_as"><?php echo $shift_result->jk_count_as->FldCaption() ?></span></th>
<?php } ?>
<?php if ($shift_result->status_jk->Visible) { // status_jk ?>
		<th><span id="elh_shift_result_status_jk" class="shift_result_status_jk"><?php echo $shift_result->status_jk->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$shift_result_delete->RecCnt = 0;
$i = 0;
while (!$shift_result_delete->Recordset->EOF) {
	$shift_result_delete->RecCnt++;
	$shift_result_delete->RowCnt++;

	// Set row properties
	$shift_result->ResetAttrs();
	$shift_result->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$shift_result_delete->LoadRowValues($shift_result_delete->Recordset);

	// Render row
	$shift_result_delete->RenderRow();
?>
	<tr<?php echo $shift_result->RowAttributes() ?>>
<?php if ($shift_result->pegawai_id->Visible) { // pegawai_id ?>
		<td<?php echo $shift_result->pegawai_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_pegawai_id" class="shift_result_pegawai_id">
<span<?php echo $shift_result->pegawai_id->ViewAttributes() ?>>
<?php echo $shift_result->pegawai_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->tgl_shift->Visible) { // tgl_shift ?>
		<td<?php echo $shift_result->tgl_shift->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_tgl_shift" class="shift_result_tgl_shift">
<span<?php echo $shift_result->tgl_shift->ViewAttributes() ?>>
<?php echo $shift_result->tgl_shift->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->khusus_lembur->Visible) { // khusus_lembur ?>
		<td<?php echo $shift_result->khusus_lembur->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_khusus_lembur" class="shift_result_khusus_lembur">
<span<?php echo $shift_result->khusus_lembur->ViewAttributes() ?>>
<?php echo $shift_result->khusus_lembur->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->khusus_extra->Visible) { // khusus_extra ?>
		<td<?php echo $shift_result->khusus_extra->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_khusus_extra" class="shift_result_khusus_extra">
<span<?php echo $shift_result->khusus_extra->ViewAttributes() ?>>
<?php echo $shift_result->khusus_extra->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->temp_id_auto->Visible) { // temp_id_auto ?>
		<td<?php echo $shift_result->temp_id_auto->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_temp_id_auto" class="shift_result_temp_id_auto">
<span<?php echo $shift_result->temp_id_auto->ViewAttributes() ?>>
<?php echo $shift_result->temp_id_auto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
		<td<?php echo $shift_result->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_jdw_kerja_m_id" class="shift_result_jdw_kerja_m_id">
<span<?php echo $shift_result->jdw_kerja_m_id->ViewAttributes() ?>>
<?php echo $shift_result->jdw_kerja_m_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->jk_id->Visible) { // jk_id ?>
		<td<?php echo $shift_result->jk_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_jk_id" class="shift_result_jk_id">
<span<?php echo $shift_result->jk_id->ViewAttributes() ?>>
<?php echo $shift_result->jk_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->jns_dok->Visible) { // jns_dok ?>
		<td<?php echo $shift_result->jns_dok->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_jns_dok" class="shift_result_jns_dok">
<span<?php echo $shift_result->jns_dok->ViewAttributes() ?>>
<?php echo $shift_result->jns_dok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->izin_jenis_id->Visible) { // izin_jenis_id ?>
		<td<?php echo $shift_result->izin_jenis_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_izin_jenis_id" class="shift_result_izin_jenis_id">
<span<?php echo $shift_result->izin_jenis_id->ViewAttributes() ?>>
<?php echo $shift_result->izin_jenis_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->cuti_n_id->Visible) { // cuti_n_id ?>
		<td<?php echo $shift_result->cuti_n_id->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_cuti_n_id" class="shift_result_cuti_n_id">
<span<?php echo $shift_result->cuti_n_id->ViewAttributes() ?>>
<?php echo $shift_result->cuti_n_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->libur_umum->Visible) { // libur_umum ?>
		<td<?php echo $shift_result->libur_umum->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_libur_umum" class="shift_result_libur_umum">
<span<?php echo $shift_result->libur_umum->ViewAttributes() ?>>
<?php echo $shift_result->libur_umum->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->libur_rutin->Visible) { // libur_rutin ?>
		<td<?php echo $shift_result->libur_rutin->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_libur_rutin" class="shift_result_libur_rutin">
<span<?php echo $shift_result->libur_rutin->ViewAttributes() ?>>
<?php echo $shift_result->libur_rutin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->jk_ot->Visible) { // jk_ot ?>
		<td<?php echo $shift_result->jk_ot->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_jk_ot" class="shift_result_jk_ot">
<span<?php echo $shift_result->jk_ot->ViewAttributes() ?>>
<?php echo $shift_result->jk_ot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->scan_in->Visible) { // scan_in ?>
		<td<?php echo $shift_result->scan_in->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_scan_in" class="shift_result_scan_in">
<span<?php echo $shift_result->scan_in->ViewAttributes() ?>>
<?php echo $shift_result->scan_in->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->att_id_in->Visible) { // att_id_in ?>
		<td<?php echo $shift_result->att_id_in->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_att_id_in" class="shift_result_att_id_in">
<span<?php echo $shift_result->att_id_in->ViewAttributes() ?>>
<?php echo $shift_result->att_id_in->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->late_permission->Visible) { // late_permission ?>
		<td<?php echo $shift_result->late_permission->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_late_permission" class="shift_result_late_permission">
<span<?php echo $shift_result->late_permission->ViewAttributes() ?>>
<?php echo $shift_result->late_permission->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->late_minute->Visible) { // late_minute ?>
		<td<?php echo $shift_result->late_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_late_minute" class="shift_result_late_minute">
<span<?php echo $shift_result->late_minute->ViewAttributes() ?>>
<?php echo $shift_result->late_minute->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->late->Visible) { // late ?>
		<td<?php echo $shift_result->late->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_late" class="shift_result_late">
<span<?php echo $shift_result->late->ViewAttributes() ?>>
<?php echo $shift_result->late->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->break_out->Visible) { // break_out ?>
		<td<?php echo $shift_result->break_out->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_break_out" class="shift_result_break_out">
<span<?php echo $shift_result->break_out->ViewAttributes() ?>>
<?php echo $shift_result->break_out->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->att_id_break1->Visible) { // att_id_break1 ?>
		<td<?php echo $shift_result->att_id_break1->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_att_id_break1" class="shift_result_att_id_break1">
<span<?php echo $shift_result->att_id_break1->ViewAttributes() ?>>
<?php echo $shift_result->att_id_break1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->break_in->Visible) { // break_in ?>
		<td<?php echo $shift_result->break_in->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_break_in" class="shift_result_break_in">
<span<?php echo $shift_result->break_in->ViewAttributes() ?>>
<?php echo $shift_result->break_in->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->att_id_break2->Visible) { // att_id_break2 ?>
		<td<?php echo $shift_result->att_id_break2->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_att_id_break2" class="shift_result_att_id_break2">
<span<?php echo $shift_result->att_id_break2->ViewAttributes() ?>>
<?php echo $shift_result->att_id_break2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->break_minute->Visible) { // break_minute ?>
		<td<?php echo $shift_result->break_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_break_minute" class="shift_result_break_minute">
<span<?php echo $shift_result->break_minute->ViewAttributes() ?>>
<?php echo $shift_result->break_minute->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->break->Visible) { // break ?>
		<td<?php echo $shift_result->break->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_break" class="shift_result_break">
<span<?php echo $shift_result->break->ViewAttributes() ?>>
<?php echo $shift_result->break->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->break_ot_minute->Visible) { // break_ot_minute ?>
		<td<?php echo $shift_result->break_ot_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_break_ot_minute" class="shift_result_break_ot_minute">
<span<?php echo $shift_result->break_ot_minute->ViewAttributes() ?>>
<?php echo $shift_result->break_ot_minute->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->break_ot->Visible) { // break_ot ?>
		<td<?php echo $shift_result->break_ot->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_break_ot" class="shift_result_break_ot">
<span<?php echo $shift_result->break_ot->ViewAttributes() ?>>
<?php echo $shift_result->break_ot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->early_permission->Visible) { // early_permission ?>
		<td<?php echo $shift_result->early_permission->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_early_permission" class="shift_result_early_permission">
<span<?php echo $shift_result->early_permission->ViewAttributes() ?>>
<?php echo $shift_result->early_permission->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->early_minute->Visible) { // early_minute ?>
		<td<?php echo $shift_result->early_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_early_minute" class="shift_result_early_minute">
<span<?php echo $shift_result->early_minute->ViewAttributes() ?>>
<?php echo $shift_result->early_minute->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->early->Visible) { // early ?>
		<td<?php echo $shift_result->early->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_early" class="shift_result_early">
<span<?php echo $shift_result->early->ViewAttributes() ?>>
<?php echo $shift_result->early->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->scan_out->Visible) { // scan_out ?>
		<td<?php echo $shift_result->scan_out->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_scan_out" class="shift_result_scan_out">
<span<?php echo $shift_result->scan_out->ViewAttributes() ?>>
<?php echo $shift_result->scan_out->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->att_id_out->Visible) { // att_id_out ?>
		<td<?php echo $shift_result->att_id_out->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_att_id_out" class="shift_result_att_id_out">
<span<?php echo $shift_result->att_id_out->ViewAttributes() ?>>
<?php echo $shift_result->att_id_out->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->durasi_minute->Visible) { // durasi_minute ?>
		<td<?php echo $shift_result->durasi_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_durasi_minute" class="shift_result_durasi_minute">
<span<?php echo $shift_result->durasi_minute->ViewAttributes() ?>>
<?php echo $shift_result->durasi_minute->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->durasi->Visible) { // durasi ?>
		<td<?php echo $shift_result->durasi->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_durasi" class="shift_result_durasi">
<span<?php echo $shift_result->durasi->ViewAttributes() ?>>
<?php echo $shift_result->durasi->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->durasi_eot_minute->Visible) { // durasi_eot_minute ?>
		<td<?php echo $shift_result->durasi_eot_minute->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_durasi_eot_minute" class="shift_result_durasi_eot_minute">
<span<?php echo $shift_result->durasi_eot_minute->ViewAttributes() ?>>
<?php echo $shift_result->durasi_eot_minute->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->jk_count_as->Visible) { // jk_count_as ?>
		<td<?php echo $shift_result->jk_count_as->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_jk_count_as" class="shift_result_jk_count_as">
<span<?php echo $shift_result->jk_count_as->ViewAttributes() ?>>
<?php echo $shift_result->jk_count_as->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($shift_result->status_jk->Visible) { // status_jk ?>
		<td<?php echo $shift_result->status_jk->CellAttributes() ?>>
<span id="el<?php echo $shift_result_delete->RowCnt ?>_shift_result_status_jk" class="shift_result_status_jk">
<span<?php echo $shift_result->status_jk->ViewAttributes() ?>>
<?php echo $shift_result->status_jk->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$shift_result_delete->Recordset->MoveNext();
}
$shift_result_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $shift_result_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fshift_resultdelete.Init();
</script>
<?php
$shift_result_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$shift_result_delete->Page_Terminate();
?>
