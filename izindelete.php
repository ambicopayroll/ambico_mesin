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

$izin_delete = NULL; // Initialize page object first

class cizin_delete extends cizin {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'izin';

	// Page object name
	var $PageObjName = 'izin_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("izinlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in izin class, izininfo.php

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
				$this->Page_Terminate("izinlist.php"); // Return to list
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
				$sThisKey .= $row['izin_urutan'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['izin_tgl'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['izin_jenis_id'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['izin_status'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("izinlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($izin_delete)) $izin_delete = new cizin_delete();

// Page init
$izin_delete->Page_Init();

// Page main
$izin_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$izin_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fizindelete = new ew_Form("fizindelete", "delete");

// Form_CustomValidate event
fizindelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fizindelete.ValidateRequired = true;
<?php } else { ?>
fizindelete.ValidateRequired = false; 
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
<?php $izin_delete->ShowPageHeader(); ?>
<?php
$izin_delete->ShowMessage();
?>
<form name="fizindelete" id="fizindelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($izin_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $izin_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="izin">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($izin_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $izin->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($izin->pegawai_id->Visible) { // pegawai_id ?>
		<th><span id="elh_izin_pegawai_id" class="izin_pegawai_id"><?php echo $izin->pegawai_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_urutan->Visible) { // izin_urutan ?>
		<th><span id="elh_izin_izin_urutan" class="izin_izin_urutan"><?php echo $izin->izin_urutan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_tgl_pengajuan->Visible) { // izin_tgl_pengajuan ?>
		<th><span id="elh_izin_izin_tgl_pengajuan" class="izin_izin_tgl_pengajuan"><?php echo $izin->izin_tgl_pengajuan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_tgl->Visible) { // izin_tgl ?>
		<th><span id="elh_izin_izin_tgl" class="izin_izin_tgl"><?php echo $izin->izin_tgl->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_jenis_id->Visible) { // izin_jenis_id ?>
		<th><span id="elh_izin_izin_jenis_id" class="izin_izin_jenis_id"><?php echo $izin->izin_jenis_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_catatan->Visible) { // izin_catatan ?>
		<th><span id="elh_izin_izin_catatan" class="izin_izin_catatan"><?php echo $izin->izin_catatan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_status->Visible) { // izin_status ?>
		<th><span id="elh_izin_izin_status" class="izin_izin_status"><?php echo $izin->izin_status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_tinggal_t1->Visible) { // izin_tinggal_t1 ?>
		<th><span id="elh_izin_izin_tinggal_t1" class="izin_izin_tinggal_t1"><?php echo $izin->izin_tinggal_t1->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_tinggal_t2->Visible) { // izin_tinggal_t2 ?>
		<th><span id="elh_izin_izin_tinggal_t2" class="izin_izin_tinggal_t2"><?php echo $izin->izin_tinggal_t2->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->cuti_n_id->Visible) { // cuti_n_id ?>
		<th><span id="elh_izin_cuti_n_id" class="izin_cuti_n_id"><?php echo $izin->cuti_n_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_ket_lain->Visible) { // izin_ket_lain ?>
		<th><span id="elh_izin_izin_ket_lain" class="izin_izin_ket_lain"><?php echo $izin->izin_ket_lain->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->izin_noscan_time->Visible) { // izin_noscan_time ?>
		<th><span id="elh_izin_izin_noscan_time" class="izin_izin_noscan_time"><?php echo $izin->izin_noscan_time->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->kat_izin_id->Visible) { // kat_izin_id ?>
		<th><span id="elh_izin_kat_izin_id" class="izin_kat_izin_id"><?php echo $izin->kat_izin_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($izin->ket_status->Visible) { // ket_status ?>
		<th><span id="elh_izin_ket_status" class="izin_ket_status"><?php echo $izin->ket_status->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$izin_delete->RecCnt = 0;
$i = 0;
while (!$izin_delete->Recordset->EOF) {
	$izin_delete->RecCnt++;
	$izin_delete->RowCnt++;

	// Set row properties
	$izin->ResetAttrs();
	$izin->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$izin_delete->LoadRowValues($izin_delete->Recordset);

	// Render row
	$izin_delete->RenderRow();
?>
	<tr<?php echo $izin->RowAttributes() ?>>
<?php if ($izin->pegawai_id->Visible) { // pegawai_id ?>
		<td<?php echo $izin->pegawai_id->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_pegawai_id" class="izin_pegawai_id">
<span<?php echo $izin->pegawai_id->ViewAttributes() ?>>
<?php echo $izin->pegawai_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_urutan->Visible) { // izin_urutan ?>
		<td<?php echo $izin->izin_urutan->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_urutan" class="izin_izin_urutan">
<span<?php echo $izin->izin_urutan->ViewAttributes() ?>>
<?php echo $izin->izin_urutan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_tgl_pengajuan->Visible) { // izin_tgl_pengajuan ?>
		<td<?php echo $izin->izin_tgl_pengajuan->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_tgl_pengajuan" class="izin_izin_tgl_pengajuan">
<span<?php echo $izin->izin_tgl_pengajuan->ViewAttributes() ?>>
<?php echo $izin->izin_tgl_pengajuan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_tgl->Visible) { // izin_tgl ?>
		<td<?php echo $izin->izin_tgl->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_tgl" class="izin_izin_tgl">
<span<?php echo $izin->izin_tgl->ViewAttributes() ?>>
<?php echo $izin->izin_tgl->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_jenis_id->Visible) { // izin_jenis_id ?>
		<td<?php echo $izin->izin_jenis_id->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_jenis_id" class="izin_izin_jenis_id">
<span<?php echo $izin->izin_jenis_id->ViewAttributes() ?>>
<?php echo $izin->izin_jenis_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_catatan->Visible) { // izin_catatan ?>
		<td<?php echo $izin->izin_catatan->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_catatan" class="izin_izin_catatan">
<span<?php echo $izin->izin_catatan->ViewAttributes() ?>>
<?php echo $izin->izin_catatan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_status->Visible) { // izin_status ?>
		<td<?php echo $izin->izin_status->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_status" class="izin_izin_status">
<span<?php echo $izin->izin_status->ViewAttributes() ?>>
<?php echo $izin->izin_status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_tinggal_t1->Visible) { // izin_tinggal_t1 ?>
		<td<?php echo $izin->izin_tinggal_t1->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_tinggal_t1" class="izin_izin_tinggal_t1">
<span<?php echo $izin->izin_tinggal_t1->ViewAttributes() ?>>
<?php echo $izin->izin_tinggal_t1->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_tinggal_t2->Visible) { // izin_tinggal_t2 ?>
		<td<?php echo $izin->izin_tinggal_t2->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_tinggal_t2" class="izin_izin_tinggal_t2">
<span<?php echo $izin->izin_tinggal_t2->ViewAttributes() ?>>
<?php echo $izin->izin_tinggal_t2->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->cuti_n_id->Visible) { // cuti_n_id ?>
		<td<?php echo $izin->cuti_n_id->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_cuti_n_id" class="izin_cuti_n_id">
<span<?php echo $izin->cuti_n_id->ViewAttributes() ?>>
<?php echo $izin->cuti_n_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_ket_lain->Visible) { // izin_ket_lain ?>
		<td<?php echo $izin->izin_ket_lain->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_ket_lain" class="izin_izin_ket_lain">
<span<?php echo $izin->izin_ket_lain->ViewAttributes() ?>>
<?php echo $izin->izin_ket_lain->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->izin_noscan_time->Visible) { // izin_noscan_time ?>
		<td<?php echo $izin->izin_noscan_time->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_izin_noscan_time" class="izin_izin_noscan_time">
<span<?php echo $izin->izin_noscan_time->ViewAttributes() ?>>
<?php echo $izin->izin_noscan_time->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->kat_izin_id->Visible) { // kat_izin_id ?>
		<td<?php echo $izin->kat_izin_id->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_kat_izin_id" class="izin_kat_izin_id">
<span<?php echo $izin->kat_izin_id->ViewAttributes() ?>>
<?php echo $izin->kat_izin_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($izin->ket_status->Visible) { // ket_status ?>
		<td<?php echo $izin->ket_status->CellAttributes() ?>>
<span id="el<?php echo $izin_delete->RowCnt ?>_izin_ket_status" class="izin_ket_status">
<span<?php echo $izin->ket_status->ViewAttributes() ?>>
<?php echo $izin->ket_status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$izin_delete->Recordset->MoveNext();
}
$izin_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $izin_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fizindelete.Init();
</script>
<?php
$izin_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$izin_delete->Page_Terminate();
?>
