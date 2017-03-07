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

$jatah_cuti_delete = NULL; // Initialize page object first

class cjatah_cuti_delete extends cjatah_cuti {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{F36F5C9B-7A33-450D-8126-2253575B79B0}";

	// Table name
	var $TableName = 'jatah_cuti';

	// Page object name
	var $PageObjName = 'jatah_cuti_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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
			$this->Page_Terminate("jatah_cutilist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in jatah_cuti class, jatah_cutiinfo.php

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
				$this->Page_Terminate("jatah_cutilist.php"); // Return to list
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
				$sThisKey .= $row['jatah_c_mulai'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['jatah_c_akhir'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jatah_cutilist.php"), "", $this->TableVar, TRUE);
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
if (!isset($jatah_cuti_delete)) $jatah_cuti_delete = new cjatah_cuti_delete();

// Page init
$jatah_cuti_delete->Page_Init();

// Page main
$jatah_cuti_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jatah_cuti_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fjatah_cutidelete = new ew_Form("fjatah_cutidelete", "delete");

// Form_CustomValidate event
fjatah_cutidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjatah_cutidelete.ValidateRequired = true;
<?php } else { ?>
fjatah_cutidelete.ValidateRequired = false; 
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
<?php $jatah_cuti_delete->ShowPageHeader(); ?>
<?php
$jatah_cuti_delete->ShowMessage();
?>
<form name="fjatah_cutidelete" id="fjatah_cutidelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jatah_cuti_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jatah_cuti_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jatah_cuti">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($jatah_cuti_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $jatah_cuti->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($jatah_cuti->pegawai_id->Visible) { // pegawai_id ?>
		<th><span id="elh_jatah_cuti_pegawai_id" class="jatah_cuti_pegawai_id"><?php echo $jatah_cuti->pegawai_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_mulai->Visible) { // jatah_c_mulai ?>
		<th><span id="elh_jatah_cuti_jatah_c_mulai" class="jatah_cuti_jatah_c_mulai"><?php echo $jatah_cuti->jatah_c_mulai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_akhir->Visible) { // jatah_c_akhir ?>
		<th><span id="elh_jatah_cuti_jatah_c_akhir" class="jatah_cuti_jatah_c_akhir"><?php echo $jatah_cuti->jatah_c_akhir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_jml->Visible) { // jatah_c_jml ?>
		<th><span id="elh_jatah_cuti_jatah_c_jml" class="jatah_cuti_jatah_c_jml"><?php echo $jatah_cuti->jatah_c_jml->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_hak_jml->Visible) { // jatah_c_hak_jml ?>
		<th><span id="elh_jatah_cuti_jatah_c_hak_jml" class="jatah_cuti_jatah_c_hak_jml"><?php echo $jatah_cuti->jatah_c_hak_jml->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_ambil_jml->Visible) { // jatah_c_ambil_jml ?>
		<th><span id="elh_jatah_cuti_jatah_c_ambil_jml" class="jatah_cuti_jatah_c_ambil_jml"><?php echo $jatah_cuti->jatah_c_ambil_jml->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_utang_jml->Visible) { // jatah_c_utang_jml ?>
		<th><span id="elh_jatah_cuti_jatah_c_utang_jml" class="jatah_cuti_jatah_c_utang_jml"><?php echo $jatah_cuti->jatah_c_utang_jml->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$jatah_cuti_delete->RecCnt = 0;
$i = 0;
while (!$jatah_cuti_delete->Recordset->EOF) {
	$jatah_cuti_delete->RecCnt++;
	$jatah_cuti_delete->RowCnt++;

	// Set row properties
	$jatah_cuti->ResetAttrs();
	$jatah_cuti->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$jatah_cuti_delete->LoadRowValues($jatah_cuti_delete->Recordset);

	// Render row
	$jatah_cuti_delete->RenderRow();
?>
	<tr<?php echo $jatah_cuti->RowAttributes() ?>>
<?php if ($jatah_cuti->pegawai_id->Visible) { // pegawai_id ?>
		<td<?php echo $jatah_cuti->pegawai_id->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_pegawai_id" class="jatah_cuti_pegawai_id">
<span<?php echo $jatah_cuti->pegawai_id->ViewAttributes() ?>>
<?php echo $jatah_cuti->pegawai_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_mulai->Visible) { // jatah_c_mulai ?>
		<td<?php echo $jatah_cuti->jatah_c_mulai->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_jatah_c_mulai" class="jatah_cuti_jatah_c_mulai">
<span<?php echo $jatah_cuti->jatah_c_mulai->ViewAttributes() ?>>
<?php echo $jatah_cuti->jatah_c_mulai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_akhir->Visible) { // jatah_c_akhir ?>
		<td<?php echo $jatah_cuti->jatah_c_akhir->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_jatah_c_akhir" class="jatah_cuti_jatah_c_akhir">
<span<?php echo $jatah_cuti->jatah_c_akhir->ViewAttributes() ?>>
<?php echo $jatah_cuti->jatah_c_akhir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_jml->Visible) { // jatah_c_jml ?>
		<td<?php echo $jatah_cuti->jatah_c_jml->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_jatah_c_jml" class="jatah_cuti_jatah_c_jml">
<span<?php echo $jatah_cuti->jatah_c_jml->ViewAttributes() ?>>
<?php echo $jatah_cuti->jatah_c_jml->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_hak_jml->Visible) { // jatah_c_hak_jml ?>
		<td<?php echo $jatah_cuti->jatah_c_hak_jml->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_jatah_c_hak_jml" class="jatah_cuti_jatah_c_hak_jml">
<span<?php echo $jatah_cuti->jatah_c_hak_jml->ViewAttributes() ?>>
<?php echo $jatah_cuti->jatah_c_hak_jml->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_ambil_jml->Visible) { // jatah_c_ambil_jml ?>
		<td<?php echo $jatah_cuti->jatah_c_ambil_jml->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_jatah_c_ambil_jml" class="jatah_cuti_jatah_c_ambil_jml">
<span<?php echo $jatah_cuti->jatah_c_ambil_jml->ViewAttributes() ?>>
<?php echo $jatah_cuti->jatah_c_ambil_jml->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jatah_cuti->jatah_c_utang_jml->Visible) { // jatah_c_utang_jml ?>
		<td<?php echo $jatah_cuti->jatah_c_utang_jml->CellAttributes() ?>>
<span id="el<?php echo $jatah_cuti_delete->RowCnt ?>_jatah_cuti_jatah_c_utang_jml" class="jatah_cuti_jatah_c_utang_jml">
<span<?php echo $jatah_cuti->jatah_c_utang_jml->ViewAttributes() ?>>
<?php echo $jatah_cuti->jatah_c_utang_jml->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$jatah_cuti_delete->Recordset->MoveNext();
}
$jatah_cuti_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jatah_cuti_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fjatah_cutidelete.Init();
</script>
<?php
$jatah_cuti_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jatah_cuti_delete->Page_Terminate();
?>
