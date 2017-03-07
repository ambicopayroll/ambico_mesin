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

$zx_kredit_m_delete = NULL; // Initialize page object first

class czx_kredit_m_delete extends czx_kredit_m {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'zx_kredit_m';

	// Page object name
	var $PageObjName = 'zx_kredit_m_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("zx_kredit_mlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in zx_kredit_m class, zx_kredit_minfo.php

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
				$this->Page_Terminate("zx_kredit_mlist.php"); // Return to list
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
				$sThisKey .= $row['id_kredit'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("zx_kredit_mlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($zx_kredit_m_delete)) $zx_kredit_m_delete = new czx_kredit_m_delete();

// Page init
$zx_kredit_m_delete->Page_Init();

// Page main
$zx_kredit_m_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zx_kredit_m_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fzx_kredit_mdelete = new ew_Form("fzx_kredit_mdelete", "delete");

// Form_CustomValidate event
fzx_kredit_mdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzx_kredit_mdelete.ValidateRequired = true;
<?php } else { ?>
fzx_kredit_mdelete.ValidateRequired = false; 
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
<?php $zx_kredit_m_delete->ShowPageHeader(); ?>
<?php
$zx_kredit_m_delete->ShowMessage();
?>
<form name="fzx_kredit_mdelete" id="fzx_kredit_mdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zx_kredit_m_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zx_kredit_m_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zx_kredit_m">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($zx_kredit_m_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $zx_kredit_m->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($zx_kredit_m->id_kredit->Visible) { // id_kredit ?>
		<th><span id="elh_zx_kredit_m_id_kredit" class="zx_kredit_m_id_kredit"><?php echo $zx_kredit_m->id_kredit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->no_kredit->Visible) { // no_kredit ?>
		<th><span id="elh_zx_kredit_m_no_kredit" class="zx_kredit_m_no_kredit"><?php echo $zx_kredit_m->no_kredit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->tgl_kredit->Visible) { // tgl_kredit ?>
		<th><span id="elh_zx_kredit_m_tgl_kredit" class="zx_kredit_m_tgl_kredit"><?php echo $zx_kredit_m->tgl_kredit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->emp_id_auto->Visible) { // emp_id_auto ?>
		<th><span id="elh_zx_kredit_m_emp_id_auto" class="zx_kredit_m_emp_id_auto"><?php echo $zx_kredit_m->emp_id_auto->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->krd_id->Visible) { // krd_id ?>
		<th><span id="elh_zx_kredit_m_krd_id" class="zx_kredit_m_krd_id"><?php echo $zx_kredit_m->krd_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->cara_hitung->Visible) { // cara_hitung ?>
		<th><span id="elh_zx_kredit_m_cara_hitung" class="zx_kredit_m_cara_hitung"><?php echo $zx_kredit_m->cara_hitung->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->tot_kredit->Visible) { // tot_kredit ?>
		<th><span id="elh_zx_kredit_m_tot_kredit" class="zx_kredit_m_tot_kredit"><?php echo $zx_kredit_m->tot_kredit->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->saldo_aw->Visible) { // saldo_aw ?>
		<th><span id="elh_zx_kredit_m_saldo_aw" class="zx_kredit_m_saldo_aw"><?php echo $zx_kredit_m->saldo_aw->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->suku_bunga->Visible) { // suku_bunga ?>
		<th><span id="elh_zx_kredit_m_suku_bunga" class="zx_kredit_m_suku_bunga"><?php echo $zx_kredit_m->suku_bunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->periode_bulan->Visible) { // periode_bulan ?>
		<th><span id="elh_zx_kredit_m_periode_bulan" class="zx_kredit_m_periode_bulan"><?php echo $zx_kredit_m->periode_bulan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->angs_pokok->Visible) { // angs_pokok ?>
		<th><span id="elh_zx_kredit_m_angs_pokok" class="zx_kredit_m_angs_pokok"><?php echo $zx_kredit_m->angs_pokok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->angs_pertama->Visible) { // angs_pertama ?>
		<th><span id="elh_zx_kredit_m_angs_pertama" class="zx_kredit_m_angs_pertama"><?php echo $zx_kredit_m->angs_pertama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->tot_debet->Visible) { // tot_debet ?>
		<th><span id="elh_zx_kredit_m_tot_debet" class="zx_kredit_m_tot_debet"><?php echo $zx_kredit_m->tot_debet->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->tot_angs_pokok->Visible) { // tot_angs_pokok ?>
		<th><span id="elh_zx_kredit_m_tot_angs_pokok" class="zx_kredit_m_tot_angs_pokok"><?php echo $zx_kredit_m->tot_angs_pokok->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->tot_bunga->Visible) { // tot_bunga ?>
		<th><span id="elh_zx_kredit_m_tot_bunga" class="zx_kredit_m_tot_bunga"><?php echo $zx_kredit_m->tot_bunga->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->def_pembulatan->Visible) { // def_pembulatan ?>
		<th><span id="elh_zx_kredit_m_def_pembulatan" class="zx_kredit_m_def_pembulatan"><?php echo $zx_kredit_m->def_pembulatan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->jumlah_piutang->Visible) { // jumlah_piutang ?>
		<th><span id="elh_zx_kredit_m_jumlah_piutang" class="zx_kredit_m_jumlah_piutang"><?php echo $zx_kredit_m->jumlah_piutang->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->approv_by->Visible) { // approv_by ?>
		<th><span id="elh_zx_kredit_m_approv_by" class="zx_kredit_m_approv_by"><?php echo $zx_kredit_m->approv_by->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->status->Visible) { // status ?>
		<th><span id="elh_zx_kredit_m_status" class="zx_kredit_m_status"><?php echo $zx_kredit_m->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->status_lunas->Visible) { // status_lunas ?>
		<th><span id="elh_zx_kredit_m_status_lunas" class="zx_kredit_m_status_lunas"><?php echo $zx_kredit_m->status_lunas->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_date->Visible) { // lastupdate_date ?>
		<th><span id="elh_zx_kredit_m_lastupdate_date" class="zx_kredit_m_lastupdate_date"><?php echo $zx_kredit_m->lastupdate_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_user->Visible) { // lastupdate_user ?>
		<th><span id="elh_zx_kredit_m_lastupdate_user" class="zx_kredit_m_lastupdate_user"><?php echo $zx_kredit_m->lastupdate_user->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$zx_kredit_m_delete->RecCnt = 0;
$i = 0;
while (!$zx_kredit_m_delete->Recordset->EOF) {
	$zx_kredit_m_delete->RecCnt++;
	$zx_kredit_m_delete->RowCnt++;

	// Set row properties
	$zx_kredit_m->ResetAttrs();
	$zx_kredit_m->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$zx_kredit_m_delete->LoadRowValues($zx_kredit_m_delete->Recordset);

	// Render row
	$zx_kredit_m_delete->RenderRow();
?>
	<tr<?php echo $zx_kredit_m->RowAttributes() ?>>
<?php if ($zx_kredit_m->id_kredit->Visible) { // id_kredit ?>
		<td<?php echo $zx_kredit_m->id_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_id_kredit" class="zx_kredit_m_id_kredit">
<span<?php echo $zx_kredit_m->id_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->id_kredit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->no_kredit->Visible) { // no_kredit ?>
		<td<?php echo $zx_kredit_m->no_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_no_kredit" class="zx_kredit_m_no_kredit">
<span<?php echo $zx_kredit_m->no_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->no_kredit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->tgl_kredit->Visible) { // tgl_kredit ?>
		<td<?php echo $zx_kredit_m->tgl_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_tgl_kredit" class="zx_kredit_m_tgl_kredit">
<span<?php echo $zx_kredit_m->tgl_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tgl_kredit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->emp_id_auto->Visible) { // emp_id_auto ?>
		<td<?php echo $zx_kredit_m->emp_id_auto->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_emp_id_auto" class="zx_kredit_m_emp_id_auto">
<span<?php echo $zx_kredit_m->emp_id_auto->ViewAttributes() ?>>
<?php echo $zx_kredit_m->emp_id_auto->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->krd_id->Visible) { // krd_id ?>
		<td<?php echo $zx_kredit_m->krd_id->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_krd_id" class="zx_kredit_m_krd_id">
<span<?php echo $zx_kredit_m->krd_id->ViewAttributes() ?>>
<?php echo $zx_kredit_m->krd_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->cara_hitung->Visible) { // cara_hitung ?>
		<td<?php echo $zx_kredit_m->cara_hitung->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_cara_hitung" class="zx_kredit_m_cara_hitung">
<span<?php echo $zx_kredit_m->cara_hitung->ViewAttributes() ?>>
<?php echo $zx_kredit_m->cara_hitung->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->tot_kredit->Visible) { // tot_kredit ?>
		<td<?php echo $zx_kredit_m->tot_kredit->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_tot_kredit" class="zx_kredit_m_tot_kredit">
<span<?php echo $zx_kredit_m->tot_kredit->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_kredit->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->saldo_aw->Visible) { // saldo_aw ?>
		<td<?php echo $zx_kredit_m->saldo_aw->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_saldo_aw" class="zx_kredit_m_saldo_aw">
<span<?php echo $zx_kredit_m->saldo_aw->ViewAttributes() ?>>
<?php echo $zx_kredit_m->saldo_aw->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->suku_bunga->Visible) { // suku_bunga ?>
		<td<?php echo $zx_kredit_m->suku_bunga->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_suku_bunga" class="zx_kredit_m_suku_bunga">
<span<?php echo $zx_kredit_m->suku_bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_m->suku_bunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->periode_bulan->Visible) { // periode_bulan ?>
		<td<?php echo $zx_kredit_m->periode_bulan->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_periode_bulan" class="zx_kredit_m_periode_bulan">
<span<?php echo $zx_kredit_m->periode_bulan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->periode_bulan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->angs_pokok->Visible) { // angs_pokok ?>
		<td<?php echo $zx_kredit_m->angs_pokok->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_angs_pokok" class="zx_kredit_m_angs_pokok">
<span<?php echo $zx_kredit_m->angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_m->angs_pokok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->angs_pertama->Visible) { // angs_pertama ?>
		<td<?php echo $zx_kredit_m->angs_pertama->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_angs_pertama" class="zx_kredit_m_angs_pertama">
<span<?php echo $zx_kredit_m->angs_pertama->ViewAttributes() ?>>
<?php echo $zx_kredit_m->angs_pertama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->tot_debet->Visible) { // tot_debet ?>
		<td<?php echo $zx_kredit_m->tot_debet->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_tot_debet" class="zx_kredit_m_tot_debet">
<span<?php echo $zx_kredit_m->tot_debet->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_debet->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->tot_angs_pokok->Visible) { // tot_angs_pokok ?>
		<td<?php echo $zx_kredit_m->tot_angs_pokok->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_tot_angs_pokok" class="zx_kredit_m_tot_angs_pokok">
<span<?php echo $zx_kredit_m->tot_angs_pokok->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_angs_pokok->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->tot_bunga->Visible) { // tot_bunga ?>
		<td<?php echo $zx_kredit_m->tot_bunga->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_tot_bunga" class="zx_kredit_m_tot_bunga">
<span<?php echo $zx_kredit_m->tot_bunga->ViewAttributes() ?>>
<?php echo $zx_kredit_m->tot_bunga->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->def_pembulatan->Visible) { // def_pembulatan ?>
		<td<?php echo $zx_kredit_m->def_pembulatan->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_def_pembulatan" class="zx_kredit_m_def_pembulatan">
<span<?php echo $zx_kredit_m->def_pembulatan->ViewAttributes() ?>>
<?php echo $zx_kredit_m->def_pembulatan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->jumlah_piutang->Visible) { // jumlah_piutang ?>
		<td<?php echo $zx_kredit_m->jumlah_piutang->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_jumlah_piutang" class="zx_kredit_m_jumlah_piutang">
<span<?php echo $zx_kredit_m->jumlah_piutang->ViewAttributes() ?>>
<?php echo $zx_kredit_m->jumlah_piutang->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->approv_by->Visible) { // approv_by ?>
		<td<?php echo $zx_kredit_m->approv_by->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_approv_by" class="zx_kredit_m_approv_by">
<span<?php echo $zx_kredit_m->approv_by->ViewAttributes() ?>>
<?php echo $zx_kredit_m->approv_by->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->status->Visible) { // status ?>
		<td<?php echo $zx_kredit_m->status->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_status" class="zx_kredit_m_status">
<span<?php echo $zx_kredit_m->status->ViewAttributes() ?>>
<?php echo $zx_kredit_m->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->status_lunas->Visible) { // status_lunas ?>
		<td<?php echo $zx_kredit_m->status_lunas->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_status_lunas" class="zx_kredit_m_status_lunas">
<span<?php echo $zx_kredit_m->status_lunas->ViewAttributes() ?>>
<?php echo $zx_kredit_m->status_lunas->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_date->Visible) { // lastupdate_date ?>
		<td<?php echo $zx_kredit_m->lastupdate_date->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_lastupdate_date" class="zx_kredit_m_lastupdate_date">
<span<?php echo $zx_kredit_m->lastupdate_date->ViewAttributes() ?>>
<?php echo $zx_kredit_m->lastupdate_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($zx_kredit_m->lastupdate_user->Visible) { // lastupdate_user ?>
		<td<?php echo $zx_kredit_m->lastupdate_user->CellAttributes() ?>>
<span id="el<?php echo $zx_kredit_m_delete->RowCnt ?>_zx_kredit_m_lastupdate_user" class="zx_kredit_m_lastupdate_user">
<span<?php echo $zx_kredit_m->lastupdate_user->ViewAttributes() ?>>
<?php echo $zx_kredit_m->lastupdate_user->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$zx_kredit_m_delete->Recordset->MoveNext();
}
$zx_kredit_m_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $zx_kredit_m_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fzx_kredit_mdelete.Init();
</script>
<?php
$zx_kredit_m_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$zx_kredit_m_delete->Page_Terminate();
?>
