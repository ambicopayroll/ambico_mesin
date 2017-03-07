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

$lembur_delete = NULL; // Initialize page object first

class clembur_delete extends clembur {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'lembur';

	// Page object name
	var $PageObjName = 'lembur_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("lemburlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in lembur class, lemburinfo.php

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
				$this->Page_Terminate("lemburlist.php"); // Return to list
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
				$sThisKey .= $row['lembur_tgl'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['lembur_mulai'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['lembur_selesai'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("lemburlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($lembur_delete)) $lembur_delete = new clembur_delete();

// Page init
$lembur_delete->Page_Init();

// Page main
$lembur_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$lembur_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = flemburdelete = new ew_Form("flemburdelete", "delete");

// Form_CustomValidate event
flemburdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flemburdelete.ValidateRequired = true;
<?php } else { ?>
flemburdelete.ValidateRequired = false; 
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
<?php $lembur_delete->ShowPageHeader(); ?>
<?php
$lembur_delete->ShowMessage();
?>
<form name="flemburdelete" id="flemburdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($lembur_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $lembur_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="lembur">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($lembur_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $lembur->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($lembur->pegawai_id->Visible) { // pegawai_id ?>
		<th><span id="elh_lembur_pegawai_id" class="lembur_pegawai_id"><?php echo $lembur->pegawai_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_tgl->Visible) { // lembur_tgl ?>
		<th><span id="elh_lembur_lembur_tgl" class="lembur_lembur_tgl"><?php echo $lembur->lembur_tgl->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_mulai->Visible) { // lembur_mulai ?>
		<th><span id="elh_lembur_lembur_mulai" class="lembur_lembur_mulai"><?php echo $lembur->lembur_mulai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_selesai->Visible) { // lembur_selesai ?>
		<th><span id="elh_lembur_lembur_selesai" class="lembur_lembur_selesai"><?php echo $lembur->lembur_selesai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_urut->Visible) { // lembur_urut ?>
		<th><span id="elh_lembur_lembur_urut" class="lembur_lembur_urut"><?php echo $lembur->lembur_urut->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->type_ot->Visible) { // type_ot ?>
		<th><span id="elh_lembur_type_ot" class="lembur_type_ot"><?php echo $lembur->type_ot->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_durasi_min->Visible) { // lembur_durasi_min ?>
		<th><span id="elh_lembur_lembur_durasi_min" class="lembur_lembur_durasi_min"><?php echo $lembur->lembur_durasi_min->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_durasi_max->Visible) { // lembur_durasi_max ?>
		<th><span id="elh_lembur_lembur_durasi_max" class="lembur_lembur_durasi_max"><?php echo $lembur->lembur_durasi_max->FldCaption() ?></span></th>
<?php } ?>
<?php if ($lembur->lembur_keperluan->Visible) { // lembur_keperluan ?>
		<th><span id="elh_lembur_lembur_keperluan" class="lembur_lembur_keperluan"><?php echo $lembur->lembur_keperluan->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$lembur_delete->RecCnt = 0;
$i = 0;
while (!$lembur_delete->Recordset->EOF) {
	$lembur_delete->RecCnt++;
	$lembur_delete->RowCnt++;

	// Set row properties
	$lembur->ResetAttrs();
	$lembur->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$lembur_delete->LoadRowValues($lembur_delete->Recordset);

	// Render row
	$lembur_delete->RenderRow();
?>
	<tr<?php echo $lembur->RowAttributes() ?>>
<?php if ($lembur->pegawai_id->Visible) { // pegawai_id ?>
		<td<?php echo $lembur->pegawai_id->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_pegawai_id" class="lembur_pegawai_id">
<span<?php echo $lembur->pegawai_id->ViewAttributes() ?>>
<?php echo $lembur->pegawai_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_tgl->Visible) { // lembur_tgl ?>
		<td<?php echo $lembur->lembur_tgl->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_tgl" class="lembur_lembur_tgl">
<span<?php echo $lembur->lembur_tgl->ViewAttributes() ?>>
<?php echo $lembur->lembur_tgl->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_mulai->Visible) { // lembur_mulai ?>
		<td<?php echo $lembur->lembur_mulai->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_mulai" class="lembur_lembur_mulai">
<span<?php echo $lembur->lembur_mulai->ViewAttributes() ?>>
<?php echo $lembur->lembur_mulai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_selesai->Visible) { // lembur_selesai ?>
		<td<?php echo $lembur->lembur_selesai->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_selesai" class="lembur_lembur_selesai">
<span<?php echo $lembur->lembur_selesai->ViewAttributes() ?>>
<?php echo $lembur->lembur_selesai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_urut->Visible) { // lembur_urut ?>
		<td<?php echo $lembur->lembur_urut->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_urut" class="lembur_lembur_urut">
<span<?php echo $lembur->lembur_urut->ViewAttributes() ?>>
<?php echo $lembur->lembur_urut->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->type_ot->Visible) { // type_ot ?>
		<td<?php echo $lembur->type_ot->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_type_ot" class="lembur_type_ot">
<span<?php echo $lembur->type_ot->ViewAttributes() ?>>
<?php echo $lembur->type_ot->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_durasi_min->Visible) { // lembur_durasi_min ?>
		<td<?php echo $lembur->lembur_durasi_min->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_durasi_min" class="lembur_lembur_durasi_min">
<span<?php echo $lembur->lembur_durasi_min->ViewAttributes() ?>>
<?php echo $lembur->lembur_durasi_min->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_durasi_max->Visible) { // lembur_durasi_max ?>
		<td<?php echo $lembur->lembur_durasi_max->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_durasi_max" class="lembur_lembur_durasi_max">
<span<?php echo $lembur->lembur_durasi_max->ViewAttributes() ?>>
<?php echo $lembur->lembur_durasi_max->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($lembur->lembur_keperluan->Visible) { // lembur_keperluan ?>
		<td<?php echo $lembur->lembur_keperluan->CellAttributes() ?>>
<span id="el<?php echo $lembur_delete->RowCnt ?>_lembur_lembur_keperluan" class="lembur_lembur_keperluan">
<span<?php echo $lembur->lembur_keperluan->ViewAttributes() ?>>
<?php echo $lembur->lembur_keperluan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$lembur_delete->Recordset->MoveNext();
}
$lembur_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $lembur_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
flemburdelete.Init();
</script>
<?php
$lembur_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$lembur_delete->Page_Terminate();
?>
