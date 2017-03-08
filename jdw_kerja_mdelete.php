<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "jdw_kerja_minfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$jdw_kerja_m_delete = NULL; // Initialize page object first

class cjdw_kerja_m_delete extends cjdw_kerja_m {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{0B4A4F9E-7A2B-4234-9791-3975C1DCDDA1}";

	// Table name
	var $TableName = 'jdw_kerja_m';

	// Page object name
	var $PageObjName = 'jdw_kerja_m_delete';

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

		// Table object (jdw_kerja_m)
		if (!isset($GLOBALS["jdw_kerja_m"]) || get_class($GLOBALS["jdw_kerja_m"]) == "cjdw_kerja_m") {
			$GLOBALS["jdw_kerja_m"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["jdw_kerja_m"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'jdw_kerja_m', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("jdw_kerja_mlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->jdw_kerja_m_id->SetVisibility();
		$this->jdw_kerja_m_kode->SetVisibility();
		$this->jdw_kerja_m_name->SetVisibility();
		$this->jdw_kerja_m_keterangan->SetVisibility();
		$this->jdw_kerja_m_periode->SetVisibility();
		$this->jdw_kerja_m_mulai->SetVisibility();
		$this->jdw_kerja_m_type->SetVisibility();
		$this->use_sama->SetVisibility();

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
		global $EW_EXPORT, $jdw_kerja_m;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($jdw_kerja_m);
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
			$this->Page_Terminate("jdw_kerja_mlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in jdw_kerja_m class, jdw_kerja_minfo.php

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
				$this->Page_Terminate("jdw_kerja_mlist.php"); // Return to list
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
		$this->jdw_kerja_m_id->setDbValue($rs->fields('jdw_kerja_m_id'));
		$this->jdw_kerja_m_kode->setDbValue($rs->fields('jdw_kerja_m_kode'));
		$this->jdw_kerja_m_name->setDbValue($rs->fields('jdw_kerja_m_name'));
		$this->jdw_kerja_m_keterangan->setDbValue($rs->fields('jdw_kerja_m_keterangan'));
		$this->jdw_kerja_m_periode->setDbValue($rs->fields('jdw_kerja_m_periode'));
		$this->jdw_kerja_m_mulai->setDbValue($rs->fields('jdw_kerja_m_mulai'));
		$this->jdw_kerja_m_type->setDbValue($rs->fields('jdw_kerja_m_type'));
		$this->use_sama->setDbValue($rs->fields('use_sama'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->jdw_kerja_m_id->DbValue = $row['jdw_kerja_m_id'];
		$this->jdw_kerja_m_kode->DbValue = $row['jdw_kerja_m_kode'];
		$this->jdw_kerja_m_name->DbValue = $row['jdw_kerja_m_name'];
		$this->jdw_kerja_m_keterangan->DbValue = $row['jdw_kerja_m_keterangan'];
		$this->jdw_kerja_m_periode->DbValue = $row['jdw_kerja_m_periode'];
		$this->jdw_kerja_m_mulai->DbValue = $row['jdw_kerja_m_mulai'];
		$this->jdw_kerja_m_type->DbValue = $row['jdw_kerja_m_type'];
		$this->use_sama->DbValue = $row['use_sama'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// jdw_kerja_m_id
		// jdw_kerja_m_kode
		// jdw_kerja_m_name
		// jdw_kerja_m_keterangan
		// jdw_kerja_m_periode
		// jdw_kerja_m_mulai
		// jdw_kerja_m_type
		// use_sama

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// jdw_kerja_m_id
		$this->jdw_kerja_m_id->ViewValue = $this->jdw_kerja_m_id->CurrentValue;
		$this->jdw_kerja_m_id->ViewCustomAttributes = "";

		// jdw_kerja_m_kode
		$this->jdw_kerja_m_kode->ViewValue = $this->jdw_kerja_m_kode->CurrentValue;
		$this->jdw_kerja_m_kode->ViewCustomAttributes = "";

		// jdw_kerja_m_name
		$this->jdw_kerja_m_name->ViewValue = $this->jdw_kerja_m_name->CurrentValue;
		$this->jdw_kerja_m_name->ViewCustomAttributes = "";

		// jdw_kerja_m_keterangan
		$this->jdw_kerja_m_keterangan->ViewValue = $this->jdw_kerja_m_keterangan->CurrentValue;
		$this->jdw_kerja_m_keterangan->ViewCustomAttributes = "";

		// jdw_kerja_m_periode
		$this->jdw_kerja_m_periode->ViewValue = $this->jdw_kerja_m_periode->CurrentValue;
		$this->jdw_kerja_m_periode->ViewCustomAttributes = "";

		// jdw_kerja_m_mulai
		$this->jdw_kerja_m_mulai->ViewValue = $this->jdw_kerja_m_mulai->CurrentValue;
		$this->jdw_kerja_m_mulai->ViewValue = ew_FormatDateTime($this->jdw_kerja_m_mulai->ViewValue, 0);
		$this->jdw_kerja_m_mulai->ViewCustomAttributes = "";

		// jdw_kerja_m_type
		$this->jdw_kerja_m_type->ViewValue = $this->jdw_kerja_m_type->CurrentValue;
		$this->jdw_kerja_m_type->ViewCustomAttributes = "";

		// use_sama
		$this->use_sama->ViewValue = $this->use_sama->CurrentValue;
		$this->use_sama->ViewCustomAttributes = "";

			// jdw_kerja_m_id
			$this->jdw_kerja_m_id->LinkCustomAttributes = "";
			$this->jdw_kerja_m_id->HrefValue = "";
			$this->jdw_kerja_m_id->TooltipValue = "";

			// jdw_kerja_m_kode
			$this->jdw_kerja_m_kode->LinkCustomAttributes = "";
			$this->jdw_kerja_m_kode->HrefValue = "";
			$this->jdw_kerja_m_kode->TooltipValue = "";

			// jdw_kerja_m_name
			$this->jdw_kerja_m_name->LinkCustomAttributes = "";
			$this->jdw_kerja_m_name->HrefValue = "";
			$this->jdw_kerja_m_name->TooltipValue = "";

			// jdw_kerja_m_keterangan
			$this->jdw_kerja_m_keterangan->LinkCustomAttributes = "";
			$this->jdw_kerja_m_keterangan->HrefValue = "";
			$this->jdw_kerja_m_keterangan->TooltipValue = "";

			// jdw_kerja_m_periode
			$this->jdw_kerja_m_periode->LinkCustomAttributes = "";
			$this->jdw_kerja_m_periode->HrefValue = "";
			$this->jdw_kerja_m_periode->TooltipValue = "";

			// jdw_kerja_m_mulai
			$this->jdw_kerja_m_mulai->LinkCustomAttributes = "";
			$this->jdw_kerja_m_mulai->HrefValue = "";
			$this->jdw_kerja_m_mulai->TooltipValue = "";

			// jdw_kerja_m_type
			$this->jdw_kerja_m_type->LinkCustomAttributes = "";
			$this->jdw_kerja_m_type->HrefValue = "";
			$this->jdw_kerja_m_type->TooltipValue = "";

			// use_sama
			$this->use_sama->LinkCustomAttributes = "";
			$this->use_sama->HrefValue = "";
			$this->use_sama->TooltipValue = "";
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
				$sThisKey .= $row['jdw_kerja_m_id'];
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
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("jdw_kerja_mlist.php"), "", $this->TableVar, TRUE);
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
if (!isset($jdw_kerja_m_delete)) $jdw_kerja_m_delete = new cjdw_kerja_m_delete();

// Page init
$jdw_kerja_m_delete->Page_Init();

// Page main
$jdw_kerja_m_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$jdw_kerja_m_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fjdw_kerja_mdelete = new ew_Form("fjdw_kerja_mdelete", "delete");

// Form_CustomValidate event
fjdw_kerja_mdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fjdw_kerja_mdelete.ValidateRequired = true;
<?php } else { ?>
fjdw_kerja_mdelete.ValidateRequired = false; 
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
<?php $jdw_kerja_m_delete->ShowPageHeader(); ?>
<?php
$jdw_kerja_m_delete->ShowMessage();
?>
<form name="fjdw_kerja_mdelete" id="fjdw_kerja_mdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($jdw_kerja_m_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $jdw_kerja_m_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="jdw_kerja_m">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($jdw_kerja_m_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $jdw_kerja_m->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($jdw_kerja_m->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_id" class="jdw_kerja_m_jdw_kerja_m_id"><?php echo $jdw_kerja_m->jdw_kerja_m_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_kode->Visible) { // jdw_kerja_m_kode ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_kode" class="jdw_kerja_m_jdw_kerja_m_kode"><?php echo $jdw_kerja_m->jdw_kerja_m_kode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_name->Visible) { // jdw_kerja_m_name ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_name" class="jdw_kerja_m_jdw_kerja_m_name"><?php echo $jdw_kerja_m->jdw_kerja_m_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_keterangan->Visible) { // jdw_kerja_m_keterangan ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_keterangan" class="jdw_kerja_m_jdw_kerja_m_keterangan"><?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_periode->Visible) { // jdw_kerja_m_periode ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_periode" class="jdw_kerja_m_jdw_kerja_m_periode"><?php echo $jdw_kerja_m->jdw_kerja_m_periode->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_mulai->Visible) { // jdw_kerja_m_mulai ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_mulai" class="jdw_kerja_m_jdw_kerja_m_mulai"><?php echo $jdw_kerja_m->jdw_kerja_m_mulai->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_type->Visible) { // jdw_kerja_m_type ?>
		<th><span id="elh_jdw_kerja_m_jdw_kerja_m_type" class="jdw_kerja_m_jdw_kerja_m_type"><?php echo $jdw_kerja_m->jdw_kerja_m_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($jdw_kerja_m->use_sama->Visible) { // use_sama ?>
		<th><span id="elh_jdw_kerja_m_use_sama" class="jdw_kerja_m_use_sama"><?php echo $jdw_kerja_m->use_sama->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$jdw_kerja_m_delete->RecCnt = 0;
$i = 0;
while (!$jdw_kerja_m_delete->Recordset->EOF) {
	$jdw_kerja_m_delete->RecCnt++;
	$jdw_kerja_m_delete->RowCnt++;

	// Set row properties
	$jdw_kerja_m->ResetAttrs();
	$jdw_kerja_m->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$jdw_kerja_m_delete->LoadRowValues($jdw_kerja_m_delete->Recordset);

	// Render row
	$jdw_kerja_m_delete->RenderRow();
?>
	<tr<?php echo $jdw_kerja_m->RowAttributes() ?>>
<?php if ($jdw_kerja_m->jdw_kerja_m_id->Visible) { // jdw_kerja_m_id ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_id->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_id" class="jdw_kerja_m_jdw_kerja_m_id">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_id->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_kode->Visible) { // jdw_kerja_m_kode ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_kode->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_kode" class="jdw_kerja_m_jdw_kerja_m_kode">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_kode->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_kode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_name->Visible) { // jdw_kerja_m_name ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_name->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_name" class="jdw_kerja_m_jdw_kerja_m_name">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_name->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_keterangan->Visible) { // jdw_kerja_m_keterangan ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_keterangan" class="jdw_kerja_m_jdw_kerja_m_keterangan">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_keterangan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_periode->Visible) { // jdw_kerja_m_periode ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_periode->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_periode" class="jdw_kerja_m_jdw_kerja_m_periode">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_periode->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_periode->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_mulai->Visible) { // jdw_kerja_m_mulai ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_mulai" class="jdw_kerja_m_jdw_kerja_m_mulai">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_mulai->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->jdw_kerja_m_type->Visible) { // jdw_kerja_m_type ?>
		<td<?php echo $jdw_kerja_m->jdw_kerja_m_type->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_jdw_kerja_m_type" class="jdw_kerja_m_jdw_kerja_m_type">
<span<?php echo $jdw_kerja_m->jdw_kerja_m_type->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->jdw_kerja_m_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($jdw_kerja_m->use_sama->Visible) { // use_sama ?>
		<td<?php echo $jdw_kerja_m->use_sama->CellAttributes() ?>>
<span id="el<?php echo $jdw_kerja_m_delete->RowCnt ?>_jdw_kerja_m_use_sama" class="jdw_kerja_m_use_sama">
<span<?php echo $jdw_kerja_m->use_sama->ViewAttributes() ?>>
<?php echo $jdw_kerja_m->use_sama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$jdw_kerja_m_delete->Recordset->MoveNext();
}
$jdw_kerja_m_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $jdw_kerja_m_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fjdw_kerja_mdelete.Init();
</script>
<?php
$jdw_kerja_m_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$jdw_kerja_m_delete->Page_Terminate();
?>
